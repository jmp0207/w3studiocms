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

class w3sRouting
{
  /**
   * Listens to the routing.load_configuration event.
   *
   * @param sfEvent An sfEvent instance
   */
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $r = $event->getSubject();

    // prepend our routes
    $r->prependRoute('sf_guard_signin', new sfRoute('/login', array('module' => 'sfGuardAuth', 'action' => 'signin')));
    $r->prependRoute('site', new sfRoute('/:lang/:page.html', array('module' => 'webSite', 'action' => 'index')));
    $r->prependRoute('editor', new sfRoute('/:W3StudioCMS/:lang/:page.html', array('module' => 'webEditor', 'action' => 'index')));
    $r->prependRoute('homepage:', new sfRoute('/', array('module' => 'webSite', 'action' => 'index')));
  }
}