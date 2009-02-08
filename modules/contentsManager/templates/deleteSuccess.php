<?php
	use_helper('I18N', 'Javascript'); 
  echo $content->redraw();
  echo javascript_tag($content->getSortables() . $content->getInteractiveMenuEvents());  