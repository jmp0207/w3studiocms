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

var w3sContent = Class.create({
	initialize: function()
	{
		this.moveCounter = 400;
	},
	
	addContent: function(iType){ 
	  var hasFailded = false;
	  var sActionPath = w3studioCMS.frontController + 'contentsManager/add';
	  if (iType == undefined) iType = 2;
	  var curWindow = W3sWindow.openModal(320, 200, false);
	  new Ajax.Updater({success:InteractiveMenu.slotName, failure:'w3s_error'}, sActionPath,
	    {asynchronous:true,
	     evalScripts:true,
	     onLoading:function()
	        {
	          curWindow.setHTMLContent('<br /><br /><br /><h1>ADDING NEW CONTENT<br />Please Wait</h1>');	          
	        },
	     onSuccess:function()
	        { 
	          new Effect.Fade('w3s_im_actions', {});
	        },
	     onFailure:function()
	        { 
	          W3sWindow.closeModal();          
	          curWindow = W3sWindow.openModal(370, 210);
	          hasFailded = true;         
	        },
	     onComplete:function()
	        {
	          if (hasFailded)
	          {
	            curWindow.setHTMLContent($('w3s_error').innerHTML);            
	          }
	          else{
	            W3sWindow.closeModal();	            
	          }
	          InteractiveMenu.stop = false;   
	        },        
	     parameters:'page=' + w3studioCMS.page +
	                '&language=' + w3studioCMS.language +
	                '&idSlot=' + InteractiveMenu.idSlot +
	                '&idContent=' + InteractiveMenu.idContent +
	                '&contentType=' + iType}); 
	},
	
	editContent: function(sText, sAddictionalParam, sModule){ 
	  sText = encodeURIComponent(sText); 
		sAddictionalParam = (sAddictionalParam != undefined) ? '&' + sAddictionalParam : '';
		if (sModule == undefined) sModule = 'contentsManager';
		
	  var sActionPath = w3studioCMS.frontController + sModule + '/edit';
	  var hasFailded = false;
	  var curWindow = W3sWindow.openModal(200, 100, false);
	  var elementName = 'w3sContentItem_' + InteractiveMenu.idContent;  
	  new Ajax.Updater({success:elementName, failure:'w3s_error'}, sActionPath,
	    {asynchronous:true,
	     evalScripts:false,
	     onLoading:function()
	        {          
	          curWindow.setHTMLContent('<br /><h1>EDITING CONTENT<br />Please Wait</h1>');
	        },
	     onSuccess:function()
	        { 
	          curWindow.setHTMLContent('<br /><h1>EDITING SUCCESS</h1>');
	          W3sWindow.closeModal(); 
	          Position.clone($(elementName), $('w3s_im_clone_element'));
	        },
	     onFailure:function()
	        { 
	          W3sWindow.closeModal(); 
	          curWindow = W3sWindow.openModal(370, 210);
	          hasFailded = true;
	        },
	     onComplete:function()
	        {  
	          if (hasFailded){
	            curWindow.setHTMLContent($('w3s_error').innerHTML);            
	          }
	        },
	     parameters:'page=' + w3studioCMS.page +
	                '&language=' + w3studioCMS.language +
	                '&idSlot=' + InteractiveMenu.idSlot +
	                '&idContent=' + InteractiveMenu.idContent +
	                '&contentType=' + InteractiveMenu.idContentType +
	                '&content=' + sText + sAddictionalParam
	                });
	  return false; 
	},
	
	deleteContent: function(){	    
	    var sActionPath = w3studioCMS.frontController + 'contentsManager/delete';
	  	var hasFailded = false;
	  	var curWindow = W3sWindow.openModal(200, 100, false);
	    new Ajax.Updater({success:InteractiveMenu.slotName,failure:'w3s_error'}, sActionPath,
	      {asynchronous:true,
	       evalScripts:true,
	       onLoading:function()
	        {
	          curWindow.setHTMLContent('<br /><h1>DELETING CONTENT<br />Please Wait</h1>');
	        },
	       onFailure:function()
	        { 
	          W3sWindow.closeModal();
	          curWindow = W3sWindow.openModal(370, 210);
	          hasFailded = true;
	        },
	       onSuccess:function(request, json)
	        {
	          W3sWindow.closeModal();
	          new Effect.Fade('w3s_im_actions', {});
	        },
	       onComplete:function()
	        {
	          if (hasFailded){
	            curWindow.setHTMLContent($('w3s_error').innerHTML);            
	          }
	        },
	       parameters:'page=' + w3studioCMS.page +
	                  '&language=' + w3studioCMS.language +
	                  '&idSlot=' + InteractiveMenu.idSlot +
	                  '&idContent=' + InteractiveMenu.idContent});
	},
	
	moveContents: function(idSlot, slotName, params)
	{   
	  var hasFailed = false;
		var sActionPath = w3studioCMS.frontController + 'contentsManager/move';
		var curWindow = W3sWindow.openModal(200, 100, false);
		
		curWindow.showCenter(false, 200, this.moveCounter);
		this.moveCounter += 300;
		if (this.moveCounter == 1000) this.moveCounter = 400;
    new Ajax.Updater('w3s_error', sActionPath,
    {asynchronous:true,
     evalScripts:true,
     onLoading:function()
        {
          curWindow.setHTMLContent('<br /><h1>MOVING CONTENT<br />Please Wait</h1>');
        },
     onFailure:function()
        { 
          hasFailed = true;
        },
     onComplete:function()
        {  
					curWindow.setHTMLContent($('w3s_error').innerHTML);
					if (!hasFailed) 
					{
		  			curWindow.close();
				  }
        },
     parameters:'page=' + w3studioCMS.page +
                '&language=' + w3studioCMS.language +
								'&slotId='  + idSlot +
								'&slotName=' + slotName +
								'&' + params});
	}
	
});



/* This function is called one time if user sorts the contents of one
 * slot, two times if user moves the contents from a slot to another.
 */