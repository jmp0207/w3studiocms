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
 * w3sContentManagerLanguages extends the w3sContentManager to represent the
 * tool to change languages on multi-languages sites.  * 
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sContentManagerLanguages
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sContentManagerLanguages extends w3sContentManager{
 	
 	protected 
 		$imagesPath,
 		$imagesExtension;
 	
 	/**
   * Constructor.
   * 
   * @param int  The content's type
   * @param object  The w3sContent object. Can be null when adding
   * @param string  The image
   *
   */   
  public function __construct($type, $content){
    $this->imagesPath = sfConfig::get('app_w3s_web_skin_images_dir') . '/languages/';
    $this->imagesExtension = '.png';
    
    parent::__construct($type, $content);  
  }
 	
 		
  /**
   * Returns the default text for the text content
   *   
   * @return string
   *
   */ 
  public function getDefaultText()
  {
  	return sprintf('<img src="%s" %s />', sfConfig::get('app_w3s_web_skin_images_dir') . '/structural/w3s_languages.png', 'width="45" height="14"');          
  }
  
  /**
   * Returns the content formatted to be correctly displayed in the editor mode.
   * Overrides the same function of w3sContentManager
   *   
   * @return string
   *
   */ 
  public function getDisplayContentForEditorMode()
  {
  	return $this->getDefaultText();         
  }
  
  /**
   * Returns the content formatted to be correctly displayed in the preview mode.
   * Overrides the same function of w3sContentManager
   *   
   * @return string
   *
   */ 
  public function getDisplayContentForPreviewMode()
  {
  	return $this->drawLanguages('preview');         
  }
  
  /**
   * Returns the content formatted to be correctly displayed in the publish mode.
   * Overrides the same function of w3sContentManager
   *   
   * @return string
   *
   */ 
  public function getDisplayContentForPublishMode()
  {
  	return $this->drawLanguages('publish');         
  } 
  
  /**
   * Sets the value of the imagesPath variable.
   * 
   * @param string
   *
   */ 
  public function setImagesPath($value)
  {
  	$this->imagesPath = w3sCommonFunctions::checkLastDirSeparator($value);         
  } 
  
  /**
   * Returns the value of the imagesPath variable.
   * 
   * @return int
   *
   */ 
  public function getImagesPath()
  {
  	return $this->imagesPath;         
  }
  
  /**
   * Sets the value of the imagesPath variable.
   * 
   * @param string
   *
   */ 
  public function setImagesExtension($value)
  {
  	$value = strtolower($value);
  	$validExtensions = array('gif', 'jpg', 'png', 'tif', 'tiff', 'bmp');
  	if (!in_array($value, $validExtensions)){
    	throw new RuntimeException(sprintf('%s requires the following extensions: \'%s\'.', get_class($this), implode('\', \'', $validExtensions))); 
    }
  	$this->imagesExtension = '.' . $value;         
  } 
  
  /**
   * Returns the value of the imagesPath variable.
   * 
   * @return int
   *
   */ 
  public function getImagesExtension()
  {
  	return $this->imagesExtension;         
  }
  
  /**
   * Draws the languages' table as a horizontal toolbar. This function assumes that
   * every image that represents a language has the same name of the language. Override
   * this function if you want to change this behaviour
   *   
   * @return string
   *
   */ 
  protected function drawLanguages($mode){
  	
  	$buttons = array();
  	$pageName = $this->content->getW3sPage()->getPageName();
  	
  	// Cycles all the site's languages
  	$languages = DbFinder::from('W3sLanguage')->find();
	  foreach($languages as $language){
	    $curLanguageName = strtolower($language->getLanguage()); 
	    
	    // Set the buttons
	    $image = $this->imagesPath . $curLanguageName . $this->imagesExtension;
	    if ($mode == 'preview')
	    {
	    	$button = array('image' => $image,
	                      'action' => sprintf('loadPreviewPage(\'%s\', %s, %s);', sfContext::getInstance()->getController()->genUrl('webEditor/preview'), $language->getId(), $this->content->getPageId()));
	    }
	    else
	    {
	    	$button = array('image' => $image,
	                      'linkedTo' => sprintf(DIRECTORY_SEPARATOR . '%s' . DIRECTORY_SEPARATOR . '%s.html', $language->getLanguage(), $pageName));
	    }
	    $buttons[] = $button;	    
	  }     
	 	
	 	// Draws the languages
	  $toolbar = new w3sToolbarHorizontal(); 
    $toolbar->setToolbar($buttons);    
	  return sprintf('<table>%s</table>', $toolbar->renderToolbar()); 
  }
}