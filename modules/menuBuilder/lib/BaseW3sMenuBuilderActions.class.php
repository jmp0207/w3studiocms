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
    if ($request->hasParameter('idContent') && $request->hasParameter('content'))
    {
      $content = DbFinder::from('W3sContent')->findPK($this->getRequestParameter('idContent'));
      if (is_object($content))
      {
        if ($content->getContentTypeId() == 5)
        {
          $currentContent = DbFinder::from('W3sContent')->findPK($this->getRequestParameter('idContent'));
          if(!$request->hasParameter('setClassToPages') || $this->getRequestParameter('setClassToPages') == 0)
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
        else
        {
          $this->getResponse()->setStatusCode(404);
          return $this->renderText('The content you tried to edit is not a valid navigation menu.');
        }
      }
      else
      {
        $this->getResponse()->setStatusCode(404);
        return $this->renderText('The content you tried to edit doesn\'t exist anymore.');
      }
    }
    else
    {
      $this->getResponse()->setStatusCode(404);
      return $this->renderText('A required parameter misses.');
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
      $content = DbFinder::from('W3sContent')->findPK($this->getRequestParameter('idContent'));
      if (is_object($content))
      {
        if ($content->getContentTypeId() == 5)
        {
          $this->menuEditor = new w3sMenuEditor($content);
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
          return $this->renderText('The content you tried to edit is not a valid navigation menu.');
        }
      }
      else
      {
        $this->getResponse()->setStatusCode(404);
        return $this->renderText('The content you tried to edit doesn\'t exist anymore.');
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
      $content = DbFinder::from('W3sContent')->findPK($this->getRequestParameter('idContent'));
      if (is_object($content))
      {
        if ($content->getContentTypeId() == 5)
        {
          $this->menuEditor = new w3sMenuEditor($content);

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
            $menuElement->setPosition($position);
            $menuElement->save();
            $position++;
          }

          $this->clearCache($this->getRequestParameter('idContent'));
        }
        else
        {
          $this->getResponse()->setStatusCode(404);
          return $this->renderText('The content you tried to delete is not a valid navigation menu.');
        }
      }
      else
      {
        $this->getResponse()->setStatusCode(404);
        return $this->renderText('The content you tried to edit doesn\'t exist anymore.');
      }
    }
    else
    {
      $this->getResponse()->setStatusCode(404);
      return $this->renderText('A required parameter misses.');
    }
  }
  
  /**
	 * Deletes a link from the current menu. Obsolete
	 
  public function executeChangePosition($request)
  {
    if ($request->hasParameter('w3s_mb_list'))
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
    else
    {
      $this->getResponse()->setStatusCode(404);
      return $this->renderText('A required parameter misses.');
    }
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