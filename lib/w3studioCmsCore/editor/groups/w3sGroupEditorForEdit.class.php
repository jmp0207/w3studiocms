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
 
class w3sGroupEditorForEdit implements w3sEditor
{
  protected 
  	$idGroup, 
    $groupName,
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
            <tr>
			        <td><input id="w3s_id_group" type="hidden" value="%s" /></td>
			        <td height="30" id="w3s_template_mapping">%s</td>
			      </tr>
			    </table>
			  </fieldset>
			</div>';

  public function __construct($idGroup, $groupName)
  {
    $this->idGroup = $idGroup;
    $this->groupName = $groupName;
  }

	/**
   * Renders the editor
   * 
   * @return string
   *
   */ 
  public function render()
  {

    $options = DbFinder::from('W3sTemplate')->find();
    $group = DbFinder::from('W3sGroup')->findPK($this->idGroup);
    return sprintf($this->editorSkeleton, label_for('group_name', w3sCommonFunctions::toI18n('Group name:')),
    																			input_tag('w3s_group_name', $this->groupName),
    																			label_for('template_name', w3sCommonFunctions::toI18n('Template name:')),
    																			select_tag('w3s_templates_select', objects_for_select($options, 'getId', 'getTemplateName', $group->getTemplateId()), 'onchange=W3sControlPanel.checkMapping();'),
																					link_to_function(w3sCommonFunctions::toI18n('Edit Group'), sprintf('W3sGroup.edit(' . $this->idGroup . ', ' . $group->getTemplateId() . ')'), 'id="w3s_change_button" class="link_button"'),
                                          $this->idGroup,
                                          '');
  }

  /**
   * Checks if a slot map exists between the template related to current group and the
   * template related to the group user wants to change
   *
   * @param  The id of the destionation template
   *
   * @return string
   *
   */
  public function checkMapping($destTemplate)
  {
    // Retrieves the template related to the current group
    $group = DbFinder::from('W3sGroup')->
                       with('W3sTemplate')->
                       findPK($this->idGroup);
    $sourceTemplate = $group->getW3sTemplate()->getId();

    // Checks if user chooses a group which have a different template from the one
    // that belongs current group
    if ($sourceTemplate != $destTemplate)
    {

      // Checks for slot mapping
      $this->currentTemplate = sprintf('[%s][%s]', $sourceTemplate, $destTemplate);
      $sourceSlotsCount = DbFinder::from('W3sSlotMapper')->
                                     where('Templates', $this->currentTemplate)->
                                     count();

      // When no slots mapping has been found, tries to invert source and destination template
      if($sourceSlotsCount == 0)
      {
        $this->currentTemplate = sprintf('[%s][%s]', $destTemplate, $sourceTemplate);
        $sourceSlotsCount = DbFinder::from('W3sSlotMapper')->
                                      where('Templates', $this->currentTemplate)->
                                      count();
      }

      // A mapping exists
      if($sourceSlotsCount > 0)
      {

        // Checks if all the slots has been mapped. Obviously there's no need to
        // map all the slots
        $templateSlotsCount = DbFinder::from('W3sSlot')->
                                       where('TemplateId', $this->currentTemplate)->
                                       count();

        // Not all the slots has been mapped. A warning is displayed
        if ($sourceSlotsCount != $templateSlotsCount)
        {
          $result = w3sCommonFunctions::toI18n('Warning: not all the source template\'s slots are not mapped on the destination template.');
        }
        else
        {
          $result = w3sCommonFunctions::toI18n('All the slots of both templates has been matched.');
        }
      }
      else
      {
        $result = w3sCommonFunctions::toI18n('There\'s not exist any map between templates\'s slots.');        
      }

      // User can always change the maps he made
      $result .= sprintf(' <a href="#" onclick="W3sControlPanel.doSlotMapping(%s, %s, \'%s\'); return false;" class="w3s_slot_link">%s</a>', $sourceTemplate, $destTemplate, sfConfig::get("app_w3s_web_skin_images_dir"), w3sCommonFunctions::toI18n('Map Slots'));
    }
    else
    {
      // When user choose a group which have the same template, no slot map is required
      $result = w3sCommonFunctions::toI18n('The templates are the same. There is not needed any map.');
    }

    return $result;
  }
}