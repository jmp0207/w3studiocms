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

  use_helper('I18N');

  // Result messages
  $type = 'error';
	switch($result){
	  case 0:
	    $message = __('An error occoured during the metatags\' saving operation.');
	    break;
	  case 1:
	  	$type = 'success_14';
	    $message = __('Metatags saved succesfully!');
	    break;
	  case 2:
	    $message = __('WARNING: A serious error occoured. A required parameter has not been initialized.');
	    break;      
	}
	
	if ($result != 1) $message .= __('You can try to change page, reenter in this page and redo the operation you made.<br /><br />If problem persists you can try to logout, signin again and redo the operation you made.<br /><br />If problem persists too, reports the error to W3StudioCMS web site \'s forum or write to W3StudioCMS\'s assistance.');
	echo w3sCommonFunctions::displayMessage($message, $type, false);