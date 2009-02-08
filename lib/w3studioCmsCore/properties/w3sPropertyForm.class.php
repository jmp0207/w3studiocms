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

/**
 * w3sPropertyForm is a generic form to render a standard form which every row is a  
 * property for a generic object
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sPropertyForm
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
  class w3sPropertyForm extends sfForm
  {   
    protected $myForm;
    
    /**
	   * Constructor.
	   * 
	   * @param array  An array that contains an array serie where every single array represents a property row  
	   * array
	   * (                                                                                                 
		 *   array(params),
		 *   [array(params), ... ]
	   * )
	   * 
	   * params are expressed as key => value
	   * 
	   * key can have the following values:
	   *   name         The id of the property
	   *   label        The label for the property
	   *   type					The type is the form's widget type
	   *   button_for   Draws a submit button after the associate property. Must be placed exactly before the control.
	   * 								This behaviour will be reviewed.
	   *   options      The options for the form's widget type   
	   */   
    public function __construct($arrayForm){ 
      $this->myForm = $arrayForm; 
      
      parent::__construct();
    }
    
    /**
	   * Configure the property form.
	   *    
	   */   
    public function configure(){    
      $widgets = array();
      $labels = array();
      $defaults = array();  
      foreach ($this->myForm as $formRow){ 
        
        if (isset($formRow["type"])){
          switch ($formRow["type"]){
            case 'hidden': 
              $widgets[$formRow["name"]] = new sfWidgetFormInputHidden(array('type' => $formRow["type"]), isset($formRow["options"]) ? $formRow["options"] : array());
              break;  
            default: 
              $widgets[$formRow["name"]] = new sfWidgetFormInput(array('type' => $formRow["type"]), isset($formRow["options"]) ? $formRow["options"] : array());
              break;
          }
        }  
        else if (isset($formRow["choices"])){
          $widgets[$formRow["name"]] = new sfWidgetFormSelect(array('choices' => $formRow["choices"]));  
        }  
        
        if (isset($formRow["label"])){
          $labels[$formRow["name"]] = $formRow["label"];  
        }  
        
        if (isset($formRow["default"])){
          $defaults[$formRow["name"]] = $formRow["default"];  
        }
      }
      
      $this->widgetSchema = new sfWidgetFormSchema($widgets);
      $this->widgetSchema->setLabels($labels);
      $this->setDefaults($defaults);
    }
  }