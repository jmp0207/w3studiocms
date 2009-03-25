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
 
class w3sTemplateEngineSlotAssociation extends w3sTemplateEngine
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
    return sprintf('<a href="#" onclick="W3sSlotAssociation.currentSelected=%s;$(\'%s\').style.backgroundColor=\'#000066\';"><div id="%s" style="min-height:24px; color:#FFF; border:1px solid #FFFFFF; background:#C20000;">%s</div></a>', $slot->getId(), 'w3sSlotItem_' . $slot->getId(), 'w3sSlotItem_' . $slot->getId(), $slot->getId() . $slot->getSlotName());
  }
}