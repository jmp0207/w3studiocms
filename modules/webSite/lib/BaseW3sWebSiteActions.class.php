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

class BaseW3sWebSiteActions extends sfActions
{
  /**
   * Draws the web page
   *
   */
  public function executeIndex()
  {
    $this->template = new w3sTemplateEngineFrontend($this->getRequestParameter('lang'), $this->getRequestParameter('page'));
    $styles = $this->template->retrieveTemplateStylesheets($this->template->getPageContents());
		foreach($styles['stylesheets'] as $style){
			$this->response->addStyleSheet($style["href"], 'last', array('media' => $style["media"]));
		}
	
    if($this->template->getIdLanguage() == -1)
    { 
    	$message = sprintf($this->getContext()->getI18N()->__('The language %s does not exist'), $this->getRequestParameter('lang'));
    }
    else if($this->template->getIdPage() == -1)
  	{ 
  		$message = sprintf($this->getContext()->getI18N()->__('The page %s does not exist for the language %s'), $this->getRequestParameter('page'), $this->template->getLanguageName());
  	}
  	else
  	{
  		$message = null;
  	}  	
  	
    $this->forward404If($this->template->getIdLanguage() == -1 || $this->template->getIdPage() == -1, $message);
    
    $this->includeFiles($this->template->getJavascripts());
    $this->includeFiles($this->template->getStylesheets());		

    // Retrieves the page's metatags
    $oMetatag = W3sSearchEnginePeer::getFromPageAndLanguage($this->template->getIdLanguage(), $this->template->getIdPage());
    
    // Sets metatags
    $this->getResponse()->addMeta('title', ($oMetatag != null) ? $oMetatag->getMetaTitle() : 'A website powered by W3StudioCMS');
    $this->getResponse()->addMeta('keywords', ($oMetatag != null) ? $oMetatag->getMetaKeywords() : '');
    $this->getResponse()->addMeta('description', ($oMetatag != null) ? $oMetatag->getMetaDescription() : '');
  }
  
  /**
   * Includes css and js files
   *
   */
  protected function includeFiles($array)
  {
  	foreach ($array as $file)
  	{
  		$info = pathinfo($file); 
  		if (isset($info['extension'])){
	  		switch($info['extension']){
	  			case 'js':
	  				$this->getResponse()->addJavascript($file);
	  				break;
	  			case 'css':
	  				$this->getResponse()->addStylesheet($file);
	  				break;
	  			default: 
	  				throw new RuntimeException(sprintf('Type file %s is not allowed in this context.', $info['extension']));
	  		}
  		}
  		else
  		{
  			throw new RuntimeException(sprintf('The file %s hasn\'t any extension', $file));
  		}  		
  	}
  }
  /*
  public function executeDrawPartialsTemplates()
  {
    $oGroup = W3sGroupPeer::doSelect(new Criteria());
    foreach ($oGroup as $group):
      $oPage = new w3sClassDrawTemplate();
      $oTemplate = $group->getW3sTemplate();
      $templateName = $oTemplate->getTemplateName();
      $projectName = $oTemplate->getW3sProject()->getProjectName();
      $rederedTemplate = $oPage->drawTemplateSkeleton($projectName, $templateName, $group->getTemplateId(), 0);
    endforeach;
    return sfView::NONE;
  }
   
  public function executeChangeRepeatedFromContentToSlot()
  {
    if ($this->getRequest->hasParameter('language')){
      $c = new Criteria();
      $c->addGroupByColumn(W3sPagePeer::GROUP_ID);
      $oPages = W3sPagePeer::doSelect($c); 
      foreach ($oPages as $oPage){
        $c = new Criteria();
        $c->add(W3sContentPeer::LANGUAGE_ID, $this->getRequestParameter('language'));
        $c->add(W3sContentPeer::PAGE_ID, $oPage->getId());
        $c->addAscendingOrderByColumn(W3sContentPeer::SLOT_ID);
        $c->addAscendingOrderByColumn(W3sContentPeer::CONTENT_POSITION);
        $c->addGroupByColumn(W3sContentPeer::SLOT_ID);
        $oContents = W3sContentPeer::doSelectJoinW3sSlot($c);
        foreach ($oContents as $oContent){ //print_r($oContent);
          $oContent->getW3sSlot()->setRepeatedContents($oContent->getRepeated());
          echo ($oContent->getW3sSlot()->save()) ? "1<br>" : "0<br>";
        }
      }
      
      return sfView::NONE;
    }
    else{
      $this->renderText('You must pass the language');
    }    
  }
  */
}

