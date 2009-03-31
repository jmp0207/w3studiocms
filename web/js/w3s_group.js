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

var w3sGroup = Class.create({
	add: function(){
    var hasFailed = false;
		var sActionPath = w3studioCMS.frontController + 'groupsManager/add';
    new Ajax.Updater('w3s_error', sActionPath,
        {asynchronous:true,
          evalScripts:false,
          onLoading:function()
	          {
	            curWindow.setHTMLContent('<br /><h1>ADDING NEW GROUP<br />Please Wait</h1>');
	          },
          onSuccess:function()
            {
              W3sControlPanel.refreshGroupsSelect();
            },
	     		onFailure:function()
		        { 
		          W3sWindow.closeModal();          
		          curWindow = W3sWindow.openModal(370, 210);
		          hasFailed = true;
		        },
          onComplete:function()
            {
              curWindow.setHTMLContent($('w3s_error').innerHTML); 
							if (!hasFailed) W3sWindow.closeModal();
            },
          parameters:'groupName=' + $('w3s_group_name').value +
                     '&idTemplate=' + $("w3s_templates_select").value
				});
    return false;    
  },

  edit: function(idGroup, idCurrentTemplate)
  {
    var hasFailed = false;
    var sActionPath = w3studioCMS.frontController + 'groupsManager/edit';
    var idTemplate = $("w3s_templates_select").value;
    new Ajax.Updater('w3s_error', sActionPath,
        {asynchronous:true,
          evalScripts:false,
          onLoading:function()
            {
              curWindow.setHTMLContent('<br /><h1>EDITING SELECTED GROUP<br />Please Wait</h1>');
            },
          onSuccess:function()
            {
              if (idTemplate != idCurrentTemplate )
              {
                W3sTemplate.loadEditorPage();
              }
              W3sControlPanel.refreshGroupsSelect();
            },
          onFailure:function()
            {
              W3sWindow.closeModal();
              curWindow = curWindow.openModal(370, 210);
              hasFailed = true;
            },
          onComplete:function()
            {
              curWindow.setHTMLContent($('w3s_error').innerHTML);
              if (!hasFailed) W3sWindow.closeModal();
            },
          parameters:'idGroup=' + idGroup +
                     '&groupName=' + $('w3s_group_name').value +
                     '&idTemplate=' + idTemplate});
    //}
    return false;
  },

  remove: function(sConfirmMessage){
    if(sConfirmMessage == undefined) sConfirmMessage = 'WARNING: If you delete the selected group, W3Studio will also delete all group\'s pages, all contents and metatags related with it: do you want to continue with deleting?';
    if (confirm(sConfirmMessage)) {
      var sActionPath = w3studioCMS.frontController + 'groupsManager/delete';      
      curWindow = W3sWindow.openModal(200, 100, false);
      new Ajax.Updater('w3s_error', sActionPath,
        {asynchronous:true,
         evalScripts:false,
          onLoading:function()
            {
              curWindow.setHTMLContent('<br /><h1>DELETING GROUP<br />Please Wait</h1>');
            },
	        onSuccess:function()
	          {
	            W3sControlPanel.refreshGroupsSelect(true);
	          },
	     		onFailure:function()
		        { 
		          W3sWindow.closeModal();          
		          curWindow = W3sWindow.openModal(270, 160);
		          hasFailded = true;         
		        },
	        onComplete:function()
	          {
	            curWindow.setHTMLContent($('w3s_error').innerHTML);   
	          },
         parameters:'idGroup=' + $("w3s_groups_select1").value});
    }
  }
});