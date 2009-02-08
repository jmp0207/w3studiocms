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
 * w3sTextEditor extends the w3sEditor and defines a base class for text editors
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sTextEditor
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
abstract class w3sTextEditor extends w3sContentsEditor
{
	protected 
		$editorSkeleton = "
      <form onsubmit=\"currentEditor.edit(); return false;\">
				<textarea id=\"%2\$s\" name=\"%2\$s\">%3\$s</textarea>
			</form>",
	  $webEditorName = 'w3s_tmce';
	    
  abstract function writeStylesheet();
  
  /**
   * Sets the value of the webEditorName variable.
   * 
   * @param string
   *
   */  
  public function setWebEditorName($value)
  {
    $this->webEditorName = $value;
  }
  
  /**
   * Returns the value of the webEditorName variable.
   * 
   * @return string
   *
   */  
  public function getWebEditorName()
  {
    return $this->webEditorName;
  }
  
  /**
   * Draws the text editor
   * 
   * @return string
   *
   */   
	public function drawEditor()
	{			
		$this->writeStylesheet();
																					
		return sprintf($this->editorSkeleton, sfContext::getInstance()->getController()->genUrl('contentsManager/edit'),
																					$this->webEditorName,
																					$this->content->getContent());		    
	}
}
