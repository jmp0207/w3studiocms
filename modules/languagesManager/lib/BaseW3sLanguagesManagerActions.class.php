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

class BaseW3sLanguagesManagerActions extends sfActions
{
	/**
   * Executes show action
   *
   */
  public function executeShow()
  {
  	$language = ($this->getRequestParameter('idLanguage') > 0) ? DbFinder::from('W3sLanguage')->findPK($this->getRequestParameter('idLanguage')) : null;
  	$languagesEditor = new w3sLanguagesEditor($language);
  	return $this->renderPartial('show', array('languagesEditor' => $languagesEditor));
  }

  /**
   * Executes add action
   *
   */
  public function executeAdd()
  {
    if (
    		$this->getRequest()->hasParameter('languageName') &&
        $this->getRequest()->hasParameter('isMain')
       )
    {
      $language = W3sLanguagePeer::getFromLanguageName($this->getRequestParameter('languageName'));
      if ($language == null)
      {
      	$params = array("isMain" => $this->getRequestParameter('isMain'), 
      									"languageName" => $this->getRequestParameter('languageName'));
      	$language = new w3sLanguageManager();
      	$result = $language->add($params);
      }
      else
      {
        $result = 2;
      }  
    }
    else
    {
      $result = 4;        
    }
    
    if ($result != 1) $this->getResponse()->setStatusCode(404);
    return $this->renderPartial('add', array('result' => $result));
  }
  
  /**
   * Executes edit action
   *
   */
  public function executeEdit()
  {
    if (
    		$this->getRequest()->hasParameter('idLanguage') &&
        $this->getRequest()->hasParameter('languageName') &&
        $this->getRequest()->hasParameter('isMain')
       )
    {
      $language = DbFinder::from('W3sLanguage')->findPK($this->getRequestParameter('idLanguage'));
     	if ($language != null)
     	{
     		$params = array("isMain" => $this->getRequestParameter('isMain'), 
      									"languageName" => $this->getRequestParameter('languageName'));
		  	$language = new w3sLanguageManager($language);
		  	$result = $language->edit($params);
     	}
      else
      {
        $result = 2;
      }  
    }
    else{
      $result = 4;        
    }
    
    if ($result != 1) $this->getResponse()->setStatusCode(404);
    return $this->renderPartial('edit', array('result' => $result));
  }

  /**
   * Executes delete action
   *
   */
  public function executeDelete()
  {
    $language = DbFinder::from('W3sLanguage')->findPK($this->getRequestParameter('idLanguage'));
    
    $language = new w3sLanguageManager($language);
    $result = $language->delete();
	  
	  
	  if ($result != 1) $this->getResponse()->setStatusCode(404);
	  return $this->renderPartial('delete', array('result' => $result));
  }
  
  /**
   * Executes drawLanguagesSelect
   *
   */
  public function executeRefreshLanguages()
  {
    $controlPanel = new w3sControlPanel();
  	return $this->renderPartial('drawLanguagesSelect', array('controlPanel' => $controlPanel));
  }
}