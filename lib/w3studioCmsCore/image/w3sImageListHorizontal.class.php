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
 * w3sImageListHorizontal builds an horizontal list of images. This object is not used in 
 * w3studioCMS and has only a demonstration pourpose. 
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sImageListVertical
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sImageListHorizontal extends w3sImageList
{
  /**
   * Sets the toolbar
   * 
   * @param object
   *
   */  
  public function initToolbar()
  {
    return new w3sToolbarHorizontal();
  }
  
  /**
   * Sets every toolbar's button 
   * 
   * @param array
   *
   */
  public function setToolbarButton($imageName){
    return array('image' => $this->attributes["src"],
                 'imageParams' => array('style' => sprintf('width:%dpx; height:%dpx', $this->attributes["width"], $this->attributes["height"])),
                 'action' => $this->setCommand(),
                 'caption' => $this->renderCaption(),
                 'imageAboveText' => '<td width="100"><a href="%s" %s %s><div style="width:' . $this->imageWidth .'px;height:' . $this->imageHeight . 'px;" class="border_green">%s</div><div>%s</div></a></td>');
  }
  
  /**
   * Sets the command executed when user clicks the selected item
   * 
   * @param string
   *
   */
  protected function setCommand($imageName){
    return sprintf('ImageManager.setImagePreview(\'%s\')', $imageName);
  }
  
  protected function renderCaption($imageName)
  {
    return sprintf('%s<br />%s%sx%s<br />%s', w3sCommonFunctions::setStringMaxWidth($imageName, 15),
                                              sfContext::getInstance()->getI18N()->__('Dim:'), 
                                              $this->attributes["realWidth"],
                                              $this->attributes["realHeight"],
                                              sfContext::getInstance()->getI18N()->__('Size:') . $this->attributes["size"]);
  }
}