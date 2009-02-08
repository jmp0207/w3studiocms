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

  use_helper('I18N', 'Javascript');

	// Result messages
	switch($result){
	  case 0:
	    $message = __('An error occoured while saving the record: try again.');
	    echo sprintf('<p class="error_message_10">%s</p>', $message);
	    break;
	  case 1:      
      echo $menuEditor->drawMenuItems();
      echo javascript_tag($menuEditor->getJsMenu());
	    break;      
	}