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
 * w3sImageListVertical builds a vertical list of images 
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sImageListVertical
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sImageListVertical extends w3sImageList
{
  /**
   * Sets the toolbar
   * 
   * @param object
   *
   */  
  public function initToolbar()
  {
    return new w3sToolbarVertical();
  }
  
  /**
   * Sets every toolbar's button 
   * 
   * @param array
   *
   */
  public function setToolbarButton($imageName){
    $selected = ($imageName == $this->selectedImage) ? 'class=\"selectedImage\"' : '';
    
    return array('image' => $this->attributes["src"],
                 'imageParams' => array('style' => sprintf('width:%dpx; height:%dpx', $this->attributes["width"], $this->attributes["height"])),
                 'action' => $this->setCommand($imageName),
                 'caption' => $this->renderCaption($imageName),
                 'imageBeforeText' => "<td style=\"text-align:center;\" class=\"border_green\"><a href=\"%1\$s\" %2\$s %3\$s>%4\$s</a></td><td id=\"w3s_" . $imageName . "\" style=\"text-align:left;\" ' . $selected . '><a href=\"#\" %2\$s %3\$s>%5\$s</a></td>",
                 'imageTextRelation' => 0);  
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
}