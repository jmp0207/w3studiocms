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

  use_helper('Javascript');

  echo javascript_tag('
    tinyMCE_GZ.init({
      plugins : "safari,pagebreak,table,inlinepopups,template,style,save,advlink,table,advhr,advimage,contextmenu,paste,directionality,noneditable,nonbreaking,xhtmlxtras,image,fullscreen,media",
      themes : "simple,advanced",
      languages : "en",
      disk_cache : true,
      debug : false
    });');
    
  echo javascript_tag('
    tinyMCE.init({
      mode : "none",
      elements : "w3s_tmce",
      theme : "advanced",
      plugins : "safari,pagebreak,table,inlinepopups,template,style,save,advimage,advlink,table,advhr,contextmenu,paste,directionality,noneditable,nonbreaking,xhtmlxtras,image,fullscreen,media",
  
      theme_advanced_buttons1 : "code,save,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect",
      theme_advanced_buttons2 : "cut,copy,paste,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,sub,sup,|,link,unlink,anchor,|,image,media",
      theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,fullscreen",
      theme_advanced_buttons4 : "",
      theme_advanced_toolbar_location : "top",
      theme_advanced_toolbar_align : "left",
      theme_advanced_statusbar_location : "bottom",
      theme_advanced_resizing : true,
      content_css : "' .sfConfig::get('app_w3s_web_css_dir') . '/w3s_tinymce_stylesheet.css",

      external_link_list_url : "' . url_for('tinyMCE/displayLinks?lang=' . $idLanguage) . '",
      external_image_list_url : "' . url_for('tinyMCE/displayImageDir') . '",
      media_external_list_url : "/js/mce_lists/media_list.js"
    });');    //