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

var w3sControlPanelTabs = Class.create({
  showSelectedTab: function(idTab, sElementTab){
    var tabs = $('w3s_control_panel_tabs_panel').getElementsByTagName('A');
    tabs = $A(tabs); 
    tabs.each(function(tab){
      if (tab.id == sElementTab){
        Element.addClassName(tab, 'w3s_active_tab');
      }
      else{
        Element.removeClassName(tab, 'w3s_active_tab');
      }
    });
    $('w3s_control_panel_content_panel').style.display = 'block';

    var panels = $('w3s_control_panel_content_panel').getElementsByClassName('w3s_tabs');
    panels = $A(panels); 
    panels.each(function(panel){ 
      panel.style.display = ((panel.id) == 'w3s_tab_' + idTab) ? 'block' : 'none';
    });
  },
	
	createTab: function(idTab, sElementTab)
	{
		switch(idTab)
		{
			case 1:
				currentTab = new w3sFileManager();
			 	break;
			case 2:			
				currentTab = new w3sSlotManager();
			 	break;
			case 3: 			
				currentTab = new w3sMetatags();
			 	break;
		}
		currentTab.show(idTab, sElementTab);
	}  
});

var w3sFileManager = Class.create(w3sControlPanelTabs, {
	show: function(idTab, sElementTab)
	{	
		this.showSelectedTab(idTab, sElementTab);
	}
});

var w3sSlotManager = Class.create(w3sControlPanelTabs, {
	show: function(idTab, sElementTab)
	{	
		this.showSelectedTab(idTab, sElementTab);
		this.load(idTab);
	},
	
	load: function(idTab){
	  bHasFailed = false;
	  if (curWindow != null) W3sWindow.closeModal();
	  var sActionPath = w3studioCMS.frontController + 'controlPanel/drawSlots';
	  new Ajax.Updater('w3s_tab_' + idTab, sActionPath,
	                  {asynchronous:true,
	                   evalScripts:true,
	                   onLoading:function()
	                      {
	                        curWindow = W3sWindow.openModal(220, 120, false);
	                        curWindow.setHTMLContent('<br /><h1>LOADING SLOTS<br />Please Wait</h1>');
	                      },                          
	                   onFailure:function(request, json)
	                      {
	                        bHasFailed = true;
	                      },
	                   onComplete:function(request, json)
	                      {
	                        if(json != null) W3sTools.updateJSON(request, json);
	                        W3sWindow.closeModal();
	                      },
	                   parameters:'lang=' + w3studioCMS.language +
	                              '&page=' + w3studioCMS.page}); 
	  return false;
	},
});

var w3sMetatags = Class.create(w3sControlPanelTabs, {
	show: function(idTab, sElementTab)
	{	
		this.showSelectedTab(idTab, sElementTab);
		this.load(idTab);
	},

	load: function(idTab){
	  if (curWindow != null) W3sWindow.closeModal();
	  var sActionPath = w3studioCMS.frontController + 'controlPanel/loadMetas';
	  new Ajax.Updater('w3s_tab_' + idTab, sActionPath,       
	                  {asynchronous:true,
	                   evalScripts:true,
	                   onLoading:function()
		                  {
		                    curWindow = W3sWindow.openModal(220, 120, false);
		                    curWindow.setHTMLContent('<br /><h1>LOADING METATAGS<br />Please Wait</h1>');
		                  },
	                   onComplete:function()
	            			  {                     
				                W3sWindow.closeModal();
	                    },
	                   parameters:'lang=' + w3studioCMS.language +
	                              '&page=' + w3studioCMS.page});  
	  return false;
	},
	
	save: function(){
	  var bHasFailed = false;
	  if (curWindow != null) W3sWindow.closeModal();
	  var sActionPath = w3studioCMS.frontController + 'controlPanel/saveMetas';
	  new Ajax.Updater('w3s_error', sActionPath,
	                  {asynchronous:true,
	                   evalScripts:true,
	                   onLoading:function()
	                      {
	                        curWindow = W3sWindow.openModal(220, 120, false);
	                        curWindow.setHTMLContent('<br /><h1>SAVING METATAGS<br />Please Wait</h1>');
	                      },                          
	                   onFailure:function(request, json)
	                      {
	                        W3sWindow.closeModal();
	                        curWindow = W3sWindow.openModal(370, 210);
	                        bHasFailed = true;
	                      },
	                   onComplete:function(request, json)
	                      {
	                        curWindow.setHTMLContent($('w3s_error').innerHTML);
	                        if (!bHasFailed) W3sWindow.closeModal();
	                      },
	                   parameters:'title=' + $('w3s_meta_title').value +
	                              '&keywords=' + $('w3s_meta_keywords').value +
	                              '&description=' + $('w3s_meta_description').value +
	                              '&lang=' + w3studioCMS.language +
	                              '&page=' + w3studioCMS.page});
	  return false;
	}
});

