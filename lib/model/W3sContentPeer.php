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

class W3sContentPeer extends BaseW3sContentPeer
{
	/**
   * Coverts a content in array.
   * @param Object an instance of a W3sContent
   *   
   * @return array
   *
   */ 
  public static function contentToArray($content, $withId = false){
  	if (!$content instanceof w3sContent) throw new RuntimeException('contentToArray requires a w3sContent object');
  	
  	$result = array("PageId"          => $content->getPageId(),
		 			          "SlotId"          => $content->getSlotId(),
		 			          "LanguageId"      => $content->getLanguageId(),
		 			          "GroupId"         => $content->getGroupId(),
		 			          "Content"         => $content->getContent(),
		 			          "ContentTypeId"   => $content->getContentTypeId(),
		  			        "ContentPosition" => $content->getContentPosition(),
		  			        "Edited"					 => '1');
  	
  	if($withId)  $result["Id"] = $content->getId();
  	return $result;
  }
}