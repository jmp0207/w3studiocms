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
 * w3sContentsEditor represents the base class to display an editor
 * for contents' management.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sContentsEditor
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
abstract class w3sContentsEditor implements w3sEditor
{
	protected 
		$content,
		$idEditor, 
		$editorSkeleton = null;
	
	// This function renders the editor itself
	abstract protected function drawEditor();
	
	/**
   * Constructor.
   * 
   * @param object  The w3sContent object to edit
   * @param string  The id of the editor
   * 
   */    
	public function __construct($content, $editorId = '')
	{ 
		$this->content = $content;
		$this->idEditor = $editorId;
		if ($this->editorSkeleton == null) throw new RuntimeException(sprintf('You have to initialize the protected variable skeletonEditor in class %s', get_class($this))); 
	}
	
	/**
   * Sets the value of the editorSkeleton variable.
   * 
   * @param string
   *
   */  
	public function setEditorSkeleton($value)
	{
    $this->editorSkeleton = $value;
  }
  
  /**
   * Returns the value of the editorSkeleton variable.
   * 
   * @return string
   *
   */  
	public function getEditorSkeleton()
	{
    return $this->editorSkeleton;
  }
	
	/**
   * Implements the editor.
   * 
   * @return string The formatted editor
   *
   */  
	public function render()
	{
		$editor = sprintf('<div id="%s">%s%s</div>', $this->idEditor, $this->drawEditor(), $this->renderCloseButton());			
  	
  	return $editor;
	}
	
	/**
   * Draws the button to close the editor.
   * 
   * @return string The formatted editor
   *
   */ 
	protected function renderCloseButton()
	{ 
  	$closeButton = new w3sButton();
  	$closeButton->fromYml('editorButtonClose.yml');  	
  	
  	return sprintf('<div class="CloseCurrentEditor">%s</div>', $closeButton->renderButton());
	}
	
	/**
   * Retrieves all the site's pages.
   * 
   * @return array An array where the key is the page's id and the value is the page's name 
   *
   */ 
	protected function getSitePages()
	{
		$oPages = DbFinder::from('W3sPage')->
						            where('ToDelete', 0)->  
						            orderBy('PageName')->
						            find();
    $pages = array('0' => 'Not linked');
    foreach($oPages as $page){
      $pages[$page->getId()] = w3sCommonFunctions::setStringMaxWidth($page->getPageName(), 20); 
    }
    
    return $pages;
  }
}
