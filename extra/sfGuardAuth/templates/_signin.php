<?php 
/*
 * This file is part of the w3studioCMS package library and it is distributed 
 * under the LGPL LICENSE Version 2.1. To use this library you must leave 
 * intact this copyright notice.
 *  
 * (c) 2007-2008 Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 *  
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.w3studiocms.com
 */

  use_helper('Javascript', 'Object', 'I18N')
?>

<div id="w3s_signin">
  <form action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
    <table style="padding: 4px">
      <?php echo $form ?>
      <tr>
        <td></td>
        <td>
          <?php 
            $oPage = W3sPagePeer::retrieveByPk($sf_request->getParameter('page'));
            if ($oPage != null)
            {
              $pageName = $oPage->getPageName();
              $oLanguage = W3sLanguagePeer::retrieveByPk($sf_request->getParameter('lang'));
              $languageName = ($oLanguage != null) ? $oLanguage->getLanguage() : '';
            }
            else
            {
              $pageName = '';
              $languageName = '';
            }
            
            /* In the test enviroment we need a real submit button. In others
             * enviroments the button to perform the login is a link to a
             * javascript function.
             */
            if (sfConfig::get('sf_environment') == 'test' || !$isAjax)
            {
              echo submit_tag(__('signin'));
            }
            else
            {
              echo link_to_function('Login', sprintf('doLogin(\'%s\', \'/W3studioCMS/%s/%s.html\')', url_for('@sf_guard_signin'), strtolower($languageName), strtolower($pageName)), 'class="link_button"');
            }
          ?>
        </td>
      </tr>
    </table>
  </form>
</div>