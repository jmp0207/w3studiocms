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
<ul id="w3s_mb_list">
<li class="header" style="">
  <div style="float:left;">Menu links</div>
  <div style="float:right;">
    <?php echo link_to_function(image_tag(sfConfig::get('app_w3s_web_skin_images_dir') . '/menu_builder/add_link.png', ''), 'objMenuBuilder.addLink(\'' . url_for('menuBuilder/add') . '\')') ?>
    <?php echo link_to_function(image_tag(sfConfig::get('app_w3s_web_skin_images_dir') . '/menu_builder/del_link.png', ''), 'objMenuBuilder.deleteLink(\'' . url_for('menuBuilder/delete') . '\', \'' . __('Do you want to delete the selected link?') . '\')') ?>
  </div>
  <div style="clear:both;"></div>
</li>
<?php
  
  // We need to clear the objMenu array, otherwise if you add or delete a link 
  // properties don't work correctly
  $propertiesScript = 'objMenu.clear();';
  foreach($menuList as $menu):
?>
    <li id="item_<?php echo $menu->getId() ?>" value="<?php echo $menu->getId() ?>" class="menu_items">
      <a href="#" onclick="objMenuBuilder.loadMenuProperties(<?php echo $menu->getId() ?>);return false;">
        <span id="item_text_<?php echo $menu->getId() ?>"><?php echo $menu->getLink() ?></span>
      </a>
    </li>
<?php
    $propertiesScript .= 'objMenu[' . $menu->getId() . ']={';
    $propertiesScript .= 'w3s_ppt_link: \'' . $menu->getLink() . '\',';
    $propertiesScript .= 'w3s_ppt_image: \'' . $menu->getImage() . '\',';
    $propertiesScript .= 'w3s_ppt_rollover_image: \'' . $menu->getRolloverImage() . '\',';
    $propertiesScript .= 'w3s_ppt_int_link: \'' . $menu->getPageId() . '\',';
    $propertiesScript .= 'w3s_ppt_ext_link: \'' . $menu->getExternalLink() . '\'};';
  endforeach; ?>
</ul>
<?php                                                    //onUpdate:function(){objMenuBuilder.moveMenuItem(\'' . url_for('menuBuilder/move') . '\', Sortable.serialize(\'w3s_mb_list\'))}, only:\'menu_items\'
  echo javascript_tag('Sortable.create(\'w3s_mb_list\', {});');
  echo javascript_tag($propertiesScript);