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
var w3sSlotAssociation = Class.create({
	
	initialize: function()
  {
    this.sourceStyle = '';
    this.destStyle = '';
    this.currentSelected = '';
    W3sMenuManager.load('tbSlotAssociation');
    $('w3s_cms').show();
    $('w3s_cms_temp').hide();
    InteractiveMenu.hide();
  },

  switchDiv: function()
  {
    if ($('w3s_cms').visible())
    {
      W3sTools.temaChange(W3sSlotAssociation.destStyle);
    }
    else
    {
      W3sTools.temaChange(W3sSlotAssociation.sourceStyle);
    }

    $('w3s_cms').toggle();
    $('w3s_cms_temp').toggle();
  },

  loadSlotAssociation: function(source, dest)
  {
	  this.doLoadSlotAssociation(source);
    this.doLoadSlotAssociation(dest, 'w3s_cms_temp');

	  return false;
	},

  doLoadSlotAssociation: function(idTemplate, destDiv)
  {
    if (destDiv == null) destDiv = 'w3s_cms';
    var sActionPath = w3studioCMS.frontController + 'webEditor/slotAssociation';
	  new Ajax.Updater({success:destDiv, failure:'w3s_error'}, sActionPath,
	      {asynchronous:true,
	       evalScripts:true,
	       onLoading:function()
	          {
	            if (destDiv == 'w3s_cms')
              {
                var curWindow = W3sWindow.openModal(200, 100, false);
                curWindow.setHTMLContent('<br /><h1>LOADING PAGE<br />Please Wait</h1>');
              }
	          },
	       onComplete:function(request, json)
	          {
	            if (json[0][1] == 1) 
              {
	              if (destDiv == 'w3s_cms')
                {  
                  W3sSlotAssociation.sourceStyle = json[1][1];
                  W3sTools.temaChange(json[1][1]);
                }
                else
                {
                  W3sSlotAssociation.destStyle = json[1][1];
                  W3sWindow.closeModal();
                }
	            }
	            else
              {
	              curWindow.setSize(230, 120);
	              curWindow.setHTMLContent($('w3s_error').innerHTML);
	            }
	          },
	        parameters:'idTemplate=' + idTemplate});
	  return false;
	}
});
