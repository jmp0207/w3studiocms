<?php

/**
 * W3sTemplate form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 12815 2008-11-09 10:43:58Z fabien $
 */
class BaseW3sTemplateForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'project_id'    => new sfWidgetFormPropelChoice(array('model' => 'W3sProject', 'add_empty' => false)),
      'template_name' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorPropelChoice(array('model' => 'W3sTemplate', 'column' => 'id', 'required' => false)),
      'project_id'    => new sfValidatorPropelChoice(array('model' => 'W3sProject', 'column' => 'id')),
      'template_name' => new sfValidatorString(array('max_length' => 255)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'W3sTemplate', 'column' => array('id')))
    );

    $this->widgetSchema->setNameFormat('w3s_template[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'W3sTemplate';
  }


}
