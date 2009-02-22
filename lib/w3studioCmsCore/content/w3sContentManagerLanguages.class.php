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
 
abstract class w3sContentManagerLanguages extends w3sContentManager{
 	
 	/**
   * Constructor.
   * 
   * @param int  The content's type
   * @param object  The w3sContent object. Can be null when adding
   * @param string  The image
   *
   *   
  public function __construct($type, $content)
  {
    parent::__construct($type, $content);  
  }*/

  abstract function drawLanguages($mode);
 		
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
}