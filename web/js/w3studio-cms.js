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

/* - - - - - - - - - - - - - - - - - - - - - - -
 JavaScript
 domenica 20 maggio 2007 8.52.04
 HAPedit 3.0.11.102
 - - - - - - - - - - - - - - - - - - - - - - - */
var lang;
var page;
var curWindow = null;
var startDragContent;
var bStopMenu = false;
//var cssPath ="/css/themes/window/"

// Shows the login form. Retrieve the login form from sfGuard/signinSuccess
function showLoginForm(sActionPath, l, p){    
  if(l != undefined) lang = l;
  if(p != undefined) page = p;
  sAjaxOptions = {asynchronous:true,
                  evalScripts:false, 
                  method:'get',
                  onComplete:function(request, json)
                    {
                      $('username').focus();
                    },
                  parameters:'page=' + page +
                             '&lang=' + lang};

  curWindow = openModalWindow(270, 160);
  curWindow.setAjaxContent(sActionPath, sAjaxOptions);
  
  
  //moveCounter + '[]=' + idSlot + '&' + moveCounter + '[]=' + slotName + '&' + moveCounter + '[]=' + iIdContent + '&' + params + '&';
  
  
  /*
  curWindow = new UI.Window({
                theme:'vista',
                width: 280,
                height: 190,
                resizable:false,
                minimizable:false,
                maximizable:false,
                draggable:false,
                close:'hide'});
  curWindow.setHeader('W3StudioCMS');
  curWindow.center({auto: true});
  curWindow.show(true);
  curWindow.activate();
  curWindow.setAjaxContent(sActionPath, sAjaxOptions);  */
  return false;
}

// Validates the login data from the executeSignin action of sfGuard module.
function doLogin(sActionPath, sPageUrl){   
  var bHasSigned = true;
  sAjaxOptions = {asynchronous:true,
                  evalScripts:false, 
                  method:'post',
                  onFailure:function()
                    {
                      bHasSigned = false;
                    },
                  onComplete:function(request, json)
                    {
                      if (bHasSigned){ 
                        closeModalWindow();
                        location.href = sPageUrl;
                      }
                    }, 
                  parameters:'lang=' + lang + 
                             '&page=' + page +
                             '&' + 'signin[username]=' + $('signin_username').value + '&signin[password]=' + $('signin_password').value};
  curWindow.setAjaxContent(sActionPath, sAjaxOptions);
  //curWindow.show(true).adapt().center({auto: true}).setAjaxContent(sActionPath, sAjaxOptions);
  return false;
}

// Closes W3StudioCMS from the executeSignout action of sfGuard module
function doLogout(sActionPath, sPageUrl){
  curWindow = openModalWindow(200, 100, false);
  new Ajax.Request(sActionPath + '?lang=' + lang + '&page=' + page,
      {asynchronous:true,
        evalScripts:false,
        onLoading:function(request, json)
          {
            curWindow.setHTMLContent('<br /><h1>LEAVING W3StudioCMS<br />Please Wait</h1>');
            //curWindow.setSize(130,130).show(true).center({auto: true});
          },   
        onComplete:function(request, json)
          {
            //$('w3s_structure').style.display = 'none';
            closeModalWindow();
            location.href = json[0][1];
            //curWindow.hide(); http://w3studio/
          }});
  return false;
}

/*
function initW3Studio(){
  bStopMenu = true;
  $('w3s_menu_manager').hide();
  sAjaxOptions = {asynchronous:true,
                  evalScripts:false,
                  onComplete:function(request, json)
                    {
                      W3StudioCMSLoader('/webEditor/loadPage?lang=' + lang + '&page=' + page);
                      W3StudioStructureLoader('/sbStructure/index?lang=' + lang + '&page=' + page);
                    }
                 };
  curWindow = openModalWindow(250, 130, false);
  curWindow.setAjaxContent('/webEditor/showLoader', sAjaxOptions);
  
  new centerElement('w3s_menu_manager_hidden', 0);
  new centerElement('w3s_menu_manager', 0);
  Event.observe('w3s_menu_manager_hidden', 'mouseover', showMenuManager, false);
     
  return false;
}*/
/*
// Loads the editor page from webEditor/index
function loadEditorPage(sActionPath, l, p){

  var paramPrevPage = (page != undefined) ? '&prevPage=' + page : '';

  var prevLang;
  var prevPage;
  if(l != undefined){
    prevLang = lang;
    lang = l;
  }

  if(p != undefined){
    prevPage = page;
    page = p;
  }

  new Ajax.Updater({success:'w3s_cms',failure:'w3s_error'}, sActionPath,
      {asynchronous:true,
        evalScripts:true,
        onLoading:function()
          {
            /* When user changes page or language it is necessary to hide
             * the iteractive menu, otherwise it can be placed under the
             * contents 
             *            
            hideInteractiveMenu();
            if (curWindow == null) curWindow = openModalWindow(200, 100, false);
            curWindow.setHTMLContent('<br /><h1>LOADING EDITOR PAGE<br />Please Wait</h1>');
            //curWindow.setSize(130,130).show(true).center({auto: true});
          }, 
        onComplete:function(request, json)
          {
            if (json[0][1] == 1){  
              temaChange(json[1][1]);
              loadMenuManager('tbMenuManager');
              closeModalWindow();
              w3sStructure.listPages($('w3s_show_pages').value);
            }
            else{
              curWindow.setSize(230, 120);
              curWindow.setHTMLContent($('w3s_error').innerHTML);
            }
          },
        onFailure:function()
          {
            curWindow.setSize(230, 120);
            curWindow.setHTMLContent($('w3s_error').innerHTML);
            lang = prevLang;
            page = prevPage;
          },
        parameters:'page=' + page +
                   '&lang=' + lang +
                   paramPrevPage});
  return false;
}

// Loads the preview page from webPreview/index
function loadPreviewPage(sActionPath, l, p){

  var paramPrevPage = (page != undefined) ? '&prevPage=' + page : '';
  var prevLang;
  var prevPage;
  if(l != undefined){
    prevLang = lang;
    lang = l;
  }
  if(p != undefined){
    prevPage = page;
    page = p;
  }

  new Ajax.Updater({success:'w3s_cms',failure:'w3s_error'}, sActionPath,
      {asynchronous:true,
       evalScripts:true,
       onLoading:function()
          {
            hideInteractiveMenu();
            if (curWindow == null) curWindow = openModalWindow(200, 100, false);
            curWindow.setHTMLContent('<br /><h1>LOADING PREVIEW PAGE<br />Please Wait</h1>');
            //curWindow.setSize(130,130).show(true).center({auto: true});
          }, 
       onComplete:function(request, json)
          {
            if (json[0][1] == 1){
              temaChange(json[1][1]);
              loadMenuManager('tbMenuManagerPreview');
              closeModalWindow();
              //curWindow.hide();
            }
            else{
              curWindow.setSize(230, 120);
              curWindow.setHTMLContent($('w3s_error').innerHTML);
            }
          },
        onFailure:function()
          {
            curWindow.setSize(230, 120);
            curWindow.setHTMLContent($('w3s_error').innerHTML);
            lang = prevLang;
            page = prevPage;
          },
        parameters:'page=' + page +
                   '&lang=' + lang +
                   paramPrevPage});
  return false;
}


// Loads the editor page from webSite/index
function W3StudioCMSLoader(sActionPath){ 
  try{

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
            //alert((json[0][1]));
              if (json[0][1] == 1){ 
                temaChange(json[1][1]);
                //$('w3s_styles').innerHTML = '<style>' + $('w3s_tmp').innerHTML + '</style>'; //decodeURIComponent(json[1][1]);
                // Set the class that indicates that the module is loaded. IE needs the class to be remove
                Element.removeClassName($('w3s_img_editor'), 'img_waiting');
                Element.addClassName($('w3s_img_editor'), 'img_done');

                //showModifiedContents();

                // Verifies if CMS is loaded
                isCMSLoaded();
              }
              else{
                //curWindow = openModalWindow(260, 160);
                curWindow.setHTMLContent($('w3s_loading_error').innerHTML);

                // We must show the structure if page is in use, when json result is 2, otherwise user cannot change page
                //if (json[2][1] == 2) w3sStructure.show();
              }

            }});
  }
  catch(e){
  }

  return false;
}


// Loads the editor structure from sbStructure/index
function W3StudioStructureLoader(sActionPath){
    new Ajax.Updater('w3s_structure', sActionPath,
        {asynchronous:true,
          evalScripts:false,
          onComplete:function()
            {
              // Set the class that indicates that the module is loaded. IE needs to remove the class
              Element.removeClassName($('w3s_img_structure'), 'img_waiting');
              Element.addClassName($('w3s_img_structure'), 'img_done');

              // Verifies if CMS is loaded
              isCMSLoaded();
            },
          parameters:'page=' + page +
                     '&lang=' + lang});

  return false;
}

// Verifies if CMS is loaded
function isCMSLoaded(){
  try{
    // Retrieves from the DIV w3s_cms_loader all the elements with class img_waiting
    var x =  $('w3s_cms_loader').getElementsByClassName('img_waiting');

    // If no one founded, the CMS has been loaded so W3StudioCMS can hide the popup
    if(x.length == 0){
      closeModalWindow();
      bStopMenu = false;
    }
  }
  catch(e){
  }
}

*/

function showModifiedContents(){
  var currentContent;
  var idContent;
  var offsets;
  var contents;
  var classes = ['w3s_deleted', 'w3s_edited', 'w3s_added'];

  classes.each(function(currentClass){
    contents = $('w3s_cms').getElementsByClassName(currentClass);
    contents = $A(contents);
    contents.each(function(content){
      $(content.id).style.width = ($(content.id).getWidth() - 2)  + 'px';
      $(content.id).style.height = ($(content.id).getHeight() - 4)  + 'px';
    });
  });
  w3sStructure.toggleDeletedContents();

  return false;
}

