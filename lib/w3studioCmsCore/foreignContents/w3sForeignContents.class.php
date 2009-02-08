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
 * w3sForeignContents class is the base class to manage contents 
 * through pages.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sForeignContents
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
abstract class w3sForeignContents{
  protected 
  	$baseContentAttributes,   // The base values to store
  	$contentValues,
  	$targetPageId,
  	$slotName;
  
  // The function that updates the content  
  abstract function update($params = array());
  
  /**
   * Constructor.
   * 
   * @param array/object The base content's attributes
   * @param array The base values.
   *
   */   
  public function __construct($baseContentAttributes, $contentValues = array())
  {
  	$this->contentValues = $contentValues; 
  	
  	if (is_array($baseContentAttributes))
  	{   								 
  		$defaultParams = array("Id"              => '0',
														 "PageId"          => '0',
							 			         "SlotId"          => '0',
							 			         "LanguageId"      => '0',
							 			         "GroupId"         => '0',
							 			         "Content"         => '',
							 			         "ContentTypeId"   => '0',
							  			       "ContentPosition" => '0',
							  			       "Edited" 				 => '1');
  		if ($diff = array_diff(array_keys($baseContentAttributes), array_keys($defaultParams)))
	    {
	      throw new InvalidArgumentException(sprintf('%s does not support the following options: \'%s\'.', get_class($this), implode('\', \'', $diff)));
	    }
  		$this->baseContentAttributes = $baseContentAttributes; 
  	}
  	elseif($baseContentAttributes instanceof w3sContentManager)
  	{ 
  		$this->baseContentAttributes = W3sContentPeer::contentToArray($baseContentAttributes->getContent(), true);  		
  	}
  	else
  	{
  		throw new InvalidArgumentException(sprintf('%s requires a w3sContentManager object or an array that represents a w3sContent object.', get_class($this)));	
  	}
  	
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
									                where('Content', $this->baseContentAttributes["Content"])->
									                where('ContentPosition', $this->baseContentAttributes["ContentPosition"])->
									                where('ToDelete', '0')->
									                findOne();
			
			// When a content is not find using the text and position as keys,
			// tries to retrieve the content that matchs only the text
			if ($foreignContent == null)
			{						              
				$foreignContent = DbFinder::from('W3sContent')->
										                where('LanguageId', $this->baseContentAttributes["LanguageId"])->
										                where('PageId', $this->targetPageId)->
										                where('SlotId', $currentSlot->getId())->
										                where('Content', $this->baseContentAttributes["Content"])->
										                where('ToDelete', '0')->
										                findOne();
			}				
			
			// When a content is not find using only the text as key,
			// tries to retrieve the content that matchs only the position
			if ($foreignContent == null)
			{						              
				$foreignContent = DbFinder::from('W3sContent')->
										                where('LanguageId', $this->baseContentAttributes["LanguageId"])->
										                where('PageId', $this->targetPageId)->
										                where('SlotId', $currentSlot->getId())->
										                where('ContentPosition', $this->baseContentAttributes["ContentPosition"])->
										                where('ToDelete', '0')->
										                findOne();
			}				 
		} 
		else
		{
			$foreignContent = false;
		}            
																											 
		return $foreignContent;
	}
}