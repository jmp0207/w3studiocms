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

var w3sTemplate = Class.create({

  initialize: function()
  {
    this.currentCss = '';
  },

	// Loads the editor page from webEditor/loadPage
	loadEditorPage: function(newlanguage, newPage)
  {
	  if (newlanguage == undefined) newlanguage = w3studioCMS.language;
	  if (newPage == undefined) newPage = w3studioCMS.page;
	  var prevPageValue = (w3studioCMS.page != undefined) ? w3studioCMS.page : '';
	  var sActionPath = w3studioCMS.frontController + 'webEditor/loadPage';	  
	  new Ajax.Updater({success:'w3s_cms',failure:'w3s_error'}, sActionPath,
	      {asynchronous:true,
	        evalScripts:true,
	        onLoading:function()
	          {
	            /* When user changes page or language it is necessary to hide
	             * the iteractive menu, otherwise it can be placed under the
	             * contents 
	             */            
	            InteractiveMenu.hide();
	            var curWindow = W3sWindow.openModal(200, 100, false);
	            curWindow.setHTMLContent('<br /><h1>LOADING EDITOR PAGE<br />Please Wait</h1>');
	            //curWindow.setSize(130,130).show(true).center({auto: true});
	          },
	        onSuccess:function()
	          {
	            w3studioCMS.language = newlanguage;
	            w3studioCMS.page = newPage;
	          },
	        onFailure:function()
	          {
	            curWindow.setSize(230, 120);
	            curWindow.setHTMLContent($('w3s_error').innerHTML);	            
	          }, 
	        onComplete:function(request, json)
	          { 
	            if (json[0][1] == 1){   
	              W3sTemplate.currentCss = json[1][1];
                W3sTools.temaChange(json[1][1]);
	              W3sMenuManager.load('tbMenuManager');
	              W3sWindow.closeModal(); 
	              W3sControlPanel.listPages();
	            }
	            else{
	              curWindow.setSize(230, 120);
	              curWindow.setHTMLContent($('w3s_error').innerHTML);
	            }
	          },
	        parameters:'page=' + newPage +
	                   '&lang=' + newlanguage +
                     '&prevPage=' + prevPageValue});
	  return false;
	},
	
	// Loads the preview page from webPreview/index
	loadPreviewPage: function(newlanguage, newPage)
  {
	  if (newlanguage == undefined) newlanguage = w3studioCMS.language;
	  if (newPage == undefined) newPage = w3studioCMS.page;
	  var paramPrevPage = (w3studioCMS.page != undefined) ? '&prevPage=' + w3studioCMS.page : '';
	  var sActionPath = w3studioCMS.frontController + 'webEditor/preview';	 
	  new Ajax.Updater({success:'w3s_cms', failure:'w3s_error'}, sActionPath,
	      {asynchronous:true,
	       evalScripts:true,
	       onLoading:function()
	          {
	            InteractiveMenu.hide();
	            var curWindow = W3sWindow.openModal(200, 100, false);
	            curWindow.setHTMLContent('<br /><h1>LOADING PREVIEW PAGE<br />Please Wait</h1>');
	            //curWindow.setSize(130,130).show(true).center({auto: true});
	          }, 
	       onComplete:function(request, json)
	          {
	            if (json[0][1] == 1){ 
	              W3sTemplate.currentCss = json[1][1];
                W3sTools.temaChange(json[1][1]);
	              W3sMenuManager.load('tbMenuManagerPreview');
	              W3sWindow.closeModal();
	            }
	            else{
	              curWindow.setSize(230, 120);
	              curWindow.setHTMLContent($('w3s_error').innerHTML);
	            }
	          },
	        onSuccess:function()
	          {
	            w3studioCMS.language = newlanguage;
	            w3studioCMS.page = newPage;
	          },
	        parameters:'page=' + newPage +
	                   '&lang=' + newlanguage +
	                   paramPrevPage});
	  return false;
	},
	
	publish: function(sMessage)
  {
	  if(confirm(sMessage)){ 
	    var sActionPath = w3studioCMS.frontController + 'webEditor/publish';
	    new Ajax.Updater('w3s_error', sActionPath,
	      {asynchronous:true,
	       evalScripts:false,
	       onLoading:function()
	        {
	          curWindow = W3sWindow.openModal(250, 120, false);
	          curWindow.setHTMLContent('<br /><h1>PUBLISHING WEB SITE.<br />This operation can take a while <br /><br />Please Wait</h1>');	          
	        },
	       onComplete:function()
	        {
	          curWindow.setHTMLContent($('w3s_error').innerHTML);
	          W3sWindow.closeModal();
	        }});
	  }
	
	  return false;
	},
	
	moveContents: function(move)
  {
		if (move){
	    W3sContent = new w3sContent();
			InteractiveMenu.stop = true;
	    $('w3s_interactive_menu').style.display='none';
	    $('w3s_im_commands').style.display='none';
			W3sMenuManager.load('tbMenuManagerMove');
	  }
	  else{
	    W3sContent = null;
			InteractiveMenu.stop = false;
	    $('w3s_interactive_menu').style.display='block';
	    $('w3s_im_commands').style.display='block';
			W3sMenuManager.load('tbMenuManager');
	  }
	}
});
