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
 
/**
 * Template class represents the page's template.
 * 
 * Note: This object is not completed yet
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sTemplateEngine
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
abstract class w3sTemplateEngine
{
  protected 
    $idPage,
    $idLanguage,
    $idTemplate,
    $languageName,								// Redonly
    $pageName,                    // Redonly
    $templateName,
    $projectName,
    $templateFileName, 
    $pageContents;
    
	abstract function drawSlot($contents);

	/**
   * Constructor.
   * 
   * @param int  The current language id
   * @param int  The current page id
   *
   */   
  public function __construct($language, $page)
  {
    // When language is null, main language is retrieved
    if ($language == null) $language = 'none';

    // When page is null, home page is retrieved
    if ($page == null) $page = 'none'; 

    // Checks that the two parameters are integers values    
    if ((int)($language) == 0)
    {
    	$oLanguage = ($language != 'none') ? W3sLanguagePeer::getFromLanguageName($language) : W3sLanguagePeer::getMainLanguage();
    }
    else
    { 
      $oLanguage = DbFinder::from('W3sLanguage')->findPk($language); //W3sLanguagePeer::getMainLanguage();
    }
    
    if ($oLanguage != null)
    {
      $this->idLanguage = $oLanguage->getId();
      $this->languageName = $oLanguage->getLanguage();
    }
    else
    {
      $this->idLanguage = -1;
      $this->languageName = 'none';
    }
    
    if ((int)($page) == 0)
    {    	
    	$oPage = ($page != 'none') ? W3sPagePeer::getFromPageName($page) : W3sPagePeer::getHomePage();      
    }
    else
    {    	
    	$oPage = DbFinder::from('W3sPage')->findPk($page);
    }
    
    if ($oPage != null)
    {
      $this->idPage = $oPage->getId();
      $this->pageName = $oPage->getPageName();
    }
    else
    {
      $this->idPage = -1;
      $this->pageName = 'none';
    }

    if ($this->idPage != -1) $this->setTemplateInfo($this->idPage);
  }
  
  public function setIdPage($value){
    $this->idPage = $value;
  }
  
  public function getIdPage(){
    return $this->idPage;
  }

  public function setIdLanguage($value){
    $this->idLanguage = $value;
  }  
  
  public function getIdLanguage(){
    return $this->idLanguage;
  }
  
  public function getLanguageName(){
    return $this->languageName;
  }
  
  public function getPageName(){
    return $this->pageName;
  }
  
  public function getPageContents()
  {
    return $this->pageContents;  
  }

  /**
   * Retrieves from an instance of the page object the informations about the template
   * used in the current page.
   *
   * @param obj  An instance of the w3sPage object
   *
   * @return array
   *
   */
  public static function retrieveTemplateAttributesFromPage($page)
  {
    if (!$page instanceof W3sPage) throw new RuntimeException(sprintf('This function requires a W3sPage class object. You passed an instance of %s object', get_class($page)));
    return array("idTemplate"   => $page->getW3sGroup()->getW3sTemplate()->getId(),
								 "templateName" => strtolower($page->getW3sGroup()->getW3sTemplate()->getTemplateName()),
                 "projectName"  => strtolower($page->getW3sGroup()->getW3sTemplate()->getW3sProject()->getProjectName()));
  }

  /**
   * Returns the template file path.
   *
   * @param str  Project name
   * @param str  Template name
   *
   * @return array
   *
   */
  public static function getTemplateFile($projectName, $templateName)
  {
    return sprintf("%1\$s%2\$s%3\$s%2\$s%4\$s%2\$s%4\$s.php", sfConfig::get('app_w3s_web_templates_dir'), DIRECTORY_SEPARATOR, $projectName, $templateName);
  }

  /**
   * Returns the available classes for a given slot. Can retrieve only the class
   * name or the full CSS style. This is made with the mode parameter
   *
   * @param      str  The slot's name.
   * @param      int  optional 0 retrieves only the class name [Default]
   *                           1 Retrieve the full css style
   *
   * @return     array  The found classes
   */
	public static function findStylesheetClasses($content, $mode=0)
  {
    // This is only a paliative solution. Hope someone can fix the parse class: I don't know Call-time pass-by-reference
    ini_set('error_reporting', 'E_ERROR');

    require_once(dirname(__FILE__).'/../tools/parser/htmlparser.inc');
    require_once(dirname(__FILE__).'/../tools/parser/common.inc');

    $slotName = $content->getW3sSlot()->getSlotName();
    $page = $content->getW3sPage();
    // Opens the template and parses its structure
    $templateAttributes = self::retrieveTemplateAttributesFromPage($page);
    $templateFile = self::getTemplateFile($templateAttributes["projectName"], $templateAttributes["templateName"]);
    $p=new HtmlParser($templateFile, unserialize(Read_File("parser/htmlgrammar.cmp")), $templateFile, 1);
    $p->Parse();
    $src="";
    GetPageSrc($p->content,$src);
    
    ob_start();
    PrintArray($p->content);
    $contents = ob_get_clean();

    // Finds the id of Slots
    $i=1;
    $elements = array($slotName);
    while(1)
    {
      preg_match('/(.*)\[content\].*\[pars\]\[id\]\[value\]=' . $slotName . '/', $contents, $res);
      if (count($res) == 0) break;
      $startKey = str_replace("[", "\[", $res[1]);
      $startKey = str_replace("]", "\]", $startKey);
      preg_match('/' . $startKey . '\[pars\]\[id\]\[value\]=(.*)/', $contents, $res);
      $elements[] = $res[1];
      $slotName = $res[1];
      $i++;

      // Prevents blocks if an infinite loop occours if a non well-format template is searched
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
    foreach ($stylesheets[1] as $stylesheet)
    {
      $stylesheet = substr($stylesheet, 1, strlen($stylesheet));
      $fp = fopen ($stylesheet, "r");
      $currentContent = fread($fp, filesize($stylesheet));
      fclose($fp);
      $currentContent = str_replace("\r\n", "", $currentContent);
      $currentContent = preg_replace('/HTML>.*?}+?/', '', $currentContent);
      $contents .= $currentContent;
    }

    // Find classes from xhtml elements
    $result = ($mode == 0) ? array('w3sNone' => 'None') : array();
    foreach($elements as $element)
    {
      $expression = ($mode == 0) ? '/#' . trim($element) . '[a-zA-Z0-9-_:\s]*\.(.*?)\{+?/' : '/#' . trim($element) . '[a-zA-Z0-9-_:\s]*(\..*?\{.*?\})+?/';
      preg_match_all($expression, $contents, $classes);
      foreach($classes[1] as $class)
      {
        if ($mode == 0)
        {
	        $result[$class] = $class;
        }
        else
        {
	        $result[] = $class;
        }
      }
    }

    // Find classes not associated to xhtml elements
    $expression = ($mode == 0) ? '/(^|})\.(.*?)\{+?/' : '/(^|})(\..*?\{.*?\})+?/';
    preg_match_all($expression, $contents, $classes);
    foreach($classes[2] as $class)
    {
      if ($mode == 0)
      {
	      $result[$class] = $class;
	    }
	    else
	    {
	      $result[] = $class;
	    }
    }

    return $result;
  }
  
  /**
   * Checks if the requested page is not in use from another user, and free the 
   * previous page used by current user.
   * 
   * @param int  The previous page id
   *
   */   
  public function isPageFree($prevPage)
  {
    $operation = $this->idLanguage . $this->idPage;
    $prevOperation = $this->idLanguage . $prevPage;
    
    return semaphore::setRequestedOperation(sfContext::getInstance()->getUser()->getGuardUser()->getId(), $operation, $prevOperation);
  }

	/**
   * Reads all the site's template files and extracts the stylesheets' references. 
   * 
   * Note: It's not possibile to use the $this->response->addStyleSheet method as 
   * made in the webSite module, because w3studioCMS needs the title attribute to 
   * change the stylesheets in editor mode.
   * 
   * @return string  The html stylesheets 
   *
   */  
  public function retrieveSiteStylesheets()
  {    
    // Gets all the project's templates from the database
    $templates = DbFinder::from('W3sTemplate')->  
		                       leftJoin('W3sProject')->
		                       find();   
    
    $result = ''; 
    foreach($templates as $template){
    	$templateContents = w3sCommonFunctions::readFileContents(self::getTemplateFile($template->getW3sProject()->getProjectName(), $template->getTemplateName()));
    	$stylesheets = $this->getStylesheetsFromContents($templateContents);  
    	foreach ($stylesheets[0] as $stylesheet){    
    		
    		// Set for every stylesheet the title that corresponds to the stylesheet's name.
    		// This is required by the function that changes the template's stylesheet    		
    		$stylesheetName = basename(w3sCommonFunctions::getTagAttribute($stylesheet, 'href'));
    		$stylesheetName = str_replace('.css', '', $stylesheetName);
	      $result .= str_replace('<link', sprintf('<link title="%s" ', $stylesheetName), $stylesheet) . "\n";    		
	    }
    }

    return $result; 
  }
  
  /**
   * Retrieves the stylesheets used by the current template. The result string will
   * be uses to change the template's stylesheet. 
   * 
   * @return string  The stylesheets name formatted as style1,[style2,style3,...]
   *
   */ 
  public function retrieveTemplateStylesheets() // $contents = null
  {    
    $stylesheets = $this->getStylesheetsFromContents($this->pageContents);
    $this->pageContents = $this->removeStylesheetsFromTemplate($stylesheets, $this->pageContents);
    
    $stylesheetResults = '';
	  foreach ($stylesheets[1] as $stylesheet)
    {
	    $stylesheetResults .= str_replace('.css', '',basename($stylesheet)) . ',';
	  }
    
    return $stylesheetResults; 
  }  
  
  /**
   * Renders the page
   * 
   * @return string  The rendered page 
   *
   */ 
  public function renderPage()
  {
    $slotNames = '';
    if ($this->idLanguage != -1 && $this->idPage != -1)
    {
      $slotContents = $this->getSlotContents($this->idLanguage, $this->idPage);
      foreach ($slotContents as $slot){
        $slotNames .= sprintf('"%s",', $slot['slotName']);
        $contents = $this->drawSlot($slot);
        $this->pageContents = preg_replace('/\<\?php.*?include_slot\(\'' . $slot['slotName'] . '\'\).*?\?\>/', $contents, $this->pageContents);
      }
    }
    else
    {
      $this->pageContents = w3sCommonFunctions::displayMessage('The page or the language requested does not exist anymore in the website');
    }
    
    // Renders the W3StudioCMS Copyright button. Please do not remove. See the function to 
    // learn the best way to implement it in your web site. Thank you
    $this->pageContents = $this->renderCopyright($this->pageContents);
    
    return $this->pageContents;
  }

  protected function setTemplateInfo($idPage)
  {
    // Gets the template information
    $page = DbFinder::from('W3sPage')->
                      with('W3sTemplate', 'W3sProject')->
                      leftJoin('W3sGroup')->
                      leftJoin('W3sTemplate')->
                      leftJoin('W3sProject')->
                      findPK($idPage);
    $this->setCurrentTemplate($page);
  }
    
  /**
   * Removes the stylesheets reference from the template
   * 
   * @param array    The template's stylesheets
   * @param string   The contents to process
   * 
   * @return string  The processed contents
   *
   */ 
  protected function removeStylesheetsFromTemplate($stylesheets, $contents)
  {
    foreach ($stylesheets[0] as $stylesheet)
    {
      $contents = str_replace($stylesheet, '', $contents); 
    }
    
    return $contents;
  }
  
  /**
   * Retrieves from the database the template associated to page requested
   * 
   * @return object  The retrieved page
   *
   */
  protected function setCurrentTemplate($page)
  {
    if ($page != null)
    {
	    $templateInfo = self::retrieveTemplateAttributesFromPage($page);
	    $this->idTemplate = $templateInfo["idTemplate"];
	    $this->templateName = $templateInfo["templateName"];
	    $this->projectName = $templateInfo["projectName"];
	    $this->pageContents = w3sCommonFunctions::readFileContents(self::getTemplateFile($this->projectName, $this->templateName));
    }
  }
  
  /**
   * Returns the page's contents grouped by slots, retrieved from database
   * 
   * @param int  The language id
   * @param int  The page id
   * 
   * @return array 
   *
   */
  protected function getSlotContents($idLanguage, $idPage)
  {
    $slotName = '';
    $isRepeated = 0;
    $resultContents = array();

    if ($this->idTemplate == null) $this->setTemplateInfo($idPage);

    $slots = DbFinder::from('W3sSlot')->
			                 where('TemplateId', $this->idTemplate)->
			                 where('ToDelete', 0)->
			                 orderBy('Id')->
			                 find();  
    foreach($slots as $slot)
    {            
	    $currentSlotContents = array();
	    $currentSlot = $slot->getId();
	    $slotName = $slot->getSlotName();
		  $isRepeated = $slot->getRepeatedContents();
	    $contents = DbFinder::from('W3sContent')->
	                where('LanguageId', $idLanguage)->
	                where('PageId', $idPage)->
	                where('SlotId', $currentSlot)->
	                where('ToDelete', 0)->
	                orderBy('ContentPosition')->
	                find();  
	    if ($contents != null)
	    {
		    foreach($contents as $content)
		    {
		      $currentSlotContents[] =  $content;
		    }
	    }
	    else
	    {
	    	$currentSlotContents[] = null; 
	    }  
	    
	    // Saves the last slot's content
	    $resultContents[] =  array('contents' => $currentSlotContents, 'idSlot' => $currentSlot, 'slotName' => $slotName, 'isRepeated' => $isRepeated);
    }
    
		return $resultContents;
  }
  
  /**
   * Returns the template's stylesheets 
   * 
   * @param string  The template contents
   * 
   * @return array
   *
   */
  protected function getStylesheetsFromContents($templateContents)
  {    
    preg_match_all('/<link.*?rel\s*=\s*["|\']stylesheet["|\'].*?href\s*=\s*["|\'](.*?)["|\'].*?\/>/', $templateContents, $stylesheets);
    
    return $stylesheets; 
  }
  
/** 
 * Renders the W3StudioCMS Copyright button.
 * This is the only way I have to let the world to know this software,  
 * for this reason I ask you to leave the copyright intact. Consider 
 * this favour as a little price for the free use of this software.
 *  
 * If you don't like the button or if you think that it isn't 
 * harmonized with your website and you still want to remove it, 
 * I hope you will consider the possibility to draw a new button 
 * or to give me a link back to http://www.w3studiocms.com. 
 * 
 * You can place the copyright everywhere on your template, simply adding an
 * include slot statement, as follows:
 * 
 * <?php include_slot('w3s_copyright')?> 
 * 
 * Suggested example
 * <div id="w3s_copyright"><?php include_slot('w3s_copyright')?></div>
 * 
 * If you don't specify that slot in your template, ws3studioCMS renders the 
 * copyright at the end of the page.
 * 
 * Thank you!
 */
  final protected function renderCopyright($pageContents)
  {
    $buttonText = (is_file(sfConfig::get('sf_web_dir') . sfConfig::get('app_w3s_web_skin_images_dir') . '/structural/w3scopyright.png')) ? sprintf('<img src="%s/structural/w3scopyright.png" style="width:75px;height:30px;display:inline !important;visibility:visible !important; border:0;" alt="Visit www.w3studiocms.com and download W3studioCMS for free to get your website" title="W3studioCMS a powerful ajax CMS" />', sfConfig::get('app_w3s_web_skin_images_dir')) : 'Powered by W3studioCMS';
    $w3sCopyrightButton = sprintf('<a href="http://www.w3studiocms.com">%s</a>', $buttonText);
    preg_match("/\<\.*?php include_slot\('w3s_copyright'\)\.*?\>/", $pageContents, $checkCopyright);
    if (count($checkCopyright) == 0)
    {
      $pageContents .= sprintf("\n" . '<div id="w3s_copyright" style="width=100%%;margin:8px 0;text-align:center;">%s</div>' . "\n", $w3sCopyrightButton);
    }
    else
    {
      $pageContents = preg_replace('/\<\?php.*?include_slot\(\'w3s_copyright\'\).*?\?\>/', $w3sCopyrightButton, $pageContents);

      /*$pageContents = str_replace('<?php include_slot(\'w3s_copyright\')?>', $w3sCopyrightButton, $pageContents);*/
    }
    
    return $pageContents;
  }
}