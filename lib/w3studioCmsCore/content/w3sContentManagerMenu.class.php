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
 * w3sContentManagerText extends the w3sContentManager to represent a menu
 * content. This object has a minimal interface and will be implemented
 * in the future.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sContentManagerMenu
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sContentManagerMenu extends w3sContentManager{
 
  /**
   * Returns the default text for the flash content
   *   
   * @return string
   *
   */ 
  public function getDefaultText()
  {
  	$links = '';
  	for ($i=0; $i<3; $i++){
  		$text = w3sCommonFunctions::toI18n('This is a link');
  		$links .= sprintf('<li><a href="#">%s</a></li>', $text);
  	}
  	
  	return sprintf('<ul>%s</ul>', $links);           
  }
  
  public function getRelatedElements()
  {
  	return W3sMenuElementPeer::getContentMenu($this->content->getId());           
  }
  
  /**
   * Inserts the menu links related to the inserted content,
   * into the w3sMenuElements table
   *   
   * @return bool
   * 
   */ 
  protected function setDefaultRelatedElements()
  {
    $bRollBack = false;
  	$con = Propel::getConnection();  
	  $con = w3sPropelWorkaround::beginTransaction($con); 
	  
    for ($i=1; $i<4; $i++){
      $newMenu= new W3sMenuElement();
      $contentValues = array("ContentId"      => $this->content->getId(),
                             "PageId"         => 0,
                             "Link"           => w3sCommonFunctions::toI18n('This is a link'),
                             "ExternalLink"   => '',
                             "Image"          => '',
                             "RolloverImage"  => '',
                             "Position"       => $i);
      $newMenu->fromArray($contentValues);  
      $result = $newMenu->save(); 
      if ($newMenu->isModified() && $result == 0){
        $bRollBack = true;
        break;
      }  
    }
    
    if (!$bRollBack)
	  {   // Everything was fine so W3StudioCMS commits to database
      $con->commit();
      $result = true;
    }
    else
    {   // Something was wrong so W3StudioCMS aborts the operation and restores to previous status
      w3sPropelWorkaround::rollBack($con);
      $result = false;
    }
  }
    
  /**
   * Renders the menu
   *  
   * @parameter  array - An array with two optional parameters: 
   * 											w3s_assigned_to     The tag which the class is assigned
   * 											w3s_assigned_class  The name of the class
   * 
   * @return     string - The rendered menu 
   * 
   */
  protected function renderMenu($params = array()){
    $result = '';
    
    // Retrieves the menu, the language name and the page name from the current content 
    $menu = W3sMenuElementPeer::getContentMenu($this->content->getId());
    $language = $this->content->getW3sLanguage()->getLanguage();
    $classForPage = $this->content->getW3sPage()->getPageName();
    $assignedTo = (array_key_exists('w3s_assigned_to', $params)) ? $params['w3s_assigned_to'] : 'li';   
    $className = (array_key_exists('w3s_assigned_class', $params)) ? $params['w3s_assigned_class'] : 'w3sNone';
    
    // Cycles the menu elements
    foreach($menu as $menuRow){
      $idPage = $menuRow->getPageId();
      
      // Checks if link is internal or external 
      if ($idPage != 0){
        
        // Checks if page exists
        $oPage = DbFinder::from('W3sPage')->findPK($idPage);
        if ($oPage != null){ 
          $pageName = $oPage->getPageName();
          $class = ($className != 'w3sNone' && $classForPage == $pageName) ? sprintf(' class="%s"', $className) : '';
          $link = sprintf('/webSite/%s/%s.html', $language, $pageName);
          $onclickEvent = '';
        }
        else{
          $class = '';
          $link = '#';
          $onclickEvent = ' onclick="alert(\'This page has been removed from the website\')"';
        }
      }
      else{
      	$class = '';
      	$onclickEvent = '';
        $link = $menuRow->getExternalLink();        
      }

			// Link name or image
      if ($menuRow->getImage() != ''){
        $rollover = ($menuRow->getRolloverImage() != '') ? sprintf(' onmouseover="this.src=\'/images/%s\'" onmouseout="this.src=\'/images/%s\'"', $menuRow->getRolloverImage(), $menuRow->getImage()) : '';
        $linkText =  sprintf('<img src="/images/%s"%s/>', $menuRow->getImage(), $rollover);
      }
      else{
        $linkText = $menuRow->getLink();
      }
      $result .= sprintf('<li%s><a href="%s"%s%s>%s</a></li>', ($assignedTo == 'li') ? $class : '', $link, $onclickEvent, ($assignedTo == 'a') ? $class : '', $linkText);
      
    }
    $result = sprintf('<ul>%s</ul>', $result);

    return $result;
  }
  
  /*
  protected function renderMenu($params = array()){
    $result = '';
    
    // Retrieves the menu, the language name and the page name from the current content 
    $menu = W3sMenuElementPeer::getContentMenu($this->content->getId());
    $language = $this->content->getW3sLanguage()->getLanguage();
    $classForPage = $this->content->getW3sPage()->getPageName();
    $assignedTo = (array_key_exists('w3s_assigned_to', $params)) ? $params['w3s_assigned_to'] : 'li';   
    $className = (array_key_exists('w3s_assigned_class', $params)) ? $params['w3s_assigned_class'] : 'w3sNone';
    
    // Cycles the menu elements
    foreach($menu as $menuRow){
      $idPage = $menuRow->getPageId();
      
      // Checks if link is internal or external 
      if ($idPage != 0){
        
        // Checks if page exists
        $oPage = DbFinder::from('W3sPage')->findPK($idPage);
        if ($oPage != null){ 
          $pageName = $oPage->getPageName();
          $class = ($className != 'w3sNone' && $classForPage == $pageName) ? sprintf('class="%s"', $className) : '';
          $link = sprintf('/webSite/%s/%s.html', $language, $pageName);
          $onclickEvent = '';
        }
        else{
          $class = '';
          $link = '#';
          $onclickEvent = 'onclick="alert(\'This page has been removed from the website\')"';
        }
      }
      else{
      	$class = '';
      	$onclickEvent = '';
        $link = $menuRow->getExternalLink();        
      }

			// Link name or image
      if ($menuRow->getImage() != ''){
        $rollover = ($menuRow->getRolloverImage() != '') ? sprintf('onmouseover="this.src=\'/images/%s\'" onmouseout="this.src=\'/images/%s\'"', $menuRow->getRolloverImage(), $menuRow->getImage()) : '';
        $linkText =  sprintf('<img src="/images/%s" %s />', $menuRow->getImage(), $rollover);
      }
      else{
        $linkText = $menuRow->getLink();
      }
      $result .= sprintf('<li %s><a href="%s" %s %s>%s</a></li>', ($assignedTo == 'li') ? $class : '', $link, $onclickEvent, ($assignedTo == 'a') ? $class : '', $linkText);
      
    }
    $result = sprintf('<ul>%s</ul>', $result);

    return $result;
  }
  */
  
  /**
   * Format content to display the image on the web page, using the image's properties
   * edited by user at runtime. Overrides the same function of w3sContentManager  
   *
   * @param      array The array with contents.
   * 
   * @return     array The array with contents formatted.
   */
  protected function formatContent($contentValues)
  {
  	// The contents passed as reference are composed as follows:
  	// [links values]|[options values]  
  	$params = explode('|', $contentValues["Content"]); 
  	
  	// Saves the links
  	$menuEditor = new w3sMenuEditor($this->content, 5);
    $menuEditor->saveLinks($this->saveParams($params[0]));
    
    // Cycles all aoptions passed from the serialized form and creates an
  	// array with the following path: array[option_name] = array[option_value]
  	$formattedParams = array();  	
  	$params = explode('&', urldecode($params[1]));
  	foreach($params as $param)
  	{
  		$paramValues = explode('=', $param);
  		$formattedParams[$paramValues[0]] = $paramValues[1];
  	}
    
    // Renders the menu
  	$contentValues["Content"] = $this->renderMenu($formattedParams);
  	
  	return $contentValues;
  }
  
  /**
   * Generates an array similar to the one generated by the 
   * getParameterHolder()->getAll() function. It needs a serialized string
   * similar to [Parameter Name][]=[Property name]=[Property value]&;
   *
   * @param      string serialized string which must match the following rule: 
   * 						 [Parameter Name A][]=[Property name]=[Property value]&[Parameter Name A][]=[Property name]=[Property value]&[Parameter Name B][]=[Property name]=[Property value]&...
   * 
   * @return     array The array with contents formatted.
   */
  protected function saveParams($params)
  {
  	
  	$params = w3sCommonFunctions::checkLastDirSeparator($params, '&');
  	preg_match_all('/([0-9]+)\[\]=(.*?)&/', $params, $res);
		$current = array();
	  $result = array();
	  $currentKey = 0;
	  foreach ($res[1] as $key => $value)
	  {
	    if ($value != $currentKey)
	    {
	      if ($currentKey != 0) $result[$currentKey] = $current;
	      $current = array();
	      $currentKey = $value;
	    }
	
	    $current[] = $res[2][$key];
	  }
	  $result[$currentKey] = $current;
	  
	  return $result;
  }
}