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

var w3sPage = Class.create({
	add: function()
	{
    var hasFailed = false;
		var sActionPath = w3studioCMS.frontController + 'pagesManager/add';
    new Ajax.Updater('w3s_error', sActionPath,
                    {asynchronous:true,
                     evalScripts:true,
                     onLoading:function()
                        {
                          if (curWindow == null) curWindow = W3sWindow.openModal(220, 120, false);
                          curWindow.setHTMLContent('<br /><h1>ADDING PAGE<br />Please Wait</h1>');
                        },
										 onSuccess:function()
                        {
                          W3sControlPanel.listPages();
                        },
										 onFailure:function()
                        {
                          hasFailed = true;
                        },
                     onComplete:function(request, json)
                        {
											 		if (hasFailed) 
													{
														curWindow.setHTMLContent($('w3s_error').innerHTML);
														//W3sPage = new w3sPage();
													}
                       },
                       parameters:'pageName=' + $('w3s_page_name').value +
                                  '&idGroup=' + $("w3s_groups_select").value +
                                  '&idLanguage=' + w3studioCMS.language}); 
    return false;
  },

  remove: function(idPage, curPage, sConfirmMessage)
	{
    if(sConfirmMessage == null) sConfirmMessage = 'WARNING: If you delete this page, W3Studio will also delete all contents and metatags related with it: do you want to continue with deleting?';
    if (confirm(sConfirmMessage)) {
      var sActionPath = w3studioCMS.frontController + 'pagesManager/delete';
      curWindow = W3sWindow.openModal(200, 100, false);
      new Ajax.Updater('w3s_page_list', sActionPath,
        {asynchronous:true,
         evalScripts:false,
         onLoading:function()
            {
              curWindow.setHTMLContent('<br /><h1>DELETING PAGE<br />Please Wait</h1>');              
            },
         onComplete:function(request, json)
            {
              W3sWindow.closeModal();
            },
         parameters:'idPage=' + idPage +
                    '&curPage=' + w3studioCMS.language + idPage +
                    '&curLang=' + w3studioCMS.language});
    }
  },

  renamePage: function(idPage, newName)
	{
    var sActionPath = w3studioCMS.frontController + 'pagesManager/rename';
    curWindow = W3sWindow.openModal(200, 100, false);
    new Ajax.Updater('w3s_page_list', sActionPath,
      {asynchronous:true,
       evalScripts:false,
       onLoading:function()
          {
            curWindow.setHTMLContent('<br /><h1>RENAMING PAGE<br />Please Wait</h1>');
          },
       onComplete:function(request, json)
          {
            W3sWindow.closeModal();
          },
       parameters:'idPage=' + idPage +
                  '&newName=' + newName +
                  '&curPage=' + w3studioCMS.language + w3studioCMS.page +
                  '&curLang=' + w3studioCMS.language});

  }
});