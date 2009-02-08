<?php

/**
 * W3sContent form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 12815 2008-11-09 10:43:58Z fabien $
 */
class BaseW3sContentForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'group_id'         => new sfWidgetFormPropelChoice(array('model' => 'W3sGroup', 'add_empty' => false)),
      'page_id'          => new sfWidgetFormPropelChoice(array('model' => 'W3sPage', 'add_empty' => false)),
      'language_id'      => new sfWidgetFormPropelChoice(array('model' => 'W3sLanguage', 'add_empty' => false)),
      'content_type_id'  => new sfWidgetFormPropelChoice(array('model' => 'W3sContentType', 'add_empty' => false)),
      'slot_id'          => new sfWidgetFormPropelChoice(array('model' => 'W3sSlot', 'add_empty' => false)),
      'content'          => new sfWidgetFormTextarea(),
      'edited'           => new sfWidgetFormInput(),
      'to_delete'        => new sfWidgetFormInput(),
      'content_position' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorPropelChoice(array('model' => 'W3sContent', 'column' => 'id', 'required' => false)),
      'group_id'         => new sfValidatorPropelChoice(array('model' => 'W3sGroup', 'column' => 'id')),
      'page_id'          => new sfValidatorPropelChoice(array('model' => 'W3sPage', 'column' => 'id')),
      'language_id'      => new sfValidatorPropelChoice(array('model' => 'W3sLanguage', 'column' => 'id')),
      'content_type_id'  => new sfValidatorPropelChoice(array('model' => 'W3sContentType', 'column' => 'id')),
      'slot_id'          => new sfValidatorPropelChoice(array('model' => 'W3sSlot', 'column' => 'id')),
      'content'          => new sfValidatorString(),
      'edited'           => new sfValidatorInteger(),
      'to_delete'        => new sfValidatorInteger(),
      'content_position' => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('w3s_content[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'W3sContent';
  }


}
