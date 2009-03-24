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

use_helper('I18N', 'Javascript');
  
// This parameter is setted in the app.yml in the plugin's config directory
switch (sfConfig::get('app_webeditor')){
  case 'tinyMCE':      
    include_partial('tinyMCE/tinyMceInit', array('idLanguage' => $template->getIdLanguage()));
    break;
  case 'fckEditor':
    // Insert here the code
    break;
}

// Set the javascripts variables needed for managing languages and pages.
$frontController = (sfConfig::get('sf_environment') != 'prod') ? sprintf('/%s_%s.php/', sfConfig::get('sf_app'),  sfConfig::get('sf_environment')) : '/';

echo $template->retrieveSiteStylesheets(); 
?>
<div id="w3s_cms"></div>
<div id="w3s_cms_temp"></div>
<div id="w3s_menu_manager_hidden"></div>


<?php 
	echo $managerMenu->renderMenuManager('full');
	echo $interactiveMenu->renderMenu();
  echo $commands->renderMenu();
  echo $actions->renderMenu();
 
  echo javascript_tag('function initW3studioCMS() { w3studioCMS = new W3studioCMS(\'' . $frontController . '\', ' . $template->getIdPage() . ', ' . $template->getIdLanguage() . '); }
											 Event.observe(window, \'load\', initW3studioCMS, false);');  