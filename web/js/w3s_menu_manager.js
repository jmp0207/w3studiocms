/*
 * This file is part of the w3studioCMS package library and it is distributed 
 * under the LGPL LICENSE Version 2.1. To use this library you must leave 
 * intact this copyright notice.
 *  
 * (c) 2007-2008 Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 *  
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 

var w3sMenuManager = Class.create({ 
	
	initialize: function()
	{
		$('w3s_menu_manager_left_controller').observe('mouseover', this.hide, false);
		$('w3s_menu_manager_right_controller').observe('mouseover', this.hide, false);
		$('w3s_menu_manager_bottom_controller').observe('mouseover', this.hide, false);		
	},
	
	show: function()
	{
	  new Effect.BlindDown('w3s_menu_manager', {});
	},
	
	hide: function()
	{
	  new Effect.BlindUp('w3s_menu_manager', {});
	},
	
	load: function(toolbarFile, sActionPath)
	{ 
	  
	  if (sActionPath == undefined) sActionPath = w3studioCMS.frontController + 'webEditor/loadMenuManager';	  
	  new Ajax.Updater('w3s_menu_manager_commands', sActionPath,
	      {asynchronous:true,
	       evalScripts:true,
	       parameters:'toolbarFile=' + toolbarFile + 
	       			  		'&mode=partial'});
	  return false;
	}
});