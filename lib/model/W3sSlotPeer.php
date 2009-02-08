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

class W3sSlotPeer extends BaseW3sSlotPeer
{
  /**
   * Retrieves the slot row from the slot name
   *  
   * @parameter  int The id of the related template
   * @parameter  str The slot's name to retrieve
   * 
   * @return     obj The related slot row. Null if it doesn't exists
   */
  public static function getPkFromSlotName($idTemplate, $slotName)
  { 
    return DbFinder::from('W3sSlot')->
									   where('TemplateId', $idTemplate)->
									   where('SlotName', $slotName)->
									   findOne();
  }
  
  /**
   * Retrieves all the slot rows related to a template
   *  
   * @parameter  int The id of the related template
   * 
   * @return     obj The related slot rows. Null if it doesn't exists
   */
  public static function getTemplateSlots($idTemplate, $repeated=-1)
  {
    $slots = DbFinder::from('W3sSlot')->where('TemplateId', $idTemplate);
		if ($repeated != -1) $slots->where('RepeatedContents', $repeated);
		
    return $slots->find();
  }
}