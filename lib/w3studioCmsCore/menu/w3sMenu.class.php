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
 * w3sMenu is the base class to draw an interface that can be builded by toolbars 
 * or predefined skeletons, called menu
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sMenu
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
abstract class w3sMenu {	
	protected
	  $menuId,													// The id of the menu 
	  $user,														// A reference to current user
	  $toolbarFile,											// The name of the yml file that describes the menu
	  $skeleton = '<table>%s</table>';
	  
	abstract protected function drawMenu();
	
	/**
   * Constructor.
   * 
   * @param string  The id of the menu
   * @param object  A reference to current user
   *
   */      
	public function __construct($menuId, $user = null){ 
		$this->menuId = $menuId; 
		$this->user = $user; 
	}
	
	/**
   * Renders the menu.
   *   
   * @return string - The rendered menu
   *
   */ 
	public function renderMenu(){
  	return sprintf('<div id="%s">%s</div>', $this->menuId, $this->drawMenu()); 
	}
	
	/**
   * Sets the value of the toolbarFile variable.
   * 
   * @param string 
   *
   */  
	public function setToolbarFile($value)
	{
	  $this->toolbarFile = $value;
	}
	
	/**
   * Returns the value of the toolbarFile variable.
   * 
   * @return string
   *
   */ 
	public function getToolbarFile()
	{
	  return $this->toolbarFile;
	}
	
	/**
   * Sets the value of the skeleton variable.
   * 
   * @param string 
   *
   */
	public function setSkeleton($value)
	{
	  $this->skeleton = $value;
	}
	
	/**
   * Returns the value of the skeleton variable.
   * 
   * @return string
   *
   */
	public function getSkeleton()
	{
	  return $this->skeleton;
	}
	
}