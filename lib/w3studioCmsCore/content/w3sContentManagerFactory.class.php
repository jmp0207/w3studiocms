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
 * w3sContentManagerFactory class. Inspired by factory pattern, returns the  
 * subclassed content, according to the required type.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sContentManagerFactory
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
  
class w3sContentManagerFactory
{

	/**
   * Creates the appropriated content, according to the $type parameter
   * 
   * @param int The object's type
   * @param object The object to encapsulate. Null when adding a content
   * 
   * @return object
   *
   */ 
	public static function create($type, $content = null){
		switch ($type){
			case 3: 
				return new w3sContentManagerImage($type, $content);
				break; 
			case 4: 
				return new w3sContentManagerScript($type, $content);
				break;
		  case 5: 
				return new w3sContentManagerMenu($type, $content);
				break;
		  case 6: 
				return new w3sContentManagerLanguagesByImageLinks($type, $content);
				break;
			case 7: 
				return new w3sContentManagerFlash($type, $content);
				break;
      case 8:
				return new w3sContentManagerLanguagesByTextualLinks($type, $content);
				break;
		  default: 
				return new w3sContentManagerText($type, $content);
				break;
		}
	}
}