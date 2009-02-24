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
 
/**
 * w3sPageEditor builds the editor to manage the site's pages
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sPageEditor
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sFileManager
{
  protected
  	$idLanguage,
  	$currentPage, 
  	$currentUser,
  	$skeleton =
  		'<div id="w3s_page_list_commands">%s</div>
			 <div id="w3s_page_list">%s</div>',
  	$rowSkeleton = 						
     '<div id="%s" class="%s">
				<div style="float:left;">
	        <div id="w3s_page_name_%s">%s</div>
	        <div id="w3s_page_name_editor_%s" style="display:none;margin-top:-1px;">%s</div>
	      </div>
	      <div style="float:right;margin-top:-1px;">
	        <div style="width:90px;float:left;text-align:left;">%s</div>
	        <div style="float:left;width:16px;">%s</div>
	        <div style="float:left;margin-right:1px;">%s</div>
	        <div style="float:left;">%s</div>
	        <div style="clear:left;"></div>
	      </div>
	      <div style="clear:right;"></div>
      </div>',
    $pageEditorSkeleton =
    	'<div style="float:left;">%s</div>
       <div style="float:left;margin:0 3px;">%s</div>
       <div style="float:left;">%s</div>
       <div style="clear:left;"></div>',
    $pagesManager =
    	'<ul>
	      <li class="w3s_right_separator">%s</li>
	      <li class="w3s_left_separator">%s</li>
	      <li id="w3s_control_panel_groups">%s</li>
	      <li>%s</li>
	      <li>%s</li>
	      <li>%s</li>
	    </ul>';
      
  public function __construct($currentUser = null, $idLanguage = 0, $currentPage = 0)
  {
  	$this->currentUser = $currentUser;
    $this->idLanguage = $idLanguage;
  	$this->currentPage = $currentPage;  	
  }
  
  public function render()
  {
	  return sprintf($this->skeleton, $this->drawPagesManager(), $this->renderPages());
  }
  
  public function renderPages()
  {
  	$i = 0;
	  $result = '';
	  $pagesList = DbFinder::from('W3sPage')->
												   with('W3sGroup')->
												   orderBy('PageName', 'ASC')->
												   where('ToDelete', '0')->
												   find();
	  foreach($pagesList as $page)
	  {
	  	$idPage = $page->getId();
	    $class = (($i/2)==intval($i/2)) ? "w3s_white_row" : "w3s_yellow_row";
	    if($this->idLanguage.$idPage == $this->currentPage) $class .= '_active';
	    
	    $c = new Criteria;
	    $c->add(W3sContentPeer::EDITED, 1);
	    $edited = $page->countW3sContents($c);
	    if ($this->idLanguage.$idPage != $this->currentPage)
	    {
	    	$changePageFunction = 'InteractiveMenu.hide();W3sTemplate.loadEditorPage(' . $this->idLanguage . ', ' . $idPage . ');';
	    	$renamePageFunction = '$(\'w3s_page_name_' . $idPage . '\').style.display=\'none\';$(\'w3s_page_name_editor_' . $idPage . '\').style.display=\'block\';'; 
	    }
	    else
	    {
	    	$changePageFunction = '';
	    	$renamePageFunction = '';
	    }
	    $result .= sprintf($this->rowSkeleton,
                        $this->idLanguage . $idPage,
                        $class,
                        $idPage,
                        link_to_function(w3sCommonFunctions::setStringMaxWidth($page->getPageName(), 20), $changePageFunction),
                        $idPage,
                        $this->drawPageEditor($page),
                        link_to_function(w3sCommonFunctions::setStringMaxWidth($page->getW3sGroup()->getGroupName(), 14), $changePageFunction, 'style="color:#FF9900;"'),
                        ($edited > 0) ? image_tag(sfConfig::get('app_w3s_web_skin_images_dir') . '/control_panel/button_edited.gif') : '&nbsp;',
                        link_to_function(image_tag(sfConfig::get('app_w3s_web_skin_images_dir') . '/control_panel/button_edit.gif', 'alt=' . __('Rename current page') . ' size=14x14'), $renamePageFunction),
                        ($this->idLanguage.$idPage != $this->currentPage) ? link_to_function(image_tag(sfConfig::get('app_w3s_web_skin_images_dir') . '/control_panel/button_delete.gif', 'alt=' . __('Delete current page') . ' size=14x14'), 'W3sPage.remove(' . $idPage . ', \'' . __('If you delete this page, W3Studio will also delete all contents and metatags related with it: do you want to continue with deleting?') . '\')') : image_tag(sfConfig::get('app_w3s_web_skin_images_dir') . '/control_panel/button_delete_disabled.gif'));
	     $i++;				
	  }
	  
	  return $result;
  }
  
  public function renderGroupsSelect()
  {
  	return select_tag('w3s_groups_select1', objects_for_select(W3sGroupPeer::getActiveGroups(), 'getId', 'getGroupName'));
  }
  
  protected function drawPageEditor($page)
  {
	  $idPage = $page->getId();
	  
	  return sprintf($this->pageEditorSkeleton, input_tag('w3s_edit_page_' . $idPage, $page->getPageName()),
	    																			  link_to_function(image_tag(sfConfig::get('app_w3s_web_skin_images_dir') . '/control_panel/button_save.gif', 'alt=' . __('Save new page name') . ' size=14x14'), 'W3sPage.renamePage(' . $idPage . ', $(\'w3s_edit_page_' . $idPage . '\').value);'),
	    																			  link_to_function(image_tag(sfConfig::get('app_w3s_web_skin_images_dir') . '/control_panel/button_close.gif', 'alt=' . __('Close edit panel') . ' size=14x14'), '$(\'w3s_page_name_' . $idPage . '\').style.display=\'block\';$(\'w3s_page_name_editor_' . $idPage . '\').style.display=\'none\';'));	    																			  
  }
  
  protected function drawPagesManager()
  {
  	$commands = w3sCommonFunctions::renderCommandsFromYml('cpPagesManager.yml', $this->currentUser);
  	return sprintf($this->pagesManager, $commands[0],
	    																	__('Group'),
	    																	$this->renderGroupsSelect(),
	    																	$commands[1],
	    																	$commands[2],
	    																	$commands[3]);
  }
}