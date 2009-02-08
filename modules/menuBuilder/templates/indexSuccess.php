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

 use_helper('I18N', 'Javascript', 'Object');
?>

<div id="w3s_menu_builder">
  <table>
    <tr>
      <td valign="top">
        <div id="w3s_mb_menu"><?php include_partial('menuList', array("menuList" => $menuList)) ?></div>
        <?php echo __('Click link name to change its properties, drag and drop to order.') ?>
      </td>
      <td valign="top">
        <div id="w3s_mb_properties"><?php include_partial('showProperties', array('form' => $form, 'myForm' => $myForm)) ?></div>

        <div id="w3s_menu_options_header" style=""><?php echo __('Options') ?></div>
        <div id="w3s_menu_options" style="">
          <table cellspacing="0" cellpadding="0">
            <tr>
              <td style="border-bottom:1px dotted #999;"><?php echo __('Set as active class'); ?></td>
              <td style="border-bottom:1px dotted #999;"><?php echo select_tag('w3s_assigned_class', options_for_select($availableClasses, '')) ?></td>
            </tr>
            <tr>
              <td style="border-bottom:1px dotted #999;"><?php echo __('Set the class on each page'); ?></td>
              <td style="border-bottom:1px dotted #999;"><?php echo checkbox_tag('w3s_class_page_assign', false) ?></td>
            </tr>
            <tr>
              <td style="border-bottom:1px dotted #999;"><?php echo __('Assign active class to'); ?></td>
              <td style="border-bottom:1px dotted #999;"><?php echo select_tag('w3s_assigned_to', options_for_select(array('li' => __('List [LI]'), 'a' => __('Link [A]'))))?></td>
            </tr>
            <tr>
              <td colspan="2"><?php echo __('Use these options to highlight the active link that corresponds to the active page, the user is navigating into.'); ?></td>
            </tr>
          </table>
        </div>
        <div id="w3s_feedback">&nbsp;</div>
      </td>
    </tr>
    <tr>
      <td valign="top" colspan="2">
        <div style="text-align:center;margin-top:10px;">
          <a href="#" class="link_button" onclick="objMenuBuilder.saveMenu('<?php echo url_for('menuBuilder/save') ?>'); return;false;"><?php echo __('Save Menu') ?></a>
        </div>
      </td>
    </tr>
  </table>
  <div class="CloseCurrentEditor" style="width:596px;text-align:right;"><?php echo link_to_function(__('Close'), 'objMenuBuilder=null;InteractiveMenu.closeEditor();') ?></div>
</div>
<?php

