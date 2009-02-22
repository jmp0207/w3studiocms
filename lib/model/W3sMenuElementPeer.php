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

class W3sMenuElementPeer extends BaseW3sMenuElementPeer
{
  /**
   * Retrieves all the menu rows related to the idContent
   *  
   * @parameter  int The id of the content
   * 
   * @return     obj The related menu
   */
  public static function getContentMenu($idContent)
  {
    return DbFinder::from('W3sMenuElement')->
									   where('contentId', $idContent)->
									   orderBy('Position', 'ASC')->
									   find();
  }
  
  /**
   * Retrieves the max position for the related content
   *  
   * @parameter  int The id of the content
   * 
   * @return     int The max position
   */
  public static function getMaxPosition($idContent){
    $menu = DbFinder::from('W3sMenuElement')->
									    where('contentId', $idContent)->
									    orderBy('Position', 'DESC')->
									    findOne();
    return $menu->getPosition();
 }
}