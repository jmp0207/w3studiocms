<?php

/**
 * slotMapper actions.
 *
 * @package    w3studioCMS
 * @subpackage slotMapper
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class BaseSlotMapperActions extends sfActions
{
  public function executeRenderTemplate(sfWebRequest $request)
  {
    $this->status = 0;
    if ($request->hasParameter('idTemplate') && $request->hasParameter('otherTemplate'))
    {
      if ($this->getUser()->isAuthenticated())
      {
        $this->status = 1;
        $this->template = new w3sTemplateEngineSlotMapper($request->getParameter('idTemplate'), $request->getParameter('otherTemplate'));

        $this->getResponse()->setHttpHeader('X-JSON', sprintf('([["status", "%s"], ["stylesheet", "%s"]])', $this->status, $this->template->retrieveTemplateStylesheets()));
      }
      else
      {
        $this->status = 4;
        $this->getResponse()->setStatusCode(404);
        $this->getResponse()->setHttpHeader('X-JSON', sprintf('([["status", "%s"]])', $this->status));
      }
    }
    else
    {
      $this->status = 2;
      $this->getResponse()->setStatusCode(404);
      $this->getResponse()->setHttpHeader('X-JSON', sprintf('([["status", "%s"]])', $this->status));
    }
  }

 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeRenderPanel(sfWebRequest $request)
  {
    $this->slotMapperPanel = new w3sSlotMapperPanel($request->getParameter('sourceId'), $request->getParameter('destId'));
  }

  public function executeSave(sfWebRequest $request)
  {
    if ($request->hasParameter('w3s_sm_source') && $request->hasParameter('w3s_sm_dest'))
    {
      $slotMapperPanel = new w3sSlotMapperPanel($request->getParameter('sourceId'), $request->getParameter('destId'));
      $this->result = $slotMapperPanel->save($request->getParameter('w3s_sm_source'), $request->getParameter('w3s_sm_dest'));
    }
  }
}
