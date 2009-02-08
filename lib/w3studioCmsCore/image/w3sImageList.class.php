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
 * w3sImageList represents the base class to build a list of images. This class
 * uses the toolbar object to build images' lists. To change this behaviour ovverride
 * the renderImageList function
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sImageList
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
abstract class w3sImageList extends w3sImage
{
  protected 
    $imageList,
    $selectedImage,
    $listName = 'w3s_images_select',  // The name of the default list
    $imageWidth = 48,                 // The default image width
    $imageHeight = 48;                // The default image height
    
  abstract protected function initToolbar();
  abstract protected function setToolbarButton($image);
  
  /**
   * Constructor.
   * 
   * @param array   An array with le list of the names of the images to display
   * @param string  The selected image 
   *
   */  
  public function __construct($imageList=array(), $selected='')
  { 
    $this->setImageList($imageList); 
    $this->selectedImage = $selected;    
  }
  
  /**
	 * Sets / Gets the value of the imageList property.
	 * 
	 * @param array
	 *
	 */  
  public function setImageList($value){
    if (!is_array($value))
	  {
	    throw new RuntimeException(sprintf('ImageList must be an array. The value you entered is %s.', $value));
	  }
    $this->imageList = $value;  
  }
  
  public function getImageList(){
    return $this->imageList;  
  }
  
  /**
	 * Sets / Gets the value of the selectedImage property.
	 * 
	 * @param array
	 *
	 */  
  public function setSelectedImage($value){
    if (!is_string($value))
	  {
	    throw new RuntimeException(sprintf('SelectedImage must be an array. The value you entered is %s.', $value));
	  }
    $this->selectedImage = $value;  
  }
  
  public function getSelectedImage(){
    return $this->selectedImage;  
  }
  
  /**
	 * Sets / Gets the value of the imageWidth property.
	 * 
	 * @param int
	 *
	 */  
  public function setImageWidth($value){
    if (!is_int($value))
	  {
	    throw new RuntimeException(sprintf('ImageWidth must be an array. The value you entered is %s.', $value));
	  }
	  $this->imageWidth = $value;  
  }
  
  public function getImageWidth(){
    return $this->imageWidth;  
  }
  
  /**
	 * Sets / Gets the value of the imageHeight property.
	 * 
	 * @param int
	 *
	 */  
  public function setImageHeight($value){
    if (!is_int($value))
	  {
	    throw new RuntimeException(sprintf('ImageHeight must be an array. The value you entered is %s.', $value));
	  }
    $this->imageHeight = $value;  
  }
  
  public function getImageHeigh(){
    return $this->imageHeight;  
  }
  
  /**
	 * Sets the image's dimensions
	 * 
	 * @param array
	 *
	 */  
  public function setImageDimensions($dimensions){
    
    // Set required options
    $requiredOptions = array("0" => "width", "1" => "height");
    
    // check option names
    if ($diff = array_diff(array_keys($dimensions), $requiredOptions))
    {
      throw new InvalidArgumentException(sprintf('%s does not support the following options: \'%s\'.', get_class($this), implode('\', \'', $diff)));
    }
    
    if ($diff = array_diff($requiredOptions, array_keys($dimensions)))
    {
      throw new RuntimeException(sprintf('%s requires the following options: \'%s\'.', get_class($this), implode('\', \'', $diff)));
    }
    
    $this->setImageWidth($dimensions["width"]);  
    $this->setImageHeight($dimensions["height"]);
  }
  
  /**
	 * Renders the image list
	 * 
	 * @return string
	 *
	 */    
  public function renderImageList()
  {
    $images = array();
    foreach($this->imageList["values"] as $image){ 
      $images[] = $this->drawListRow($image);
    } 
    $toolbar = $this->initToolbar();
    $toolbar->setToolbar($images); 
    
    $imageList = sprintf('<div id="%s">%s</div>', $this->listName, sprintf('<table>%s</table>', $toolbar->renderToolbar()));
          
    return $imageList;
  }
  
  /**
	 * Draws every image list's row  
	 * 
	 * @return string
	 *
	 */   
  protected function drawListRow($image){
    $this->imageFromFile(sfConfig::get('app_images_path') . DIRECTORY_SEPARATOR . $image);
    $this->setImagePreview($this->imageWidth, $this->imageHeight);
        
    return $this->setToolbarButton($image);                
  }
  
  /**
	 * Sets the default caption of the image list, as follows:
	 *   Image Name
	 *   Image dimensions
	 *   Image size 
	 * 
	 * @return string
	 *
	 */  
  protected function renderCaption($imageName)
  {
    return sprintf('%s<br />%s%s x %s<br />%s', w3sCommonFunctions::setStringMaxWidth($imageName), 
                                                sfContext::getInstance()->getI18N()->__('Dim:'), 
                                                $this->attributes["realWidth"],
                                                $this->attributes["realHeight"],
                                                sfContext::getInstance()->getI18N()->__('Size:') . $this->attributes["size"]);
  }
}