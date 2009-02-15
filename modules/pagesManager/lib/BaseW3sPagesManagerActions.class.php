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

class BaseW3sPagesManagerActions extends sfActions
{
	/**
   * Shows the module for page add
   */
  public function executeShow()
  {    
    $this->pageEditor = new w3sPageEditor();
  }

  /**
   * Adds the page
   */
  public function executeAdd($request)
  {
    if($request->hasParameter('pageName') && $request->hasParameter('idGroup'))
    {
      $page = new w3sPageManager();
      $result = $page->add($this->getRequestParameter('pageName'), $this->getRequestParameter('idGroup'));

      if ($result != 1) $this->getResponse()->setStatusCode(404);
      return $this->renderPartial('add', array('result' => $result));
    }
    else
	  {
	  	$this->getResponse()->setStatusCode(404);
    	return $this->renderText(w3sCommonFunctions::toI18n('One or more required parameter are missing.'));
	  }
  }

  /**
   * Lists pages
   */
  public function executeShowPages($request)
  {
    if ($request->hasParameter('curLang') && $request->hasParameter('curPage'))
    {
      $fileManager = new w3sFileManager($this->getRequestParameter('curLang'), $this->getRequestParameter('curPage'));
      return $this->renderPartial('listPages', array('fileManager' => $fileManager));
    }
    else
	  {
	  	$this->getResponse()->setStatusCode(404);
    	return $this->renderText(w3sCommonFunctions::toI18n('One or more required parameter are missing.'));
	  }
  }

  /**
   * Deletes the page
   */
  public function executeDelete($request)
  {    
    if ($request->hasParameter('idPage') && $request->hasParameter('curLang') && $request->hasParameter('curPage'))
    {
      $result = 0;
      $page = DbFinder::from('W3sPage')->findPK($this->getRequestParameter('idPage'));
      if ($page != null)
      {
        $pageManager = new w3sPageManager($page);
        $pageManager->delete();

        $fileManager = new w3sFileManager($this->getRequestParameter('curLang'), $this->getRequestParameter('curPage'));
        return $this->renderPartial('listPages', array('fileManager' => $fileManager));
      }
      else
      {
        $this->getResponse()->setStatusCode(404);
        return $this->renderText(w3sCommonFunctions::toI18n('The requested page does not exists anymore.'));
      }
    }
    else
	  {
	  	$this->getResponse()->setStatusCode(404);
    	return $this->renderText(w3sCommonFunctions::toI18n('One or more required parameter are missing.'));
	  }
  }

  /**
   * Renames the page
   */
  public function executeRename($request)
  {
    if ($request->hasParameter('idPage') && $request->hasParameter('newName'))
    {
      $page = W3sPagePeer::retrieveByPk($this->getRequestParameter('idPage'));
      if ($page != null)
      {
        $page->setPageName($this->getRequestParameter('newName'));
        $page->save();

        $fileManager = new w3sFileManager($this->getRequestParameter('curLang'), $this->getRequestParameter('curPage'));
        return $this->renderPartial('listPages', array('fileManager' => $fileManager));
      }
      else
      {
        $this->getResponse()->setStatusCode(404);
        return $this->renderText(w3sCommonFunctions::toI18n('The requested page does not exists anymore.'));
      }
    }
    else
	  {
	  	$this->getResponse()->setStatusCode(404);
    	return $this->renderText(w3sCommonFunctions::toI18n('One or more required parameter are missing.'));
	  }
  }
}