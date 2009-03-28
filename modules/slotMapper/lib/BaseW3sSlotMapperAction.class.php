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
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeRender(sfWebRequest $request)
  {
    $this->slotMapperPanel = new w3sSlotMapperPanel($request->getParameter('sourceId'), $request->getParameter('destId'));
  }
}
