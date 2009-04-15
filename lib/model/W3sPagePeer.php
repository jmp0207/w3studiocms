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

class W3sPagePeer extends BaseW3sPagePeer
{
  /**
   * Returns the website's pages in ascending order
   *
   * @return     array The pages list
   */
  public static function getPagesOrderedByName()
  {
    /*
    $result = '';
    $c = new Criteria();
    $c->add(self::TO_DELETE, '0');
    $c->addAscendingOrderByColumn(self::PAGE_NAME);
    $pages = self::doSelect($c);
    foreach ($pages as $page){
      $result[] = $page;
    }
    
    return $result;*/
    
    return DbFinder::from('W3sPage')->
									   orderby('PageName', 'ASC')->
									   where('ToDelete', '0')->
									   find();
    
    
  }
  
  /**
   * Returns the page row from page's name 
   *
   * @parameter  string The name of the page
   * 
   * @return     obj The page row
   */
  public static function getFromPageName($pageName)
  {
    return DbFinder::from('W3sPage')->
                     where('PageName', w3sCommonFunctions::slugify($pageName))->
									   where('ToDelete', '0')->
									   findOne();
  }
  
  /**
   * Returns the web-site's home page row
   *
   * @return     obj The page row
   */
  public static function getHomePage()
  {
    /*
    $c = new Criteria();
    $c->add(self::IS_HOME, 1);
    $c->add(self::TO_DELETE, '0');

    return self::doSelectOne($c);*/
    return DbFinder::from('W3sPage')->
									   where('IsHome', '1')->
									   where('ToDelete', '0')->
									   findOne();
  }
}
