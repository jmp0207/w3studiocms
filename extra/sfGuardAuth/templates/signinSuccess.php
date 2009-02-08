<?php use_helper('Javascript', 'Object', 'I18N') ?>

<div id="w3s_signin">
  <form action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
    <table>
      <?php echo $form ?>
      <tr>
        <td>
          <?php
            $oPage = W3sPagePeer::retrieveByPk($sf_request->getParameter('page'));
            if ($oPage != null){
              $pageName = $oPage->getPageName();
              $oLanguage = W3sLanguagePeer::retrieveByPk($sf_request->getParameter('lang'));
              $languageName = ($oLanguage != null) ? $oLanguage->getLanguage() : '';
            }
            else{
              $pageName = '';
              $languageName = '';
            }

            /* In the test enviroment we need a real submit button. In others
             * enviroments the button to perform the login is a link to a
             * javascript function.
             */
            if (sfConfig::get('sf_environment') != 'test'){ 
              echo link_to_function('Login', 'doLogin(\'' . url_for('@sf_guard_signin') . '\', \'' . url_for('/webEditor/index?lang=' . strtolower($languageName) . '&page=' . strtolower($pageName)) . '\')', 'class="link_button"');
            }
            else{
              echo submit_tag(__('signin'));
            }
          ?>
        </td>
      </tr>
    </table>
  </form>
</div>