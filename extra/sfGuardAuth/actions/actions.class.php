<?php

require_once(sfConfig::get('sf_plugins_dir').'/sfGuardPlugin/modules/sfGuardAuth/lib/BasesfGuardAuthActions.class.php');

class sfGuardAuthActions extends BasesfGuardAuthActions
{
   
  public function executeSignin($request)
  {
  	$user = $this->getUser();
    if ($user->isAuthenticated())
    {
      return $this->redirect('@homepage');
    }

    $this->form = new sfGuardFormSignin();

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('signin'));
      if ($this->form->isValid())
      {      	
        $values = $this->form->getValues();
        $this->getUser()->signin($values['user']);

        $signinUrl = sfConfig::get('app_sf_guard_plugin_success_signin_url', $user->getReferer($request->getReferer()));
				
				return sfView::NONE;
      }
    }
  }  
 
  public function executeSignout($request)
  {
    /* We need to be sure that user is authenticated because it can have more
     * sessions opened in several browsers ot browsers tabs
     */
    if ($this->getUser()->isAuthenticated()){
      $idUser = $this->getUser()->getGuardUser()->getId();
      $this->getUser()->signOut();
  
      $operation = $this->getRequestParameter('lang') . $this->getRequestParameter('page');
      semaphore::deleteOperation($idUser, $operation);
    }

    $oPage = W3sPagePeer::retrieveByPk($this->getRequestParameter('page'));
    $oLanguage = W3sLanguagePeer::retrieveByPk($this->getRequestParameter('lang'));
    $this->getResponse()->setHttpHeader('X-JSON', '([["sPageUrl", "/' . strtolower($oLanguage->getLanguage()) . '/' . strtolower($oPage->getPageName()) . '.html"]])');
    return sfView::HEADER_ONLY;

  }

  public function handleErrorSignin()
  {
    $this->getResponse()->setStatusCode(404);
    $user = $this->getUser();
    if (!$user->hasAttribute('referer'))
    {
      $user->setAttribute('referer', $this->getRequest()->getReferer());
    }
  }

}