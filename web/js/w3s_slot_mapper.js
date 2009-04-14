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
var w3sSlotMapper = Class.create({
	
	initialize: function(source, dest, imagePath)
  {
    this.sourceTemplateId = source;
    this.destTemplateId = dest;
    this.imagePath = imagePath;
    this.sourceStyle = '';
    this.destStyle = '';
    this.currentSelected1 = '';
    this.currentSelected2 = '';
    this.sourceSelected = '';
    this.destSelected = '';

    var objBody = document.getElementsByTagName("body").item(0);
    var objTmpDiv = document.createElement("div");
    objTmpDiv.setAttribute('id','w3s_slot_mapper1');
    objBody.appendChild(objTmpDiv);

    objTmpDiv = document.createElement("div");
    objTmpDiv.setAttribute('id','w3s_slot_mapper2');
    objBody.appendChild(objTmpDiv);

    var objSMPanel = document.createElement("div");
    objSMPanel.setAttribute('id','w3s_sm_panel');
    objSMPanel.onmouseover = function()
    {
      W3sTools.opacize('w3s_sm_panel', '1')
    }

    objSMPanel.onmouseout = function()
    {
      W3sTools.opacize('w3s_sm_panel', '0.1')
    }
    objBody.appendChild(objSMPanel);
    this.load();

    $('w3s_cms').hide();
    $('w3s_slot_mapper1').show();
    $('w3s_slot_mapper2').hide();
    InteractiveMenu.hide();
    W3sControlPanel.hide();
  },

  close: function()
  {
    var objBody = document.getElementsByTagName("body").item(0);
    objBody.removeChild($('w3s_slot_mapper1'));
    objBody.removeChild($('w3s_slot_mapper2'));
    objBody.removeChild($('w3s_sm_panel'));
    W3sTools.temaChange(W3sTemplate.currentCss);
    $('w3s_cms').show();
    W3sControlPanel.show();
  },

  load: function()
  {
    var sActionPath = w3studioCMS.frontController + 'slotMapper/renderPanel';
    new Ajax.Updater('w3s_sm_panel', sActionPath,
        {asynchronous:true,
          evalScripts:false,
	        parameters:'sourceId=' + this.sourceTemplateId +
                     '&destId=' + this.destTemplateId });

	  return false;
  },

  map: function(inverted)
  {    
    if (inverted == null) inverted = false;
    if ($('w3s_sm_source').value != '' &&  $('w3s_sm_dest').value != '')
    {
      var source = (!inverted) ? $('w3s_sm_source').value : $('w3s_sm_dest').value;
      var dest = (!inverted) ? $('w3s_sm_dest').value : $('w3s_sm_source').value;

      var divs = $('w3s_sm_maps').getElementsByTagName('div');
      var rowClass = (divs.length % 2) ? "w3s_white_row" : "w3s_blue_row";

      var divName = source + '-' + dest;
      var objMapDiv = $("w3s_mapping");

      var objSourceInput = document.createElement("input");
      objSourceInput.setAttribute('id', 'w3s_sm_source');
      objSourceInput.setAttribute('name', 'w3s_sm_source[]');
      objSourceInput.setAttribute('type', 'hidden');
      objSourceInput.setAttribute('value',  source);

      var objDestInput = document.createElement("input");
      objDestInput.setAttribute('id', 'w3s_sm_dest');
      objDestInput.setAttribute('name', 'w3s_sm_dest[]');
      objDestInput.setAttribute('type', 'hidden');
      objDestInput.setAttribute('value',  dest);

      var objRowDiv = document.createElement("div");
      objRowDiv.setAttribute('id', divName);
      objRowDiv.setAttribute('class', rowClass);

      var objRowDiv1 = document.createElement("div");
      objRowDiv1.setAttribute('style', 'float:left');
      objRowDiv1.innerHTML = $('w3s_sm_dest_name').value + ' -> ' + $('w3s_sm_source_name').value;
      objRowDiv1.appendChild(objSourceInput);
      objRowDiv1.appendChild(objDestInput);
      var objRowDiv2 = document.createElement("div");
      objRowDiv2.setAttribute('style', 'float:right');
      var objImg = document.createElement("img");
      objImg.setAttribute('src', this.imagePath + '/control_panel/button_delete.gif');
      objImg.setAttribute('style', 'border:0 width:14px height:14px');
      objImg.onclick = function()
                        {
                          W3sSlotMapper.remove(source, dest);
                        }
      objRowDiv2.appendChild(objImg);

      objRowDiv.appendChild(objRowDiv1);
      objRowDiv.appendChild(objRowDiv2);
      objMapDiv.appendChild(objRowDiv);

      Element.removeClassName($(this.currentSelected1), 'currentSelectedSlot');
      Element.addClassName($(this.currentSelected1), 'slotSelected');

      Element.removeClassName($(this.currentSelected2), 'currentSelectedSlot');
      Element.addClassName($(this.currentSelected2), 'slotSelected');

      $('w3s_sm_source').value = '';
      $('w3s_sm_source_name').value = '';

      $('w3s_sm_dest').value = '';
      $('w3s_sm_dest_name').value = '';
    }
    return false;
  },

  save: function()
  {
    var hasFailed = false;
    $('w3s_mapping').enable(); 
    var sActionPath = w3studioCMS.frontController + 'slotMapper/save';
	  new Ajax.Updater('w3s_error', sActionPath,
	      {asynchronous:true,
	       evalScripts:false,
	       onLoading:function()
	          {
	            var curWindow = W3sWindow.openModal(200, 100, false);
              curWindow.setHTMLContent('<br /><h1>SAVING MAPS<br />Please Wait</h1>');
	          },
         onFailure:function()
           {
              hasFailed = true;
           },
	       onComplete:function(request, json)
	          {
	            curWindow.setSize(230, 120);
	            curWindow.setHTMLContent($('w3s_error').innerHTML);
              //if (!hasFailed) W3sWindow.closeModal();
	          },
	        parameters:'sourceId=' + W3sSlotMapper.sourceTemplateId +
                     '&destId=' + W3sSlotMapper.destTemplateId +
                     '&' + $('w3s_mapping').serialize()});
  },

  remove: function(source, dest)
  {
    var sourceSlotName = 'w3sSlotItem_' + source;
    var destSlotName = 'w3sSlotItem_' + dest;
    Element.removeClassName(sourceSlotName, 'slotSelected');
    Element.addClassName(sourceSlotName, 'slotNotSelected');
    Element.removeClassName(destSlotName, 'slotSelected');
    Element.addClassName(destSlotName , 'slotNotSelected');
    $('w3s_mapping').removeChild($(source + '-' + dest));
  },

  selectSlot: function(id, name)
  {
    slotName = 'w3sSlotItem_' + id;
    if (!$(slotName).hasClassName('slotSelected'))
    {      
      if ($('w3s_slot_mapper1').visible())
      {
        W3sSlotMapper.sourceSelected = id;
        $('w3s_sm_source').value = id;
        $('w3s_sm_source_name').value = name;
        W3sSlotMapper.doSelect(slotName, this.currentSelected1);
        this.currentSelected1 = slotName;
      }
      else
      {
        W3sSlotMapper.destSelected = id;
        $('w3s_sm_dest').value = id;
        $('w3s_sm_dest_name').value = name;
        W3sSlotMapper.doSelect(slotName, this.currentSelected2);
        this.currentSelected2 = slotName;
      }
    }
    else
    {
      alert("This slot has already been mapped and cannot be selected again since you don't remove the current mapping")
    }

    return false;
  },

  doSelect: function(slotName, currentSlot)
  {
    if (currentSlot != '')
    {
      Element.removeClassName($(currentSlot), 'currentSelectedSlot');
      Element.addClassName($(currentSlot), 'slotNotSelected');
    }    
    Element.addClassName($(slotName), 'currentSelectedSlot');

    return false;
  },

  switchDiv: function()
  {
    if ($('w3s_slot_mapper1').visible())
    {
      W3sTools.temaChange(W3sSlotMapper.destStyle);
    }
    else
    {
      W3sTools.temaChange(W3sSlotMapper.sourceStyle);
    }

    $('w3s_slot_mapper1').toggle();
    $('w3s_slot_mapper2').toggle();

    return false;
  },

  loadSlotMapper: function(source, dest)
  {
    this.doLoadSlotMapper(this.sourceTemplateId, this.destTemplateId);
    this.doLoadSlotMapper(this.destTemplateId, this.sourceTemplateId, 'w3s_slot_mapper2');

	  return false;
	},

  doLoadSlotMapper: function(idTemplate, otherTemplate, destDiv)
  {
    if (destDiv == null) destDiv = 'w3s_slot_mapper1';
    var sActionPath = w3studioCMS.frontController + 'slotMapper/renderTemplate';
	  new Ajax.Updater({success:destDiv, failure:'w3s_error'}, sActionPath,
	      {asynchronous:true,
	       evalScripts:true,
	       onLoading:function()
	          {
	            if (destDiv == 'w3s_slot_mapper1')
              {
                var curWindow = W3sWindow.openModal(200, 100, false);
                curWindow.setHTMLContent('<br /><h1>LOADING MODULE<br />Please Wait</h1>');
              }
	          },
	       onComplete:function(request, json)
	          {
	            if (json[0][1] == 1) 
              {
	              if (destDiv == 'w3s_slot_mapper1')
                {  
                  W3sSlotMapper.sourceStyle = json[1][1];
                  W3sTools.temaChange(json[1][1]);
                }
                else
                {
                  W3sSlotMapper.destStyle = json[1][1];
                  W3sWindow.closeModal();
                }
	            }
	            else
              {
	              curWindow.setSize(230, 120);
	              curWindow.setHTMLContent($('w3s_error').innerHTML);
	            }
	          },
	        parameters:'idTemplate=' + idTemplate +
                     '&otherTemplate=' + otherTemplate});

	  return false;
	}
});
