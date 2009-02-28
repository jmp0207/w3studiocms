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
 * Template class represents the page's template.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sSlotTemplateEditor
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sSlotTemplateEditor extends w3sTemplateEngineEditor
{  
	
	/**
   * Returns the available classes for a given slot. Can retrieve only the class
   * name or the full CSS style. This is made with the mode parameter 
   *
   * @param      str  The slot's name.
   * @param      int  optional 0 retrieves only the class name [Default]
   *                           1 Retrieve the full css style
   * 
   * @return     array  The found classes 
   */
	public function findStylesheetClasses($slotName, $mode=0){
   
    // This is only a paliative solution. Hope someone can fix the parse class: I don't know Call-time pass-by-reference
    ini_set('error_reporting', 'E_ERROR');
    
    require_once(dirname(__FILE__).'/../tools/parser/htmlparser.inc');
    require_once(dirname(__FILE__).'/../tools/parser/common.inc');

    // Opens the template and parses its structure
    $templateFile = sfConfig::get('app_w3s_web_templates_dir') . '/' . $this->projectName . '/' . $this->templateName . '/' . $this->templateName . '.php';
    $p=new HtmlParser($templateFile, unserialize(Read_File("parser/htmlgrammar.cmp")), $templateFile, 1);
    $p->Parse();
    $src="";
    GetPageSrc($p->content,$src);

    ob_start();
    PrintArray($p->content);
    $contents = ob_get_clean();

    // Finds the id of Slots 
    $i=1;
    $elements = array($slotName);
    while(1)
    {
      preg_match('/(.*)\[content\].*\[pars\]\[id\]\[value\]=' . $slotName . '/', $contents, $res);
      if (count($res) == 0) break;
      $startKey = str_replace("[", "\[", $res[1]);
      $startKey = str_replace("]", "\]", $startKey);
      preg_match('/' . $startKey . '\[pars\]\[id\]\[value\]=(.*)/', $contents, $res);
      $elements[] = $res[1];
      $slotName = $res[1];
      $i++;
      
      // Prevents blocks if an infinite loop occours if a non well-format template is searched
      if ($i==100) break;
    } 

    // Finds all the template's stylesheets 
    $fp = fopen ($templateFile, "r");
    $templateContents = fread($fp, filesize($templateFile));    
    fclose($fp);
    $templateContents = str_replace("\r\n", "", $templateContents);   
    preg_match_all('/.*?rel=["|\']stylesheet["|\'].*?href\s*=\s*["|\'](.*?)["|\'].*?/', $templateContents, $stylesheets);
    
    // Creates a single stylesheet from the stylesheets retrieved
    $contents = '';
    foreach ($stylesheets[1] as $stylesheet)
    { 
      $stylesheet = substr($stylesheet, 1, strlen($stylesheet));
      $fp = fopen ($stylesheet, "r");
      $currentContent = fread($fp, filesize($stylesheet)); 
      fclose($fp);     
      $currentContent = str_replace("\r\n", "", $currentContent);
      $currentContent = preg_replace('/HTML>.*?}+?/', '', $currentContent);
      $contents .= $currentContent;      
    }

    // Find classes from xhtml elements
    $result = ($mode == 0) ? array('w3sNone' => 'None') : array();
    foreach($elements as $element)
    {
      $expression = ($mode == 0) ? '/#' . trim($element) . '[a-zA-Z0-9-_:\s]*\.(.*?)\{+?/' : '/#' . trim($element) . '[a-zA-Z0-9-_:\s]*(\..*?\{.*?\})+?/'; 
      preg_match_all($expression, $contents, $classes); 
      foreach($classes[1] as $class)
      {
        if ($mode == 0)
        {
	        $result[$class] = $class;
        }  
        else
        {
	        $result[] = $class;
        }
      }
    }

    // Find classes not associated to xhtml elements
    $expression = ($mode == 0) ? '/(^|})\.(.*?)\{+?/' : '/(^|})(\..*?\{.*?\})+?/';
    preg_match_all($expression, $contents, $classes); 
    foreach($classes[2] as $class)
    {
      if ($mode == 0)
      {
	      $result[$class] = $class;
	    }  
	    else
	    {
	      $result[] = $class;
	    }
    }
   	
    return $result;
  }
}