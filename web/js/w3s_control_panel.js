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

var w3sControlPanel = Class.create({
  initialize: function()
	{
    try
		{
      var objBody = document.getElementsByTagName("body").item(0);
        
      var objControlPanel = document.createElement("div");
      objControlPanel.setAttribute('id','w3s_control_panel');
      objControlPanel.onmouseover = function()
			{
        W3sTools.opacize('w3s_control_panel', '1')
      }

      objControlPanel.onmouseout = function()
			{
        W3sTools.opacize('w3s_control_panel', '0.1')
      }
      objControlPanel.style.display = 'none';
      objBody.appendChild(objControlPanel);
			
			W3sPage = new w3sPage(); 
			W3sGroup = new w3sGroup();
			W3sLanguage = new w3sLanguage();
			W3sFileManager = new w3sFileManager();
			W3sSlotManager = new w3sSlotManager();
			W3sControlPanelTabs = new w3sControlPanelTabs();
    }
    catch(e)
		{
      alert('w3sControlPanel.initialize:' + e)
    }
  },

  show: function()
	{
    try
		{
      new Effect.Appear('w3s_control_panel');
    }
    catch(e)
		{
      alert('w3sControlPanel.show:' + e)
    }
  },  
  
  hide: function(){
    new Effect.Fade('w3s_control_panel');
  },
	
  toggleDeletedContents: function(){
    var toDelete = $('w3s_cms').getElementsByClassName('w3s_deleted');
    toDelete = $A(toDelete);
    toDelete.each(function(content){
      content.style.display = (content.style.display == 'none') ? 'block' : 'none';
    });
  },

  showAddPageModule: function(){ 
		var sActionPath = w3studioCMS.frontController + 'pagesManager/show';
    var sAjaxOptions = {asynchronous:true,
                        evalScripts:false,
                        method:'get'
											 };

    curWindow = W3sWindow.openModal(280, 170);
    curWindow.setAjaxContent(sActionPath, sAjaxOptions);
    
		return false;
  },

  listPages: function(){ 
  	var sActionPath = w3studioCMS.frontController + 'pagesManager/showPages';
    new Ajax.Updater('w3s_page_list', sActionPath,
                    {asynchronous:true,
                     evalScripts:false,
                     onLoading:function()
                        {
                          if (curWindow == null) curWindow = W3sWindow.openModal(200, 100, false);
                          curWindow.setHTMLContent('<br /><h1>REFRESHING PAGE\'S LIST<br />Please Wait</h1>');                          
                        },
                     onComplete:function(request, json)
                        {
                          W3sWindow.closeModal();
                        },
                     parameters:'curPage=' + w3studioCMS.language + w3studioCMS.page +
                                '&curLang=' + w3studioCMS.language});

  },

  /*
   *  Groups management
   */
  showAddGroupModule: function(op){
    var sActionPath = w3studioCMS.frontController + 'groupsManager/show';
    if (op == undefined) op=1;
    var sGroupName = (op == 2) ? '&groupName=' + $("w3s_groups_select1").options[$("w3s_groups_select1").selectedIndex].text + '&idGroup=' + $("w3s_groups_select1").value : '';
    
    // TEMPORARY
    /*
    if (op == 1){
      }
    else{

    }*/
      //curWindow.setContent('<p>&nbsp;</p>');
      var sAjaxOptions = {asynchronous:true,
                          evGroupalScripts:false,
                          method:'get',
                          onComplete:function(request, json)
                            {
                              $('w3s_group_name').focus();
                            },
                          parameters:'op=' + op + sGroupName};
      curWindow = W3sWindow.openModal(350, 180);
      curWindow.setAjaxContent(sActionPath, sAjaxOptions);
    
    return false;
  },

  refreshGroupsSelect: function(bRefreshPages){
		if (bRefreshPages == null) bRefreshPages = false;
  	sActionPath = w3studioCMS.frontController + 'groupsManager/refresh';
    new Ajax.Updater('w3s_control_panel_groups', sActionPath,
        {asynchronous:true,
          evalScripts:false,
          onComplete:function()
            {
              W3sWindow.closeModal();
							if (bRefreshPages) W3sControlPanel.listPages();
            }
				});
    return false;
  },

  checkMapping: function()
  {
    sActionPath = w3studioCMS.frontController + 'groupsManager/checkMapping';
    new Ajax.Updater('w3s_template_mapping', sActionPath,
        {asynchronous:true,
         evalScripts:false,
         onLoading:function()
            {
              $('w3s_template_mapping').innerHTML('Checking mapping...');
            },
          onComplete:function()
            {
              //W3sWindow.closeModal();
            },
          parameters:'idGroup=' + $("w3s_id_group").value +
                     '&idDestTemplate=' + $("w3s_templates_select").value
				});
    return false;
  },

/*
  checkElementsForChange: function(sActionPath, idGroup, param){
    if(param.substr(1, 1) != '&') param = '&' + param;
    new Ajax.Updater('w3s_check_elements', sActionPath,
      {asynchronous:true,
       evalScripts:false,
       onLoading:function()
            {
              $('w3s_change_button').style.visibility = 'hidden';
              $('w3s_check_elements').innerHTML = 'Template\'s elements checking: please wait';
            },
       onComplete:function(request, json)
            {
              $('w3s_change_button').style.visibility = 'visible';
            },
       parameters:'idGroup=' + idGroup + param});
  },

  showChangePageGroupModule: function(idPage, idGroup){
    alert('This function is not implemented yet');
    /*
    sAjaxOptions = {asynchronous:true,
                    evalScripts:false,
                    method:'get',
                    onComplete:function()
                      {
                        $('w3s_group_name').focus();
                      },
                    parameters:'idGroup=' + idGroup +
                               '&idPage=' + idPage}
    curWindow = W3sWindow.openModal(330, 160);
    curWindow.setAjaxContent(sActionPath, sAjaxOptions);
    *
    return false;
  },

  changeGroupPage: function(sActionPath){
    var resultString = checkGroupElements();
    if (resultString != 'Request aborted'){
      new Ajax.Request(sActionPath,
          {asynchronous:true,
            evalScripts:false,
            onComplete:function(request, json)
              {
                if (json[0][1] == 1){
                  W3sControlPanel.listPages();
                }
              },
              parameters:'idGroup=' + $('w3s_eg_id_group').value +
                         '&idPage=' + $('w3s_eg_id_page').value +
                         '&idNewGroup=' + $('w3s_new_group_select').value +
                         '&elements=' + resultString});
    }

    return false;
  },*/

	openLanguagesInterface: function(idLanguage){      
	  if (curWindow != null) W3sWindow.closeModal();
	  if (idLanguage == undefined) idLanguage = 0;
	  var sActionPath = w3studioCMS.frontController + 'languagesManager/show';
	  var sAjaxOptions = {asynchronous:true,
	                      evalScripts:false,
	                      method:'get',
	                      parameters:'idLanguage=' + idLanguage};
	
	  curWindow = W3sWindow.openModal(320, 180);
	  curWindow.setAjaxContent(sActionPath, sAjaxOptions);
	  
	  return false;
	},
	
	highlightSlot: function(sElementName, repeated){   
	  W3sTools.opacize('w3s_control_panel', '1');
	  $('w3s_im_commands').style.display = 'none';
	  if (repeated == undefined) repeated = 0;
	  
	  var offsets = Position.cumulativeOffset($(sElementName));
	  var iWidth = Element.getWidth(sElementName);
	  var iHeight = Element.getHeight(sElementName);
	  
	  var sColor;     
	  switch (repeated){
	    case 0:
	      sColor = '#66AA00';
	      break;
	    case 1:
	      sColor = '#FF9900';
	      break;
	    case 2:
	      sColor = '#003399';
	      break;
	  }
	    
	  $('w3s_interactive_menu').setStyle({
	    width: iWidth + 'px',
	    height: iHeight + 'px',
	    top: offsets[1] + 'px',
	    left: offsets[0] + 'px',
	    border: '2px solid ' + sColor,
	    display: 'block'
	  });
	
	  return false;
	},
	  	
	showRepeatedContentsForm: function(idSlot){ 
	  InteractiveMenu.stop = true;
	  var sActionPath = w3studioCMS.frontController + 'contentsManager/showChangeRepeatedContents';
	  var curWindow = W3sWindow.openModal(350, 250, true);
	  sAjaxOptions = {asynchronous:true,
	                  evalScripts:false,
	                  parameters:'idSlot=' + idSlot};
	  curWindow.setAjaxContent(sActionPath, sAjaxOptions);
	  
	  return false;
	},
	
	changeRepeatedStatus: function(slotName){
	  var hasFailded = false;
	  var sActionPath = w3studioCMS.frontController + 'contentsManager/changeRepeatedContents';
	  new Ajax.Updater({success:'w3s_slot_list', failure:'w3s_error'}, sActionPath,
	      {asynchronous:true,
	        evalScripts:false,
	        onLoading:function()
	          {
	            curWindow.setHTMLContent('<br /><h1>CHANGING THE REPEATED STATUS<br />Please Wait</h1>');
	            
	            //curWindow.setSize(130,130).show(true).center({auto: true});
	          },
	       onSuccess:function()
	          {
	            W3sWindow.closeModal();
	          },
	       onFailure:function()
	          { 
	            W3sWindow.closeModal();
	            curWindow = W3sWindow.openModal(370, 210);
	            hasFailded = true;
	          },   
	        onComplete:function(request, json)
	          {
	            if (hasFailded){
	              curWindow.setHTMLContent($('w3s_error').innerHTML);            
	            }
	            InteractiveMenu.stop = false;
	          },
	        parameters:'newRepeatedValue=' + $('repeated_value').value +
		                 '&lang=' + w3studioCMS.language +
	                   '&page=' + w3studioCMS.page + 
		                 '&slotName=' + slotName});
	  
	  return false;
	}
});



/* Checks that user has not assigned a number of elements greater than the
 * number of elements contained in the availble element's SELECT. If everything
 * is fine, returns the string which contains the elements associations between
 * the old and the new template.
 */
function checkGroupElements(){
  var tString;
  var bResult = true;
  var resultString = '';

  /* Retrieves all the elements that are not present in the new template. These elements are
   * identified by a SELECT.
   */
  if ($('w3s_elements_list') != null){
  var selects = $('w3s_elements_list').getElementsByTagName('SELECT');
  if (selects.length>0){
    selects = $A(selects);
    var options = selects[0].getElementsByTagName('OPTION');

    // Don't consider the Delete element
    var optionsElements = options.length-1;

    // Cycles all the SELECTS
    var countNotToDelete=0;
    selects.each(function(select){

      // Creates the string with the template's elements association
      tString = select.id;
      resultString += tString.substr(7, tString.length) + ',';
      resultString += select.options[select.selectedIndex].value + '|';

      // Checks if user set associations between elements of the old and the new template
      if (optionsElements > 0){

        /* Checks the value of the current SELECT: Zero indicates that the element must be
         * deleted, so if the value is not zero user made an association
         */
        if(select.options[select.selectedIndex].value != 0) countNotToDelete++;
      }
    });

    /* If user made associations and the count of associated elements is greater than the available elements
     * it's probal that user made duplicated associations.
     */
    if(countNotToDelete > optionsElements) bResult = (confirm('WARNING: You have choosed to transfer the contents of ' + countNotToDelete + ' elements, but there are only ' + optionsElements + ' availables: do you want to continue anymore?'));
    if (!bResult) resultString = 'Request aborted';
  }

  if (bResult){

    /* Retrieves all the elements that are present in both template. These elements are
     * identified by an INPUT.
     */
    var inputs = $('w3s_elements_list').getElementsByTagName('INPUT');
    inputs = $A(inputs);
    inputs.each(function(input){

      // Creates the string with the template's elements association
      tString = input.id;
      resultString += tString.substr(7, tString.length) + ',';
      resultString += input.value + '|';
    });
  }
  }

  return resultString;
}