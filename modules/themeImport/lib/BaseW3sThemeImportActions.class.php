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
  public function executeShow()
  {
  	$theme = new w3sThemeImport();
  	
  	return $this->renderPartial('show', array('theme' => $theme));
  }
  
  public function executeAdd()
  {
  	$theme = new w3sThemeImport();
  	$result = $theme->add($this->getRequestParameter('projectName'));
  	
  	if ($result != 1)
  	{
  		$this->getResponse()->setStatusCode(404);
  		$message = __('An error occoured while saving record: try again.');
  		return $this->renderText(w3sCommonFunctions::displayMessage($message, 'error', true));
  	}
  	else
  	{
  		return $this->renderPartial('refresh');
  	}
  }
  
  public function executeExtract()
  {
    if ($handle = opendir(sfConfig::get('app_w3s_web_templates_dir')))
    {
      while (false !== ($file = readdir($handle)))
      {
        $currentFile = sfConfig::get('app_w3s_web_templates_dir') . DIRECTORY_SEPARATOR . $file;
        if (is_file($currentFile))
        {
          $fileInfo = pathinfo($currentFile);
          if ($fileInfo['extension'] == 'zip')
          {
            if (w3sCommonFunctions::extractZipFile($currentFile, sfConfig::get('app_w3s_web_templates_dir')))
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