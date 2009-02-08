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
 * w3sImageListMenuBuilder draws the image list used by the menuBuilder object
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sImageListMenuBuilder
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */

class w3sImageListMenuBuilder extends w3sImageListVertical
{
  protected $inputToUpdate;
  
  /**
   * Constructor.
   * 
   * @param array  An array with le list of the names of the images to display
   * @param array  This object writes the name of the selected image into an
   *               input box. This is the name of that input bux. 
   *
   */  
  public function __construct($imageList, $inputToUpdate)
  { 
    $this->inputToUpdate = $inputToUpdate; 
    
    parent::__construct($imageList);  
  }
 
  /**
   * Sets the command when the image is selected.
   * 
   * @param string  The name of the selected image  
   *
   */  
  protected function setCommand($imageName){
    return sprintf('$(\'%s\').value = \'%s\';objMenuBuilder.saveMenuLinkImage(1);', $this->inputToUpdate, $imageName);
  }
}