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

 use_helper('Javascript');
?>
<div id="w3s_properties">
  <form id="w3s_properties_form">
    <table cellspacing="0">
      <tr><td colspan="3" class='header'><?php echo __('Properties') ?></td></tr>
      <?php				
        $button = '';
        foreach($myForm as $formRow):
          if(empty($formRow["button_for"])):
            $currentOptions = isset($formRow["options"]) ? $formRow["options"] : array();
            $type = '';
            if (isset($formRow["type"])) $type = $formRow["type"];
            if ($type != 'hidden'):
      ?>
              <tr>
                <th><?php echo $form[$formRow["name"]]->renderLabel(); ?></th>
                <td>
                  <?php echo $form[$formRow["name"]]->render($currentOptions); ?>
                  <?php echo ($button != '') ? $form[$button]->render($options) : ''; ?>
                </td>
              </tr>
      <?php
            else:
              echo $form[$formRow["name"]]->render($currentOptions);
            endif;
            $value = isset($currentOptions["value"]) ? $currentOptions["value"] : '';

            $button = '';
          else:
            $button = $formRow["name"];
            $options = $formRow["options"];
          endif;
        endforeach;
      ?>


      <?php /*echo $form*/ ?>
    </table>
  </form>
</div>

<?php
/*
 * 

*/
?>