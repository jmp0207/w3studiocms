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

class BaseW3sContentsManagerActions extends sfActions
{	
	/* 
   * The default contents' actions 
   */ 
	
  /**
   * Adds a new content below the content which id has been passed as reference
   *
   */
  public function executeAdd($request)
  {    
    if($request->hasParameter('idContent'))
    {
	    $contentType = ($request->hasParameter('contentType')) ? $this->getRequestParameter('contentType') : 2;
	    $this->content = w3sContentManagerFactory::create($contentType);
	    $referenceContent = ($this->getRequestParameter('idContent') > 0) ? DbFinder::from('W3sContent')->findPK($this->getRequestParameter('idContent')) : null; 
	    if ($referenceContent == null)
	    { 										
	    	if($request->hasParameter('page') &&
		    	 $request->hasParameter('idSlot') &&
		    	 $request->hasParameter('language'))
		   	{
		    	$param = array("PageId"                 => $this->getRequestParameter('page'),
					               "SlotId"                 => $this->getRequestParameter('idSlot'),
					               "LanguageId"             => $this->getRequestParameter('language'),
					               "GroupId"                => 0,
					               "Content"        				=> '',
					               "ContentTypeId"          => $contentType,
					               "ContentPosition"        => 0,
					               "Edited"                 => 1);
		   	}
		   	else
		   	{
		   		$this->getResponse()->setStatusCode(404);
		   		return $this->renderText(w3sCommonFunctions::toI18n('A required parameter misses.'));
		   	}
	    }
	    else
	    {
	    	$param = clone($referenceContent);
	    	
	    	// This constrains the content to insert of the dafault text
	    	$param->setContent('');
	    }
	    
	    $result = $this->content->add($param);
	    if ($result != 1)
      {
	    	$this->getResponse()->setStatusCode(404);
	    	return $this->renderText($this->content->displayError($result, true));
	    }
    }
    else
    {
    	$this->getResponse()->setStatusCode(404);
    	return $this->renderText(w3sCommonFunctions::toI18n('A required parameter misses.'));
    }
  }

  /**
   * Edit a content
   *
   */
  public function executeEdit($request)
  {     
    if($request->hasParameter('idContent') && $request->hasParameter('content'))
    {
	    $currentContent = DbFinder::from('W3sContent')->findPK($request->getParameter('idContent'));
	    $content = w3sContentManagerFactory::create($currentContent->getContentTypeId(), $currentContent);
	    $result = $content->edit(array('Content' => $this->getRequestParameter('content')));
	    
	    if ($result != 1){     	
	    	$this->getResponse()->setStatusCode(404);
	    	return $this->renderText($content->displayError($result, true));
	    }
	    return $this->renderText($content->getDisplayContentForEditorMode());
	  }
    else
    {
    	$this->getResponse()->setStatusCode(404);
    	return $this->renderText(w3sCommonFunctions::toI18n('A required parameter misses.'));
    }
  }

  /**
   * Deletes a content
   *
   */
  public function executeDelete($request)
  {
    if($request->hasParameter('idContent'))
    {
	    $currentContent = DbFinder::from('W3sContent')->findPK($request->getParameter('idContent'));
      $this->content = w3sContentManagerFactory::create($currentContent->getContentTypeId(), $currentContent);
	    $result = $this->content->delete();
	    if ($result != 1){     	
	    	$this->getResponse()->setStatusCode(404);
	    	return $this->renderText($this->content->displayError($result, true));
	    }
	  }
    else
    {
    	$this->getResponse()->setStatusCode(404);
    	return $this->renderText(w3sCommonFunctions::toI18n('A required parameter misses.'));
    }
  }

  /**
   * Moves the content through the web page. The content has been moved using the scriptacoluos's
   * drag-drop functions
   */
  public function executeMove($request)
  {
    $slotManager = new w3sContentsMove($this->getRequestParameter('language'), 
    																	 $this->getRequestParameter('page'), 
    																	 $this->getRequestParameter('slotId'));
    $slotContents = ($request->hasParameter($this->getRequestParameter('slotName'))) ? $this->getRequestParameter($this->getRequestParameter('slotName')) : array();    
    
    $result = $slotManager->moveContents($slotContents); //  print_r($slotContents);  echo $this->getRequestParameter('slotName');
    
    if ($result != 1) $this->getResponse()->setStatusCode(404);
    return $this->renderPartial('move', array('result' => $result));
  }
  
  /* 
   * The actions needed to manage the repeated contents
   */ 
  
  public function executeShowChangeRepeatedContents()
  {
    $this->slot = W3sSlotPeer::retrieveByPk($this->getRequestParameter('idSlot'));
  }
  
  public function executeChangeRepeatedContents($request)
  {
    if ($request->hasParameter('page') &&
        $request->hasParameter('lang') &&
        $request->hasParameter('slotName') &&
        $request->hasParameter('newRepeatedValue'))
      {
        $bRollBack = false;
        $con = Propel::getConnection();
        $con = w3sPropelWorkaround::beginTransaction($con); 
        
				$slots = DbFinder::from('W3sSlot')->						               
						               where('SlotName', $this->getRequestParameter('slotName'))->
						               find(); 
        foreach($slots as $slot){
	        $slot->setRepeatedContents($this->getRequestParameter('newRepeatedValue'));
	        $result = $slot->save();
	        if ($slot->isModified() && $result == 0){
	          $bRollBack = true;
	          break;
	        }
	      }
        
        if (!$bRollBack)
        {   // Everything was fine so W3StudioCMS commits to database
          $con->commit();
          $this->idLanguage = $this->getRequestParameter('lang');
          $this->idPage = $this->getRequestParameter('page');
          $this->forward('controlPanel', 'drawSlots');
        }
        else
        {               // Something was wrong so W3StudioCMS aborts the operation and restores to previous status
          w3sPropelWorkaround::rollBack($con);
          $this->result = 0;
          $this->getResponse()->setStatusCode(404);
          return sfView::ERROR;
        }
     }
     else{
       $this->result = 2;
       $this->getResponse()->setStatusCode(404);
       return sfView::ERROR;
     }     
  }      
}