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
 
class w3sContentManagerLanguagesByImageLinks extends w3sContentManagerLanguages
{ 	
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
  public function __construct($type, $content)
  {
    $this->imagesPath = sfConfig::get('app_w3s_web_skin_images_dir') . '/languages/';
    $this->imagesExtension = '.png';

    parent::__construct($type, $content);  
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
  public function drawLanguages($mode){
  	
  	$buttons = array();
  	$pageName = $this->content->getW3sPage()->getPageName();
  	
  	// Cycles all the site's languages
  	$languages = DbFinder::from('W3sLanguage')->find();
	  foreach($languages as $language)
    {
	   
	    // Set the buttons
	    $image = $this->imagesPath . $language->getLanguage() . $this->imagesExtension;
	    if ($mode == 'preview')
	    {
	    	$button = array('image' => $image,
	                      'action' => sprintf('W3sTemplate.loadPreviewPage(\'%s\', %s, %s);', sfContext::getInstance()->getController()->genUrl('webEditor/preview'), $language->getId(), $this->content->getPageId()));
	    }
	    else
	    {
	    	$button = array('image' => $image,
	                      'linkedTo' => sprintf('/%s/%s.html', $language->getLanguage(), $pageName));
	    }
	    $buttons[] = $button;	    
	  }     
	 	
	 	// Draws the languages
	  $toolbar = new w3sToolbarHorizontal(); 
    $toolbar->setToolbar($buttons);    
	  return sprintf('<table>%s</table>', $toolbar->renderToolbar()); 
  }
}