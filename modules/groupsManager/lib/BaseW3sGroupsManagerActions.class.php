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

class BaseW3sGroupsManagerActions extends sfActions
{
	/**
   * Shows the module that allows the user to edit or add a group
   */
  public function executeShow()
  {
    $groupEditor = new w3sGroupEditor();
    return $this->renderPartial('show', array('groupEditor' => $groupEditor));     
  }

  /**
   * Saves the group
   */
  public function executeAdd($request)
  {
	  if($request->hasParameter('groupName') && $request->hasParameter('idTemplate'))
    {
		  $group = new w3sGroupManager();
		  $result = $group->add($this->getRequestParameter('groupName'), $this->getRequestParameter('idTemplate'));
		  if($result != 1) $this->getResponse()->setStatusCode(404);
		  return $this->renderPartial('add', array('result' => $result));
    }
	  else
	  {
	  	$this->getResponse()->setStatusCode(404);
    	return $this->renderText(w3sCommonFunctions::toI18n('A required parameter misses.'));
	  }
  }

  /**
   * Reloads the combobox with groups
   */
  public function executeRefresh()
  {
    $fileManager = new w3sFileManager();
    return $this->renderPartial('drawGroupsSelect', array('fileManager' => $fileManager));
  }

  /**
   * Deletes the group that is selected into the groups' combobox
   */
  public function executeDelete()
  {
    if ($this->getRequest()->hasParameter('idGroup'))
    { 
      $result = 0;
      $group = DbFinder::from('W3sGroup')->findPK($this->getRequestParameter('idGroup'));
      if (!empty($group))
      {      
        $groupManager = new w3sGroupManager($group);
        $result = $groupManager->delete();
      }
      if ($result != 1) $this->getResponse()->setStatusCode(404);
    } 
    else{
      $result = 2;
      $this->getResponse()->setStatusCode(404);
    }
    return $this->renderPartial('delete', array('result' => $result));
  }
  
  // **********************************************
  // The following actions must totally be reviewed
  // **********************************************
  
  /**
   * Shows the module that allows the user to change the page's group
   */
  public function executeShowChangePageGroupModule()
  {
    if ($this->getRequestParameter('idGroup') != ''){
      $this->idPage = $this->getRequestParameter('idPage');
      $this->idGroup = $this->getRequestParameter('idGroup');
      $c = new Criteria();
      $c->add(W3sGroupPeer::TO_DELETE, 0);
      $this->options = W3sGroupPeer::doSelect($c);
    }
  }
  
  /**
   * Changes the group for the selected page
   */
  public function executeChangePageGroup()
  {
    // Changes the page's group
    $page = new W3sPage;
    $this->result = $page->changePageGroup($this->getRequestParameter('idPage'),
                                           $this->getRequestParameter('idNewGroup'),
                                           $this->getRequestParameter('idGroup'),
                                           $this->getRequestParameter('elements'));

    // This header tells the ajax function the result of the adding operation
    $this->result = $this->getResponse()->setHttpHeader("X-JSON", '([["", ' . $this->result . ']])');

    /* Something was wrong. We need to pass the templates options because the result of this action
     * displays again the interface to add/edit the group
     */
    if ($this->result != 1){
      $this->idPage = $this->getRequestParameter('idPage');
      $this->idGroup = $this->getRequestParameter('idGroup');
      $this->options = W3sTemplatePeer::doSelect(new Criteria());
    }

  }
  
  /**
   * Reloads the combobox with groups.
   */
  public function executeCheckTemplateElements()
  {
    // Change page mode
    if ($this->getRequestParameter('idNewGroup')  != ''){
      $oGroups = W3sGroupPeer::retrieveByPk($this->getRequestParameter('idNewGroup'));
      $idNewTemplate = $oGroups->getTemplateId();
    }

    // Change group mode
    else{
      $idNewTemplate = $this->getRequestParameter('idNewTemplate');
    }

    $groups = W3sGroupPeer::retrieveByPk($this->getRequestParameter('idGroup'));
    if ($groups->getTemplateId() != $idNewTemplate){
      $group = new W3sGroup();
      $this->changinElements = $group->checkTemplateElementsForChange($this->getRequestParameter('idGroup'), $groups->getTemplateId(), $idNewTemplate);
      //$this->availableElements = W3sTemplateElementPeer::divNamesOptionsForSelect($idNewTemplate, true);
    }
    else{
      $this->changinElements = array();
    }
  }
}