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
 * w3sSlotMapperPanel builds the editor to manage the site's pages
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sPageEditor
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sSlotMapperPanel
{
  protected 
  	$currentTemplate,
    $sourceTemplate,
    $destTemplate,
    $invertedMapExists,
  	$panelSkeleton = 						
     '<div>
			  <div id="w3s_sm_panel_title">%s</div>
			  <div id="w3s_sm_commands">%s</div>
        <div id="w3s_sm_current_mapping">
          <table>
            <tr><td style="text-align:right;font-weight:bold">%s</td><td><input id="w3s_sm_source_name" value="%s" /></td><td><input id="w3s_sm_source" type="hidden" /></td></tr>
            <tr><td style="text-align:right;font-weight:bold">%s</td><td><input id="w3s_sm_dest_name" value="%s" /></td><td><input id="w3s_sm_dest" type="hidden" /></td></tr>
          </table>
        </div>
				<div id="w3s_sm_maps"><form id="w3s_mapping">%s</form></div>
			</div>',
    $rowSkeleton =
     '<div id="%s" class="%s">
        <div style="float:left;">
        %s
        <input id="w3s_sm_source" name="w3s_sm_source[]" value="%s" type="hidden" />
        <input id="w3s_sm_dest" name="w3s_sm_dest[]" value="%s" type="hidden" />
        </div>
        <div style="float:right;">%s</div>
      </div>',
    $commandsSkeleton =
     '<ul id="w3s_slot_list">
        <li>%s</li>
        <li>%s</li>
        <li>%s</li>
        <li>%s</li>
      </ul>';

  
  public function __construct($sourceTemplate, $destTemplate)
  {
  	$this->sourceTemplate  = $sourceTemplate;
    $this->destTemplate = $destTemplate;
    $this->invertedMapExists = false;


    $this->currentTemplate = sprintf('[%s][%s]', $sourceTemplate, $destTemplate);
    $slots = DbFinder::from('W3sSlotMapper')->
                      where('Templates', $this->currentTemplate)->
                      count();
    if ($slots == 0)
    {
      $currentTemplate1 = sprintf('[%s][%s]', $destTemplate, $sourceTemplate);
      $slots = DbFinder::from('W3sSlotMapper')->
                        where('Templates', $currentTemplate1)->
                        count();
      if ($slots > 0)
      {
        $this->sourceTemplate  = $destTemplate;
        $this->destTemplate = $sourceTemplate;
        $this->currentTemplate = $currentTemplate1;
        $this->invertedMapExists = true;
      }
    }
  }
	
	public function render()
	{
		return sprintf($this->panelSkeleton, $this->drawTitle(),
																				 $this->drawCommands(),
                                         w3sCommonFunctions::toI18N('Source slot:'),
                                         w3sCommonFunctions::toI18N('None mapped'),
                                         w3sCommonFunctions::toI18N('Dest slot'),
                                         w3sCommonFunctions::toI18N('None mapped'),
																				 $this->drawMaps());
	}

  public function save($sourceSlots, $destSlots)
  {
    
    $maps = (!$this->invertedMapExists) ? array_combine($sourceSlots, $destSlots) : array_combine($destSlots, $sourceSlots);

    $i = 0;
    $con = Propel::getConnection();

    $bRollBack = false;
    $con = w3sPropelWorkaround::beginTransaction($con);
    DbFinder::from('W3sSlotMapper')->
              where('Templates', $this->currentTemplate)->
              delete();
    foreach($maps as $sourceSlot => $destSlots)
    {
      $slotMapper = new w3sSlotMapper();
      $slotMapper->setTemplates($this->currentTemplate);
      $slotMapper->setSlotIdSource($sourceSlot);
      $slotMapper->setSlotIdDestination($destSlots);
      $result = $slotMapper->save();
      if ($slotMapper->isModified() && $result == 0)
		  {
        $bRollBack = true;
        break;
      }
      $i++;
    }
    if (!$bRollBack)
    {
      $con->commit();
      $result = true;
    }
    else{
      w3sPropelWorkaround::rollBack($con);
      $result = false;
    }

    return $result;
  }
	
	protected function drawTitle()
	{
    return sprintf(w3sCommonFunctions::toI18N('Slot Mapper'));
	}

  protected function drawMaps()
	{
    
    $result = '';

    $slots = DbFinder::from('W3sSlotMapper')->
                          where('Templates', $this->currentTemplate)->
                          find();
    if ($slots == null)
    { 
      $this->matchSlots();
      $slots = DbFinder::from('W3sSlotMapper')->
                          where('Templates', $this->currentTemplate)->
                          find();
    }

    $i = 0;
    foreach($slots as $slot)
    {
      $sourceSlot = DbFinder::from('W3sSlot')->findPk($slot->getSlotIdSource());
      $destSlot = DbFinder::from('W3sSlot')->findPk($slot->getSlotIdDestination());
      $idName = $slot->getSlotIdSource() . '-' . $slot->getSlotIdDestination();
      $class = ($i % 2) ? "w3s_white_row" : "w3s_blue_row";
      $result .= sprintf($this->rowSkeleton,
                          $idName,
                          $class,
                          $sourceSlot->getSlotName() . ' -> ' . $destSlot->getSlotName(),
                          $slot->getSlotIdSource(),
                          $slot->getSlotIdDestination(),
                          link_to_function(image_tag(sfConfig::get('app_w3s_web_skin_images_dir') . '/control_panel/button_delete.gif', 'alt=' . w3sCommonFunctions::toI18N('Delete current page') . ' size=14x14 style="border=0'), 'W3sSlotMapper.remove(' . $slot->getSlotIdSource() . ',' . $slot->getSlotIdDestination() . ')'));
      $i++; 
    }

    return $result;
	}

  protected function drawCommands()
	{
    return sprintf($this->commandsSkeleton, 
                    link_to_function(w3sCommonFunctions::toI18N('Switch template'), 'W3sSlotMapper.switchDiv()'),
                    link_to_function(w3sCommonFunctions::toI18N('Map slots'), 'W3sSlotMapper.map()'),
                    link_to_function(w3sCommonFunctions::toI18N('Save map'), 'W3sSlotMapper.save();'),
                    link_to_function(w3sCommonFunctions::toI18N('Return to editor'), 'W3sSlotMapper.close();'));
	}

  protected function matchSlots()
  {
    $slots = DbFinder::from('W3sSlot')->
                          where('TemplateId', $this->sourceTemplate)->
                          find();
    foreach($slots as $slot)
    {
      $destSlot = DbFinder::from('W3sSlot')->
                          where('TemplateId', $this->destTemplate)->
                          where('SlotName', $slot->getSlotName())->
                          findOne();
      if ($destSlot != null)
      {
        $slotMapper = new w3sSlotMapper();
        $slotMapper->setTemplates($this->currentTemplate);
        $slotMapper->setSlotIdSource($slot->getId());
        $slotMapper->setSlotIdDestination($destSlot->getId());
        $slotMapper->save();
      }
    }
  }
}