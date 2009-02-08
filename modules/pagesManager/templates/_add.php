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

  use_helper('I18N', 'Object', 'Javascript');

  // If something failed W3StudioCMS shows the add/edit module again
  if ($result != 1)
  { 
  	$pageEditor = new w3sPageEditor();
  	echo $pageEditor->render();
  }      

  // Result messages
  switch($result){
    case 0:
      $message = __('An error occoured during the save operation. Page not added.');
      break;
    case 1:
      $message = __('Page added succesfully!<br />Loading pages, please wait.');
      break;
    case 2:
      $message = __('This page already exists in the web site: use another name.');
      break;
    case 4:
      $message = __('You must provide a name for the new page.');
      break;
    case 8:
      $message = __('The new page you are inserting cannot be assigned to group provided because it doesn\'t exist anymore');
      break;
    case 16:
      $message = __('The new page you are inserting cannot be assigned to group provided because it has been removed from w3studioCMS');
      break;  
  }
  
  echo w3sCommonFunctions::displayMessage($message, 'error', false);