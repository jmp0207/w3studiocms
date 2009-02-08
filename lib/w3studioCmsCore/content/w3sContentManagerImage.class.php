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
 * w3sContentManagerImage extends the w3sContentManager to represent an image
 * content.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sContentManagerImage
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
  
class w3sContentManagerImage extends w3sContentManager{
  protected $image,				// The html image
            $attributes;  // The image's attributes
    
  /**
   * Constructor.
   * 
   * @param int  The content's type
   * @param object  The w3sContent object. Can be null when adding
   * @param string  The image
   *
   */   
  public function __construct($type, $content = null, $image='')
  {
    if ($image != '') $this->image = new w3sImage($image);
    
    parent::__construct($type, $content);  
  }
  
  /**
   * Returns the default text for the image content
   *   
   * @return string
   *
   */ 
  public function getDefaultText()
  {
  	return sprintf('<img src="%s/common/sample_image.png" width="80" height="80" title="%s" alt="%s" />', sfConfig::get('app_w3s_web_skin_images_dir'), w3sCommonFunctions::toI18N('Type here a title that describes the image'), w3sCommonFunctions::toI18N('Type here a description of the image'));           
  }
    
  /**
   * Format content to display the image on the web page, using the image's properties
   * edited by user at runtime. Overrides the same function of w3sContentManager  
   *
   * @param      array The array with contents.
   * 
   * @return     array The array with contents formatted.
   */
  protected function formatContent($contentValues)
  {
  	
  	// Cycles all properties passed from the serialized form and creates an
  	// array with the following path: array[property_name] = array[property_value]
  	$formattedProperties = array();
  	$properties = explode('&', urldecode($contentValues["Content"]));
  	foreach($properties as $property)
  	{
  		$propertyValues = explode('=', $property);
  		$formattedProperties[$propertyValues[0]] = $propertyValues[1];
  	}
  	
  	// Creates the image
  	$image = sprintf('<img src="%s" alt="%s" title="%s" width="%s" height="%s" />', 
  										sfConfig::get('app_absolute_images_path') . $formattedProperties['w3s_ppt_image'], 
											$formattedProperties['w3s_ppt_alt_text'],
											$formattedProperties['w3s_ppt_title_text'],
											$formattedProperties['w3s_ppt_width'],
											$formattedProperties['w3s_ppt_height']);
  	
  	// Check for a link
  	if ($formattedProperties['w3s_ppt_int_link'] != 0)
  	{
  		
  		// If internal link the page's id is passed, so the page name is retrieved from the db
  		$page = DbFinder::from('W3sPage')->findPK($formattedProperties['w3s_ppt_int_link']);
  		$link = $page->getPageName() . '.html'; 
  	}
  	elseif ($formattedProperties['w3s_ppt_ext_link'] != '')
  	{
  		$link = $formattedProperties['w3s_ppt_ext_link'];
  	}
  	else
  	{
  		$link = null;
  	}
  	if ($link != null) $image = sprintf('<a href="%s">%s</a>', $link, $image);
  	
  	// Updates the Content
  	$contentValues["Content"] = $image;
  	
  	return $contentValues;
  }
}