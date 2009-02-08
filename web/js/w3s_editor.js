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


var editor = Class.create({
	
	initialize: function(idContent, idContentType, idSlot)
	{
	  this.idContent = idContent; 
	  this.idContentType = idContentType;
	  this.idSlot = idSlot;	  
	},
	
	openEditor: function(){		 
	  var sActionPath = w3studioCMS.frontController + 'webEditor/openEditor';
	  var hasFailded = false; 
	  
	  var curWindow = W3sWindow.openModal(370, 230, false);
	  new Ajax.Updater({success:'w3s_im_editor', failure:'w3s_error'}, sActionPath,
	      {asynchronous:true,
	        evalScripts:true,
	        onLoading:function()
	        {
	          var sMessage = (this.idContentType != 6) ? '<br /><br /><br /><br /><h1>LOADING EDITOR<br />Please Wait</h1>' : '<br /><h1>UPDATING LANGUAGES<br />Please Wait</h1>';
	          curWindow.setHTMLContent(sMessage);
	        },
	        onFailure:function()
	        {
	          hasFailded = true;
	          bStopMenu = false;
	        },
	        onComplete:function(request, json)
	        {
	          if (!hasFailded){
	            W3sWindow.closeModal(); 
	            if (currentEditor.idContentType != 6) {
	              $('w3s_im_commands').style.display='none';
	              $('w3s_im_editor').style.display='block';
	  
	              var objElement = $('w3sContentItem_' + currentEditor.idContent);
	              var iWidth = (objElement.offsetWidth > 460) ? objElement.offsetWidth : 460;
	              var iHeight = (objElement.offsetHeight > 180) ? objElement.offsetHeight + 74 : 250;
	  			  
	  			  		currentEditor.show(iWidth, iHeight);
	  			 
	              var offsets = Position.cumulativeOffset($('w3s_im_editor'));
	              var offsetLeft = offsets[0];
	              
	              // Displays commands on the left side of Interactive menu
	              if (offsetLeft + Element.getWidth('w3s_im_editor') > screen.width){ 
	                $('w3s_interactive_menu').setStyle({
	                  left: offsetLeft - iWidth  + 'px'
	                });
	              }
	  
	              new Effect.BlindDown('w3s_im_editor', {});
	            }
	          }
	          else{
	            curWindow.setHTMLContent($('w3s_error').innerHTML);  
	          }
	        },
	        parameters:'idContent=' + this.idContent +
	                   '&contentType=' + this.idContentType +
	                   '&page=' + w3studioCMS.page +
	                   '&language=' + w3studioCMS.language +
	                   '&idSlot=' + this.idSlot});
	},
	
	close: function()
	{	
		//$('w3s_btn_close').stopObserving('click', currentEditor.close);		  
  	InteractiveMenu.closeEditor();
  	currentEditor = null;
  	W3sContent = null;
  	
  	return false;
	}
});

var textEditor = Class.create(editor, {
	
	initEditor: function()
	{
	    W3sContent = new w3sContent(); 
	    //$('w3s_btn_close').observe('click', currentEditor.close);	
	  		    
	    return false;
	},
	
	show: function(iWidth, iHeight)
	{	
		$('w3s_tmce').style.width = iWidth + 'px';
	    $('w3s_tmce').style.height = iHeight + 'px';
	    $('w3s_im_editor').style.width = (iWidth + 12) + 'px';
	    $('w3s_im_editor').style.height = ($('w3s_editor_container').getHeight() + 8) + 'px';
	    try{ 
				tinyMCE.execCommand('mceAddControl', false, 'w3s_tmce');
	      currentEditor.initEditor();
	    }
	    catch(e){ 
	      alert('TinyMCE was not loaded. Verify that it is correctly installed and its initialization script is correctly setted');
	    }
	  		    
	    return false;
	},
	
	edit: function()
	{
		sText = tinyMCE.get('w3s_tmce').getContent();
		W3sContent.editContent(sText);
		
		return false;
	},
	
	close: function()
	{	
		//$('w3s_btn_close').stopObserving('click', currentEditor.close);		
		tinyMCE.execCommand('mceRemoveControl', false, 'w3s_tmce');  
  	InteractiveMenu.closeEditor();
  	currentEditor = null;
  	W3sContent = null;
  	
  	return false;
	}
});

var imageEditor = Class.create(editor, {
	
	initEditor: function()
	{
	    W3sContent = new w3sContent(); 
	    ImageManager = new imageManager(); 
	    W3sTools.bOpenFileUploader = W3sTools.openFileUploader.bind(W3sTools, 1); 
	    //$('w3s_btn_close').observe('click', currentEditor.close);	
	    $('w3s_file_uploader_btn').observe('click', W3sTools.bOpenFileUploader);
	    $('w3s_delete_image_btn').observe('click', ImageManager.deleteSelectedImage);
	    $('w3s_insert_image_btn').observe('click', this.insertImage);
	  		    
	    return false;  
	},
	
	show: function(iWidth, iHeight)
	{	
		Position.clone($('w3s_image_manager'), $('w3s_im_editor'));
		currentEditor.initEditor();
	  		    
	  return false;  
	},
	
	close: function()
	{	
		//$('w3s_btn_close').stopObserving('click', currentEditor.close);	
		$('w3s_file_uploader_btn').stopObserving('click', W3sTools.bOpenFileUploader);		  
  	$('w3s_delete_image_btn').stopObserving('click', ImageManager.deleteSelectedImage);
    $('w3s_insert_image_btn').stopObserving('click', this.insertImage);
    W3sContent = null;
    ImageManager = null;
  	InteractiveMenu.closeEditor();
  		    
    return false;  
	},
  
  insertImage: function()
  {
    $('w3s_properties_form').enable(); 
    W3sContent.editContent($('w3s_properties_form').serialize());
  		    
    return false;    	
  }
});

var scriptEditor = Class.create(editor, {
	
	initEditor: function()
	{
	    W3sContent = new w3sContent(); 
	    //$('w3s_btn_close').observe('click', currentEditor.close);		  
	    $('w3s_edit_content_btn').observe('click', this.edit); 
	  		    
	    return false;  
	},
	
	show: function(iWidth, iHeight)
	{	
		Position.clone($('w3s_scripts_manager'), $('w3s_im_editor'));
		currentEditor.initEditor();	
	  		    
	  return false;  
	},
		
	edit: function()
	{
		W3sContent.editContent($('w3s_script').value);
	  		    
    return false;  
	}
});

var menuEditor = Class.create(editor, {
	
	initEditor: function()
	{ 
	    W3sContent = new w3sContent(); 
			objMenuBuilder = new MenuBuilder();
	    //$('w3s_btn_close').observe('click', currentEditor.close);		  
	    //$('w3s_edit_content_btn').observe('click', this.edit); 
	  		    
	    return false;  
	},
	
	show: function()
	{	
	  Position.clone($('w3s_menu_builder'), $('w3s_im_editor'));
		currentEditor.initEditor();
	  		    
	  return false;  
	},
	
	edit: function()
	{
		var params = objMenuBuilder.setLinks();
		var classToPages = ($('w3s_class_page_assign').checked) ? 1 : 0;
    W3sContent.editContent(params  + '|' + $('w3s_options_form').serialize(), 'setClassToPages=' + classToPages, 'menuBuilder');
				    
    return false;  
	}
});
