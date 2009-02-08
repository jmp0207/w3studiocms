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
?>

<table border="0" id="w3s_cms_loader">
  <tr>
    <td colspan="2" class="align_center">
      <h1><?php echo __('W3StudioCMS is loading'); ?></h1>
      <b><?php echo __('Please wait'); ?></b>
    </td>
  </tr>
  <tr>
    <td width="32"><div id="w3s_img_editor" class="img_waiting"></div></td>
    <td width="220" align="left">
      <?php echo __('Web Editor'); ?>
    </td>
  </tr>
  <tr>
    <td width="32"><div id="w3s_img_structure" class="img_waiting"></div></td>
    <td width="220" align="left">
      <?php echo __('Structure'); ?>
    </td>
  </tr>
</table>