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

/**
 * w3sToolbarHorizontal draws a vertical toolbar
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sToolbarVertical
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
class w3sToolbarVertical extends w3sToolbar
{
  /**
   * Renders the toolbar.
   *
   * @param object  A reference to current user.
   *
   */
  public function renderToolbar()
  {

    $toolbar = '';
    foreach ($this->toolbar as $button){ 
      $oButton = new w3sButton($this->currentUser);
      $oButton->fromArray($button);
      $toolbar .= sprintf('<tr>%s</tr>', $oButton->render());
    }
   
    return $toolbar;
  }  
}