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

var w3sLanguage = Class.create({
	add: function(){
	  var hasFailed = false;
	  var isMain = $('w3s_main_language').checked ? 1 : 0;
	  var sActionPath = w3studioCMS.frontController + 'languagesManager/add';
	  new Ajax.Updater('w3s_error', sActionPath,
	                  {asynchronous:true,
	                   evalScripts:false,
	                   onLoading:function()
	                      {
	                        if (curWindow == null) curWindow = W3sWindow.openModal(320, 180, false);
	                        curWindow.setHTMLContent('<br /><h1>ADDING LANGUAGE<br />This operation can take a while.<br />Please Wait</h1>');
	                      },
										 onSuccess: function()
										    {
											 		W3sLanguage.refreshLanguages();
											  },
	                   onFailure:function()
	                      { 
	                        curWindow.closeModal();
	                        curWindow = W3sWindow.openModal(320, 180);
	                        hasFailed = true;
	                      },
	                   onComplete:function(request, json)
	                      {
	                        curWindow.setHTMLContent($('w3s_error').innerHTML);
													if (!hasFailed) W3sWindow.closeModal();
	                      },
	                   parameters:'languageName=' + $('w3s_language_name').value +
	                              '&isMain=' + isMain});
	  return false;
	},
	
	edit: function(){
	  var hasFailed = false;
	  var isMain = $('w3s_main_language').checked ? 1 : 0;
	  var sActionPath = w3studioCMS.frontController + 'languagesManager/edit';
	  new Ajax.Updater('w3s_error', sActionPath,
	                  {asynchronous:true,
	                   evalScripts:false,
	                   onLoading:function()
	                      {
	                        if (curWindow == null) curWindow = W3sWindow.openModal(220, 120, false);
	                        curWindow.setHTMLContent('<br /><h1>EDITING LANGUAGE<br />Please Wait</h1>');
	                      },
										 onSuccess: function()
										    {
											 		W3sLanguage.refreshLanguages();
											  },
	                   onFailure:function()
	                      { 
	                        W3sWindow.closeModal();
	                        curWindow = W3sWindow.openModal(320, 180);
	                        hasFailded = true;
	                      },
	                   onComplete:function(request, json)
	                      {
	                        curWindow.setHTMLContent($('w3s_error').innerHTML);
													if (!hasFailed) W3sWindow.closeModal();
	                      },
	                   parameters:'languageName=' + $('w3s_language_name').value +
	                              '&isMain=' + isMain +
	                              '&idLanguage=' + $('w3s_languages_select').value});
	  return false;
	},
	
	remove: function(sConfirmMessage){
	  var hasFailded = false;
	  if(sConfirmMessage == null) sConfirmMessage = 'WARNING: If you delete this language, W3Studio will also delete all contents and metatags related with it: do you want to continue with deleting?';
	  if (confirm(sConfirmMessage)) {
	    var sActionPath = w3studioCMS.frontController + 'languagesManager/delete';
			new Ajax.Updater('w3s_error', sActionPath,
	      {asynchronous:true,
	       evalScripts:false,
	       onLoading:function()
	          {
	            curWindow = W3sWindow.openModal(220, 120, false);
	            curWindow.setHTMLContent('<br /><h1>DELETING LANGUAGE<br />Please Wait</h1>');
	          },
				 onSuccess: function()
				    {
					 		W3sLanguage.refreshLanguages();
					  },						
	       onFailure:function()
	          {
	             W3sWindow.closeModal();
	             curWindow = W3sWindow.openModal(320, 180);
	             hasFailded = true;
	          },
	       onComplete:function(request, json)
	          {
	            curWindow.setHTMLContent($('w3s_error').innerHTML);
							if (!hasFailded) W3sWindow.closeModal();
	          },
	       parameters:'idLanguage=' + $('w3s_languages_select').value});
	  }
	},
	
	refreshLanguages: function()
	{
		var sActionPath = w3studioCMS.frontController + 'languagesManager/refreshLanguages';
		new Ajax.Updater('w3s_languages', sActionPath,
	                  {asynchronous:true,
	                   evalScripts:false});
	}
});