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

class W3sGroupPeer extends BaseW3sGroupPeer
{
  /**
   * Retrieves the primary key of group's name from groups' table
   *
   * @parameter  string The name of the page
   * @return     int The primary key id
   */
  public static function getFromName($groupName)
  {    
    return DbFinder::from('W3sGroup')->
    								 where('GroupName', $groupName)->
								     where('ToDelete', '0')->
								     findOne(); 
  }
  
  public static function getActiveGroups()
  {
  	return DbFinder::from('W3sGroup')->
								     where('ToDelete', '0')->
								     find(); 
  }
}