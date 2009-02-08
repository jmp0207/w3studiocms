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
 * w3sPageEditor builds the editor to manage the site's pages
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sPageEditor
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sMetatagsManager
{
  protected
  	$metatag,
  	$skeleton = 						
     '<table width="100%%" border="0" cellpadding="4" cellspacing="2">
			  <tr>
			    <td valign="top">%s</td>
			    <td>%s</td>
			  </tr>
			  <tr>
			    <td valign="top">%s</td>
			    <td>%s</td>
			  </tr>
			  <tr>
			    <td valign="top">%s</td>
			    <td>%s</td>
			  </tr>
			  <tr>
			    <td></td>
			    <td valign="top">%s</td>
			  </tr>
			</table>';
      
  public function __construct($metatag=null)
  {
  	if ($metatag != null && !$metatag instanceof W3sSearchEngine) throw new RuntimeException(sprintf('This function requires a W3sSearchEngine class object. You passed an instance of %s object', get_class($metatag)));
  	$this->metatag = $metatag;
  }
  
  public function render()
  {
	  $metaTitle = ($this->metatag != null) ? $this->metatag->getMetaTitle() : '';
    $metaKeywords = ($this->metatag != null) ? $this->metatag->getMetaKeywords() : '';
    $metaDescription = ($this->metatag != null) ? $this->metatag->getMetaDescription() : '';
	  return sprintf($this->skeleton, label_for('meta_title', __('Title:')), 
	  																input_tag('w3s_meta_title', $metaTitle, 'size=34'),
	  																label_for('meta_meta_keywords', __('Keywords:')),
	  																textarea_tag('w3s_meta_keywords', $metaKeywords, 'size=31x10'),
	  																label_for('meta_meta_description', __('Description:')),
	  																textarea_tag('w3s_meta_description', $metaDescription, 'size=31x10'),
	  																link_to_function(__('Store metatags'), 'currentTab.save()', 'class="link_button"'));
  }
}