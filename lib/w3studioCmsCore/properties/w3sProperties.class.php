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
 * w3sProperties class renders the property table used by several editors
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sProperties
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sProperties
{
	protected 
		$properties, 
		$params,
		$divName,
		$formName;
	
	/**
   * Constructor.
   * 
   * @param array  An array that contains an array serie where every single array represents a 
   * 							 property row. See w3sPropertyForm class for the detailed instructions to 
   * 							 build a property array.
   *    
   */   
	public function __construct($params){ 		
		$this->params = $params;
		$this->divName = 'w3s_properties';
		$this->formName = 'w3s_properties_form';
	}
	
	/**
   * Sets the value of the divName variable.
   * 
   * @param string
   *
   */  
	public function setDivName($value){
    $this->divName = $value;
  }
  
  /**
   * Returns the value of the divName variable.
   * 
   * @return string
   *
   */  
	public function getDivName(){
    return $this->divName;
  }
  
  /**
   * Sets the value of the formName variable.
   * 
   * @param string
   *
   */  
	public function setFormName($value){
    $this->formName = $value;
  }
  
  /**
   * Returns the value of the formName variable.
   * 
   * @return string
   *
   */  
	public function getFormName(){
    return $this->formName;
  }
	
	/**
   * Renders the properties.
   * 
   * @return string
   *
   */ 	
	public function render(){
		$button = '';
    $options = '';
		$result = sprintf('<tr><td colspan="2" class="header">%s</td></tr>', sfContext::getInstance()->getI18N()->__('Properties'));
    
    $properties = new w3sPropertyForm($this->params);
    foreach($this->params as $formRow){ 
      if(empty($formRow["button_for"])){
        $currentOptions = isset($formRow["options"]) ? $formRow["options"] : array();
        
        // Manages the select type, because that widget hasn't the type attribute
        $type = (isset($formRow["type"])) ? $formRow["type"] : '';
        
        if ($type != 'hidden') 
        {
          // Renders all types instead the hidden type
          $result .= sprintf('<tr><th>%s</th><td>%s%s</td></tr>', $properties[$formRow["name"]]->renderLabel(), 
          																												$properties[$formRow["name"]]->render($currentOptions), 
          																												($button != '') ? $properties[$button]->render($options) : ''); 
        }
        else 									 
        {
          // Renders the hidden type
          $result .= $properties[$formRow["name"]]->render($currentOptions);
        }
        $button = '';
      }
      else
      {
        
        // The button will be rendered for the next control
        $button = $formRow["name"];
        $options = $formRow["options"];
      }
    }
    
    return sprintf('<div id="%s"><form id="%s"><table cellspacing="0">%s</table></form></div>', $this->divName, $this->formName, $result);
	}
}