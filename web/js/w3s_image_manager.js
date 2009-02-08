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

var imageManager = Class.create({  
  
  initialize: function()
  {
    this.currentSelectedImage = '';
  },
  
  refreshImagelist: function()
  {
    var sActionPath = w3studioCMS.frontController + 'imagesManager/refreshImages';
		new Ajax.Updater('w3s_images_select', sActionPath,
      {asynchronous:true,
       evalScripts:false,
       onFailure:function(request, json)
          {
            if (curWindow == null) curWindow = W3sWindow.openModal(200, 100, false);
            curWindow.setHTMLContent('<br /><h1>REFRESHING IMAGE\'S LIST<br />Please Wait</h1>');
          },      
       onComplete:function(request, json)
          {
            W3sTools.resetFormObjects('w3s_properties_form'); 
            W3sWindow.closeModal();
          }
      });
                  
    return false;
  },

  setImagePreview: function(sImageName)
  { 
    var sActionPath = w3studioCMS.frontController + 'imagesManager/changeImage';
    
    //setCanvas = ($('w3s_fit_preview').checked == true) ? 1 : 0;
    setCanvas = 1;
    new Ajax.Request(sActionPath,
      {asynchronous:true,
       evalScripts:false,
       onLoading:function()
          {
            if (curWindow == null) curWindow = W3sWindow.openModal(200, 100, false);
            curWindow.setHTMLContent('<br /><h1>LOADING IMAGE<br />Please Wait</h1>');
          },      
       onComplete:function(request, json)
         {            
           W3sTools.updateJSON(request, json);
           
           if (this.currentSelectedImage != '') Element.removeClassName($(this.currentSelectedImage), 'currentSelectedImage');
           this.currentSelectedImage = 'w3s_' + sImageName;
           Element.addClassName($(this.currentSelectedImage), 'currentSelectedImage');
          
           // Checks the image type, 2 is jpg, so it can enable or disable the quality input box
           //objImageEditor.setQualitySelect(json[4][1]);
           
           W3sWindow.closeModal();
         },
       parameters:'image=' + sImageName +
                  '&setCanvas=' + setCanvas +
                  '&previewWidth=' + (Element.getWidth('w3s_image_preview') - 4) +
                  '&previewHeight=' + (Element.getHeight('w3s_image_preview')- 4)});
                 
    return false;
  },
  
  deleteSelectedImage: function()
  {                                                          
    if (confirm('Are you sure?')) {
      var sActionPath = w3studioCMS.frontController + 'imagesManager/deleteImage';
      new Ajax.Updater('w3s_images_select', sActionPath,
        {asynchronous:true,
         evalScripts:false,
         onLoading:function(request, json)
          {
            if (curWindow == null) curWindow = W3sWindow.openModal(200, 100, false);
            curWindow.setHTMLContent('<br /><h1>DELETING IMAGE<br />Please Wait</h1>');
          },
         onComplete:function(request, json)
          {
          	
            //W3sTools.resetFormObjects('w3s_image_editor_form');
            W3sTools.resetFormObjects('w3s_properties_form'); 
                       
            $('w3s_image_preview').innerHTML = '';
            W3sWindow.closeModal();
          },
         parameters:'image=' + $("w3s_ppt_image").value 
        });
    }
    return false;
  }
});