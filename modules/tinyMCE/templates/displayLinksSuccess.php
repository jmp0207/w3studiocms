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

  $mcsLinks = '';
  foreach ($linksList as $link){
    $mcsLinks .= '["' . $link->getPageName() . '", "' . $language . '/' . $link->getPageName() . '.html"],';
  }
  echo 'var tinyMCELinkList = new Array(' . substr($mcsLinks, 0, strlen($mcsLinks)-1) . ');';