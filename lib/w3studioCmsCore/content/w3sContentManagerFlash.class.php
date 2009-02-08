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
 * w3sContentManagerFlash extends the w3sContentManager to represent a flash
 * content. This object has a minimal interface and will be implemented
 * in the future.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sContentManagerFlash
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
  
class w3sContentManagerFlash extends w3sContentManager{
 
  /**
   * Returns the default text for the flash content
   *   
   * @return string
   *
   */ 
  public function getDefaultText()
  {
  	return 'TODO';           
  }

  /**
   * Returns the content formatted to be correctly displayed in the editor mode.
   *   
   * @return string
   *
   */ 
  public function getDisplayContentForEditorMode()
  {
  	$content = $this->content->getContent();
  	$width = w3sCommonFunctions::getTagAttribute($content, 'width');
    $height = w3sCommonFunctions::getTagAttribute($content, 'height');
  	
  	return sprintf('<div style="width:%spx;height:%spx;background-image:url(%s);background-position:center center;background-repeat:no-repeat;border:1px dotted #D90000;"></div>', $width, $height, sfConfig::get('app_w3s_web_skin_images_dir') . '/structural/w3s_flash.png');           
  }
}