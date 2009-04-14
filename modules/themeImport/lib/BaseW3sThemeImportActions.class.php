<?php
/*
 * This file is part of the w3studioCMS package library and it is distributed 
 * under the LGPL LICENSE Version 2.1. To use this library you must leave 
 * intact this copyright notice.
 *  
 * (c) 2007-2008 Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 *  
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.w3studiocms.com
 */

class BaseW3sThemeImportActions extends sfActions
{
  public function executeShow($request)
  {
  	$theme = new w3sThemeImport();
  	
  	return $this->renderPartial('show', array('theme' => $theme));
  }
  
  public function executeAdd($request)
  {
  	if($request->hasParameter('themeName'))
    {
      $theme = new w3sThemeImport();
      $result = $theme->add($this->getRequestParameter('themeName'));

      if (!$result)
      {
        $this->getResponse()->setStatusCode(404);
        $message = w3sCommonFunctions::toI18n('An error occoured while addinf the new theme.');
        return $this->renderText(w3sCommonFunctions::displayMessage($message, 'error', true));
      }
      else
      {
        $options = array('web_dir' => sfConfig::get('sf_web_dir'),
                         'themes_dir' => sfConfig::get('sf_themes_dir', sfConfig::get('sf_root_dir').DIRECTORY_SEPARATOR.'themes'));
        $theme->publishAssets(array(), $options);

        return $this->renderPartial('refresh');
      }
    }
    else
    {
      $this->getResponse()->setStatusCode(404);
      $message = w3sCommonFunctions::toI18n('themeName parameter is required.');
      return $this->renderText(w3sCommonFunctions::displayMessage($message, 'error', true));
    }
  }

  public function executeRemove($request)
  {
  	if($request->hasParameter('themeName'))
    {
      $theme = new w3sThemeImport();
      $theme->remove($request->getParameter('themeName'));

      return $this->renderPartial('refresh');
    }
    else
    {
      $this->getResponse()->setStatusCode(404);
      $message = w3sCommonFunctions::toI18n('themeName parameter is required.');
      return $this->renderText(w3sCommonFunctions::displayMessage($message, 'error', true));
    }
  }
  
  public function executeExtract($request)
  {
    if ($handle = opendir(sfConfig::get('app_w3s_web_themes_dir')))
    {
      while (false !== ($file = readdir($handle)))
      {
        $currentFile = sfConfig::get('app_w3s_web_themes_dir') . DIRECTORY_SEPARATOR . $file;
        if (is_file($currentFile))
        {
          $fileInfo = pathinfo($currentFile);
          if ($fileInfo['extension'] == 'zip')
          {
            if (w3sCommonFunctions::extractZipFile($currentFile, sfConfig::get('app_w3s_web_themes_dir')))
            {
              unlink($currentFile);               
            }  
          }  
        }
      }
    }
    closedir($handle);

    return $this->renderPartial('refresh');
  }
}