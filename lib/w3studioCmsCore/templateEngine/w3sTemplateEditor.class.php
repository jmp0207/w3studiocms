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
 * Template class represents the page's template.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sTemplateEngineEditor
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sTemplateEngineEditor extends w3sTemplateEngine
{  
	protected 
		$interactiveMenuEvents = '',
		$sortables = '';							
	
	public function getInteractiveMenuEvents()
  {
    return $this->interactiveMenuEvents;  
  }
  
  public function getSortables()
  {
    return $this->sortables;  
  }
  
  /**
   * Overrides the standard function
   * 
   * @return string  The rendered page 
   *
   */ 
  public function renderPage()
  {
    $slotNames = '';
    if ($this->idLanguage != -1 && $this->idPage != -1)
    {
      $slotContents = $this->getSlotContents($this->idLanguage, $this->idPage);
      foreach ($slotContents as $slot)
      {
        $slotNames .= sprintf('"%s",', $slot['slotName']);
        $contents = $this->drawSlot($slot);
        $this->pageContents = preg_replace('/\<\?php.*?include_slot\(\'' . $slot['slotName'] . '\'\).*?\?\>/', $contents, $this->pageContents);
      }

      $this->setSortables($slotContents);
    }
    else
    {
      $this->pageContents = w3sCommonFunctions::displayMessage('The page or the language requested does not exist anymore in the website');
    }
    
    // Renders the W3StudioCMS Copyright button. Please do not remove, neither when
    // you override this function. See the function to learn the best way to implement
    // it in your web site. Thank you.
    $this->pageContents = $this->renderCopyright($this->pageContents);
    
    return $this->pageContents;
  }
  
  public function setSortables()
  {
    $this->sortables = '';
  	$listOptions = '';

    $slotContents = $this->getSlotContents($this->idLanguage, $this->idPage);
  	foreach ($slotContents as $slot)
    {
      $listOptions .= sprintf('"%s",', $slot['slotName']);
    }
    $listOptions = sprintf('dropOnEmpty:true,containment:[%s],constraint:false,', substr($listOptions, 0, strlen($listOptions)-1));
    
    foreach ($slotContents as $slot)
    { 
    	$function = sprintf("onUpdate:function(){W3sContent.moveContents('%1\$s', '%2\$s', Sortable.serialize('%2\$s'))}", $slot['idSlot'], $slot['slotName']);
			$this->sortables .= sprintf('Sortable.create(\'%s\', {%s %s, tag:\'div\'});', $slot['slotName'], $listOptions, $function);
    }
  }
 
  /** 
   * Draws the contents' slot when in editor contents. 
   * 
   * @param object   A slot object
   * 
   * @return string  The contents that belong to slot formatted as string
   * 
   */
  public function drawSlot($slot)
  {

    $result = '';
   
    $validParam = true;
    $defaultParams = array('0' => 'contents', '1' => 'idSlot', '2' => 'slotName', '3' => 'isRepeated', '4' => 'setEventForRedraw');
    if (is_array($slot))
    {
      if (array_diff(array_keys($slot), $defaultParams))
	    {
	      $validParam = false;
	    }
      else
      {
        if (($slot['contents'][0] != null) && (!$slot['contents'][0] instanceof W3sContent))
        {
          throw new RuntimeException('Contents param have to be an instance of W3sContent');
        }
      }
    }
    else
    {
      $validParam = false;
    }

    if (!$validParam) throw new RuntimeException(sprintf('DrawSlot requires an array with the following options: %s', array_values($defaultParams)));
 
    foreach ($slot['contents'] as $content)
    {
    	if ($slot['contents'][0] != null)
      {	      
	      // Draws the slot with contents
	      $curContent = w3sContentManagerFactory::create($content->getContentTypeId(), $content);
	      if (isset($slot['setEventForRedraw']))
	      { 
	      	$this->interactiveMenuEvents .= sprintf("Event.observe('%s', 'mouseover', InteractiveMenu.show.bind(InteractiveMenu, %s, '%s', %s, %s, '%s', '%s'));\n", 'w3sContentItem_' . $content->getId(), $content->getId(), $content->getContentTypeId(), $content->getSlotId(), $content->getGroupId(), $slot['slotName'], $slot["isRepeated"]);
	      }
	      else
	      {
	      	$this->interactiveMenuEvents .= sprintf('%s,%s,%s,%s,%s,%s,%s|', 'w3sContentItem_' . $content->getId(), $content->getId(), $content->getContentTypeId(), $content->getSlotId(), $content->getGroupId(), $slot['slotName'], $slot["isRepeated"]);
	      }
	      
	      $result .= sprintf('<div id="%s">%s</div>', 'w3sContentItem_' . $content->getId(), $curContent->getDisplayContentForEditorMode());	      
	    }
	    else{	 
        
        // Draws the slot without contents
        if (isset($slot['setEventForRedraw']))
	      { 	      																																								  
	      	$this->interactiveMenuEvents .= sprintf("Event.observe('%s', 'mouseover', InteractiveMenu.hide.bind(InteractiveMenu, 0, '0', %s, 0, '%s', '0'));\n", 'w3sContent_0' . $slot['idSlot'], $slot['idSlot'], $slot['slotName']);
	      }
	      else
	      {
	      	$this->interactiveMenuEvents .= sprintf('%s,0,0,%s,0,%s,0|', 'w3sContent_0' . $slot['idSlot'], $slot['idSlot'], $slot['slotName']);
	      }
        $result .= sprintf('<div style="padding:1px;" id="w3sContent_0' . $slot['idSlot'] . '"><img src="%s" style="border:0px;" onclick="InteractiveMenu.openActionsMenuForAddContent(this)" /></div>', sfConfig::get('app_w3s_web_skin_images_dir') . '/structural/button_add_content.gif');       
	    }        
    }  

    return $result;
  }
}