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
 * w3sPageEditor implements the editor to manage the site's pages
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sPageEditor
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sPageEditor implements w3sEditor
{
  protected 
  	$editorSkeleton = 						
     '<div id="w3s_pages_form">
				<fieldset>
		    <table width="100%%" cellpadding="4" cellspacing="2">
		      <tr>
		        <td>%s</td>
		        <td>%s</td>
		      </tr>
		      <tr>
		        <td>%s</td>
		        <td>%s</td>
		      </tr>
		      <tr>
		        <td></td>
		        <td height="30">%s</td>
		      </tr>
		    </table>
		  	</fieldset>
			</div>';

	/**
   * Renders the images editor
   * 
   * @return string
   *
   */ 
  public function render()
  {    
    return sprintf($this->editorSkeleton, label_for('page_name', __('Page name:')), 
    																			input_tag('w3s_page_name', ''),
																					label_for('group_name', __('Group name:')),
																					select_tag('w3s_groups_select', objects_for_select(W3sGroupPeer::getActiveGroups(), 'getId', 'getGroupName')), 
																					link_to_function(__('Add Page'), 'W3sPage.add()', 'class="link_button"'));
  }
}