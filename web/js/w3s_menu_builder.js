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

//var objMenu = Array();

var MenuBuilder = Class.create(
{
  initialize: function()
  {
    this.idMenu = 0;
    this.currentSelectedLink = '';
    this.objMenu = Array();
  },
  
  loadMenuProperties: function(idMenu)
  {
    this.idMenu = idMenu;
    if (this.currentSelectedLink != '') Element.removeClassName($(this.currentSelectedLink), 'currentSelectedLink');
    this.currentSelectedLink = 'item_' + this.idMenu;
    Element.addClassName($(this.currentSelectedLink), 'currentSelectedLink');

    properties = $('w3s_properties_form').getElements();
    properties.each(function(property)
    {
      if (!property.hasClassName('combined_button')) property.value = objMenuBuilder.objMenu[idMenu][property.id];
    });
  },

  saveMenuLinkImage: function(updateList)
  {
    if (updateList == 1) $('item_text_' + this.idMenu).innerHTML = $('w3s_ppt_link').value;
    objMenuBuilder.objMenu[this.idMenu] = $('w3s_properties_form').serialize(true);		
  },

  addLink: function()
  {
    var sActionPath = w3studioCMS.frontController + 'menuBuilder/addLink';
		
    new Ajax.Updater({success:'w3s_mb_menu', failure:'w3s_feedback'}, sActionPath,
        {asynchronous:true,
          evalScripts:true,
          onLoading:function()
            {
              $('w3s_feedback').innerHTML = 'Saving changes...';
            },
          onSuccess:function()
            {
              $('w3s_feedback').innerHTML = '&nbsp;';
            },
         parameters:'idContent='+InteractiveMenu.idContent
				});
    return false;
  },

  deleteLink: function(sConfirmMessage)
  {
    if (this.idMenu != 0)
    {
      if(sConfirmMessage == undefined) sConfirmMessage = 'Do you want to delete the selected link?';
      if (confirm(sConfirmMessage))
      {
				var sActionPath = w3studioCMS.frontController + 'menuBuilder/deleteLink';
        new Ajax.Updater({success:'w3s_mb_menu',failure:'w3s_feedback'}, sActionPath,
          {asynchronous:true,
           evalScripts:true,
           onLoading:function()
              {
                $('w3s_feedback').innerHTML = 'Saving changes...';
              },
           onSuccess:function(request, json)
              {
                $('w3s_feedback').innerHTML = '';
              },
           parameters:'idMenu=' + this.idMenu +
                      '&idContent='+InteractiveMenu.idContent});
      }
    }
    else
    {
      alert('You must select a link')
    }
  },

  saveMenu: function(sActionPath){
    var hasFailded = false;
    var classToPages = ($('w3s_class_page_assign').checked) ? 1 : 0;
    var params = this.setLinks();
     
    new Ajax.Updater({success:'w3sContentItem_' + InteractiveMenu.idContent, failure:'w3s_error'}, sActionPath,
          {asynchronous:true,
           evalScripts:false,
           onLoading:function()
              {
                if (curWindow == null) curWindow = W3sWindow.openModal(320, 200, false);
                curWindow.setHTMLContent('<br /><h1>SAVING MENU<br />Please Wait</h1>');
              },
         onFailure:function()
            { 
              //W3sWindow.closeModal();          
              //curWindow = W3sWindow.openModal(370, 210);
              hasFailded = true;         
            },
           onComplete:function(request, json)
              {
                if (hasFailded){
              curWindow.setHTMLContent($('w3s_error').innerHTML);            
            }
            else{
             W3sWindow.closeModal();
            }
              },
           parameters:params +
                      'idContent=' + InteractiveMenu.idContent +
                      '&lang=' + w3studioCMS.language +
                      '&idSlot=' + InteractiveMenu.idSlot +
                      '&idGroup=' + InteractiveMenu.idGroup +
                      '&idPage=' + w3studioCMS.page +
                      '&classAssigned=' + $F('w3s_assigned_class') +
                      '&setClassToPages=' + classToPages +
                      '&assignedTo=' + $F('w3s_assigned_to')});
    return false;
  },
  
  setLinks: function(){ 
  	var params='';
    var links = $('w3s_menu_items').getElementsByTagName('li'); 
    links = $A(links); 
    links.each(function(link){ 
      if (link.value != -1) {
        a = link.value; 
        properties = $('w3s_properties_form').getElements();
        properties.each(function(property){ 
          if (!Element.hasClassName(property, 'combined_button')) params += link.value + '[]=' + property.id + '=' + objMenuBuilder.objMenu[a][property.id] + "&";          
        });
      }
    });

    return params;
  },

  showImages: function(property)
  {
    sAjaxOptions = {asynchronous:true,
                    evalScripts:false,
                    parameters:'property=' + property};

    curWindow = W3sWindow.openModal(210, 360, true, true, false, false, true);
    curWindow.setAjaxContent('/menuBuilder/showImages', sAjaxOptions);
    return false;
  }
});