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
 * w3sForeignContents class is the base class for adding, editing or deleting 
 * the contents that must be repeated between foreign pages.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sForeignContents
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
abstract class w3sRepeatedForeignContents extends w3sForeignContents{
  
  // The function that updates the content  
  abstract function updateForeignContent();
  
  /**
   * Update the related contents.
   * 
   * @return bool
   *
   */  
  public function update($params = array()){
		$result = true;
		
		// Retrieves the slot
		$slot = DbFinder::from('W3sSlot')->findPK($this->baseContentAttributes["SlotId"]);
		
		/*
		echo $this->baseContentAttributes["SlotId"];
		print_r($slot);
		echo "<br><br>";*/
		 
		// Checks if the slot has contents to update 
		if ($slot->getRepeatedContents() > 0){			
		 	$this->slotName = $slot->getSlotName(); 
		 	$pagesFinder = DbFinder::from('W3sPage')->
					 									   where('ToDelete', '0')->
					 										 where('Id', '!=', $this->baseContentAttributes["PageId"]);
		 	if ($slot->getRepeatedContents() == 1) $pagesFinder = $pagesFinder->where('GroupId', $this->baseContentAttributes["GroupId"]);
    	$pages = $pagesFinder->find();  
		 	 
		  // Checks for all the website's pages
		  $contents = array();
		  foreach($pages as $targetPage){
		    $this->targetPageId = $targetPage->getId();
		    
		    // This function is declared in the derived classes
		    if ($this->updateForeignContent() != 1){ 
		    	$result = false;
		    	break;
		    }
		  }
		}

		return $result;
	}
}