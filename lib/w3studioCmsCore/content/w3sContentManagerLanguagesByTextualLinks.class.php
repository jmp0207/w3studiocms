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
 * w3sContentManagerLanguages extends the w3sContentManager to represent the
 * tool to change languages on multi-languages sites.  * 
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sContentManagerLanguages
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sContentManagerLanguagesByTextualLinks extends w3sContentManagerLanguages
{ 
  /**
   * Draws the languages' table as a horizontal toolbar. This function assumes that
   * every image that represents a language has the same name of the language. Override
   * this function if you want to change this behaviour
   *   
   * @return string
   *
   */ 
  public function drawLanguages($mode)
  {
  	
  	$pageName = $this->content->getW3sPage()->getPageName();
  	
  	// Cycles all the site's languages
  	$languages = DbFinder::from('W3sLanguage')->find();
	  $buttons = '';
    foreach($languages as $language){
	    // Set the buttons
	    if ($mode == 'preview')
	    {
	    	$buttons .= sprintf('<td><a href="#" onclick="W3sTemplate.loadPreviewPage(\'%s\', %s, %s)">%s</a></td>', sfContext::getInstance()->getController()->genUrl('webEditor/preview'), $language->getId(), $this->content->getPageId(), $language->getLanguage());
	    }
	    else
	    {
	    	$buttons .= sprintf('<td><a href="/%s/%s.html">%s</a></td>', $language->getLanguage(), $pageName, $language->getLanguage());
	    }
	  }
	 	
	 	// Draws the languages
	  return sprintf('<table><tr>%s</tr></table>', $buttons);
  }
}