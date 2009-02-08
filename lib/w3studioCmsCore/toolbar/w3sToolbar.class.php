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
 * w3sToolbar is the base class to draw a toolbar, a collection of buttons.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sToolbar
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
  abstract class w3sToolbar
  {
    protected 
      $toolbar,
      $currentUser;
      
    abstract function renderToolbar();
    
    /**
     * Constructor.
     * 
     * @param object  A reference to current user.
     *
     */   
    public function __construct($currentUser = null){
      if($currentUser != null) $this->setCurrentUser($currentUser);
    }
    
    /**
     * Sets / Gets the value of the toolbar property.
     * 
     * @param object
     *
     */ 
    public function setToolbar($value){
      if (!is_array($value))
      {
        throw new RuntimeException(sprintf('toolbar must be an array. The value you entered is %s.', $value));
      }
      $this->toolbar = $value;
    }

    public function getToolbar(){
      return $this->toolbar;
    }

    /**
     * Sets / Gets the value of the currentUser property.
     * 
     * @param object
     *
     */ 
    public function setCurrentUser($value)
    {
      if (!is_object($value))
      {
        throw new RuntimeException(sprintf('CurrentUser param must be an object. The value you entered is %s.', $value));
      }

      $this->currentUser = $value;
    }

    public function getCurrentUser()
    {
      return $this->currentUser;
    }

    /**
     * Sets the toolbar buttons from a yml file. See the tbMenuManager.yml for a full example
     * 
     * @param array
     *
     */
    public function fromYml($fileName)
    {              
      $this->toolbar = w3sCommonFunctions::loadScript($fileName);   
    }
  }