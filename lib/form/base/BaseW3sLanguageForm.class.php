<?php

/**
 * W3sLanguage form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 12815 2008-11-09 10:43:58Z fabien $
 */
class BaseW3sLanguageForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'language'      => new sfWidgetFormInput(),
      'main_language' => new sfWidgetFormInput(),
      'to_delete'     => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorPropelChoice(array('model' => 'W3sLanguage', 'column' => 'id', 'required' => false)),
      'language'      => new sfValidatorString(array('max_length' => 50)),
      'main_language' => new sfValidatorString(),
      'to_delete'     => new sfValidatorInteger(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'W3sLanguage', 'column' => array('id')))
    );

    $this->widgetSchema->setNameFormat('w3s_language[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'W3sLanguage';
  }


}
