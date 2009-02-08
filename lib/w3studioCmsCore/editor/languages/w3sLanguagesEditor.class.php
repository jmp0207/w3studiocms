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
 * w3sLanguagesEditor implements the editor to manage the site's languages
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sLanguagesEditor
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sLanguagesEditor implements w3sEditor
{
  protected 
  	$language,
  	$editorSkeleton = 						
     '<div id="w3s_pages_form">
			  <fieldset>
			    <table width="100%%" border="0" cellpadding="4" cellspacing="2">
			      <tr>
			        <td>%s</td>
			        <td>%s</td>
			      </tr>			      
			      <tr>
			        <td>%s</td>
			        <td>%s</td>
			      </tr>
			      <tr>
			        <td></td>
			        <td height="30">%s</td>
			      </tr>
			    </table>
			  </fieldset>
				%s
			</div>';
			
  public function __construct($language = null)
  {
  	$this->language = $language;
  }
		
	/**
   * Renders the editor
   * 
   * @return string
   *
   */ 
  public function render()
  {     
    $idLanguage = 0; 
		$isMain = 0;
		$languageName = '';
    if ($this->language != null)
    {
    	$idLanguage = $this->language->getId(); 
    	$isMain = $this->language->getMainLanguage();
    	$languageName = $this->language->getLanguage();
    }
    
    $setEnabled = ($idLanguage == 0 || ($idLanguage != 0 && $isMain == 0)) ? '' : 'DISABLED';
    $function = ($idLanguage == 0) ? link_to_function(__('Add Language'), 'W3sLanguage.add()', 'class="link_button"') : link_to_function(__('Edit Language'), 'W3sLanguage.edit()', 'class="link_button"');
    $additionalInfo = ($idLanguage == 0) ? '<div id="w3s_message"><p class="error_message">' . __('PAY ATTENTION: This operation will also insert all contents for the new language. These contents will be copied from the main language of your website.') . '</p></div>' : '';
    $checked = ($isMain == 1) ? 'CHECKED' : '';
    return sprintf($this->editorSkeleton, label_for('language_name', __('Language name:')), 
    																			input_tag('w3s_language_name', $languageName),
    																			label_for('main_language', __('Main language:')),
    																			sprintf('<input name="w3s_main_language" id="w3s_main_language" %s type="checkbox" %s />', $setEnabled, $checked),
																					$function,
																					$additionalInfo);
  }  
}