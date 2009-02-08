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

class W3sLanguagePeer extends BaseW3sLanguagePeer
{
  /**
   * Returns the primary key of language's name from languages' table
   *
   * @parameter  string The name of the language
   * @return     array The primary key id
   */
  public static function getFromLanguageName($languageName)
  {    
    return DbFinder::from('W3sLanguage')->
    								 where('Language', $languageName)->
								     where('ToDelete', '0')->
								     findOne(); 
  }

  /**
   * Returns the primary key of main language from languages' table
   *
   * @return     array The primary key id
   */
  public static function getMainLanguage(){
    return DbFinder::from('W3sLanguage')->
    								 where('MainLanguage', '1')->
								     where('ToDelete', '0')->
								     findOne(); 
  }
  
  /**
   * Returns the primary key of main language from languages' table
   *
   * @return     array The primary key id
   */
  public static function getActiveLanguages(){
    $c = new Criteria();
    $c->add(self::TO_DELETE, '0');
    
    return self::doSelect($c);
  }
}