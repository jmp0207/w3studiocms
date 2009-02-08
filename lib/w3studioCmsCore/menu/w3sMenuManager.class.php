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
 * w3sMenuManager extends w3sMenuHorizontal. Draws the menu manager menu, that is
 * an horizontal menu.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sMenuManager
 * 
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
class w3sMenuManager extends w3sMenuHorizontal{
  protected $partialSkeleton = 
			'<table id="w3s_menu_manager_commands" border="0" cellpadding="0" cellspacing="0">%s</table>';
		   
	/**
   * Constructor.
   * 
   * @param string  The id of the menu
   * @param string  The toolbar file name
   * @param object  A reference to current user
   *
   */ 
  public function __construct($menuId, $toolbarFile, $user = null){ 
		$this->skeleton = 
			'<div id="w3s_menu_manager_left_controller" style="width:30px;height:95px;float:left;"></div>
		   <div id="w3s_menu_manager_left">&nbsp;</div>
		   <div id="w3s_menu_manager_center">
				 <table id="w3s_menu_manager_commands" border="0" cellpadding="0" cellspacing="0">
		    	 %s      
		     </table>
			 </div>
		   <div id="w3s_menu_manager_right">&nbsp;</div>
		   <div id="w3s_menu_manager_right_controller" style="width:30px;height:95px;float:right;"></div>
		   <div id="w3s_menu_manager_bottom_controller" style="width:608px;height:30px;clear:both;"></div>';
		
		parent::__construct($menuId, $toolbarFile, $user);
	}
	
	public function renderMenuManager($mode='full'){
  	return ($mode == 'full') ? sprintf('<div id="%s">%s</div>', $this->menuId, $this->drawMenu()) : $this->drawPartialMenu(); 
	}
	
	/**
   * Draws the menu.
   *   
   * @return string - The rendered menu
   *
   */ 
	protected function drawPartialMenu(){ 
		$toolbar = new w3sToolbarHorizontal($this->user); 
    $toolbar->fromYml($this->toolbarFile);
    
    return $toolbar->renderToolbar();
	}
}