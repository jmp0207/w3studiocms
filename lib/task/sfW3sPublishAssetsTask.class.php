<?php

/**
 * Publishes Web Assets for third party themes
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage task
 * @author     Yevgeniy Viktorov <wik@osmonitoring.com>
 * @
 */
class sfW3sPublishAssetsTask extends sfBaseTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('web_dir', null, sfCommandOption::PARAMETER_REQUIRED, 'path/to/web/dir', sfConfig::get('sf_web_dir')),
      new sfCommandOption('themes_dir', null, sfCommandOption::PARAMETER_REQUIRED, 'path/to/themes/dir', sfConfig::get('sf_themes_dir', sfConfig::get('sf_root_dir').DIRECTORY_SEPARATOR.'themes')),
    ));

    $this->namespace = 'w3s';
    $this->name = 'publish-assets';

    $this->briefDescription = 'Publishes web assets for all themes';

    $this->detailedDescription = <<<EOF
The [w3s:publish-assets|INFO] task will publish web assets from all themes.

  [./symfony w3s:publish-assets|INFO]

EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $themeImport = new w3sThemeImport();
    $events = $themeImport->publishAssets($arguments, $options);

    foreach($events as $event)
    {
      $this->dispatcher->notify(new sfEvent($this, 'application.log', array($event)));
    }
  }
}
