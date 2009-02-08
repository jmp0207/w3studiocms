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
 * w3sContentsMove moves a content from a slot to another or into the
 * same slot.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sContentsMove
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sContentsMove
{
  protected 
  	$slotContents,
  	$currentSlotId;									   
  
  /**
   * Constructor.
   * 
   * @param int The language id
   * @param int The page id
   * @param int The slot id
   *
   */                         
  public function __construct($languageId, $pageId, $currentSlotId)
  {
  	$this->slotContents = DbFinder::from('W3sContent')->  										
							  										where('LanguageId', $languageId)->
							  										where('PageId', $pageId)->
							  										where('SlotId', $currentSlotId)->
							  										where('ToDelete', '0')->
							  										find();
  	$this->currentSlotId = $currentSlotId;
  }
  
  /**
   * Moves the content.
   * 
   * @param array An array with the id of contents that belongs to the slot
   *
   */   
  public function moveContents($newSlotContents)
  {
  	$con = Propel::getConnection();
	  $bRollBack = false;
	  $con = w3sPropelWorkaround::beginTransaction($con); 
    
    // Builds an array with the contents' id of the current slot before saving
    // the moving operation  
  	$oldSlotContents = array();
  	foreach ($this->slotContents as $content)
  	{
  		$oldSlotContents[] = $content->getId();
  	}
  	
  	// Compares the both arrays to find the content to move. There are 
  	// three possible cases:
  	// $res1 = 0 and $res2 = 1 - The slot is receiving the content 
  	// $res1 = 1 and $res2 = 0 - The slot is losing the content
  	// $res1 = 0 and $res2 = 0 - The slot is the same
  	$res1 = array_diff($oldSlotContents, $newSlotContents);
		$res2 = array_diff($newSlotContents, $oldSlotContents);
  	if (count($res1) == 0 && count($res2) == 1)
		{ 
			
			// Here the slot receives the content. This means that the content changes
			// its slotId. Here is managed this change 
			$content = DbFinder::from('W3sContent')->findPK($this->getArrayValueFromKey($res2));
			$contentManager = w3sContentManagerFactory::create($content->getContentTypeId(), $content);
			$params = array("SlotId" => $this->currentSlotId);
			$result = $contentManager->edit($params);
			if ($contentManager->getContent()->isModified() && $result == 0) $bRollBack = true; 
		}
		
		// Here the slot loses the content. In this case when the new position will be changed
		// the content that moves will not be involved in the change position, so have to be skipped 
		$currentContentId = (count($res1) == 1 && count($res2) == 0) ? $this->getArrayValueFromKey($res1) : null;	
		if (!$bRollBack && !$this->changeContentsPosition($newSlotContents, $currentContentId)) $bRollBack = true;
		
		if (!$bRollBack)
	  {
	    $con->commit();
	    $result = 1;
	  }
	  else{
	    w3sPropelWorkaround::rollBack($con);
	    $result = 0;
	  }
	  
	  return $result;
  }
  
  /**
   * Changes the content's position.
   * 
   * @param array An array with the id of contents that belongs to the new slot
   * @param int optional The content that is leaving the slot. When null the condition
   * 						is skipped
   * 
   */   
  protected function changeContentsPosition($newSlotContents, $leavingContentId = null)
  {
  	$bResult = true;
  	$position = 1;
		foreach ($newSlotContents as $contentId)
  	{
  		
  		// Skips the content position change when the slot loses the content.
  		// In the other two cases, this operation is always performed  
  		if ($leavingContentId == null || $contentId != $leavingContentId)
  		{
	  		$content = DbFinder::from('W3sContent')->findPK($contentId);
	  		$contentManager = w3sContentManagerFactory::create($content->getContentTypeId(), $content);
				$params = array("ContentPosition" => $position);
				$result = $contentManager->edit($params);
				if ($contentManager->getContent()->isModified() && $result == 0)
				{
					$bResult = false;
					break;
				}
				$contentManager = null;
				$content = null;
				$position++;
  		}
  	}
  	
  	return $bResult;
  }
  
  /**
   * Gets the value of an array with a single value when its key 
   * isn't known. 
   * 
   * @param array The array
   * 
   */   
  private function getArrayValueFromKey($array)
  {
  	$t = array_keys($array);
		return $array[$t[0]];
  }
}