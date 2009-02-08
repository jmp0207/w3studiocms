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

class imageEditorActions extends sfActions
{
  /**
   * Executes index action. The associated view requires an array which must be passed to the variable
   * $this->imageAttributes. In this function we will load the attributes of the image
   * stored into the W3Studio's database. Comment the code inside to load your own
   * images.
   *
   */
  public function executeIndex()
  {
    $this->form = new w3sImageEditorForm();
    $this->imageAttributes = w3sClassImageManager::getImageAttributesFromContent($this->getRequestParameter('idContent'));
  }

  /**
   * Executes ResizeImage action
   *
   * This action can be used into an ajax action to resize an image. This function requires
   * some parameters:
   *  image         : The name of the image to resize
   *  imagePath     : The full image path
   *  imageWidth    : The new image width
   *  imageHeight   : The new image height
   *  imageType     : The new image type. This parameter must be 1-GIF 2-JPG 3-PNG. Other
   *                  formats will be ignored
   *  imageQuality  : The new value for the image quality. Applies only to JPG images
   *
   *  return A JSon header.
   */
  public function executeResizeImage()
  {
    // Resizes the image
    $imagePath = w3sCommonFunctions::checkLastDirSeparator(sfConfig::get('app_images_path'));
    $imageEditor = new ImageEditor($this->getRequestParameter('image'), $imagePath);
    echo $this->getRequestParameter('imageWidth') . ' - ' . $this->getRequestParameter('imageHeight');
    $imageEditor->resize($this->getRequestParameter('imageWidth'), $this->getRequestParameter('imageHeight'));

    // Retrieve the image type and verifies if the new image will be of the same type
    $imageAttributes = getimagesize($imagePath . $this->getRequestParameter('image'));
    $newType = ($this->getRequestParameter('imageType') != $imageAttributes[2]) ? $this->getRequestParameter('imageType') : 0;

    // Writes the new image and returns its name
    $newFileName = $imageEditor->outputFile($this->getRequestParameter('image'),
                                             $imagePath, 
                                             $newType, 
                                             $this->getRequestParameter('imageQuality'));

    // Produces the output header for ajax calling.
    $canvasImage = w3sClassImageManager::getImageAttributes($newFileName, $this->getRequestParameter('previewWidth'), $this->getRequestParameter('previewHeight'), $this->getRequestParameter('setCanvas'));
    //$output = '[["w3s_image_size", "' . $imageAttributes["size"] . '"],["w3s_image_preview", "' . $imageAttributes["htmlImage"] . '"], ["w3s_image_canvas", "' . $imageAttributes["canvasValue"] . '"],["w3s_start_width", "' . $imageAttributes["width"] . '"],["w3s_start_height", "' . $imageAttributes["height"] . '"]]';
    $jsonImage = '[["w3s_image_preview", "' . $canvasImage["htmlImage"] . '"],';
    $jsonImage .= '["w3s_editor_image_size", "' . $canvasImage["size"] . '"],';
    $jsonImage .= '["w3s_editor_width", "' . $canvasImage["width"] . '"],';
    $jsonImage .= '["w3s_editor_height", "' . $canvasImage["height"] . '"],';
    $jsonImage .= '["w3s_editor_type_select", "' . $canvasImage["imageType"] . '"],';
    $jsonImage .= '["w3s_editor_canvas", "' . $canvasImage["canvasValue"] . '"],';
    $jsonImage .= '["w3s_editor_start_width", "' . $canvasImage["width"] . '"],';
    $jsonImage .= '["w3s_editor_start_height", "' . $canvasImage["height"] . '"],';
    $jsonImage .= '["w3s_ppt_htmlImage", "' . $canvasImage["htmlImage"] . '"],';
    $jsonImage .= '["w3s_ppt_imageType", "' . $canvasImage["imageType"] . '"],';
    $jsonImage .= '["w3s_ppt_size", "' . $canvasImage["size"] . '"],';
    $jsonImage .= '["w3s_ppt_width", "' . $canvasImage["width"] . '"],';
    $jsonImage .= '["w3s_ppt_height", "' . $canvasImage["height"] . '"]]';
    
    $this->imagesList = w3sCommonFunctions::buildFilesList($imagePath, $newFileName, array('gif', 'jpg', 'jpeg', 'png'));
    /*
    if ($newFileName != $this->getRequestParameter('image')){
    }*/
    $this->getResponse()->setHttpHeader("X-JSON", '('.$jsonImage.')');
    
    return sfView::HEADER_ONLY;
  }
  
  /**
   * Executes RotateImage action
   *
   * This action can be used into an ajax action to rotate an image. This function requires
   * some parameters:
   *  image         : The name of the image to resize
   *  imagePath     : The full image path
   *  degrees       : The angle value for rotation. It can be 90, 180, 270
   *  imageType     : The new image type. This parameter must be 1-GIF 2-JPG 3-PNG. Other
   *                  formats will be ignored
   *  imageQuality  : The new value for the image quality. Applies only to JPG images
   *
   *  return A JSon header.
   */
  public function executeRotateImage()
  { 
    $imageEditor = new ImageEditor($this->getRequestParameter('image'), sfConfig::get('app_images_path'));
    $imageEditor->rotate($this->getRequestParameter('degrees'));
    
    $imageEditor->outputFile($this->getRequestParameter('image'), 
                             sfConfig::get('app_images_path'), 
                             $this->getRequestParameter('imageType'), 
                             $this->getRequestParameter('imageQuality'));
    
    // Produces the output header for ajax calling.
    $output = '';
    if ($this->getRequestParameter('previewWidth') != 0 && $this->getRequestParameter('previewHeight') != 0){
      $imageAttributes = w3sClassImageManager::getImageAttributes($this->getRequestParameter('image'), $this->getRequestParameter('previewWidth'), $this->getRequestParameter('previewHeight'), $this->getRequestParameter('setCanvas'));
  
      $output = '[["w3s_image_size", "' . $imageAttributes["size"] . '"],["w3s_image_width", "' . $imageAttributes["width"] . '"],["w3s_image_height", "' . $imageAttributes["height"] . '"],["w3s_image_preview", "' . $imageAttributes["image"] . '"],["w3s_image_canvas", "' . $imageAttributes["canvas"] . '"],["w3s_start_width", "' . $imageAttributes["width"] . '"],["w3s_start_height", "' . $imageAttributes["height"] . '"]]';
    }
    
    $this->getResponse()->setHttpHeader("X-JSON", '('.$output.')');
    return sfView::HEADER_ONLY;
  }
}