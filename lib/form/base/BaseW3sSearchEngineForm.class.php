<?php

/**
 * W3sSearchEngine form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 12815 2008-11-09 10:43:58Z fabien $
 */
class BaseW3sSearchEngineForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'page_id'            => new sfWidgetFormPropelChoice(array('model' => 'W3sPage', 'add_empty' => false)),
      'language_id'        => new sfWidgetFormPropelChoice(array('model' => 'W3sLanguage', 'add_empty' => false)),
      'meta_title'         => new sfWidgetFormTextarea(),
      'meta_description'   => new sfWidgetFormTextarea(),
      'meta_keywords'      => new sfWidgetFormTextarea(),
      'sitemap_changefreq' => new sfWidgetFormTextarea(),
      'sitemap_lastmod'    => new sfWidgetFormTextarea(),
      'sitemap_priority'   => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorPropelChoice(array('model' => 'W3sSearchEngine', 'column' => 'id', 'required' => false)),
      'page_id'            => new sfValidatorPropelChoice(array('model' => 'W3sPage', 'column' => 'id')),
      'language_id'        => new sfValidatorPropelChoice(array('model' => 'W3sLanguage', 'column' => 'id')),
      'meta_title'         => new sfValidatorString(),
      'meta_description'   => new sfValidatorString(),
      'meta_keywords'      => new sfValidatorString(),
      'sitemap_changefreq' => new sfValidatorString(),
      'sitemap_lastmod'    => new sfValidatorString(),
      'sitemap_priority'   => new sfValidatorString(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'W3sSearchEngine', 'column' => array('id')))
    );

    $this->widgetSchema->setNameFormat('w3s_search_engine[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'W3sSearchEngine';
  }


}
