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
 * w3sTextEditorTinyMCE extends the w3sTextEditor to use the tinyMCE web-editor 
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sTextEditorTinyMCE
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
class w3sTextEditorTinyMCE extends w3sTextEditor
{
	protected
	  $standardCss = 
			'body {
        background-color: #FFFFFF;
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 10px;
        scrollbar-3dlight-color: #F0F0EE;
        scrollbar-arrow-color: #676662;
        scrollbar-base-color: #F0F0EE;
        scrollbar-darkshadow-color: #DDDDDD;
        scrollbar-face-color: #E0E0DD;
        scrollbar-highlight-color: #F0F0EE;
        scrollbar-shadow-color: #F0F0EE;
        scrollbar-track-color: #F5F5F5;
      }

      td {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 10px;
        border:1px dotted #aaa;
      }

      pre {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 10px;
      }

      thead {
        background-color: #FFBBBB;
      }

      tfoot {
        background-color: #BBBBFF;
      }

      th {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 13px;
      }';
	
	/**
   * Returns the current standard CSS
   */
	public function getStandardCss(){
    return $this->standardCss;
  }

	/**
   * Sets the current standard CSS
   */
  public function setStandardCss($value){
    $this->standardCss = $value;
  }
  
	/**
   * Writes the Stylesheet for the tinyMCE editor
   */
  public function writeStylesheet()
  {
    $group = $this->content->getW3sGroup();
    $template = $group->getW3sTemplate();
    $templateName = strtolower($template->getTemplateName());
    $projectName = strtolower($template->getW3sProject()->getProjectName());
    $classes = w3sTemplateEngine::findStylesheetClasses($this->content, 1);
    
    $css = $this->standardCss;
    foreach($classes as $class){
      $css .= $class . "\n";
    }

    $handle = fopen(sfConfig::get('sf_web_dir') . sfConfig::get('app_w3s_web_css_dir') . '/w3s_tinymce_stylesheet.css', 'w');
    fwrite($handle, $css);
    fclose($handle);
  }
}
