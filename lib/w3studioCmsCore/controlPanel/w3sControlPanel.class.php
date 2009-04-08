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
 * w3sControlPanel builds the editor to manage pages,groups, languages, slots and metatags
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sControlPanel
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sControlPanel implements w3sEditor
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

  /**
   * Constructor.
   *
   * @param object  An object that represents the current user
   * @param int     The id of the current language
   * @param int     The id of the current page
   *
   */
  public function __construct($currentUser = null, $idLanguage = 0, $currentPage = 0)
  {
  	$this->idLanguage = $idLanguage;
  	$this->currentPage = $currentPage;
  	$this->currentUser = $currentUser;
  }

  /**
   * Implements the interface w3sEditor.
   *
   * @return string The rendered panel
   *
   */
	public function render()
	{
		return sprintf($this->panelSkeleton, $this->drawTitle(),
																				 $this->drawLanguagesPanel(),
																				 $this->drawTabsPanel(),
																				 $this->drawContentsTabs());
	} 

  /**
   * Draws a select which contains all the site's languages
   *
   * @return string The drawed select
   *
   */
	public function drawLanguagesSelect()
	{
		$languageOptions = DbFinder::from('W3sLanguage')->
									     where('ToDelete', '0')->
									     find();									      
		return select_tag('w3s_languages_select', objects_for_select($languageOptions, 'getId', 'getLanguage'));
	}

  /**
   * Draws the panel title.
   *
   * @return string The drawed title
   *
   */
	protected function drawTitle()
	{
		return sprintf($this->titleSkeleton, w3sCommonFunctions::toI18N('Control panel'), link_to_function(image_tag(sfConfig::get('app_w3s_web_skin_images_dir') . '/control_panel/button.jpg'), 'W3sControlPanel.hide()', 'alt="" title="Close the Control Panel" width="16" height="12"'));
	} 

  /**
   * Draws the control panel to manage languages
   *
   * @return string The drawed panel
   *
   */
	protected function drawLanguagesPanel()
	{			     
		$commands = w3sCommonFunctions::renderCommandsFromYml('cpLanguagesManager.yml', $this->currentUser);
		return sprintf($this->languagesPanelSkeleton, w3sCommonFunctions::toI18N('Language'),
																									$this->drawLanguagesSelect(),
																									$commands[0],
																									$commands[1],
																									$commands[2],
																									$commands[3]);		
	} 

  /**
   * Draws the tabbed panel to change panel
   *
   * @return string The drawed panel
   *
   */
	protected function drawTabsPanel()
	{
		$tabs = w3sCommonFunctions::loadScript('cpTabs.yml');
		$result = '';
		foreach ($tabs as $key => $tab)
		{
			$result .= sprintf('<li>%s</li>', link_to_function(w3sCommonFunctions::toI18N($tab["text"]), 'W3sControlPanelTabs.createTab(' . $key . ', this.id)', 'id=' . $tab["id"]));
		}
		return sprintf('<ul>%s</ul>', $result);
	} 

  /**
   * Draws the panel that contains the current section used. Renders the deafult
   * section, that is the fileManager
   *
   * @return string The drawed panel
   *
   */
	protected function drawContentsTabs()
	{
		$tabs = w3sCommonFunctions::loadScript('cpTabs.yml');
		$result = '';
		foreach ($tabs as $key => $tab)
		{
			$content = '';
			if ($key == 1)
			{
				$fileManager = new w3sFileManager($this->currentUser, $this->idLanguage, $this->currentPage);
				$content = $fileManager->render();
			}
			$result .= sprintf('<div id="w3s_tab_%s" class="w3s_tabs">%s</div>', $key, $content);
		}
		
		return $result;
	} 
	
}