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
  public function executeIndex($request)
  {
    if ($request->hasParameter('lang') && $request->hasParameter('page'))
    {
      if ($this->getRequestParameter('lang') != 0 && $this->getRequestParameter('page') != 0)
      {
        $controlPanel = new w3sControlPanel($this->getUser(), $this->getRequestParameter('lang'), $this->getRequestParameter('lang') . $this->getRequestParameter('page'));
        return $this->renderPartial('controlPanel', array('controlPanel' => $controlPanel));
      }
      else
      {
        $this->getResponse()->setStatusCode(404);
        return $this->renderText(w3sCommonFunctions::toI18n('A required parameter has not a valid value.'));
      }
    }
    else
    {
      $this->getResponse()->setStatusCode(404);
      return $this->renderText(w3sCommonFunctions::toI18n('Page and language parameters are required to draw the current browsed page'));
    }
  }

  public function executeDrawSlots($request)
  {
    if ($request->hasParameter('lang') && $request->hasParameter('page'))
    {
      if ($this->getRequestParameter('lang') != 0 && $this->getRequestParameter('page') != 0)
      {
        $slotManager = new w3sSlotManager($this->getRequestParameter('lang'), $this->getRequestParameter('page'));
        return $this->renderPartial('slots', array('slotManager' => $slotManager));
      }
      else
      {
        $this->getResponse()->setStatusCode(404);
        return $this->renderText(w3sCommonFunctions::toI18n('A required parameter has not a valid value.'));
      }
    }
    else
    {
      $this->getResponse()->setStatusCode(404);
      return $this->renderText(w3sCommonFunctions::toI18n('A required parameter misses'));
    }
  }

  /**
   * Saves the metatags for the current page
   *
   */
  public function executeLoadMetas($request){
    if ($request->hasParameter('lang') && $request->hasParameter('page'))
    {
      if ($this->getRequestParameter('lang') != 0 && $this->getRequestParameter('page') != 0)
      {
        $metatag = W3sSearchEnginePeer::getFromPageAndLanguage($this->getRequestParameter('lang'), $this->getRequestParameter('page'));
        $metatagManager = new w3sMetatagsManager($metatag);
        return $this->renderPartial('metatagsInterface', array('metatagManager' => $metatagManager));
      }
      else
      {
        $this->getResponse()->setStatusCode(404);
        return $this->renderText(w3sCommonFunctions::toI18n('A required parameter has not a valid value.'));
      }
    }
    else
    {
      $this->getResponse()->setStatusCode(404);
      return $this->renderText(w3sCommonFunctions::toI18n('A required parameter misses'));
    }
  }
  
  /**
   * Saves the metatags for the current page
   *
   */
  public function executeSaveMetas($request){
    if ($request->hasParameter('lang') && $request->hasParameter('page'))
    {
      if ($this->getRequestParameter('lang') != 0 && $this->getRequestParameter('page') != 0)
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
        $result = 4;
        $this->getResponse()->setStatusCode(404);
      }
    }
    else{
      $result = 2;
      $this->getResponse()->setStatusCode(404);
    }
    
    return $this->renderPartial('saveMetatags', array('result' => $result));
  }
}