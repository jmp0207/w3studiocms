<?php
require_once(sfConfig::get('sf_plugins_dir').'/sfGuardPlugin/modules/sfGuardAuth/lib/BasesfGuardAuthActions.class.php');

class sfGuardAuthComponents extends sfComponents
{
   
  public function executeSignin($request)
  {
  	$this->isAjax = ($request->getHttpHeader('X_REQUESTED_WITH') == 'XMLHttpRequest') ? true : false;

    $language = ($request->getParameter('lang') == null) ? DbFinder::from('W3sLanguage')->where('mainLanguage', 1)->findOne()->getId() : $request->getParameter('lang');
    $page = ($request->getParameter('page') == null) ? DbFinder::from('W3sPage')->where('isHome', 1)->findOne()->getId() : $request->getParameter('page');

    $defaults = array('lang' => $language, 'page' => $page);
    $this->form = new sfGuardFormW3studioCmsSignin($defaults);
  }
}