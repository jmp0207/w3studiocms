<?php
/*
 * This file is part of the w3studioCMS package library and it is distributed 
 * under the LGPL LICENSE Version 2.1. To use this library you must leave 
 * intact this copyright notice.
 *  
 * (c) 2007-2008 Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 *  
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.w3studiocms.com
 */


class w3sCommonFunctions
{
  public static function toI18n($value)
  {
    try
    {
	  	$result = sfContext::getInstance()->getI18N()->__($value);		
    }
    catch(Exception $e)
    {
      $result = $value;
    }
    
    return $result;
  }
  /*
  public static function getPageElementsId($projectName, $templateName)
  {
    ob_start();
    include(sfConfig::get('app_w3s_web_templates_dir') . '/' . $projectName . '/' . $templateName . '/' . $templateName . '.php');
    $template = ob_get_clean();
    preg_match_all('/id\s*=\s*["|\'](.*?)["|\']/', $template, $result);
    
    return $result[1];
  }*/

  public static function getConfigurationFilePath($fileName)
  {
    if (sfConfig::get('app_w3s_scripts_dir') == null){
			throw new exception('Error: The w3s_scripts_dir constant is not defined. Please define it in your app.yml file');
		}

		$w3sScriptFile = self::checkLastDirSeparator(sfConfig::get('app_w3s_scripts_dir')) . 'w3sPaths.yml';
		if (!is_file($w3sScriptFile))
		{
			throw new exception(sprintf('%s is not a valid file.', $w3sScriptFile));
		}

		$searchPaths = sfYaml::load(self::checkLastDirSeparator(sfConfig::get('app_w3s_scripts_dir')) . 'w3sPaths.yml');

		$result = '';
		foreach($searchPaths['paths'] as $searchPath){
			$searchPath = self::checkLastDirSeparator($searchPath);
			if (is_file($searchPath. $fileName))
			{
				$result = $searchPath;
				break;
			}
		}

    return $result;
  }
  
  public static function loadScript($fileName){
  	$file = self::getConfigurationFilePath($fileName) . $fileName;
		
		if (!is_file($file))
    {
			throw new exception(sprintf('Error: The file %s does not exist in any of the paths you specified in the w3sPaths.yml configuration file.', $fileName));
		}
		
		return sfYaml::load($file); 			
  }
  
  public static function renderCommandsFromYml($ymlFile, $currentUser = null)
  {
  	$renderedCommands = array();
		$commands = self::loadScript($ymlFile);
		foreach($commands as $command)
		{
			$oButton = new w3sButton($currentUser);
	    $oButton->fromArray($command);
	    $renderedCommands[] = $oButton->renderButton();
		}
		
		return $renderedCommands;
  }
  
  /**
   * MOVED TO w3sTemplateEngine
   * Returns the available classes for a given templates's DIV. Can retrieve only the class
   *
   * name or the full CSS style. This is done with the last parameter 
   *
   * @param      str The div's id.
   * @param      str The project name
   * @param      str The template name
   * @param      int optional 0 retrieves only the class name
   *                           1 Retrieve the full css style
   * 
   * @return     array The founded classes 
   *
  public static function getAvailableClasses($searchValue, $projectName, $templateName, $mode=0){

    // This is only a paliative solution. Hope someone can fix the parse class: I don't know Call-time pass-by-reference
    ini_set('error_reporting', 'E_ERROR');

    require_once('parser/htmlparser.inc');
    require_once('parser/common.inc');

    // Opens the template and parses its structure
    $templateFile = sfConfig::get('app_w3s_web_templates_dir') . '/' . $projectName . '/' . $templateName . '/' . $templateName . '.php';
    $p=new HtmlParser($templateFile, unserialize(Read_File("parser/htmlgrammar.cmp")), $templateFile, 1);
    $p->Parse();
    $src="";
    GetPageSrc($p->content,$src);

    ob_start();
    PrintArray($p->content);
    $contents = ob_get_clean();

    // Finds the id of DIVS 
    $i=1;
    $elements = array($searchValue);
    while(1){
      preg_match('/(.*)\[content\].*\[pars\]\[id\]\[value\]=' . $searchValue . '/', $contents, $res);
      if (count($res) == 0) break;
      $startKey = str_replace("[", "\[", $res[1]);
      $startKey = str_replace("]", "\]", $startKey);
      preg_match('/' . $startKey . '\[pars\]\[id\]\[value\]=(.*)/', $contents, $res);
      $elements[] = $res[1];
      $searchValue = $res[1];
      $i++;
      
      // Prevents blocks if an infinite loop occours
      if ($i==100) break;
    } 

    // Finds all the template's stylesheets 
    $fp = fopen ($templateFile, "r");
    $templateContents = fread($fp, filesize($templateFile));    
    fclose($fp);
    $templateContents = str_replace("\r\n", "", $templateContents);   
    preg_match_all('/.*?rel=["|\']stylesheet["|\'].*?href\s*=\s*["|\'](.*?)["|\'].*?/', $templateContents, $stylesheets);
    
    // Creates a single stylesheet from the stylesheets retrieved
    $contents = '';
    foreach ($stylesheets[1] as $stylesheet): 
      $stylesheet = substr($stylesheet, 1, strlen($stylesheet));
      $fp = fopen ($stylesheet, "r");
      $currentContent = fread($fp, filesize($stylesheet)); 
      fclose($fp);     
      $currentContent = str_replace("\r\n", "", $currentContent);
      $currentContent = preg_replace('/HTML>.*?}+?/', '', $currentContent);
      $contents .= $currentContent;      
    endforeach;

    // Find classes from xhtml elements
    $result = ($mode == 0) ? array('w3sNone' => 'None') : array();
    foreach($elements as $element):
      $expression = ($mode == 0) ? '/#' . trim($element) . '[a-zA-Z0-9-_:\s]*\.(.*?)\{+?/' : '/#' . trim($element) . '[a-zA-Z0-9-_:\s]*(\..*?\{.*?\})+?/'; 
      preg_match_all($expression, $contents, $classes); 
      foreach($classes[1] as $class):
        if ($mode == 0){
	        $result[$class] = $class;
        }  
        else{
	        $result[] = $class;
        }
      endforeach;
    endforeach;

    // Find classes not associated to xhtml elements
    $expression = ($mode == 0) ? '/(^|})\.(.*?)\{+?/' : '/(^|})(\..*?\{.*?\})+?/';
    preg_match_all($expression, $contents, $classes); 
    foreach($classes[2] as $class):
      if ($mode == 0){
	      $result[$class] = $class;
	    }  
	    else{
	      $result[] = $class;
	    }
    endforeach;
   	
    return $result;
  }*/

  /**
   * Returns all files stored in a given directory.
   *
   * @param      str Path of the directory to scan.
   * @param      str optional The name of the selected file
   * @param      array optional An array with the files extensions we want to retireve. If empty
   *              returns all files.
   * @return     array An array which first argument is a singled dimension array which lists
   *             all the files of given directory and the second argument is the index of the
   *             selected file. If any file matches this value is set to -1
   */
  public static function buildFilesList($dirPath, $selectedFile = '', $fileTypes = array()){
    
    // Sets the predefined values 
    $selected = -1;
    $files = array();
    if ($handle = opendir($dirPath)) {
      while (false !== ($file = readdir($handle))) {
        
        // Only files will be examined
        $dir = $dirPath . '/' . $file;
        if (!is_dir($dir)){
          
          // Retriving informations about the current file
          $info = pathinfo($dir);
          
          /* If the file's extension matches with one in the $fileTypes array or 
           * that array is empty stores the file
           **/
          if (isset($info["extension"]) && in_array($info["extension"], $fileTypes) || empty($fileTypes)) $files[] = $file;
          
        }
      }
      
      // Files sorting
      sort($files);
      
      // Retriving the index of the selected element
      $selected = array_search($selectedFile, $files);
    }

    return array("values" => $files, "selected" => $selected);
  }

	public static function stringToArray($string){
		preg_match_all('/
	      \s*(\w+)              # key                               \\1
	      \s*=\s*               # =
	      (\'|")?               # values may be included in \' or " \\2
	      (.*?)                 # value                             \\3
	      (?(2) \\2)            # matching \' or " if needed        \\4      
	    /x', $string, $matches, PREG_SET_ORDER);    
    $attributes = array();
    foreach ($matches as $val)
    {
      $attributes[$val[1]] = $val[3];
    }
    
    return $attributes;
	}
	
  /**
   * Retrieves the html attribute from a string
   *
   * @param      str The string to search
   * @param      str The attribute needed
   * 
   * @return     str The attribute value. Returns a blank string if the attribute wasn't found
   */
  public static function getTagAttribute($source, $attribute){
    $result = '';
    
    if ($source != '' && $attribute != ''){
      
      // Retrieves the attribute required
      preg_match('/' . $attribute . '\s*=\s*["|\'](.*?)["|\'].*?/', $source, $match);
      $result = (isset($match[1])) ? $match[1] : '';    
    }  
    
    return $result;
  }
  
  /**
   * Retrieves all the html attributes from a string
   *
   * @param      str The string to search
   * @param      str The tag examined
   * 
   * @return     str The attribute value. Returns a blank string if the attribute wasn't found
   */
  public static function getHtmlAttributes($source, $tag){
    $result = '';
    
    if ($source != ''){
      
      // Retrieves the attribute required 
      preg_match_all('/[^<' . $tag . '](.*?)\s*=\s*["|\'](.*?)["|\'].*?/', $source, $match);
      $result = (isset($match[1])) ? array_combine($match[1], $match[2]) : array();    
    }  
    
    return $result;
  }
 
  /**
   * Add a slash at the end of the $string variable if it was not found
   *
   * @param      str The string to verify
   * @return     str The string modified as needed
   */
  public static function checkLastDirSeparator($string, $char=DIRECTORY_SEPARATOR){ 
    $string = trim($string);
    return (substr($string, strlen($string) - 1, 1) != $char) ? $string . $char : $string;
  }

  /**
   * Set a string which has a leng of a number of characters predefined. If string is lengther
   * than the width we need, it will be reduced to max width and three point will be added.
   *
   * @param      str The string to check
   * @param      int The length desidered
   * @return     str The string modified as needed
   */
  public static function setStringMaxWidth($string, $length=20){
    return (strlen($string) > $length) ? substr($string, 0, $length - 4) . '...' : $string;
  }

  /**
   * Converts a date to uninix time 
   *
   * @param      str The $date
   * 
   * @return     time 
   */
  public static function dateToTime($date){
    preg_match_all('([^-:][0-9]*)',  $date, $r);
    return mktime ($r[0][3], $r[0][4], $r[0][5], $r[0][1], $r[0][2], $r[0][0]);
  }
 
  /**
   * Extracts a zip file to a given path 
   *
   * @param      str The zip file
   * @param      str The destination path
   * 
   * @return     int 0 fails, 1 success
   */ 
  public static function extractZipFile($zipFile, $destinationPath){
    $zip = new ZipArchive;
    if ($zip->open($zipFile) === TRUE) {
      $result = $zip->extractTo($destinationPath); 
      $zip->close();
            
      return $result;
    } else {
      return 0;
    }
  }
  
  /**
   * Reads the template file and returns its contents 
   * 
   * @param string   The project name
   * @param string   The template name
   * 
   * @return string  The template's contents
   *
   */
  public static function readFileContents($filename){
    $result = '';
    $handle = @fopen($filename, "r");
    if ($handle)
    {
	    $result = fread($handle, filesize($filename));
	    fclose($handle);
    }
    
    return $result;
  }
  
  /**
   * Reads the template file and returns its contents 
   * 
   * @param string   The project name
   * @param string   The template name
   * 
   * @return string  The template's contents
   *
   */
  public static function writeFileContents($filename, $contents){
    $fp = fopen ($filename, "w");
	  fwrite($fp, $contents);
	  fclose($fp);
  }
  
  /**
   * Deletes all files and directories stored in a given directory.
   * At last deletes the source directory
   *
   * @param      str Path of the directory to delete.
   */
  public static function deleteDirectoryRecursiveFull($sourceDir){
    self::deleteDirectoryRecursive($sourceDir);
    @rmdir($sourceDir);
  }

  /**
   * Deletes all files and directories stored in a given directory, leaving
   * the source directory intact
   *
   * @param      str Path of the directory to delete.
   */
  public static function deleteDirectoryRecursive($sourceDir, $ignore=array()){
    if ($handle = opendir($sourceDir)){
      while (false !== ($file = readdir($handle))){
        if ($file != '.' && $file != '..'){
          if (is_dir($sourceDir . '/' . $file)){
            if (!in_array($file, $ignore))
            {
              self::deleteDirectoryRecursive($sourceDir . '/' . $file, $ignore);
              rmdir($sourceDir . '/' . $file);
            }
          }
          else{
            chmod ($sourceDir . '/' . $file, 0777);
            unlink ($sourceDir . '/' . $file);
          }
          flush();
        }
      }
    }
    closedir($handle);
  }
  
  public static function linkConverter($content, $mode='editor')
  {
  	$text = $content->getContent();  		
		$oLanguage = $content->getW3sLanguage();
		
    // Retrieves all the links, the attributes and the link content
    preg_match_all('/\<a(.*?)\>(.*?)\<\/a\>?/', $text, $result);
    $i=0;
    foreach($result[1] as $linkAttributes){
      
      // Examines one attribute a time and retrieves href and class attributes if exists      
      $href = '';
      $class = '';
      $linkAttributes = explode(' ', $linkAttributes);
      foreach($linkAttributes as $attribute){        
        if (strpos($attribute, 'href') !== false)  $href = self::getTagAttribute($attribute, 'href');
        if (strpos($attribute, 'class') !== false) $class = self::getTagAttribute($attribute, 'class');
      }
      $currentPageName = self::getPageNameFromLink($href);
      
      // Checks if the examined link is internal and in this case W3StudioCMS converts it 
      $oPage = W3sPagePeer::getFromPageName($currentPageName); 
      if ($oPage != null){
        $classValue = (!empty($class)) ? 'class=' . $class : '';
        $anchorPos = strpos($href, '#');
        $anchor = ($anchorPos !== false) ? substr($href, $anchorPos, strlen($href) - $anchorPos) : '';
        /*
        $symfonyLink = ($mode == 'preview') ?
          link_to_function($result[2][$i], 'W3sTemplate.loadPreviewPage('. $content->getLanguageId() . ', ' . $oPage->getId() . ')', $classValue)
                                            :
          '<a href="/' . $oLanguage->getLanguage() . '/' . $currentPageName . '.html' . $anchor . '"' . $classValue . '>' . $result[2][$i] . '</a>';
        */
        $symfonyLink = ($mode == 'preview') ?
          sprintf('<a href="#" onclick="W3sTemplate.loadPreviewPage(%s, %s); return false;" %s>%s</a>', $content->getLanguageId(), $oPage->getId(), $classValue, $result[2][$i])
                                            :
          sprintf('<a href="/%s/%s.html%s" %s>%s</a>', $oLanguage->getLanguage(), $currentPageName, $anchor, $classValue, $result[2][$i]);

        $text = str_replace($result[0][$i], $symfonyLink, $text);
      }
      else{
        if (substr($href, 0, 1) != '/' && 
            strpos($href, 'http://') === false && 
            strpos($href, 'mailto:') === false &&
            $href != '#'){
          $text = str_replace($href, 'http://' . $href, $text);      
        }
      }  
      $i++;
    }   
    
    // target="_blank" has been deprecated, so it must be replaced with a javascript function
    $text = str_replace('target="_blank"', 'onclick="window.open(this.href); return false;"', $text);  
    
    return $text;     
  }
  
  public static function getPageNameFromLink($link){ 
    $pageInfo = pathinfo($link);
    if (isset($pageInfo['extension']) && $pageInfo['extension'] == 'html')
    {
	    $startPos = strripos($link, '/');  //+1
	    $pageName = substr($link, $startPos, strlen($link) - $startPos);
	    $pageInfo = pathinfo($pageName);
	    $pageName = (isset($pageInfo['extension'])) ? str_replace('.' . $pageInfo['extension'], '', $pageInfo['basename']) : $pageName;
    }
    else
    {
    	$pageName = $link;
    }
    return $pageName;
  }
  
  /**
   * Format the size of the file.
   *
   * @param      string The name of the file.
   * 
   * @return     string The file size formatted.
   */
  public static function formatFileSize($fileName){
    $size = filesize($fileName);
    
    if ($size >= 1000000)
    {
    	$size = round($size/1000000, 1) . ' Mb';
    }
    else if ($size >= 1000)
    {
    	$size = round($size/1000) . ' Kb';
    }
    else
    {
    	$size = $size . ' Bytes';
    }
    
    return $size;
  }
  
  public static function displayMessage($message, $type='error', $closeLink = true)
  {
  	$class = $type . '_message';
  	$result = '<div id="w3s_message"><p class="' . $class . '">%s</p></div>';
  	if ($closeLink)
  	{
  		$result .= '<div id="w3s_message_close_section"><a href="#" onclick="W3sWindow.closeModal();">%s</a></div>';
  		$result = sprintf($result, $message, w3sCommonFunctions::toI18n('Close'));
  	}
  	else
  	{
  		$result = sprintf($result, $message);
  	}
  	
	  return $result;
  }

  // code derived from http://php.vrana.cz/vytvoreni-pratelskeho-url.php
  static public function slugify($text)
  {
    // replace non letter or digits by -
    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

    // trim
    $text = trim($text, '-');

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // lowercase
    $text = strtolower($text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    if (empty($text))
    {
      return 'n-a';
    }

    return $text;
  }
}