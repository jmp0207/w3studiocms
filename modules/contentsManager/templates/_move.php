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
  
  switch($result){
    case 0:
      $message = __('An error occoured while moving the requested content.');
      $type = "error";
      $closeLink = true;       
      break;
    case 1:
      $message = __('The content has been correctly moved.');
      $type = "success_14";
      $closeLink = false;
      break;
  }
  
  echo w3sCommonFunctions::displayMessage($message, $type, $closeLink);  
/*
  // Result messages
  switch($result){
    case 0:
      echo '<p class="error_message_14" style="padding:10px;">' . __('An error occoured while moving the requested content.') . '</p>';
      break;
    case 1:
      echo '<p class="error_message_14" style="padding:10px;">' .__('An error occoured while moving one content.') . '</p>';
      break;
    case 2:
      echo '<p class="success_message_14" style="padding:10px;">' . __('The content has been succesfully moved.') . '</p>';
      break;
    
  }

  if ($result != 2):
?>
<p style="font: 11px Verdana, Arial, Helvetica, sans-serif; padding:10px; text-align:left;">
  <?php echo __('You can try to change page, reenter in this page and redo the operation you made.<br /><br />If problem persists you can try to logout, signin again and redo the operation you made.<br /><br />If problem persists too, reports the error to W3StudioCMS web site \'s forum.');?>
  <?php echo link_to_function(__('Close'), 'closeModalWindow()'); ?>
</p>
<?php endif ;*/