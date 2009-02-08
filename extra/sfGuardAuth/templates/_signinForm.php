<?php use_helper('Validation', 'I18N', 'Javascript') ?>

<div id="sf_guard_auth_form">
<form action="<?php echo url_for('@sf_guard_signin') ?>" method="post">

  <fieldset>

    <div class="form_row" id="sf_guard_auth_username">
      <?php
      echo form_error('username');
      echo label_for('username', __('Username:'));
      echo input_tag('username', $sf_data->get('sf_params')->get('username'));
      ?>
    </div>

    <div class="form_row" id="sf_guard_auth_password">
      <?php
      echo form_error('password');
      echo label_for('password', __('Password:'));
      echo input_password_tag('password');
      //echo input_hidden_tag('w3s_language', $sf_request->getParameter('lang'));
      //echo input_hidden_tag('w3s_page', $sf_request->getParameter('page'));
      ?>
    </div>
  </fieldset>

  <div class="form_row" style="text-align:center;">
    <?php
      $oPage = W3sPagePeer::retrieveByPk($sf_request->getParameter('page'));
      if ($oPage != null):
        $pageName = $oPage->getPageName();
        $oLanguage = W3sLanguagePeer::retrieveByPk($sf_request->getParameter('lang'));
        $languageName = ($oPage != null) ? $oLanguage->getLanguage() : '';
      else:
        $pageName = '';
        $languageName = '';
      endif; 
      
      /* In the test enviroment we need a real submit button. In others
       * enviroments the button to perform the login is a link to a
       * javascript function.
       */ 
       
      if (SF_ENVIRONMENT != 'test'){ 
      	echo link_to_function('Login', 'doLogin(\'' . url_for('@sf_guard_signin') . '\', \'' . url_for('/webEditor/index?lang=' . strtolower($languageName) . '&page=' . strtolower($pageName)) . '\')', 'class="link_button"');
      }
      else{
      	echo submit_tag(__('sign in'));
      }
      //echo link_to(__('Forgot your password?'), '@sf_guard_password', array('id' => 'sf_guard_auth_forgot_password')) 
    ?>
  </div>
</form>
</div>