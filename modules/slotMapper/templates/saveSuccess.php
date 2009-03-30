<?php
  use_helper('I18N');

	// Result messages
	switch($result){
	  case 0:
	    $message = __('An error occoured while saving slots mapper.');
	    break;
	  case 1:
	    $message = __('The slots mapper has been correctly saved.');
	    break;
	}
	//$message .= __('You can try to change page, reenter in this page and redo the operation you made.<br /><br />If problem persists you can try to logout, signin again and redo the operation you made.<br /><br />If problem persists too, reports the error to W3StudioCMS web site \'s forum or write to W3StudioCMS\'s assistance.');
	echo w3sCommonFunctions::displayMessage($message);