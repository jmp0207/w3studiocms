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
 * w3sSlotMapperPanel builds the editor to manage slot mapper
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sSlotMapperPanel
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sSlotMapperPanel implements w3sEditor
{
  protected 
  	$currentTemplate, // The unique id that identifies the slot mapper for two templates, formatted as: [idSource][idDestination]
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

  /**
   * Constructor.
   *
   * @param int  The id of the source template
   * @param int  The id of the destionation template
   *
   */
  public function __construct($sourceTemplate, $destTemplate)
  {
    $sourceExists = DbFinder::from('W3sTemplate')->findPK($sourceTemplate);
    $destExists = DbFinder::from('W3sTemplate')->findPK($destTemplate);

    if ($sourceExists != null && $destExists != null)
    {
      $this->sourceTemplate  = $sourceTemplate;
      $this->destTemplate = $destTemplate;
      $this->invertedMapExists = false;

      // Checks if the currentTemplate exists.
      $this->currentTemplate = sprintf('[%s][%s]', $sourceTemplate, $destTemplate);
      $slots = DbFinder::from('W3sSlotMapper')->
                        where('Templates', $this->currentTemplate)->
                        count();

      // Current template doesn't exist
      if ($slots == 0)
      {

        // Checks for corrispondence, tring to invert destination with source
        $currentTemplate1 = sprintf('[%s][%s]', $destTemplate, $sourceTemplate);
        $slots = DbFinder::from('W3sSlotMapper')->
                          where('Templates', $currentTemplate1)->
                          count();
        if ($slots > 0)
        {

          // The templates are inverted, so everything have to be inverted
          $this->sourceTemplate  = $destTemplate;
          $this->destTemplate = $sourceTemplate;
          $this->currentTemplate = $currentTemplate1;
          $this->invertedMapExists = true;
        }
      }
    }
    else
    {
      $this->currentTemplate = null;
    }
  }

  /**
   * Implements the interface w3sEditor.
   *
   * @return string The rendered panel
   *
   */
	public function render()
	{
		if ($this->currentTemplate != null)
    {
      $result = sprintf($this->panelSkeleton, $this->drawTitle(),
                                               $this->drawCommands(),
                                               w3sCommonFunctions::toI18N('Source slot:'),
                                               w3sCommonFunctions::toI18N('None mapped'),
                                               w3sCommonFunctions::toI18N('Dest slot'),
                                               w3sCommonFunctions::toI18N('None mapped'),
                                               $this->drawMaps());
    }
    else
    {
      $result = w3sCommonFunctions::toI18N('The Slot Mapper Panel cannot be rendered because at least one of the templates required does not exist');
    }

    return $result;
	}

	/**
   * Draws the panel title.
   *
   * @return string The drawed title
   *
   */
	protected function drawTitle()
	{
    return sprintf(w3sCommonFunctions::toI18N('Slot Mapper'));
	}

  /**
   * Draws all the mapped slots.
   *
   * @return string The drawed slots
   *
   */
  protected function drawMaps()
	{
    
    $result = '';

    // Checks is a mapping exists
    $slots = DbFinder::from('W3sSlotMapper')->
                          where('Templates', $this->currentTemplate)->
                          find();
    if ($slots == null)
    { 

      // Tries to match the slots when any mapping exists
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

  /**
   * Draws the panel commands.
   *
   * @return string The drawed commands
   *
   */
  protected function drawCommands()
	{
    return sprintf($this->commandsSkeleton, 
                    link_to_function(w3sCommonFunctions::toI18N('Switch template'), 'W3sSlotMapper.switchDiv()'),
                    link_to_function(w3sCommonFunctions::toI18N('Map slots'), 'W3sSlotMapper.map(' . $this->invertedMapExists . ')'),
                    link_to_function(w3sCommonFunctions::toI18N('Save map'), 'W3sSlotMapper.save();'),
                    link_to_function(w3sCommonFunctions::toI18N('Return to editor'), 'W3sSlotMapper.close();'));
	}

  /**
   * Saves the current mapping
   *
   * @param array  An array that contains the slots id for source template
   * @param array  An array that contains the slots id for destionation template
   *
   * @return int 0 - An error occoured
   *             1 - Savving success
   *             2 - Source and destination malformed and cannot be combined.
   *
   */
  public function save($sourceSlots, $destSlots)
  {
    if ($this->currentTemplate != null)
    {
      try
      {
        $maps = array_combine($sourceSlots, $destSlots);
      }
      catch(Exception $e)
      {
        return 2;
      }

      try
      {
        $con = Propel::getConnection();

        $bRollBack = false;
        $con = w3sPropelWorkaround::beginTransaction($con);

        // Previous mapping will be deleted
        DbFinder::from('W3sSlotMapper')->
                  where('Templates', $this->currentTemplate)->
                  delete();
        foreach($maps as $sourceSlot => $destSlots)
        {
          if (!$this->saveMap($sourceSlot, $destSlots))
          {
            $bRollBack = true;
            break;
          }
        }

        if (!$bRollBack)
        {
          $con->commit();
          $result = 1;
        }
        else
        {
          w3sPropelWorkaround::rollBack($con);
          $result = 0;
        }
      }
      catch(Exception $e)
      {
        $result = 0;
      }
    }
    else
    {
      $result = 4;
    }

    return $result;
  }

  /**
   * Tries to make an automation match of the templates slots using their names
   *
   * @return bool false - An error occoured
   *              true  - Savving success
   *
   */
  protected function matchSlots()
  {
    $con = Propel::getConnection();
    $bRollBack = false;
    $con = w3sPropelWorkaround::beginTransaction($con);

    // Search the slots of source template
    $slots = DbFinder::from('W3sSlot')->
                          where('TemplateId', $this->sourceTemplate)->
                          find();
    foreach($slots as $slot)
    {

      // Search the same slots on destination template by name
      $destSlot = DbFinder::from('W3sSlot')->
                          where('TemplateId', $this->destTemplate)->
                          where('SlotName', $slot->getSlotName())->
                          findOne();
      if ($destSlot != null)
      {

        // Saves the mapping when a match has been found
        if (!$this->saveMap($slot->getId(), $destSlot->getId()))
        {
          $bRollBack = true;
          break;
        }
      }
    }

    if (!$bRollBack)
    {
      $con->commit();
      $result = true;
    }
    else
    {
      w3sPropelWorkaround::rollBack($con);
      $result = false;
    }
    
    return $result;
  }

  /**
   * @param int  The id of the source slot
   * @param int  The id of the destionation slot
   *
   * @return bool false - An error occoured
   *              true  - Savving success
   *
   */
  protected function saveMap($sourceSlot, $destSlots)
  {
    $result = true;

    $slotMapper = new w3sSlotMapper();
    $slotMapper->setTemplates($this->currentTemplate);
    $slotMapper->setSlotIdSource($sourceSlot);
    $slotMapper->setSlotIdDestination($destSlots);
    $result = $slotMapper->save();
    if ($slotMapper->isModified() && $result == 0) $result = false;

    return $result;
  }
}