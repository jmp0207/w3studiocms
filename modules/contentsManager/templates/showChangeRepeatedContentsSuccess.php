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
?>
<?php use_helper('I18N', 'Javascript', 'Object') ?>
<?php $repeated = ($slot != null) ? $slot->getRepeatedContents() : -1 ?>
<?php if ($repeated > -1): ?>
<div id="w3s_repeated">
  <div class="form_row">
  <p><?php echo __('In this module you can choose how the selected content will be repeated in the web site.');?></p>
  <p><?php echo __('For example the logo is usually the same for all the site\'s pages, and this module help you to do this operation one time for all.');?></p>
  <p><?php echo __('The only condition is that the DIV\'s name declared in the template must be the same.');?></p> 
  <p><?php echo __('For example your web site has three templates: if exists a DIV called Logo in every template, when you save the content for one page this will be repeated for every page.');?></p>
  </div>
    
  <div class="form_row" class="align_center">
  <?php
    $repeatedValues = array(
      '0' => __('Content is not repeated'),
      '1' => __('Content is repeated for the pages of their group'),
      '2' => __('Content is repeated for the pages of entire website'),);
    echo select_tag('repeated_value', options_for_select($repeatedValues, $repeated));
  ?>
  </div>
  <div class="form_row" class="align_center">
  <?php
    echo link_to_function(__('Change repeated status'), 'W3sControlPanel.changeRepeatedStatus(\'' . $slot->getSlotName() . '\')', 'class="link_button"');
  ?>
  </div>
</div>
<?php else: ?>
  <div class="form_row">
    <p class="error_message"><?php echo __('There is a problem with this content: it seems that it doesn\'t exist anymore. Try to delete and insert it again.');?></p>
  </div>
<?php endif; ?>  