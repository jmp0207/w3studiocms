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

var w3sWindow = Class.create({
	
	openStandard: function (URL,titolo,w,h,scroll){
	  if (!scroll){scroll='no';}
	  var iTop=window.screen.height/2-h/2-10;
	  var iLeft=window.screen.width/2-w/2-10;
	  var windowprops = "location=no,scrollbars="+scroll+",menubars=no,toolbars=no,resizable=no" + ",left=" + iLeft + ",top=" + iTop + ",width=" + w + ",height=" + h;
	  popup = window.open(URL,titolo,windowprops);
	  popup.focus();
	  return popup;
	},
	
	openModal: function(iWidth, iHeight, bClosable, bResizable, bMinimizable, bMaximizable, bDraggable){
	  
	  // Prevents incidental interactions with contents
	  var imExists = ($('w3s_im_editor') != null) ? true : false;
	  if (imExists && $('w3s_im_editor').style.display == 'block' && !bStopMenu) bStopMenu=true;
	  
	  // Set predefined values for the new window
	  if (iWidth == undefined) iWidth = 250;
	  if (iHeight == undefined) iHeight = 130;
	  if (bClosable == undefined) bClosable = true;
	  if (bResizable == undefined) bResizable = false;
	  if (bMinimizable == undefined) bMinimizable = false;
	  if (bMaximizable == undefined) bMaximizable = false;
	  if (bDraggable == undefined) bDraggable = false;
	  
	  curWindow = new Window({
	                    className: "alphacube",
	                    title:'W3StudioCMS',
	                    width: iWidth,
	                    height: iHeight,
	                    closable:bClosable,
	                    resizable:bResizable,
	                    minimizable:bMinimizable,
	                    maximizable:bMaximizable,
	                    draggable:bDraggable,
	                    showEffect:Element.show,
	                    onClose:function(){if(imExists &&  !$('w3s_im_editor').style.display == 'none' && bStopMenu) bStopMenu=false;},
	                    destroyOnClose:true});
	  curWindow.showCenter();
	  
	  return curWindow;
	},
	
	closeModal: function(){
	  curWindow.close();
	  curWindow = null;
	}	
});