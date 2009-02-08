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

  use_helper('I18N', 'Javascript');

  $i = 0;
  $drags = '';
  echo '<ul>';
  foreach($pagesList as $page):
    $idPage = $page->getId();
    $class = (($i/2)==intval($i/2)) ? "w3s_white_row" : "w3s_yellow_row";
?>
    <li id="w3s_page_<?php echo $idPage ?>" class="<?php echo $class; ?>"><?php echo w3sCommonFunctions::setStringMaxWidth($page->getPageName(), 25);?></li>
<?php
    $drags .= 'new Draggable(\'w3s_page_' . $idPage . '\', {revert:1});';
    $i++;
  endforeach;
  echo '</ul>';
  echo javascript_tag($drags);
?>

