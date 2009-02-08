<?php

/**
 * W3sSlot form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 12815 2008-11-09 10:43:58Z fabien $
 */
class BaseW3sSlotForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'template_id'       => new sfWidgetFormPropelChoice(array('model' => 'W3sTemplate', 'add_empty' => false)),
      'slot_name'         => new sfWidgetFormInput(),
      'repeated_contents' => new sfWidgetFormInput(),
      'to_delete'         => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorPropelChoice(array('model' => 'W3sSlot', 'column' => 'id', 'required' => false)),
      'template_id'       => new sfValidatorPropelChoice(array('model' => 'W3sTemplate', 'column' => 'id')),
      'slot_name'         => new sfValidatorString(array('max_length' => 255)),
      'repeated_contents' => new sfValidatorInteger(),
      'to_delete'         => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('w3s_slot[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'W3sSlot';
  }


}
