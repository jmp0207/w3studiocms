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

class BaseW3sControlPanelActions extends sfActions
{
	/**
   * Displays the structure menu
   *
   */
  public function executeIndex()
  {
    $controlPanel = new w3sControlPanel($this->getRequestParameter('lang'), $this->getRequestParameter('lang') . $this->getRequestParameter('page'), $this->getUser());
    return $this->renderPartial('controlPanel', array('controlPanel' => $controlPanel));    
  }

  public function executeDrawSlots()
  {
    $slotManager = new w3sSlotManager($this->getRequestParameter('lang'), $this->getRequestParameter('page'));
    return $this->renderPartial('slots', array('slotManager' => $slotManager));
  }

  /**
   * Saves the metatags for the current page
   *
   */
  public function executeLoadMetas(){
    // Checks that all the required parameters exists 
    $metatag = W3sSearchEnginePeer::getFromPageAndLanguage($this->getRequestParameter('lang'), $this->getRequestParameter('page'));
    $metatagManager = new w3sMetatagsManager($metatag);
    return $this->renderPartial('metatagsInterface', array('metatagManager' => $metatagManager));
  }
  
  /**
   * Saves the metatags for the current page
   *
   */
  public function executeSaveMetas(){
    // Checks that all the required parameters exists 
    if ($this->getRequest()->hasParameter('lang') &&
        $this->getRequest()->hasParameter('page'))
    {
      $oMetas = W3sSearchEnginePeer::getFromPageAndLanguage($this->getRequestParameter('lang'), $this->getRequestParameter('page'));
      
      // W3StudioCMS adds a new meta, if metatags for corrent page haven't already been setted
      if ($oMetas == null) $oMetas = new W3sSearchEngine;
      $metaValues = array("PageId"             => $this->getRequestParameter('page'),
                          "LanguageId"         => $this->getRequestParameter('lang'),
                          "MetaTitle"          => $this->getRequestParameter('title'),
                          "MetaDescription"    => $this->getRequestParameter('description'),
                          "MetaKeywords"       => $this->getRequestParameter('keywords'));
      
      $oMetas->fromArray($metaValues);
      $result = $oMetas->save();
    }
    else{
      $result = 2;
      $this->getResponse()->setStatusCode(404);
    }
    
    return $this->renderPartial('saveMetatags', array('result' => $result));
  }
}