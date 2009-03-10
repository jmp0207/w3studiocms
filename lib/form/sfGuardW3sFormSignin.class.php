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

class sfGuardFormW3studioCmsSignin extends sfGuardFormSignin
{
  public function configure()
  {
    $this->setWidgets(array(
      'username' => new sfWidgetFormInput(),
      'password' => new sfWidgetFormInput(array('type' => 'password')),
      'lang' => new sfWidgetFormInputHidden(),
      'page' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'username' => new sfValidatorString(),
      'password' => new sfValidatorString(),
      'lang' => new sfValidatorString(),
      'page' => new sfValidatorString(),
    ));

    $this->validatorSchema->setPostValidator(new sfGuardW3sValidatorUser());

    $this->widgetSchema->setNameFormat('signin[%s]');
  }
}
