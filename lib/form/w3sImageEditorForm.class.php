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

  class w3sImageEditorForm extends sfForm
  {   
    protected static $subjects = array("1" => "gif", "2" => "jpg", "3" => "png"); 
    
    public function configure(){      
      $this->setWidgets(array(
        'editor_image_size' => new sfWidgetFormInput(),
        'editor_width' => new sfWidgetFormInput(),
        'editor_start_width' => new sfWidgetFormInputHidden(),
        'editor_height' => new sfWidgetFormInput(),
        'editor_start_height' => new sfWidgetFormInputHidden(),
        'editor_aspect_ratio' => new sfWidgetFormInputCheckbox(),
        'editor_image_type_select' => new sfWidgetFormSelect(array('choices' => self::$subjects), array('onchange' => 'objImageEditor.setQualitySelect($(\'w3s_editor_image_type_select\').value)')),
        'editor_quality' => new sfWidgetFormInput()
      ));
      $this->widgetSchema->setLabels(array(
        'editor_image_size' => 'Size',
        'editor_width' => 'Width',
        'editor_height' => 'Height',
        'editor_aspect_ratio' => 'Constrain',
        'editor_image_type_select' => 'Output',
        'editor_quality' => 'Quality'
      ));
      $this->setDefaults(array('editor_aspect_ratio' => true, 'editor_quality' => 80));
      $this->widgetSchema->setNameFormat('w3s[%s]');
    }
  }