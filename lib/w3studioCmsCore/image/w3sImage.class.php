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
 * w3sImage class manages images.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sImage
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
  
class w3sImage{
  protected $image,        // The html image
            $attributes;   // The image's attributes
    
  /**
   * Constructor.
   * 
   * @param int  The content's type
   * @param object  The w3sContent object. Can be null when adding
   * @param string  The image
   *
   */   
  public function __construct($image){
    if ($image != '')
    {       
      // Retrieves the image from an existing HTML image or from an image file
      if (htmlentities($image) != $image)
      { 
        $this->imageFromHtml($image);
      }
      else{
        $this->imageFromFile($image);
      } 
    }
  }
  
  /**
   * Returns the value of the image variable.
   * 
   * @return string
   *
   */  
  public function getImage(){
    return $this->image;
  }
 
  /**
   * Returns the value of the attributes variable.
   * 
   * @return string
   *
   */ 
  public function getAttributes(){
    return $this->attributes;
  }
  
  /**
   * Resizes the image and updates the image's attributes to fit it in a rectangle 
   * which dimensions are passed as reference.
   * 
   * @param int The width of the preview rectangle
   * @param int The height of the preview rectangle
   *
   */ 
  public function setImagePreview($previewWidth, $previewHeight)
  {
    // If required, resizes the image to fit in the preview window, otherwise set canvas to 100
    if (($this->attributes["realWidth"] > $previewWidth) || ($this->attributes["realHeight"] > $previewHeight)){
      $canvas = $this->calulateCanvas($this->attributes["realWidth"], $this->attributes["realHeight"], $previewWidth, $previewHeight);
      
      // Changes image's width and height.
      $this->attributes["width"] = $canvas["width"];
      $this->attributes["height"] = $canvas["height"];
      $this->attributes["canvas"] = $canvas["value"]; 
      
      // Sets the image with the new dimensions
      $this->image = sprintf('<img src="%s" alt="%s" title="%s" style="width:%spx;height:%spx" />', $this->attributes["src"], $this->attributes["alt"], $this->attributes["title"], $this->attributes["width"], $this->attributes["height"]);  
    }
    else{
      $this->attributes["canvas"] = '100';
    }
  }
  
  /**
   * Reads the image's file, retrieves its attributes and sets the html image
   * 
   * @param str The image's file
   *
   */ 
  protected function imageFromFile($fileName)
  {
    if (!is_file($fileName)){
      throw new RuntimeException(sprintf('%s is not a valid file.', $fileName)); 
    }    
    $this->attributes["fullImagePath"] = $fileName;
    $this->setImageAttributes();
    $this->attributes["src"] = sfConfig::get('app_absolute_images_path') . basename($fileName);
    $this->attributes["alt"] = '';
    $this->attributes["title"] = '';    
    $this->attributes["linkedTo"] = '';
    $this->image = sprintf('<img src="%s" width="%s" height="%s" />', $this->attributes["src"], $this->attributes["realWidth"], $this->attributes["realHeight"]);
  }
  
  /**
   * Sets the image and its attributes from an html image
   * 
   * @param str The html image 
   *
   */ 
  protected function imageFromHtml($value)
  {    
    // retrieves only the image
    $htmlImage = trim(strip_tags($value, '<img>'));
    $this->image = $htmlImage; 
    
    // Retrieves all attributes from the image  
    $this->attributes = w3sCommonFunctions::stringToArray($this->image);
    $this->attributes["fullImagePath"] = (basename($this->attributes["src"]) != 'sample_image.png') ? str_replace(sfConfig::get('app_absolute_images_path'), w3sCommonFunctions::checkLastDirSeparator(sfConfig::get('app_images_path')), $this->attributes['src']) : sfConfig::get('sf_web_dir') . $this->attributes['src'];
    $this->attributes["linkedTo"] = w3sCommonFunctions::getTagAttribute(trim(strip_tags($value, '<a>')), 'href');
    $this->setImageAttributes();
  }
  
  /**
   * Sets the image and its attributes from an html image
   * 
   * @param str The html image 
   *
   */ 
  protected function setImageAttributes()
  {
    $imageAttributes = getimagesize($this->attributes["fullImagePath"]);
    if (!is_array($imageAttributes))
    {
      throw new RuntimeException(sprintf('%s isn\'t a valid image file.', basename($this->attributes["fullImagePath"])));
    }
    $this->attributes["realWidth"] = $imageAttributes[0];
    $this->attributes["realHeight"] = $imageAttributes[1];
    $this->attributes["imageType"] = $imageAttributes[2];   
    $this->attributes["width"] = $this->attributes["realWidth"];
    $this->attributes["height"] = $this->attributes["realHeight"]; 
    $this->attributes["size"] = w3sCommonFunctions::formatFileSize($this->attributes["fullImagePath"]);
  }
  
  /**
   * Calculates new image dimensions and relative canvas value to fit an image into a
   * static window
   *
   * @param      int The width of image.
   * @param      int The height of image.
   * @param      int The width of the preview rectangle.
   * @param      int The height of the preview rectangle.
   * 
   * @return     array The resized width, the resized height and the calculated canvas
   */
  protected function calulateCanvas($imageWidth, $imageHeight, $previewWidth, $previewHeight)
  {

    // Set the canvas to maximum and the picture resized dimensions to picture dimensions
    $canvas = 100;
    $resizedWidth = $imageWidth;
    $resizedHeight = $imageHeight;

    // Calculate the max side for resizing
    $diffPreviewWidth = $imageWidth - $previewWidth;
    $diffPreviewHeight = $imageHeight - $previewHeight;
    $max = max($diffPreviewWidth, $diffPreviewHeight);

    // If the max value is not negative we have to resize
    if ($max == abs($max)){
      if ($max == $diffPreviewWidth){

        // The max side is the width side
        $canvas = $previewWidth/$imageWidth;
        $resizedHeight = intval($imageHeight * $canvas);
        $resizedWidth = ($diffPreviewWidth > 0) ? $imageWidth * $canvas : $imageWidth;
      }
      elseif($max == $diffPreviewHeight){

        // The max side is the height side
        $canvas = $previewHeight/$imageHeight;
        $resizedWidth = intval($imageWidth * $canvas);
        $resizedHeight = ($diffPreviewHeight > 0) ? $imageHeight * $canvas : $imageHeight;
      }
    }
    if ($canvas != 100) $canvas = intval($canvas * 100);

    return array("width" => $resizedWidth, 
                 "height" => $resizedHeight, 
                 "value" => $canvas);
  }
}