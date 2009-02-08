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
 
class w3sControlPanel
{
  protected 
  	$currentUser,
  	$panelSkeleton = 						
     '<div>
			  <div id="w3s_control_panel_title">%s</div>
			  <div id="w3s_error"></div>
			  <div id="w3s_control_panel_languages_panel">%s</div>
				<div id="w3s_control_panel_tabs_panel">%s</div>
				<div id="w3s_control_panel_content_panel">%s</div>
			</div>',
	  $titleSkeleton = '
			<div style="float:left;">%s</div>
	    <div style="float:right;">%s</div>
	    <div style="clear:right;"></div>',
	  $languagesPanelSkeleton = '
		  <ul>			  
			  <li>%s</li>
			  <li id="w3s_languages">%s</li>
			  <li>%s</li>
			  <li>%s</li>
			  <li>%s</li>
				<li>%s</li>
			</ul>';
	
	public function __construct($idLanguage = 0, $currentPage = 0, $currentUser)
  {
  	$this->idLanguage = $idLanguage;
  	$this->currentPage = $currentPage;
  	$this->currentUser = $currentUser;
  }
	
	public function render()
	{
		return sprintf($this->panelSkeleton, $this->drawTitle(),
																				 $this->drawLanguagesPanel(),
																				 $this->drawTabsPanel(),
																				 $this->drawContentsTabs());
	} 
	
	public function drawLanguagesSelect()
	{
		$languageOptions = DbFinder::from('W3sLanguage')->
									     where('ToDelete', '0')->
									     find();									      
		return select_tag('w3s_languages_select', objects_for_select($languageOptions, 'getId', 'getLanguage'));
	}
		    
	protected function drawTitle()
	{
		return sprintf($this->titleSkeleton, __('Control panel'), link_to_function(image_tag(sfConfig::get('app_w3s_web_skin_images_dir') . '/structure/button.jpg'), 'W3sControlPanel.hide()', 'alt="" title="Close the Control Panel" width="16" height="12"'));
	} 
	
	protected function drawLanguagesPanel()
	{			     
		$commands = w3sCommonFunctions::renderCommandsFromYml('cpLanguagesManager.yml', $this->currentUser);
		return sprintf($this->languagesPanelSkeleton, __('Language'), 
																									$this->drawLanguagesSelect(),
																									$commands[0],
																									$commands[1],
																									$commands[2],
																									$commands[3]);		
	} 
	
	protected function drawTabsPanel()
	{
		$tabs = w3sCommonFunctions::loadScript('w3sTabs.yml');
		$result = '';
		foreach ($tabs as $key => $tab)
		{
			$result .= sprintf('<li>%s</li>', link_to_function(__($tab["text"]), 'W3sControlPanelTabs.createTab(' . $key . ', this.id)', 'id=' . $tab["id"]));
		}
		return sprintf('<ul>%s</ul>', $result);
	} 
	
	protected function drawContentsTabs()
	{
		$tabs = w3sCommonFunctions::loadScript('w3sTabs.yml');
		$result = '';
		foreach ($tabs as $key => $tab)
		{
			$content = '';
			if ($key == 1)
			{
				$fileManager = new w3sFileManager($this->idLanguage, $this->currentPage, $this->currentUser);
				$content = $fileManager->render();
			}
			$result .= sprintf('<div id="w3s_tab_%s" class="w3s_tabs">%s</div>', $key, $content);
		}
		
		return $result;
	} 
	
}