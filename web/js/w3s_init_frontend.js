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

var language; 
var page;

// Shows the login form. Retrieve the login form from sfGuard/signinSuccess
function showLoginForm(sActionPath, l, p)
{    
  W3sWindow = new w3sWindow();
  page = (p != undefined) ? p : 0;
  language = (l != undefined) ? l : 0;
  sAjaxOptions = {asynchronous:true,
                  evalScripts:false, 
                  method:'get',
                  onComplete:function(request, json)
                    {
                      $('username').focus();
                    },
                  parameters:'page=' + page +
                             '&lang=' + language};

  curWindow = W3sWindow.openModal(270, 160);
  curWindow.setAjaxContent(sActionPath, sAjaxOptions);
  W3sWindow = null;
  
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
                        W3sWindow = new w3sWindow();
                        W3sWindow.closeModal();
                        location.href = sPageUrl;
                      }
                    }, 
                  parameters:'lang=' + language + 
                             '&page=' + page +
                             '&' + 'signin[username]=' + $('signin_username').value + '&signin[password]=' + $('signin_password').value};
  curWindow.setAjaxContent(sActionPath, sAjaxOptions);
  
  return false;
}