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

class tinyMCEActions extends sfActions
{
  /*
   * Creates the tinyMCE images formatted list 
   */
  public function executeDisplayImageDir()
  {
    $this->imagesList = w3sCommonFunctions::buildFilesList(sfConfig::get('app_images_path'), '', array('gif', 'jpg', 'jpeg', 'png'));
  }
  
  /*
   * Creates the tinyMCE links formatted list 
   */
  public function executeDisplayLinks()
  {
    $this->linksList = W3sPagePeer::getPagesOrderedByName();

    $oLanguage =  W3sLanguagePeer::retrieveByPk($this->getRequestParameter('lang'));
    $this->language = $oLanguage->getLanguage();     // 'italian';    . $idLanguage
  }
}