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

var W3studioCMS = Class.create({

	initialize: function(frontController, page, language) 
	{  
		  this.frontController = frontController;
	    this.page = page;
	    this.language = language;
	    
	    W3sTools = new w3sTools(); 
	    W3sTemplate = new w3sTemplate();
	    W3sMenuManager = new w3sMenuManager();
	    W3sControlPanel = new w3sControlPanel(); 
	    W3sWindow = new w3sWindow();
			W3sTemplateImport = new w3sTemplateImport();
	    $('w3s_menu_manager_hidden').observe('mouseover', W3sMenuManager.show, false);
	    this.initW3Studio();
	},
	
	initW3Studio: function (){ 
	  $('w3s_menu_manager').hide();
	  sAjaxOptions = {asynchronous:true,
	                  evalScripts:false,
	                  onComplete:function()
	                    {
	                      w3studioCMS.W3StudioCMSLoader();
	                      w3studioCMS.W3StudioStructureLoader();
	                    }
	                 };
	  curWindow = W3sWindow.openModal(250, 130, false);
	  curWindow.setAjaxContent(this.frontController + 'webEditor/showLoader', sAjaxOptions);
	  
	  new W3sTools.centerElement('w3s_menu_manager_hidden', 0);
	  new W3sTools.centerElement('w3s_menu_manager', 0);	  
	     
	  return false;
	},
	
	// Loads the editor page from webSite/index
	W3StudioCMSLoader: function ()
  {
    try
    {  
	    var sActionPath = this.frontController + 'webEditor/loadPage?lang=' + this.language + '&page=' + this.page + '&prevPage=';
      
	    // We need a DIV to store an eventually error that may occoured during loading. Here it is creating dinamically
	    var objBody = document.getElementsByTagName("body").item(0);
	    var objLoadingError = document.createElement("div");
	    objLoadingError.setAttribute('id','w3s_loading_error');
	    objLoadingError.style.display = 'none';
	    objBody.appendChild(objLoadingError);
	
	    new Ajax.Updater({success:'w3s_cms',failure:'w3s_loading_error'}, sActionPath,
	        {asynchronous:true,
	          evalScripts:true,
	          onComplete:function(request, json)
	            {	            
	              if (json[0][1] == 1){ 
                  W3sTemplate.currentCss = json[1][1];
	                W3sTools.temaChange(json[1][1]);
	                Element.removeClassName($('w3s_img_editor'), 'img_waiting');
	                Element.addClassName($('w3s_img_editor'), 'img_done');
	
	                // Verifies if CMS is loaded
	                w3studioCMS.isCMSLoaded();
	              }
	              else{
	                curWindow.setHTMLContent($('w3s_loading_error').innerHTML);	
	              }
	            }});
	  }
	  catch(e){
      alert(e);
	  }
	
	  return false;
	},
	
	// Loads the editor structure
	W3StudioStructureLoader: function (){  	    
	    var sActionPath = this.frontController + 'controlPanel/index'; 
	    new Ajax.Updater('w3s_control_panel', sActionPath,
	        {asynchronous:true,
	          evalScripts:false,
	          onComplete:function()
	            {
	              // Set the class that indicates that the module is loaded. IE needs to remove the class
	              Element.removeClassName($('w3s_img_structure'), 'img_waiting');
	              Element.addClassName($('w3s_img_structure'), 'img_done');
	
	              // Verifies if CMS is loaded
	              w3studioCMS.isCMSLoaded();
	            },
	          parameters:'page=' + this.page +
	                     '&lang=' + this.language});
	
	  return false;
	},
	
	// Verifies if CMS is loaded
	isCMSLoaded: function (){
	  try{
	    // Retrieves from the DIV w3s_cms_loader all the elements with class img_waiting
	    var x =  $('w3s_cms_loader').getElementsByClassName('img_waiting');
	
	    // If no one founded, the CMS has been loaded so W3StudioCMS can hide the popup
	    if(x.length == 0){
	      W3sWindow.closeModal();
	    }
	  }
	  catch(e){
	  }
	},
	
	// Closes W3StudioCMS from the executeSignout action of sfGuard module
	logout: function(){
	  var sActionPath = this.frontController + 'sfGuardAuth/signout'; 
	  var curWindow = W3sWindow.openModal(200, 100, false);
	  new Ajax.Request(sActionPath,
	      {asynchronous:true,
	        evalScripts:false,
	        onLoading:function(request, json)
	          {
	            curWindow.setHTMLContent('<br /><h1>LEAVING W3StudioCMS<br />Please Wait</h1>');	            
	          },   
	        onComplete:function(request, json)
	          {
	            W3sWindow.closeModal();
	            location.href = json[0][1];
	          },
	          parameters:'page=' + this.page +
	                     '&lang=' + this.language});
	  return false;
	}
});
