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
  public function executeShowAddModule()
  {    
    $this->pageEditor = new w3sPageEditor();
  }

  /**
   * Adds the page
   */
  public function executeAdd()
  {
    $page = new w3sPageManager();
    $result = $page->add($this->getRequestParameter('pageName'), $this->getRequestParameter('idGroup'));
    
    if ($result != 1) $this->getResponse()->setStatusCode(404);
    return $this->renderPartial('add', array('result' => $result));
  }

  /**
   * Lists pages
   */
  public function executeShowPages()
  {
    $fileManager = new w3sFileManager($this->getRequestParameter('curLang'), $this->getRequestParameter('curPage'));
    return $this->renderPartial('listPages', array('fileManager' => $fileManager));
  }

  /**
   * Deletes the page
   */
  public function executeDelete()
  {
    $page = new w3sPageManager(DbFinder::from('W3sPage')->findPK($this->getRequestParameter('idPage')));
    $page->delete();

		$fileManager = new w3sFileManager($this->getRequestParameter('curLang'), $this->getRequestParameter('curPage'));
    return $this->renderPartial('listPages', array('fileManager' => $fileManager));
  }

  /**
   * Renames the page
   */
  public function executeRename()
  {
    $page = W3sPagePeer::retrieveByPk($this->getRequestParameter('idPage'));
    if ($page != null){
      $page->setPageName($this->getRequestParameter('newName'));
      $page->save();
    }

    $fileManager = new w3sFileManager($this->getRequestParameter('curLang'), $this->getRequestParameter('curPage'));
    return $this->renderPartial('listPages', array('fileManager' => $fileManager));
  }
}