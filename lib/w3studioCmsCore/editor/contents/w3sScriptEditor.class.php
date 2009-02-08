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
 * w3sScriptEditor extends the w3sContentsEditor to build the editor to manage 
 * a php script.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sScriptEditor
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sScriptEditor extends w3sContentsEditor
{
	protected 
		$editorSkeleton =     // Declares the editor's skeleton	
     '<table>
		    %s
		    <tr>
		      <th></th>
		      <td align="center">  
		        <input id="w3s_edit_content_btn" type="submit" value="%s" />
		      </td>  
		    </tr>
		  </table>';
  
  /**
   * Draws the scripts editor
   * 
   * @return string
   *
   */   	
	public function drawEditor()
	{			
		$form = new w3sScriptForm($this->content->getContent());
		
    return sprintf($this->editorSkeleton, $form['script']->renderRow(array('cols' => 71, 'rows' => 20)),
																					sfContext::getInstance()->getI18N()->__('Insert script'));		     
																					
	}
}
