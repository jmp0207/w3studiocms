<?php

/**
 * W3sGroup form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 12815 2008-11-09 10:43:58Z fabien $
 */
class BaseW3sGroupForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'template_id' => new sfWidgetFormPropelChoice(array('model' => 'W3sTemplate', 'add_empty' => false)),
      'group_name'  => new sfWidgetFormInput(),
      'edited'      => new sfWidgetFormInput(),
      'to_delete'   => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorPropelChoice(array('model' => 'W3sGroup', 'column' => 'id', 'required' => false)),
      'template_id' => new sfValidatorPropelChoice(array('model' => 'W3sTemplate', 'column' => 'id')),
      'group_name'  => new sfValidatorString(array('max_length' => 255)),
      'edited'      => new sfValidatorInteger(),
      'to_delete'   => new sfValidatorInteger(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'W3sGroup', 'column' => array('id')))
    );

    $this->widgetSchema->setNameFormat('w3s_group[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'W3sGroup';
  }


}
