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

class BaseW3sEditorActions extends sfActions
{
  
  /**
   * Loads the W3StudioCMS and sets the interactive menu, the menu manager,
   * the text editor
   *
   */
  public function executeIndex()
  {    
    $this->template = new w3sTemplateEngineEditor($this->getRequestParameter('lang'), $this->getRequestParameter('page'));
    $this->managerMenu = new w3sMenuManager('w3s_menu_manager', 'tbMenuManager.yml', $this->getUser());
    $this->interactiveMenu = new w3sMenuInteractive('w3s_interactive_menu');
    $this->commands = new w3sMenuVertical('w3s_im_commands', 'tbInteractiveMenuCommands.yml');    
    $this->actions = new w3sMenuVertical('w3s_im_actions', 'tbInteractiveMenuActions.yml');
  }
  
  /**
   * Executes loadPage action
   *
   */
  public function executeLoadPage($request)
  {    
    if ($request->hasParameter('prevPage'))
    {
      $this->status = 0;
      if ($this->getUser()->isAuthenticated())
      {
        $this->template = new w3sTemplateEngineEditor($this->getRequestParameter('lang'), $this->getRequestParameter('page'));        
        if ($this->template->getIdLanguage() != -1 && $this->template->getIdPage() != -1)
        {
          $this->status = ($this->template->isPageFree($this->getRequestParameter('prevPage'))) ? 1 : 4;         
        }
        else
        {
          $this->status = 8;
        }
      }
      else
      {
        $this->status = 2;
      }

      if ($this->status != 1) $this->getResponse()->setStatusCode(404);
      $this->getResponse()->setHttpHeader('X-JSON', sprintf('([["status", "%s"], ["stylesheet", "%s"]])', $this->status, $this->template->retrieveTemplateStylesheets()));
    }
    else
    {
      $this->status = 16;
      $this->getResponse()->setStatusCode(404);
      $this->getResponse()->setHttpHeader('X-JSON', sprintf('([["status", "%s"]])', $this->status));
    }
  }
  
  public function executePreview($request)
  {
    if ($request->hasParameter('prevPage'))
    {
      $this->status = 0;
      if ($this->getUser()->isAuthenticated())
      {
        $this->template = new w3sTemplateEnginePreview($this->getRequestParameter('lang'), $this->getRequestParameter('page'));
        if ($this->template->getIdLanguage() != -1 && $this->template->getIdPage() != -1)
        {
          $this->status = ($this->template->isPageFree($this->getRequestParameter('prevPage'))) ? 1 : 4;
        }
        else
        {
          $this->status = 8;
        }
      }
      else
      {
        $this->status = 2;
      }

      if($this->status != 1) $this->getResponse()->setStatusCode(404);
      $this->getResponse()->setHttpHeader('X-JSON', sprintf('([["status", "%s"], ["stylesheet", "%s"]])', $this->status, $this->template->retrieveTemplateStylesheets()));
    }
    else
    {
      $this->status = 16;
      $this->getResponse()->setStatusCode(404);
      $this->getResponse()->setHttpHeader('X-JSON', sprintf('([["status", "%s"]])', $this->status));
    }
  }

  /**
   * Shows the W3StudioCMS' loader
   *
   */
  public function executeShowLoader()
  {
  	return $this->renderPartial('showLoader');
  }

  /**
   * Executes loadMenuManager action
   *
   */
  public function executeLoadMenuManager($request)
  {   	
    $toolbarFile = ($request->hasParameter('toolbarFile')) ? $this->getRequestParameter('toolbarFile') : 'tbMenuManager';
    
    if ($toolbarFile == '') $toolbarFile = 'tbMenuManager';
    if (substr($toolbarFile, strlen($toolbarFile) - 4, 4) != '.yml') $toolbarFile .= '.yml';
    if ($toolbarFile != '' && !file_exists(w3sCommonFunctions::getConfigurationFilePath($toolbarFile) . $toolbarFile)) $toolbarFile = 'tbMenuManager.yml';
    $mode = ($request->hasParameter('mode')) ? $this->getRequestParameter('mode') : 'full';
    if ($mode == '') $mode = 'full';
    $menu = new w3sMenuManager('w3s_menu_manager',  $toolbarFile, $this->getUser());

    return $this->renderText($menu->renderMenuManager($mode));
  }

  /**
   * Executes openEditor action
   *
   */
  public function executeOpenEditor($request)
  { 
    // The parameters passed to the forwarded action are already setted in the javascript
    if ($request->hasParameter('idContent') && $this->getRequestParameter('idContent') > 0)
    { 
      $content = W3sContentPeer::retrieveByPk($this->getRequestParameter('idContent'));
      if ($content != null)
      {
	      $this->editor = w3sEditorFactory::create($content);
	      return $this->renderPartial('showEditor');
      }      
    }
    
    $this->getResponse()->setStatusCode(404);       
    return $this->renderText(w3sCommonFunctions::toI18n('The content you are trying to edit does not exists anymore.')); 
  }
  
  /**
   * Publish the website. 
   */
  public function executePublish()
  {
    $this->template = new w3sTemplateEnginePublisher();
  }
}