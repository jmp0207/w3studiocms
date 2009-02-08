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
 * w3sContentManagerText extends the w3sContentManager to represent a text
 * content.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sContentManagerText
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sContentManagerText extends w3sContentManager{
 
  /**
   * Returns the default text for the text content
   *   
   * @return string
   *
   */ 
  public function getDefaultText()
  {
  	return 'Click to edit this text';           
  }
}