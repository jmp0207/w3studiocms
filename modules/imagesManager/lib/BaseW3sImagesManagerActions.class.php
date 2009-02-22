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

require_once(dirname(__FILE__).'/../../contentsManager/lib/BaseW3sContentManagerActions.class.php');

class BaseW3sImagesManagerActions extends BaseW3sContentsManagerActions
{	
  /* 
   * The actions needed to manage the image content type
   */ 
  public function executeChangeImage($request)
  {
    /*
     * Retriving the attributes of the selected image
     */
    if ($request->hasParameter('image') && $request->hasParameter('previewWidth') && $request->hasParameter('previewHeight'))
    {
      try
      {
        $image = new w3sImage(sfConfig::get('app_images_path') . DIRECTORY_SEPARATOR . $this->getRequestParameter('image'));
        $image->setImagePreview($this->getRequestParameter('previewWidth'), $this->getRequestParameter('previewHeight'));
        $attributes = $image->getAttributes();

        /*
         * Output building for JSon function. All the elements stored in this header
         * will be refreshed when ajax request is done. It must be written in a single line otherwise
         * IE is not able to read it!
         */
        $jsonImage = '[["w3s_image_preview", "' . addslashes($image->getImage()) . '"],';

        /* TODO: Used for image editor. These must be reviewed when the image editor will be ready
        $jsonImage .= '["w3s_editor_image_size", "' . $canvasImage["size"] . '"],';
        $jsonImage .= '["w3s_editor_width", "' . $canvasImage["width"] . '"],';
        $jsonImage .= '["w3s_editor_height", "' . $canvasImage["height"] . '"],';
        $jsonImage .= '["w3s_editor_type_select", "' . $canvasImage["imageType"] . '"],';
        $jsonImage .= '["w3s_editor_canvas", "' . $canvasImage["canvasValue"] . '"],';
        $jsonImage .= '["w3s_editor_start_width", "' . $canvasImage["width"] . '"],';
        $jsonImage .= '["w3s_editor_start_height", "' . $canvasImage["height"] . '"],';
        */

        $jsonImage .= '["w3s_ppt_image", "' . $this->getRequestParameter('image') . '"],';
        $jsonImage .= '["w3s_ppt_imageType", "' . $attributes["imageType"] . '"],';
        $jsonImage .= '["w3s_ppt_size", "' . $attributes["size"] . '"],';
        $jsonImage .= '["w3s_ppt_width", "' . $attributes["realWidth"] . '"],';
        $jsonImage .= '["w3s_ppt_height", "' . $attributes["realHeight"] . '"]]';

        $this->getResponse()->setHttpHeader("X-JSON", '('.$jsonImage.')');

        return sfView::HEADER_ONLY;
      }
      catch(Exception $e)
      {
      }
    }

    $this->getResponse()->setStatusCode(404);
    return sfView::NONE;
  }
  
  /**
   * Executes delete image action.
   */
  public function executeDeleteImage($request)
  {
    if($request->hasParameter('image'))
    {
      $file = sfConfig::get('app_images_path') . DIRECTORY_SEPARATOR . $this->getRequestParameter('image');
      if (is_file($file))
      {
        if (unlink($file))
        {
          /*
           * After deleting we have to refresh the images directory. Perhaps it may be simple
           * to remove the image from the images list on client side, but this will assure
           * that deleting operation was done correctly.
           */
          $this->forward('imagesManager', 'refreshImages');
        }
        else
        {
           $this->getResponse()->setStatusCode(404);
           return $this->renderText('Something was wrong when trying to delete the image. Check the permissions on image file.');
        }        
      }
      else
      {
        $this->getResponse()->setStatusCode(404);
        return $this->renderText('The image file does not exist.');
      }      
    }
    else
    {
      $this->getResponse()->setStatusCode(404);
      return $this->renderText('Image file is required.');
    }
  }
  
  /**
   * Executes delete image action.
   */
  public function executeRefreshImages()
  {
    try
    {
      $imageList = new w3sImageListVertical(w3sCommonFunctions::buildFilesList(sfConfig::get('app_images_path'), '', array('gif', 'jpg', 'jpeg', 'png')), '');
      return $this->renderText($imageList->renderImageList());
    }
    catch(Exception $e)
    {
      return $this->renderText('Something was wrong while refreshing images lists.');
    }
  }  
}