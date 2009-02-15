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
  $type = "error";
  switch($result){
    case 0:
      $message = __('An error occoured while saving record: try again.');       
      break;
    case 1:
      $message = __('The language has been correctly added.');
      $type = "success_14";
      break;
    case 2:
      $message =  __('This language already exists in the web site: use another name.');
      break;
    case 4:      
      $message =  __('The name of the language is required.');
      break;
  }
  
  echo w3sCommonFunctions::displayMessage($message, $type);  