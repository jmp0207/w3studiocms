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
 * w3sGroupForeignContentsMenu class is the base class used to save the menu 
 * content type through a group of pages.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sGroupForeignContents
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
  
class w3sGroupForeignContentsMenu extends w3sGroupForeignContents{
  
  /**
   * Edit the menu on the foreign page 
   *  
   * @return int The operation result
   *
   */  
  public function updateForeignContent($params = array()){		
    
    // If the foreign content is empty it means that the template hasn't that slot and 
  	// assumes that everything was correctly done.
  	$result = 1;
  	$foreignContent = $this->getForeignContent();
  	//if($this->targetPageId== 98) print_r($foreignContent);
  	if ($foreignContent != null){ 
	    
	    $content = w3sContentManagerFactory::create(5, $foreignContent);
	    
	    // Copies the related contents from the base content to the current foreign content
	    if ($foreignContent->getId() != $this->baseContentAttributes["Id"]) w3sContentManagerMenuPeer::copyRelatedElements($this->baseContentAttributes["Id"], $foreignContent->getId()); 
	    
	    // Blocks the content's update on foreign page because this operation is alreay called 
	    // during the update on foreign pages. 
	    $content->setUpdateForeigns(false);
	    $result = $content->edit(array("Content" => $params, "ContentTypeId" => "5")); 
	    if($result != 1) break;
  	}
  	
  	return $result;    
	}
	
	/**
   * Retrieves the content that corresponds to the current content on the target page.
   * 
   * @return object The finded content
   *
   */  
	protected function getForeignContent(){		
		
		// Retrieves the slot where the same content of the base content is placed. 
		// This because when working at site level the slots have the same name
		// but not the same id.		
		$currentSlot = DbFinder::from('W3sSlot')->
														join('W3sSlot.TemplateId', 'W3sTemplate.Id', 'inner join')->
														join('W3sTemplate.Id', 'W3sGroup.TemplateId', 'inner join')->
														join('W3sGroup.Id', 'W3sPage.GroupId', 'inner join')->														
						                where('W3sPage.Id', $this->targetPageId)->
						                where('SlotName', $this->slotName)->
						                findOne();   
		
		// Current slot can be null if there is a page that has a different template 
		// from the one assigned to the page we are working on. In this case the
		// returned value for the $foreignContent is null. 				                
		if ($currentSlot != null)
		{
							               
			// Updates the SlotId value with the foreign content's value				                
			$this->baseContentAttributes["SlotId"] = $currentSlot->getId();
		
		  // Retrieves the content that matchs text and position and returns it            
	    $foreignContent = DbFinder::from('W3sContent')->
									                where('LanguageId', $this->baseContentAttributes["LanguageId"])->
									                where('PageId', $this->targetPageId)->
									                where('SlotId', $currentSlot->getId())->
									                where('ToDelete', '0')->
									                findOne();
		} 
		else
		{
			$foreignContent = false;
		}            
																											 
		return $foreignContent;
	}
}