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
 * w3sImageEditor extends the w3sContentsEditor to build the editor 
 * to manage an image.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sImageEditor
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
class w3sImageEditor extends w3sContentsEditor
{
  protected 
  	$image,
  	$attributes,									// The image's attributes		
  	$editorSkeleton = 						// Declares the editor's skeleton	
     '<div class="w3s_toolbar"><table cellspacing="0" cellpadding="0">%s</table></div>
      <table cellspacing="4">
        <tbody>          
		      <tr>
		        <td colspan="2" align="left">%s</td>
		      </tr>
		      <tr>
		        <td valign="top">%s</td>      
		        <td valign="top">%s</td>
		        <td valign="top">%s</td>
		        <input type="submit" id="w3s_uploader_support" onclick="ImageManager.refreshImagelist(); return false;" />
		      </tr>          
    	  <tbody>
    	</table>',
    $previewSkeleton =            // Declares the skeleton to display the image preview  
     '<table cellspacing="0" cellpadding="0">
	      <tbody>
	        <tr>
	          <td>
	            <div id="w3s_image_preview">%s</div>
	          </td>
	        </tr>
	        <tr>
	          <td class="align_right">
	            %s
	            <span id="w3s_editor_canvas">%s</span> %%
	          </td>
	        </tr>
	      </tbody>
	    </table>';
  
  /**
   * Constructor.
   * 
   * @param object  The w3sContent object to edit
   * @param string  The id of the editor
   * 
   */   
  public function __construct($content, $editorId)
  {
  	$this->image = new w3sImage($content->getContent());
  	$this->attributes = $this->image->getAttributes();
  	
  	parent::__construct($content, $editorId);
  }
  
  /**
   * Sets the value of the previewSkeleton variable.
   * 
   * @param string
   *
   */  
  public function setPreviewSkeleton($value)
  {
    $this->previewSkeleton = $value;
  }
  
  /**
   * Returns the value of the previewSkeleton variable.
   * 
   * @return string
   *
   */  
  public function getPreviewSkeleton()
  {
    return $this->previewSkeleton;
  }

	/**
   * Draws the images editor
   * 
   * @return string
   *
   */ 
  public function drawEditor()
  {    
    return sprintf($this->editorSkeleton, $this->drawToolbar('tbImageManager.yml'), '', $this->drawImagesList(), $this->drawPreviewWindow(306, 278), $this->drawProperties());
  }
  
  /**
   * Draws a list of images
   * 
   * @return string
   *
   */ 
	protected function drawImagesList()
  {    
    $imageList = new w3sImageListVertical(w3sCommonFunctions::buildFilesList(sfConfig::get('app_images_path'), '', array('gif', 'jpg', 'jpeg', 'png')), basename($this->attributes["fullImagePath"]));
    return $imageList->renderImageList();
  }
  
  /**
   * Draws the editor's toolbar
   * 
   * @return string
   *
   */ 
  protected function drawToolbar($toolbarFile)
  {
    $toolbar = new w3sToolbarHorizontal(); 
    $toolbar->fromYml($toolbarFile);
    return $toolbar->renderToolbar();
  }

	/**
   * Draws the image's preview window
   * 
   * @return string
   *
   */ 
  protected function drawPreviewWindow($previewWidth, $previewHeight)
  { 
  	$this->image->setImagePreview($previewWidth, $previewHeight);
  	$attributes = $this->image->getAttributes();
    return sprintf($this->previewSkeleton, $this->image->getImage(), sfContext::getInstance()->getI18N()->__('Aspect ratio:'), $attributes['canvas']);
  }
  
  /**
   * Draws the image's properties
   * 
   * @return string
   *
   */ 
  protected function drawProperties()
  {
    $pages = $this->getSitePages();
    $linkedPage = w3sCommonFunctions::getPageNameFromLink($this->attributes['linkedTo']);         
    $search = array_search($linkedPage, $pages);
    if ($search !== false)
    { 
	    $selectedPageIndex = $search;
	    $externalLink = '';
    }
    else
    {
    	$selectedPageIndex = -1;
	    $externalLink = $this->attributes['linkedTo'];
    }
  	$params = 
      array(
        array('name' => 'w3s_ppt_image', 'label' => 'Image', 'type' => 'input', 'options' => array('value' => basename($this->attributes["fullImagePath"]), 'disabled'=> true, 'class' => 'disabled')),
        array('name' => 'w3s_ppt_width', 'label' => 'Width', 'type' => 'input', 'options' => array('value' => $this->attributes["width"], 'disabled'=> true, 'class' => 'disabled')),
        array('name' => 'w3s_ppt_height', 'label' => 'Height', 'type' => 'input', 'options' => array('value' => $this->attributes["height"], 'disabled'=> true, 'class' => 'disabled')),
        array('name' => 'w3s_ppt_size', 'label' => 'Size', 'type' => 'input', 'options' => array('value' => $this->attributes["size"], 'disabled'=> true, 'class' => 'disabled')),
        array('name' => 'w3s_ppt_img_alignment', 'label' => 'Align', 'choices' => array("w3s_none" => "Not aligned", "w3s_left" => "Left", "w3s_right" => "Right")),
        array('name' => 'w3s_ppt_title_text', 'label' => 'Title', 'type' => 'input', 'options' => array('value' => $this->attributes["title"])),
        array('name' => 'w3s_ppt_alt_text', 'label' => 'Alt', 'type' => 'input', 'options' => array('value' => $this->attributes["alt"])),
        array('name' => 'w3s_ppt_int_link', 'label' => 'Int. Link', 'default' => $selectedPageIndex, 'choices' => $pages),
        array('name' => 'w3s_ppt_ext_link', 'label' => 'Ext. Link', 'type' => 'input', 'options' => array('value' => $externalLink)),
        array('name' => 'w3s_ppt_htmlImage', 'type' => 'hidden', 'options' => array('value' => $this->image->getImage())),
        array('name' => 'w3s_ppt_imageType', 'type' => 'hidden', 'options' => array('value' => $this->attributes["imageType"]))
      );
    $properties = new w3sProperties($params);
    
    return $properties->render();
  }
}