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
 
class w3sTemplateEnginePreview extends w3sTemplateEngine
{  
  /** 
   * Draws the contents' slot when in preview mode.
   * 
   * @param object   A slot object
   * 
   * @return string  The contents that belong to slot formatted as string
   * 
   */
  public function drawSlot($slot){ 
    $result = '';
    foreach ($slot['contents'] as $content)
    {
      if ($content != null)
      {
        $curContent = w3sContentManagerFactory::create($content->getContentTypeId(), $content);
        $result .= $curContent->getDisplayContentForPreviewMode();
      }
    }  
    return $result;
  }
}