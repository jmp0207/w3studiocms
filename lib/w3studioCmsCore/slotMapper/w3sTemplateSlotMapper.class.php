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
 * @subpackage w3sTemplateEnginePreview
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sTemplateEngineSlotMapper extends w3sTemplateEngine
{

  public function __construct($idTemplate)
  {
    $this->idTemplate = $idTemplate;
    $template = DbFinder::from('W3sTemplate')->
                          with('W3sProject')->
                          findPK($idTemplate);
	  $this->templateName = $template->getTemplateName();
	  $this->projectName = $template->getW3sProject()->getProjectName();
    $this->pageContents = w3sCommonFunctions::readFileContents(self::getTemplateFile($this->projectName, $this->templateName));
  }

  public function renderPage()
  {
    $slots = DbFinder::from('W3sSlot')->
                      where('TemplateId', $this->idTemplate)->
                      find(); 
    foreach ($slots as $slot){
      $contents = $this->drawSlot($slot);
      $this->pageContents = preg_replace('/\<\?php.*?include_slot\(\'' . $slot->getSlotName() . '\'\).*?\?\>/', $contents, $this->pageContents);
    }

    // Renders the W3StudioCMS Copyright button. Please do not remove. See the function to
    // learn the best way to implement it in your web site. Thank you
    $this->pageContents = $this->renderCopyright($this->pageContents);

    return $this->pageContents;
  }

  /** 
   * Draws the contents' slot when in preview contents. 
   * 
   * @param object   A slot object
   * 
   * @return string  The contents that belong to slot formatted as string
   * 
   */
  public function drawSlot($slot)
  {
    $slotMapper = DbFinder::from('W3sSlotMapper')->
                      where('SlotIdSource', $slot->getId())->
                      findOne();
    if ($slotMapper == null)
    {
      $slotMapper = DbFinder::from('W3sSlotMapper')->
                      where('SlotIdDestination', $slot->getId())->
                      findOne();
    }
    $class = ($slotMapper == null) ? 'slotNotSelected' : 'slotSelected';
    
    return sprintf('<a href="#" onclick="W3sSlotMapper.selectSlot(%s, \'%s\')"><div id="%s" class="%s">%s</div></a>', $slot->getId(), $slot->getSlotName(), 'w3sSlotItem_' . $slot->getId(), $class, $slot->getId() . $slot->getSlotName());
  }
}