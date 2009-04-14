<?php
  use_helper('I18N');

	// Result messages
	switch($result)
  {
	  case 0:
	    $message = __('An error occoured while saving slots mapper.');
	    break;
	  case 1:
	    $message = __('The slots mapper has been correctly saved.');
	    break;
    case 2:
	    $message = __('The count of source slots differs from the count of destionation slots: cannot save.');
	    break;
    case 4:
	    $message = __('At least one of the two templates does not exist.');
	    break;
	}

  echo w3sCommonFunctions::displayMessage($message);