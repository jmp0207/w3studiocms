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
 * Renders the frontend's page.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sTemplateEngineFrontend
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sTemplateEngineFrontend extends w3sTemplateEngine
{  
  protected
    $languageName,
    $pageName,
    $stylesheets,
    $javascripts;

  public function __construct($idLanguage, $idPage)
  {  	
    $this->stylesheets = w3sCommonFunctions::loadScript('TemplateFrontendDefaultCSS.yml');
    $this->javascripts = w3sCommonFunctions::loadScript('TemplateFrontendDefaultJS.yml');
    $this->idLanguage = $idLanguage;
    $this->idPage = $idPage; 
    
    parent::__construct($idLanguage, $idPage);
  }
  
  public function getPageName()
  {
    return $this->pageName;
  }

  public function setPageName($value)
  {
    $this->pageName = $value;
  }
  
  public function getLanguageName()
  {
    return $this->languageName;
  }

  public function setLanguageName($value)
  {
    $this->languageName = $value;
  }
  
  public function getJavascripts()
  {
    return $this->javascripts;
  }
  
  public function setJavascripts($array)
  {
    $this->javascripts = $array;
  }

  public function getStylesheets()
  {
    return $this->stylesheets;
  }

  public function setStylesheets($array)
  {
    $this->stylesheets = $array;
  }

  public function renderPage()
  {
    $filename = sfConfig::get('app_w3s_web_published_dir') . '/' . $this->languageName . '/' . $this->pageName . '.php';

    // If the file exists draws it, otherwise shows an empty page with the link to open the editor
    if (is_file($filename))
    { 
      ob_start();
      include($filename);
      $contents = ob_get_clean();      
    }
    else{
      $contents = sprintf('<a href="#" onclick="%s">%s</a>', 'showLoginForm(\'' . sfContext::getInstance()->getController()->genUrl('webEditor/index') . '\',  \'' . $this->idLanguage . '\', \'' . $this->idPage . '\')', 'Edit Site' );
    }
    
    $stylesheets = $this->getStylesheetsFromContents($contents);
    $contents = $this->removeStylesheetsFromTemplate($stylesheets, $contents);
    
    return $contents;
  }
  
  /**
   * Removes the stylesheets reference from the template
   * 
   * @param array    The template's stylesheets
   * @param string   The contents to process
   * 
   * @return array   The processed contents
   *
   */ 
  public function retrieveTemplateStylesheets($contents = null)
  {    
    $result = array();
    $stylesheets = $this->getStylesheetsFromContents($contents);
    //$result['contents'] = $this->removeStylesheetsFromTemplate($stylesheets, $contents);
    
    // Returns the stylesheets
    $stylesheetResults = array();
		foreach ($stylesheets[0] as $stylesheet)
    {
			$stylesheetsAttributes = w3sCommonFunctions::getHtmlAttributes($stylesheet, 'link');
      $stylesheetResults[] = array('href' => $stylesheetsAttributes['href'], 'media' => $stylesheetsAttributes['media']);
    }
    //$result['stylesheets'] = $stylesheetResults;
    $result = $stylesheetResults;
    
    return $result; 
  }
  
  /** 
   * When in frontend contents there's nothing to draw because the page has been rendered
   * when it has been published 
   */ 
  public function drawSlot($contents) {}
}