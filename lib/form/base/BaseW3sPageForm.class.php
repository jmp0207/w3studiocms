<?php

/**
 * W3sPage form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 12815 2008-11-09 10:43:58Z fabien $
 */
class BaseW3sPageForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'group_id'  => new sfWidgetFormPropelChoice(array('model' => 'W3sGroup', 'add_empty' => false)),
      'page_name' => new sfWidgetFormInput(),
      'is_home'   => new sfWidgetFormInput(),
      'to_delete' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorPropelChoice(array('model' => 'W3sPage', 'column' => 'id', 'required' => false)),
      'group_id'  => new sfValidatorPropelChoice(array('model' => 'W3sGroup', 'column' => 'id')),
      'page_name' => new sfValidatorString(array('max_length' => 255)),
      'is_home'   => new sfValidatorInteger(),
      'to_delete' => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('w3s_page[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'W3sPage';
  }


}
