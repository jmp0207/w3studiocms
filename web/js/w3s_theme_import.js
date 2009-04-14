/*
 * This file is part of the w3studioCMS package library and it is distributed 
 * under the LGPL LICENSE Version 2.1. To use this library you must leave 
 * intact this copyright notice.
 *  
 * (c) 2007-2008 Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 *  
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

var w3sThemeImport = Class.create({
  
  show: function()
	{
	  var sActionPath = w3studioCMS.frontController + 'themeImport/show';
		sAjaxOptions = {asynchronous:true,
	                  evalScripts:false,
	                  method:'get'};
		var curWindow = W3sWindow.openModal(470, 320, true);
		
	  curWindow.setAjaxContent(sActionPath, sAjaxOptions);
	  return false;
	},
	
	extract: function()
  {
    var sActionPath = w3studioCMS.frontController + 'themeImport/extract';
		new Ajax.Updater('w3s_template_import_contents', sActionPath,
      {asynchronous:true,
        evalScripts:false
       });    
  },
	
	add: function(themeName)
  {
	  var hasFailded = false;
	  var sActionPath = w3studioCMS.frontController + 'themeImport/add';
	  var curWindow = W3sWindow.openModal(320, 200, false);
	  new Ajax.Updater({success:'w3s_template_import_contents', failure:'w3s_error'}, sActionPath,
	    {asynchronous:true,
	     evalScripts:false,
	     onLoading:function()
	        {
	          curWindow.setHTMLContent('<br /><br /><br /><h1>ADDING THEME<br />Please Wait</h1>');
	        },
	     onFailure:function()
	        { 
	          hasFailded = true;        
	        },
	     onComplete:function()
	        {
	          
						if (!hasFailded)
						{
	          	curWindow.setHTMLContent($('w3s_error').innerHTML);
							W3sWindow.closeModal();         
	          }				
	        },        
	     parameters:'themeName=' + themeName});
	},

	remove: function(themeName)
  {
	  var hasFailded = false;
	  var sActionPath = w3studioCMS.frontController + 'themeImport/remove';
	  var curWindow = W3sWindow.openModal(320, 200, false);
	  new Ajax.Updater({success:'w3s_template_import_contents', failure:'w3s_error'}, sActionPath,
	    {asynchronous:true,
	     evalScripts:false,
	     onLoading:function()
	        {
	          curWindow.setHTMLContent('<br /><br /><br /><h1>REMOVING THEME<br />Please Wait</h1>');
	        },
	     onFailure:function()
	        {
	          hasFailded = true;
	        },
	     onComplete:function()
	        {

						if (!hasFailded)
						{
	          	curWindow.setHTMLContent($('w3s_error').innerHTML);
							W3sWindow.closeModal();
	          }
	        },
	     parameters:'themeName=' + themeName});
	}
});
