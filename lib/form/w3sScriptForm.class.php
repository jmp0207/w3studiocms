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

  class w3sScriptForm extends sfForm
  {
    private $script;
    
    public function __construct($script){
      $this->script = $script;
      parent::__construct();
    }
    
    public function configure(){      
      $this->setWidgets(array(
        'script' => new sfWidgetFormTextarea()
      ));
      $script = ($this->script != '') ? $this->script : __('Insert here your script') ;
      $this->setDefault('script', $script);
      
      $this->widgetSchema->setNameFormat('w3s[%s]');
    }
  }