<?php

/**
 * Publishes Web Assets for third party themes
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage task
 * @author     Yevgeniy Viktorov <wik@osmonitoring.com>
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
    $themesDir = $options['themes_dir'];
    $exclude = array('.registry','.svn', '.gitignore','.channels');
    if (is_dir($themesDir))
    {
      foreach (new DirectoryIterator($themesDir) as $file)
      {
        if (true === $file->isDir() && !$file->isDot())
        {
          if (!in_array($file->getFileName(), $exclude))
          {
            $theme = $file->getFileName();
            $webDir = $themesDir.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.'web';
            if (is_dir($webDir))
            {
              $filesystem = new sfFilesystem();
              // remove first
              if (is_dir($options['web_dir'].DIRECTORY_SEPARATOR.$theme))
              {
                $this->dispatcher->notify(new sfEvent($this, 'application.log', array('Unpublishing assets for '.$theme)));
                $filesystem->remove($options['web_dir'].DIRECTORY_SEPARATOR.$theme);
              }

              $this->dispatcher->notify(new sfEvent($this, 'application.log', array('Publishing assets for '.$theme)));
              $filesystem->symlink($webDir, $options['web_dir'].DIRECTORY_SEPARATOR.$theme, true);
            }
          }
        }
      }
    }
  }
}
