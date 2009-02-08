<?php

/**
 * W3sMenuElement form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 12815 2008-11-09 10:43:58Z fabien $
 */
class BaseW3sMenuElementForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'content_id'     => new sfWidgetFormPropelChoice(array('model' => 'W3sContent', 'add_empty' => false)),
      'page_id'        => new sfWidgetFormInput(),
      'link'           => new sfWidgetFormTextarea(),
      'external_link'  => new sfWidgetFormTextarea(),
      'image'          => new sfWidgetFormTextarea(),
      'rollover_image' => new sfWidgetFormTextarea(),
      'position'       => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorPropelChoice(array('model' => 'W3sMenuElement', 'column' => 'id', 'required' => false)),
      'content_id'     => new sfValidatorPropelChoice(array('model' => 'W3sContent', 'column' => 'id')),
      'page_id'        => new sfValidatorInteger(),
      'link'           => new sfValidatorString(),
      'external_link'  => new sfValidatorString(),
      'image'          => new sfValidatorString(),
      'rollover_image' => new sfValidatorString(),
      'position'       => new sfValidatorInteger(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'W3sMenuElement', 'column' => array('id')))
    );

    $this->widgetSchema->setNameFormat('w3s_menu_element[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'W3sMenuElement';
  }


}
