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
 * w3sMenuHorizontal extends the w3sMenu. Draws an horizontal menu.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sMenuInteractive * 
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sMenuInteractive extends w3sMenu{
	
	/**
   * Constructor.
   * 
   * @param string  The id of the menu
   * @param object  A reference to current user
   *
   */ 
	public function __construct($menuId, $user = null){ 
		// Defines the skeleton
		$this->skeleton = 
			'<a href="#" id="w3s_editor_opener" onclick="InteractiveMenu.openEditor();return false;"><div id="w3s_im_clone_element"></div></a>
			 <div id="w3s_im_editor" style="display:none;"></div>';
			 
		parent::__construct($menuId, $user);
	}
	
	/**
   * Draws the menu.
   *   
   * @return string - The rendered menu
   *
   */   
	public function drawMenu(){
  	return $this->skeleton; 
	}
}