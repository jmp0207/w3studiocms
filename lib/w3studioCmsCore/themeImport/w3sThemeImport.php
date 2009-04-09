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
 * w3sThemeImport class represents the interface to manage the templates.
 * This class must be deeply tested.
 *  
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sThemeImport
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sThemeImport implements w3sEditor
{
	protected 
		$interfaceSkeleton = 
			'<div id="%s">
				<table style="width:100%%">
	        <tr><td><div class="w3s_toolbar"><table>%s</table></div></td></tr>
					<tr><td id="w3s_template_import_contents" valign="top">%s</td></tr>
					<input type="submit" id="w3s_uploader_support" onclick="W3sThemeImport.extract(); return false;" />
				</table>
			 </div>',	  
		$toolbarFile = 'tbTemplateImport.yml',
		$themes = array(),
		$defaultInfo = array("Author" => '', "License" => '');
		
	public function render()
	{
		return sprintf($this->interfaceSkeleton, 'w3s_import_templates', 
																						 $this->renderToolbar(), 
																						 $this->renderThemes(DbFinder::from('W3sProject')->find()));
	}
	
	public function setDefaultInfo($array = null)
	{
		$this->defaultInfo = $array;
	} 
	
	protected function renderToolbar()
	{
		$toolbar = new w3sToolbarHorizontal(); 
    $toolbar->fromYml($this->toolbarFile);
    
    return $toolbar->renderToolbar();
	}
	
	public function renderThemes()
  {
    $themeRows = '';
    
    if ($handle = opendir(sfConfig::get('app_w3s_web_templates_dir'))){
	    while (false !== ($file = readdir($handle)))
	    {
	    	if ($file != "." && $file != ".." && $file != ".svn" && $file != ".gitignore")
	    	{
	        $theme = DbFinder::from('W3sProject')->
	        										 where('ProjectName', $file)->
	        										 findOne();
	        $exists = count($theme);
		    	
		    	$themeRows .= sprintf('<tr><td style="text-align:left;">%s</td></tr>', $this->renderThemeRow($file, $exists));
	    	}
	    }
    }
    
    return sprintf('<table id="w3s_table_import">%s</table>', $themeRows);;
  }
  
  protected function renderThemeRow($themeName, $exists)
  {
    $rowSchema = '<span class="info_header">%s:</span> %s<br />';
    $defaultImage = sfConfig::get('app_w3s_web_images_dir') .  '/common/template_sample.png';
    $infoDir = sprintf("%1\$s%2\$s%3\$s%2\$sdata%2\$s", sfConfig::get('app_w3s_web_templates_dir'), DIRECTORY_SEPARATOR, $themeName);
    
    $fileInfo = $infoDir . 'info.yml';
   	$info = (is_file($fileInfo)) ? sfYaml::load($fileInfo) : null;
   	if ($info != null)
   	{
   		
      // Image needs the absolute path
      //$infoDir = str_replace(sfConfig::get('sf_web_dir'), '', $infoDir);
      $info = $info["Info"];
   		//$info['Image'] = $infoDir . $info['Image']; 
   	}
   	else
   	{
   		$info = array('Author' => 'Unknown', 'License' => 'Unknown', 'Image' => $defaultImage);
   	}
   	
   	$themeRows = '';
    $themeInfo = array_intersect_key($info, $this->defaultInfo);
   	$addInfo = array('Theme name' => $themeName);
   	$themeInfo = array_merge($addInfo, $themeInfo);
   	   	
   	foreach($themeInfo as $key => $value)
    {
    	$themeRows .= sprintf($rowSchema, $key, $value);
    } 
    $themeRows .= sprintf($rowSchema, __('Theme\'s templates'), count($this->scanTheme($themeName)));
    
    $operation = '';
    if ($exists == 0)
    {
    	$operation = link_to_function(__('Add'), 'W3sTemplateImport.add(\'' . $themeName . '\')', 'class="link_button"');
    }

    return sprintf('<tr><td style="width:96px;">%s</td><td valign="bottom" style="width:250px;"><p>%s</p></td><td valign="bottom">%s</td></tr>', image_tag($info['Image'], "class=template_image"), $themeRows, $operation);
  }
  
  protected function scanTheme($themeName)
  {
    $result = array();
    $themeDir = sfConfig::get('app_w3s_web_templates_dir')  . DIRECTORY_SEPARATOR . $themeName . DIRECTORY_SEPARATOR . 'templates';
    if ($handle = opendir($themeDir))
    {
      while (false !== ($file = readdir($handle)))
      {
        if ($file != "." && $file != ".." && $file != ".svn")
        {
          $currentFile = $themeDir . DIRECTORY_SEPARATOR . $file;
          if (is_file($currentFile))
          {
            $theme_dir = $currentFile;
            // cut off file extension
            $result[] = substr($file, 0, -4);
          }
        }
      }
      closedir($handle);
    }
    
    return $result;
  }
  
  protected function getSlotsFromTemplate($themeName, $templateName)
  {	
  	$templateFile = sprintf("%1\$s%2\$s%3\$s%2\$stemplates%2\$s%4\$s.php", sfConfig::get('app_w3s_web_templates_dir'), DIRECTORY_SEPARATOR, $themeName, $templateName);
  	$contents = w3sCommonFunctions::readFileContents($templateFile);	  
	  preg_match_all('/include_slot\s*\(\s*[\'|"](.*?)[\'|"]\s*\)/', $contents, $matchResults);
	  
		return $matchResults[1];
  }
  
  public function add($themeName)
  {
    $bRollBack = false;
    $con = Propel::getConnection();
    $con = w3sPropelWorkaround::beginTransaction($con); 
    
    $theme = new W3sTheme();
    $theme->setThemeName($themeName);
    $result = $theme->save();
    if ($theme->isModified() && $result == 0) $bRollBack = true;
     
    if (!$bRollBack)
    {
	    $idTheme = $theme->getId();
	    $templates = $this->scanTheme($themeName);
	   	foreach ($templates as $templateName)
	   	{ 
	    	$template = new W3sTemplate;
	      $template->setThemeId($idTheme);
	      $template->setTemplateName($templateName);
	      $result = $template->save();
	      if ($template->isModified() && $result == 0)
	      {
	        $bRollBack = true;
	        break;
	      }
	      
	      if (!$bRollBack)
    		{
		      $idTemplate = $template->getId();
		      $slots = $this->getSlotsFromTemplate($themeName, $templateName);
		      foreach($slots as $slotName)
		      {
		      	$slot = new W3sSlot;
			      $slot->setTemplateId($idTemplate);
			      $slot->setSlotName($slotName);
			      $result = $slot->save();
			      if ($slot->isModified() && $result == 0)
			      {
			        $bRollBack = true;
			        break;
			      }
		      }
    		}
	   	}
    }
    
    if (!$bRollBack)
    { // Everything was fine so W3StudioCMS commits to database
      $con->commit();
      $result = 1;
    }
    else
    { // Something was wrong so W3StudioCMS aborts the operation and restores to previous status
      w3sPropelWorkaround::rollBack($con);
      $result = 0;
    }
    
    return $result;
  }
  
  /*
  protected function renderAvailableThemes()
  {
    $result = array();
    $themeRows = '';
    $loadedThemes = $this->scanThemes();
    $availableThemes = array_diff($loadedThemes, $this->themes);
    foreach ($availableThemes as $availableTheme)
    {
    	$themeRows .= sprintf('<tr><td style="text-align:left;">%s</td></tr>', $availableTheme);
    }
    
    return sprintf('<table>%s</table>', $themeRows);;
  }
  
  protected function renderTemplates()
  {
    $templates = DbFinder::from('W3sTemplate')->  
		                      leftJoin('W3sTheme')->
		                      where('W3sTheme.InUse', '1')->
		                      find();   
    $templateRows = '';
    foreach ($templates as $template)
    {
    	$templateRows .= sprintf('<tr><td style="text-align:left;">%s</td></tr>', $template->getTemplateName());	
    }
    
    return sprintf('<table>%s</table>', $templateRows);;
  }*/
	
	
  
	
	 
}
