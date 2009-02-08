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
 * w3sEditorFactory class. Inspired by factory pattern, returns the  
 * subclassed editor, according to the content's type to edit.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sEditorFactory
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sEditorFactory
{
	/**
   * Creates the appropriated editor, according to the type of content to edit
   * 
   * @param object 
   * 
   * @return object
   *
   */ 
	public static function create($content)
	{
		switch ($content->getContentTypeId())
		{
			case 3: 
				return new w3sImageEditor($content, 'w3s_image_manager');
				break;
			case 4: 
				return new w3sScriptEditor($content, 'w3s_scripts_manager');
				break;
		  case 5: 
				return new w3sMenuEditor($content, 'w3s_menu_builder');
				break;
			default:
			  return new w3sTextEditorTinyMCE($content, 'w3s_editor_container');			  
		}
	}
}