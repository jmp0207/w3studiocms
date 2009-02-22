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

require_once(dirname(__FILE__).'/../../contentsManager/lib/BaseW3sContentManagerActions.class.php');

class BaseW3sMenuBuilderActions extends BaseW3sContentsManagerActions
{
	
	/**
	 * Overrides the default content's edit action 
	 */
	public function executeEdit($request)
  {     
    $currentContent = DbFinder::from('W3sContent')->findPK($this->getRequestParameter('idContent'));
    if($this->getRequestParameter('setClassToPages') == 0)
    { 
	    $content = w3sContentManagerFactory::create($currentContent->getContentTypeId(), $currentContent);
	    $result = $content->edit(array('Content' => $this->getRequestParameter('content')));
    }
    else
    { 
    	$content = w3sContentManagerFactory::create($currentContent->getContentTypeId(), $currentContent);
    	$menu = new w3sGroupForeignContentsMenu($content);
    	$result = $menu->update($this->getRequestParameter('content'));
    }
    
    if ($result == 1)
    { 
    	$currentContent = DbFinder::from('W3sContent')->findPK($this->getRequestParameter('idContent'));
    	$content = w3sContentManagerFactory::create($currentContent->getContentTypeId(), $currentContent);
    	
    	return $this->renderText($content->getDisplayContentForEditorMode());
    }
    else
    {     	
    	$this->getResponse()->setStatusCode(404);
    	return $this->renderText($content->displayError($result, true));
    }    
  }
  
  /**
	 * Show the images for the menu builder editor
	 */
  public function executeShowImages()
  {
    try
    {
      $imagesList = w3sCommonFunctions::buildFilesList(sfConfig::get('app_images_path'), '', array('gif', 'jpg', 'jpeg', 'png'));
      $imageList = new w3sImageListMenuBuilder($imagesList, $this->getRequestParameter('property'));

      return $this->renderText($imageList->renderImageList(), $this->getRequestParameter('property'));
    }
    catch(Exception $e)
    {
      return $this->renderText('Something was wrong while refreshing images lists.');
    }
  }

	/**
	 * Adds a link to the current menu
	 */
  public function executeAddLink($request)
  { 
    if ($request->hasParameter('idContent'))
    {
      $this->menuEditor = new w3sMenuEditor(DbFinder::from('W3sContent')->findPK($this->getRequestParameter('idContent')), 5);
      //$this->menuEditor->saveLinks($this->getRequest()->getParameterHolder()->getAll());
      $this->result = $this->menuEditor->saveMenuLink($this->getContext()->getI18N()->__('This is a link'));

      if ($this->result != 0)
      {
        $this->clearCache($this->getRequestParameter('idContent'));
      }
      else
      {
        $this->getResponse()->setStatusCode(404);
      }
    }
    else
    {
      $this->getResponse()->setStatusCode(404);
      return $this->renderText('A required parameter misses.');
    }
  }

	/**
	 * Deletes a link from the current menu
	 */
  public function executeDeleteLink($request)
  {
    if ($request->hasParameter('idContent') && $this->getRequestParameter('idMenu'))
    {
      $this->menuEditor = new w3sMenuEditor(DbFinder::from('W3sContent')->findPK($this->getRequestParameter('idContent')), 5);
      
      $menu = DbFinder::from('W3sMenuElement')->findPK($this->getRequestParameter('idMenu'));
      $position = $menu->getPosition();
      $menu->delete();
      
      $menuElements =  DbFinder::from('W3sMenuElement')->
                       where('contentId', $this->getRequestParameter('idContent'))->
                       where('Position', '>', $position)->
                       orderBy('Position', 'ASC')->
                       find(); print_R($menuElements);
      foreach($menuElements as $menuElement)
      {
        echo $position."\n";
        $menuElement->setPosition($position);
        $menuElement->save();
        $position++;
      }

      $this->clearCache($this->getRequestParameter('idContent'));
    }
    else
    {
      $this->getResponse()->setStatusCode(404);
      return $this->renderText('A required parameter misses.');
    }
  }
  
  /**
	 * Deletes a link from the current menu
	 */
  public function executeChangePosition()
  {
    $i = 1;
    $menuList = $this->getRequestParameter('w3s_mb_list');
    foreach($menuList as $idMenu)
    {
    	$menu = DbFinder::from('W3sMenuElement')->findPK($idMenu);
			$menu->setPosition($i);
			$menu->save();
			$i++;
    }
    
    return sfView::NONE;
  }

/*
  public function executeSave()
  {
    // We assure that all the operations W3StudioCMS makes will be successfully done
    $con = Propel::getConnection();
    $bRollBack = false;
    $con->begin();
    
    $this->saveLinks($this->getRequest()->getParameterHolder()->getAll());

    $oLanguage = W3sLanguagePeer::retrieveByPK($this->getRequestParameter('lang'));
    $language = $oLanguage->getLanguage();
    $menu = W3sMenuElementPeer::getContentMenu($this->getRequestParameter('idContent'));

    if($this->getRequestParameter('setClassToPages') == 1){
      $oMenuElement = new W3sMenuElement();
      $group = W3sGroupPeer::retrieveByPk($this->getRequestParameter('idGroup'));
      foreach($group->getW3sPages() as $page):
        $idPage = $page->getId();
        $currentContents = W3sContentPeer::getSlotContents($this->getRequestParameter('idSlot'), $this->getRequestParameter('lang'), $idPage);       
        foreach($currentContents as $currentContent):
        	//echo $oPage->getPageName() . ' - ' . $this->getRequestParameter('classAssigned') . '<br>';
          $result = $this->renderMenu($menu, $language, $this->getRequestParameter('assignedTo'), $page->getPageName(), $this->getRequestParameter('classAssigned'));

          if ($idPage == $this->getRequestParameter('idPage')) $savedResult = $result;
          if ($currentContent == null){
            $currentContent = new W3sContent;
            $aParams = array("idPage" => $idPage,
                             "idLanguage" => $this->getRequestParameter('lang'),
                             "idSlot" => $this->getRequestParameter('idSlot'),
                             "idGroup" => $this->getRequestParameter('idGroup'),
                             "idContent" => 0,
                             "content" => $result,
                             "contentType" => '5');
          }
          else{
            $aParams = array("idPage" => $idPage,
                             "idLanguage" => $this->getRequestParameter('lang'),
                             "idSlot" => $this->getRequestParameter('idSlot'),
                             "idContent" => $currentContent->getId(),
                             "content" => $result,
                             "contentType" => '5');
          }
          $currentContent->saveContent($aParams);
          $oMenuElement->copyMenuFromContent($this->getRequestParameter('idContent'), $currentContent->getId());
        endforeach;    
      endforeach;
    }
    else{
      $oPage = W3sPagePeer::retrieveByPk($this->getRequestParameter('idPage'));      
      $savedResult = $this->renderMenu($menu, $language, $this->getRequestParameter('assignedTo'), $oPage->getPageName(), $this->getRequestParameter('classAssigned'));
      
      $contentValues = array("idPage"                 => $this->getRequestParameter('idPage'),
                             "idLanguage"             => $this->getRequestParameter('lang'),
                             "idSlot"                 => $this->getRequestParameter('idSlot'),
                             "idContent"              => $this->getRequestParameter('idContent'),
                             "contentType"            => '5',
                             "content"                => $savedResult);
      $content = new W3sContent();
      if ($content->saveContent($contentValues) != 1){
        $this->getResponse()->setStatusCode(404);
        return sfView::NONE;
      }
    }    
    
    if (!$bRollBack){ // Everything was fine so W3StudioCMS commits to database
      $con->commit();
      $result = 1;
    }
    else{             // Something was wrong so W3StudioCMS aborts the operation and restores to previous status
      w3sPropelWorkaround::rollBack($con);
      $result = 0;
    }
    
    $this->clearCache($this->getRequestParameter('idContent'));
   
    return $this->renderText($savedResult);
  }*/
  
  /**
   * Clears the cache for the current content's modue
   *
   * @param      int The id of the current content
   * 
   * @return     none
   */
  protected function clearCache($idContent){    
    if (sfConfig::get('sf_cache')){
      $cacheManager = $this->getContext()->getViewCacheManager();
      $cacheManager->remove('menuBuilder/index?idContent=' . $idContent);
     }  
  }

}