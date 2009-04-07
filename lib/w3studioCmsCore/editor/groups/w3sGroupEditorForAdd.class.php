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
 * w3sGroupEditor implements the editor to manage the site's groups
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sGroupEditor
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sGroupEditorForAdd implements w3sEditor
{
  protected 
  	$editorSkeleton = 						
     '<div id="w3s_add_group_module">
			  <fieldset>
			    <table width="100%%" border="0">
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
   * Renders the editor
   * 
   * @return string
   *
   */ 
  public function render()
  {     
    $options = DbFinder::from('W3sTemplate')->find();
    return sprintf($this->editorSkeleton, label_for('group_name', w3sCommonFunctions::toI18n('Group name:')),
    																			input_tag('w3s_group_name', ''),
    																			label_for('template_name', w3sCommonFunctions::toI18n('Template name:')),
    																			select_tag('w3s_templates_select', objects_for_select($options, 'getId', 'getTemplateName')),
																					link_to_function(w3sCommonFunctions::toI18n('Add Group'), 'W3sGroup.add()', 'id="w3s_change_button" class="link_button"'));
  }
}