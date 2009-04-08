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
 * w3sPageEditor builds the editor to manage the site's pages
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sPageEditor
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sSlotManager implements w3sEditor
{
  protected
  	$idPage,
  	$idLanguage, 
  	$rowSkeleton = 						
     '<div id="%s" class="%s">
      <div style="float:left;">
        <div id="w3s_slot_name_%s" style="display:block;">%s</div>
      </div>
      <div style="float:right;margin-top:-1px;">%s</div>
      <div style="clear:right;"></div>
      </div>';
  
  public function __construct($idLanguage, $idPage)
  {
  	$this->idLanguage = $idLanguage;
  	$this->idPage = $idPage;
  }
    
  public function render()
  {
  	$result = '';
  	$page = DbFinder::from('W3sPage')->
                      with('W3sTemplate', 'W3sProject')->  
                      leftJoin('W3sGroup')->
                      leftJoin('W3sTemplate')->
                      leftJoin('W3sProject')->
                      findPK($this->idPage);   
    $slots = W3sSlotPeer::getTemplateSlots($page->getW3sGroup()->getTemplateId());
    $i = 0;
    foreach($slots as $slot){
	    $idSlot = $slot->getId();
	    $class = (($i/2) == intval($i/2)) ? "w3s_white_row" : "w3s_blue_row";
	    switch($slot->getRepeatedContents()){
        case 0:
          $repeatedColor = 'green';
          $repeatedAlt = __('This contents is not repeated through pages');
          break;
        case 1:
          $repeatedColor = 'orange';
          $repeatedAlt = __('This contents is repeated at group level');
          break;
        case 2:
          $repeatedColor = 'blue';
          $repeatedAlt = __('This contents is repeated at site level');
          break;
      }
	    $result .= sprintf($this->rowSkeleton, $this->idLanguage . $idSlot,
	    																			 $class,
	    																			 $idSlot,
	    																			 link_to_function($slot->getSlotName(), 'W3sControlPanel.showRepeatedContentsForm(' . $idSlot . ');', 'onmouseover="W3sControlPanel.highlightSlot(\'' . $slot->getSlotName() . '\', ' . $slot->getRepeatedContents() . ')"'),
	    																			 image_tag(sfConfig::get('app_w3s_web_skin_images_dir') . '/control_panel/button_slot_' . $repeatedColor . '.jpg', 'title=' . $repeatedAlt . ' size=14x14'));
	     $i++;																
    }
    
    return sprintf('<div id="w3s_slot_list">%s</div>', $result);
  }
}