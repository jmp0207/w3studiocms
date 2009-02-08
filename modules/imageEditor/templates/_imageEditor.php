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

  $gifSelected = ($imageAttributes['imageType'] == 1) ? 'SELECTED' : '';
  $jpgSelected = ($imageAttributes['imageType'] == 2) ? 'SELECTED' : '';
  $pngSelected = ($imageAttributes['imageType'] == 3) ? 'SELECTED' : '';
  $quality = ($imageAttributes['imageType'] != 2) ? 'size="2" class="quality_disabled" disabled="disabled"' : 'size="2" class="quality_enabled"';
?>
  <div id="w3s_image_editor">
  <form id="w3s_image_editor_form">
  <table cellpadding="0" cellspacing="2">
    <tr>
      <th>
        <?php echo __('Size') ?> 
      </th>
      <th>
        <?php echo __('Width') ?>
      </th>  
      <th>
        <?php echo __('Height') ?>        
      </th>
      <th>
        <?php echo __('Constrain') ?> 
      </th> 
      <th>
        <?php echo __('Output') ?> 
      </th> 
      <th>
        <?php echo __('Quality') ?> 
      </th>
      
      <td rowspan="2">
        <a href="#" class="buttons" onclick="objImageEditor.resize('<?php echo url_for('imageEditor/resizeImage') ?>', $('w3s_ppt_image').value, 0, (Element.getWidth('w3s_image_preview') - 4), (Element.getHeight('w3s_image_preview') - 4))" /><?php echo __('Edit') ?></a>
        <?php /* echo submit_tag( __('Edit'), 'class="buttons" onclick="var sel=$(\'w3s_images_select\'); var setCanvas = ($(\'w3s_fit_preview\').checked == true) ? 1 : 0; objImageEditor.resize(\'' . url_for('imageEditor/resizeImage') . '\', sel.options[sel.selectedIndex].text, \'' . $imageAttributes['imagePath'] . '\', setCanvas, (Element.getWidth(\'w3s_image_preview\') - 4), (Element.getHeight(\'w3s_image_preview\') - 4))"' ) */ ?>
      </td>
      <td rowspan="2">
        <a href="#" class="buttons" class="buttons" onclick="objImageEditor.rotate('<?php echo url_for('imageEditor/rotateImage') ?>', 90, $('w3s_ppt_image').value, 0, (Element.getWidth('w3s_image_preview') - 4), (Element.getHeight('w3s_image_preview') - 4))" /><?php echo __('90° Dx') ?></a>
        <?php /* echo submit_tag( __('90° Dx'), 'class="buttons" onclick="var sel=$(\'w3s_images_select\'); var setCanvas = ($(\'w3s_fit_preview\').checked == true) ? 1 : 0; objImageEditor.rotate(\'' . url_for('imageEditor/rotateImage') . '\', 90, sel.options[sel.selectedIndex].text, \'' . $imageAttributes['imagePath'] . '\', setCanvas, (Element.getWidth(\'w3s_image_preview\') - 4), (Element.getHeight(\'w3s_image_preview\') - 4))"' ) */ ?>
      </td>
      <td rowspan="2">
        <a href="#" class="buttons" class="buttons" onclick="var sel=$('w3s_images_select'); var setCanvas = ($('w3s_fit_preview').checked == true) ? 1 : 0; objImageEditor.rotate('<?php echo url_for('imageEditor/rotateImage') ?>', 270, sel.options[sel.selectedIndex].text, '' . $imageAttributes['imagePath'] . '', setCanvas, (Element.getWidth('w3s_image_preview') - 4), (Element.getHeight('w3s_image_preview') - 4))" /><?php echo __('90° Sx') ?></a>
        <?php /* echo submit_tag( __('90° Sx'), 'class="buttons" onclick="var sel=$(\'w3s_images_select\'); var setCanvas = ($(\'w3s_fit_preview\').checked == true) ? 1 : 0; objImageEditor.rotate(\'' . url_for('imageEditor/rotateImage') . '\', 270, sel.options[sel.selectedIndex].text, \'' . $imageAttributes['imagePath'] . '\', setCanvas, (Element.getWidth(\'w3s_image_preview\') - 4), (Element.getHeight(\'w3s_image_preview\') - 4))"' ) */ ?>
      </td>  
      
    </tr>
    <tr> 
      <td>
        <?php echo $form["editor_image_size"]->render(array('value' => $imageAttributes['size'], 'size' => 5)) ?>
      </td> 
      <td>
        <?php echo $form["editor_width"]->render(array('value' => $imageAttributes['width'], 'size' => 5, 'onkeyup' => 'objImageEditor.setConstrainDimension(\'w3s_editor_width\', \'w3s_editor_height\')')) ?>        
        <?php echo $form["editor_start_width"]->render(array('value' => $imageAttributes['width'])) ?>
      </td> 
      <td>
        <?php echo $form["editor_height"]->render(array('value' => $imageAttributes['height'], 'size' => 5, 'onkeyup' => 'objImageEditor.setConstrainDimension(\'w3s_editor_height\', \'w3s_editor_width\')')) ?>
        <?php echo $form["editor_start_height"]->render(array('value' => $imageAttributes['height'])) ?> 
      </td>
      <td>
        <?php echo $form["editor_aspect_ratio"] ?>
      </td> 
      <td>
        <?php echo $form["editor_image_type_select"] ?>
      </td> 
      <td>
        <?php
          $class = ($imageAttributes['imageType'] == 2) ? 'enabled' : 'disabled';
          echo $form["editor_quality"]->render(array('size' => 5, 'class' => $class)); 
        ?>
      </td>      
    </tr>
    
    <?php 
    /*
    <tr>
      <th>
        <?php echo __('Size') ?> 
      </th> 
      <td>
        <?php echo $form["image_size"]->render(array('size' => 4)) ?>
      </td> 
      <th>
        <?php echo $form["width"]->renderLabel() ?> 
      </th> 
      <td>
        <?php echo $form["width"]->render(array('size' => 4, 'onkeyup' => 'objImageEditor.setConstrainDimension(\'w3s_image_width\', \'w3s_image_height\')')) ?>
      </td> 
      <th>
        <?php echo $form["height"]->renderLabel() ?> 
      </th> 
      <td>
        <?php echo $form["height"]->render(array('size' => 4, 'onkeyup' => 'objImageEditor.setConstrainDimension(\'w3s_image_height\', \'w3s_image_width\')')) ?>
      </td>
      <th>
        <?php echo __('Constrain') ?> 
      </th> 
      <td>
        <?php echo $form["aspect_ratio"] ?>
      </td> 
      <th>
        <?php echo __('Output') ?> 
      </th> 
      <td>
        <?php echo $form["image_type_select"] ?>
      </td> 
      <th>
        <?php echo $form["quality"]->renderLabel() ?> 
      </th> 
      <td>
        <?php echo $form["quality"]->render(array('size' => 4)) ?>
      </td>
      <td>
        <input type="submit" value="<?php echo __('Edit') ?>" class="buttons" onclick="var sel=$('w3s_images_select'); var setCanvas = ($('w3s_fit_preview').checked == true) ? 1 : 0; objImageEditor.resize('' . url_for('imageEditor/resizeImage') . '', sel.options[sel.selectedIndex].text, '' . $imageAttributes['imagePath'] . '', setCanvas, (Element.getWidth('w3s_image_preview') - 4), (Element.getHeight('w3s_image_preview') - 4))" />
        <?php /* echo submit_tag( __('Edit'), 'class="buttons" onclick="var sel=$(\'w3s_images_select\'); var setCanvas = ($(\'w3s_fit_preview\').checked == true) ? 1 : 0; objImageEditor.resize(\'' . url_for('imageEditor/resizeImage') . '\', sel.options[sel.selectedIndex].text, \'' . $imageAttributes['imagePath'] . '\', setCanvas, (Element.getWidth(\'w3s_image_preview\') - 4), (Element.getHeight(\'w3s_image_preview\') - 4))"' ) * ?>
      </td>
      <td>
        <input type="submit" value="<?php echo __('90° Dx') ?>" class="buttons" onclick="var sel=$('w3s_images_select'); var setCanvas = ($('w3s_fit_preview').checked == true) ? 1 : 0; objImageEditor.rotate('' . url_for('imageEditor/rotateImage') . '', 90, sel.options[sel.selectedIndex].text, '' . $imageAttributes['imagePath'] . '', setCanvas, (Element.getWidth('w3s_image_preview') - 4), (Element.getHeight('w3s_image_preview') - 4))" />
        <?php /* echo submit_tag( __('90° Dx'), 'class="buttons" onclick="var sel=$(\'w3s_images_select\'); var setCanvas = ($(\'w3s_fit_preview\').checked == true) ? 1 : 0; objImageEditor.rotate(\'' . url_for('imageEditor/rotateImage') . '\', 90, sel.options[sel.selectedIndex].text, \'' . $imageAttributes['imagePath'] . '\', setCanvas, (Element.getWidth(\'w3s_image_preview\') - 4), (Element.getHeight(\'w3s_image_preview\') - 4))"' ) * ?>
      </td>
      <td>
        <input type="submit" value="<?php echo __('90° Sx') ?>" class="buttons" onclick="var sel=$('w3s_images_select'); var setCanvas = ($('w3s_fit_preview').checked == true) ? 1 : 0; objImageEditor.rotate('' . url_for('imageEditor/rotateImage') . '', 270, sel.options[sel.selectedIndex].text, '' . $imageAttributes['imagePath'] . '', setCanvas, (Element.getWidth('w3s_image_preview') - 4), (Element.getHeight('w3s_image_preview') - 4))" />
        <?php /* echo submit_tag( __('90° Sx'), 'class="buttons" onclick="var sel=$(\'w3s_images_select\'); var setCanvas = ($(\'w3s_fit_preview\').checked == true) ? 1 : 0; objImageEditor.rotate(\'' . url_for('imageEditor/rotateImage') . '\', 270, sel.options[sel.selectedIndex].text, \'' . $imageAttributes['imagePath'] . '\', setCanvas, (Element.getWidth(\'w3s_image_preview\') - 4), (Element.getHeight(\'w3s_image_preview\') - 4))"' ) *
         ?>
      </td>  
    </tr>
    
    <tr>
      <td style="width:80px;">
        <p><?php echo __('Size') ?> </p>
        <p><span id="w3s_image_size"><?php echo $imageAttributes['size'] ?> </span></p>
      </td> 
      <td>
        <p><?php echo __('Width') ?></p>
        <p>
          <?php echo input_tag("w3s_image_width", $imageAttributes['width'], 'size="4" onkeyup="objImageEditor.setConstrainDimension(\'w3s_image_width\', \'w3s_image_height\');"') ?>
          <?php echo input_hidden_tag("w3s_start_width", $imageAttributes['width'])?>
        </p>
      </td>
      <td>
        <p><?php echo __('Height') ?></p>
        <p>
          <?php echo input_tag("w3s_image_height", $imageAttributes['height'], 'size="4" onkeyup="objImageEditor.setConstrainDimension(\'w3s_image_height\', \'w3s_image_width\');"') ?>
          <?php echo input_hidden_tag("w3s_start_height", $imageAttributes['height']) ?>
        </p>
      </td>
      <td>
        <p><?php echo __('Constrain'); ?></p>
        <p style="margin:2px 0px !important;"><?php echo checkbox_tag("w3s_aspect_ratio", '', true) ?></p>
      </td>
      <td>
        <p><?php echo __('Output'); ?></p>
        <p>
          <select id="w3s_image_type_select" onchange="objImageEditor.setQualitySelect(); return false;">
            <option name="gif" <?php echo $gifSelected; ?>>gif</option>
            <option name="jpg" <?php echo $jpgSelected; ?>>jpg</option>
            <option name="png" <?php echo $pngSelected; ?>>png</option>
          </select>
        </p>
      </td>
      <td>
        <p><?php echo __('Quality') ?></p>
        <p><?php echo input_tag("w3s_quality", '80', $quality ) ?></p>
      </td>
      <td>
        <?php echo submit_tag( __('Edit'), 'class="buttons" onclick="var sel=$(\'w3s_images_select\'); var setCanvas = ($(\'w3s_fit_preview\').checked == true) ? 1 : 0; objImageEditor.resize(\'' . url_for('imageEditor/resizeImage') . '\', sel.options[sel.selectedIndex].text, \'' . $imageAttributes['imagePath'] . '\', setCanvas, (Element.getWidth(\'w3s_image_preview\') - 4), (Element.getHeight(\'w3s_image_preview\') - 4))"' ) ?>
      </td>
      <td>
        <?php echo submit_tag( __('90° Dx'), 'class="buttons" onclick="var sel=$(\'w3s_images_select\'); var setCanvas = ($(\'w3s_fit_preview\').checked == true) ? 1 : 0; objImageEditor.rotate(\'' . url_for('imageEditor/rotateImage') . '\', 90, sel.options[sel.selectedIndex].text, \'' . $imageAttributes['imagePath'] . '\', setCanvas, (Element.getWidth(\'w3s_image_preview\') - 4), (Element.getHeight(\'w3s_image_preview\') - 4))"' ) ?>
      </td>
      <td>
        <?php echo submit_tag( __('90° Sx'), 'class="buttons" onclick="var sel=$(\'w3s_images_select\'); var setCanvas = ($(\'w3s_fit_preview\').checked == true) ? 1 : 0; objImageEditor.rotate(\'' . url_for('imageEditor/rotateImage') . '\', 270, sel.options[sel.selectedIndex].text, \'' . $imageAttributes['imagePath'] . '\', setCanvas, (Element.getWidth(\'w3s_image_preview\') - 4), (Element.getHeight(\'w3s_image_preview\') - 4))"' ) ?>
      </td>
    </tr>  
    */
    ?>         
  </table>
  </form>
  </div> 