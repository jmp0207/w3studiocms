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

    if ($request->isMethod('post'))
    {
      $this->isAjax = ($request->getHttpHeader('X_REQUESTED_WITH') == 'XMLHttpRequest') ? true : false;

      $defaults = array('lang' => $request->getParameter('lang'), 'page' => $request->getParameter('page'));
      $this->form = new sfGuardFormW3studioCmsSignin($defaults);

      $this->form->bind($request->getParameter('signin'));
      
      if ($this->form->isValid())
      {      	
        $values = $this->form->getValues();
        
        $this->getUser()->signin($values['user']);

        $signinUrl = sfConfig::get('app_sf_guard_plugin_success_signin_url', $user->getReferer($request->getReferer()));

        if ($this->isAjax)
        {
          return sfView::NONE;
        }
        else
        {
          if ((int)$values['lang'] > 0 && (int)$values['page'] > 0)
          {
            $language = DbFinder::from('W3sLanguage')->findPK($values['lang']);
            $page = DbFinder::from('W3sPage')->findPK($values['page']);
            if ($language == null) $language = DbFinder::from('W3sLanguage')->where('mainLanguage', 1)->findOne();
            if ($page == null) $page = DbFinder::from('W3sPage')->where('isHome', 1)->findOne();
            $languageName = $language->getLanguage();
            $pageName = $page->getPageName();
          }
          else
          {
            $languageName = $values['lang'];
            $pageName = $values['page'];
          }

          return $this->redirect(sprintf('/W3studioCms/%s/%s.html', $languageName, $pageName));
        }
      }
    }
  }  
 
  public function executeSignout($request)
  {
    /* We need to be sure that user is authenticated because it can have more
     * sessions opened in several browsers ot browsers tabs
     */
    if ($this->getUser()->isAuthenticated())
    {
      $idUser = $this->getUser()->getGuardUser()->getId();
      $this->getUser()->signOut();
  
      $operation = $request->getParameter('lang') . $request->getParameter('page');
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