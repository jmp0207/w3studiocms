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

var interactiveMenu = Class.create({ 
	
	initialize: function(items)
	{
	  this.stop = false; 
	  this.items = items;
	  //$('w3s_editor_opener').observe('click', this.openEditor);
	},
	
	setEvents: function()
	{
		var items = $A(this.items.split('|'));
	  	items.each(function(item){
		  item = $A(item.split(',')); 
		  if (item[0] != '')
		  { 
		    InteractiveMenu.bShow = (item[1] != 0) 
		      ? 
		    	InteractiveMenu.show.bind(InteractiveMenu, item[1], item[2], item[3], item[4], item[5], item[6]) 
		      : 
		      InteractiveMenu.hide.bind(InteractiveMenu, item[1], item[2], item[3], item[4], item[5], item[6]);
		    $(item[0]).observe('mouseover', InteractiveMenu.bShow);
		  }
		});
	},
	
	setParams: function(params)
	{ 
	  var params = $A(params); 
	  this.idContent = params[0];
	  this.idContentType = params[1];
	  this.idSlot = params[2];	  
	  this.idGroup = params[3];
	  this.slotName = params[4];
	  this.repeated = params[5];   
	},	
	
	show: function()
	{  
	  if (!this.stop){ 
		  InteractiveMenu.setParams(arguments);
		  if (this.repeated == undefined) this.repeated = 0;
		  var elementName = 'w3sContentItem_' + this.idContent; 
		
		  var offsets = Position.cumulativeOffset($(elementName));
		  var iWidth = (Element.getWidth(elementName) > 8) ? Element.getWidth(elementName) : Element.getWidth(this.slotName);
		  var iHeight = (Element.getHeight(elementName) > 8) ? Element.getHeight(elementName) : Element.getHeight(this.slotName);
		
		  $('w3s_interactive_menu').setStyle({
		    width: iWidth + 'px',
		    height: iHeight + 'px',
		    top: offsets[1] + 'px',
		    left: offsets[0] + 'px',
		    display: 'none'
		  });
		
		  $('w3s_im_clone_element').setStyle({
		    width: iWidth + 'px',
		    height: iHeight + 'px'
		  });
		  
		  $('w3s_interactive_menu').style.display = 'block';                               
		  var commandsLeft = offsets[0];
		  if (iWidth > screen.width - 39){  //        The right corner of Commands coincide with the right corner of Interactive menu
		    iWidth -= 4;
		    commandsLeft += (iWidth - 39);
		    $('w3s_interactive_menu').setStyle({
		      width: iWidth + 'px',
		      borderLeft: '1px solid #99CCAA',
		      borderRight: '0',
		      borderTop: '1px solid #99CCAA',
		      borderBottom: '1px solid #336600'
		    });
		    $('w3s_im_commands').setStyle({
		      borderLeft: '0',
		      borderRight: '1px solid #336600'
		    });  
		  }
		  else if (commandsLeft > 38){ // Displays Commands on the left side of Interactive menu
		    commandsLeft -= 39;
		    $('w3s_interactive_menu').setStyle({
		      borderLeft: '0',
		      borderRight: '1px solid #336600',
		      borderTop: '1px solid #99CCAA',
		      borderBottom: '1px solid #336600'
		    });
		    $('w3s_im_commands').setStyle({
		      borderLeft: '1px solid #99CCAA',
		      borderRight: '0'
		    });
		  }
		  else{                  // Displays Commands on the right side of Interactive menu
		    commandsLeft += iWidth;
		    $('w3s_interactive_menu').setStyle({
		      borderLeft: '1px solid #99CCAA',
		      borderRight: '0',
		      borderTop: '1px solid #99CCAA',
		      borderBottom: '1px solid #336600'
		    });
		    $('w3s_im_commands').setStyle({
		      borderLeft: '0',
		      borderRight: '1px solid #336600'
		    });
		  }
		
		  switch (this.repeated){
		    case '0':
		      $('w3s_im_clone_element').style.backgroundColor = '#EEF9F9';
		      $('w3s_im_actions').style.backgroundImage = 'url(/sfW3studioCmsPlugin/images/interactive_menu/actions_bg_0.png)';
		      $('w3s_im_actions').style.backgroundColor = '#669900';
		      $('w3s_im_commands').style.backgroundColor = '#336600';
		      break;           
		    case '1':
		      $('w3s_im_clone_element').style.backgroundColor = '#990000';
		      $('w3s_im_actions').style.backgroundImage = 'url(/sfW3studioCmsPlugin/images/interactive_menu/actions_bg_1.png)';
		      $('w3s_im_actions').style.backgroundColor = '#CC9900';
		      $('w3s_im_commands').style.backgroundColor = '#CC6600';
		      break;
		    case '2':
		      $('w3s_im_clone_element').style.backgroundColor = '#000066';
		      $('w3s_im_actions').style.backgroundImage = 'url(/sfW3studioCmsPlugin/images/interactive_menu/actions_bg_2.png)';
		      $('w3s_im_actions').style.backgroundColor = '#006699';
		      $('w3s_im_commands').style.backgroundColor = '#003366';
		      break;
		  }
		  
		  $('w3s_im_commands').style.top = offsets[1] + 'px';
		  $('w3s_im_commands').style.left = commandsLeft + 'px';
		  $('w3s_im_commands').style.height = (iHeight > 110) ? iHeight + 'px' : 110 + 'px';
		  $('w3s_im_commands').style.display = 'block';
	  }
	  return false;
	},
	
	openEditor: function(){
		InteractiveMenu.stop = true; 	
		switch(InteractiveMenu.idContentType)
		{
			case '2':
				currentEditor = new textEditor(InteractiveMenu.idContent, InteractiveMenu.idContentType, InteractiveMenu.idSlot);
			 	break;
			case '3':			
				currentEditor = new imageEditor(InteractiveMenu.idContent, InteractiveMenu.idContentType, InteractiveMenu.idSlot);
			 	break;
			case '4': 			
				currentEditor = new scriptEditor(InteractiveMenu.idContent, InteractiveMenu.idContentType, InteractiveMenu.idSlot);
			 	break;
			case '5':
				currentEditor = new menuEditor(InteractiveMenu.idContent, InteractiveMenu.idContentType, InteractiveMenu.idSlot);
			 	break;
		}
		currentEditor.openEditor();
	},
	
	closeEditor: function()
	{
	    new Effect.BlindUp('w3s_im_editor',{afterFinishInternal:function(effect){effect.element.hide();effect.element.undoClipping();$('w3s_im_commands').style.display='';InteractiveMenu.stop = false;} });
	},
	
	hide: function()
	{
	    InteractiveMenu.setParams(arguments);
	    $('w3s_im_editor').style.display='none';
	    $('w3s_interactive_menu').style.display='none';
	    $('w3s_im_commands').style.display='none';
	
	    return false;
	},
	
	openActionsMenu: function()
	{
	  InteractiveMenu.stop = true; 
	  W3sContent = new w3sContent(); 
	  var offsets = Position.cumulativeOffset($('w3s_im_commands'));
	  var iLeft = offsets[0];
	  var iWidth = Element.getWidth('w3s_im_actions');
	
	  iLeft = (iLeft + iWidth < Element.getWidth(document.getElementsByTagName("body").item(0))) ? iLeft + Element.getWidth('w3s_im_commands') : iLeft - iWidth;
	  $('w3s_im_actions').style.top = offsets[1] + 'px';
	  $('w3s_im_actions').style.left = iLeft + 'px';
	  $('w3s_im_actions').style.display = 'block';
	},
	
	openActionsMenuForAddContent: function(sElement)
	{
	  InteractiveMenu.stop = true; 
	  //InteractiveMenu.hide();
	  W3sContent = new w3sContent(); 
	  var offsets = Position.cumulativeOffset($(sElement));
	  var iTop = offsets[1];
	  var iHeight = Element.getHeight(sElement);
	
	  $('w3s_im_actions').style.top = iTop + iHeight + 'px';
	  $('w3s_im_actions').style.left = offsets[0] + 'px';
	  $('w3s_im_actions').style.display = 'block';
	},
	
	closeActionsMenu: function()
	{
	  W3sContent = null;
	  $('w3s_im_actions').style.display = 'none'; 
	  InteractiveMenu.stop = false; 
	  return false;
	},
	
	contentDelete: function(sMessage)
	{
		InteractiveMenu.stop = true; 
		if(confirm(sMessage)){
			W3sContent = new w3sContent(); 
			W3sContent.deleteContent();
			W3sContent = null;	
		}
		InteractiveMenu.stop = false; 
	}
});