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
 
/**
 * w3sProjectImport class represents the interface to manage the templates.
 * This class must be deeply tested.
 *  
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sProjectImport
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sProjectImport implements w3sEditor
{
	protected 
		$interfaceSkeleton = 
			'<div id="%s">
				<table style="width:100%%">
	        <tr><td><div class="w3s_toolbar"><table>%s</table></div></td></tr>
					<tr><td id="w3s_template_import_contents" valign="top">%s</td></tr>
					<input type="submit" id="w3s_uploader_support" onclick="W3sTemplateImport.extract(); return false;" />
				</table>
			 </div>',	  
		$toolbarFile = 'tbTemplateImport.yml',
		$projects = array(),
		$defaultInfo = array("Author" => '', "License" => '');
		
	public function render()
	{
		return sprintf($this->interfaceSkeleton, 'w3s_import_templates', 
																						 $this->renderToolbar(), 
																						 $this->renderProjects(DbFinder::from('W3sProject')->find()));
	}
	
	public function setDefaultInfo($array = null)
	{
		$this->defaultInfo = $array;
	} 
	
	protected function renderToolbar()
	{
		$toolbar = new w3sToolbarHorizontal(); 
    $toolbar->fromYml($this->toolbarFile);
    
    return $toolbar->renderToolbar();
	}
	
	public function renderProjects()
  {
    $projectRows = '';
    
    if ($handle = opendir(sfConfig::get('app_w3s_web_templates_dir'))){
	    while (false !== ($file = readdir($handle)))
	    {
	    	if ($file != "." && $file != ".." && $file != ".svn")
	    	{
	        $project = DbFinder::from('W3sProject')->
	        										 where('ProjectName', $file)->
	        										 findOne();
	        $exists = count($project);    
		    	
		    	$projectRows .= sprintf('<tr><td style="text-align:left;">%s</td></tr>', $this->renderProjectRow($file, $exists));
	    	}
	    }
    }
    
    return sprintf('<table id="w3s_table_import">%s</table>', $projectRows);;
  }
  
  protected function renderProjectRow($projectName, $exists)
  {
    $rowSchema = '<span class="info_header">%s:</span> %s<br />';
    $defaultImage = sfConfig::get('app_w3s_web_images_dir') .  '/common/template_sample.png';
    $infoDir = sprintf("%1\$s%2\$s%3\$s%2\$sinfo%2\$s", sfConfig::get('app_w3s_web_templates_dir'), DIRECTORY_SEPARATOR, $projectName);
    $fileInfo = $infoDir . 'info.yml';
   	$info = (is_file($fileInfo)) ? sfYaml::load($fileInfo) : null;
   	if ($info != null)
   	{
   		$info = $info["Info"];
   		$info['Image'] = $infoDir . $info['Image'];
   		if (!is_file($info['Image'])) $info['Image'] = $defaultImage; 
   	}
   	else
   	{
   		$info = array('Author' => 'Unknown', 'License' => 'Unknown', 'Image' => $defaultImage);
   	}
   	
   	$projectRows = '';
    $projectInfo = array_intersect_key($info, $this->defaultInfo);    
   	$addInfo = array('Project name' => $projectName);
   	$projectInfo = array_merge($addInfo, $projectInfo);
   	   	
   	foreach($projectInfo as $key => $value)
    {
    	$projectRows .= sprintf($rowSchema, $key, $value);	
    } 
    $projectRows .= sprintf($rowSchema, __('Project\'s templates'), count($this->scanProject($projectName)));
    
    $operation = '';
    if ($exists == 0)
    {
    	$operation = link_to_function(__('Add'), 'W3sTemplateImport.add(\'' . $projectName . '\')', 'class="link_button"');
    }
    
    return sprintf('<tr><td style="width:96px;">%s</td><td valign="bottom" style="width:250px;"><p>%s</p></td><td valign="bottom">%s</td></tr>', image_tag($info['Image'], "class=template_image"), $projectRows, $operation);
  }
  
  protected function scanProject($projectName)
  {
    $result = array();
    $projectDir = sfConfig::get('app_w3s_web_templates_dir')  . DIRECTORY_SEPARATOR . $projectName;
    if ($handle = opendir($projectDir))
    {
      while (false !== ($file = readdir($handle)))
      {
        if ($file != "." && $file != ".." && $file != ".svn")
        {
          $currentFile = $projectDir . DIRECTORY_SEPARATOR . $file;
          if (is_dir($currentFile) && $file != 'info')
          {
            $project_dir = $currentFile;
						$result[] = $file;
          }
        }
      }
      closedir($handle);
    }
    
    return $result;
  }
  
  protected function getSlotsFromTemplate($projectName, $templateName)
  {	
  	$templateFile = sprintf("%1\$s%2\$s%3\$s%2\$s%4\$s%2\$s%4\$s.php", sfConfig::get('app_w3s_web_templates_dir'), DIRECTORY_SEPARATOR, $projectName, $templateName);
  	$contents = w3sCommonFunctions::readFileContents($templateFile);	  
	  preg_match_all('/include_slot\s*\(\s*[\'|"](.*?)[\'|"]\s*\)/', $contents, $matchResults);
	  
		return $matchResults[1];
  }
  
  public function add($projectName)
  {
    $bRollBack = false;
    $con = Propel::getConnection();
    $con = w3sPropelWorkaround::beginTransaction($con); 
    
    $project = new W3sProject;
    $project->setProjectName($projectName);
    $result = $project->save();
    if ($project->isModified() && $result == 0) $bRollBack = true;
     
    if (!$bRollBack)
    {
	    $idProject = $project->getId();	    
	    $templates = $this->scanProject($projectName);	   	
	   	foreach ($templates as $templateName)
	   	{ 
	    	$template = new W3sTemplate;
	      $template->setProjectId($idProject);
	      $template->setTemplateName($templateName);
	      $result = $template->save();
	      if ($template->isModified() && $result == 0)
	      {
	        $bRollBack = true;
	        break;
	      }
	      
	      if (!$bRollBack)
    		{
		      $idTemplate = $template->getId();
		      $slots = $this->getSlotsFromTemplate($projectName, $templateName);
		      foreach($slots as $slotName)
		      {
		      	$slot = new W3sSlot;
			      $slot->setTemplateId($idTemplate);
			      $slot->setSlotName($slotName);
			      $result = $slot->save();
			      if ($slot->isModified() && $result == 0)
			      {
			        $bRollBack = true;
			        break;
			      }
		      }
    		}
	   	}
    }
    
    if (!$bRollBack)
    { // Everything was fine so W3StudioCMS commits to database
      $con->commit();
      $result = 1;
    }
    else
    { // Something was wrong so W3StudioCMS aborts the operation and restores to previous status
      w3sPropelWorkaround::rollBack($con);
      $result = 0;
    }
    
    return $result;
  }
  
  /*
  protected function renderAvailableProjects()
  {
    $result = array();
    $projectRows = '';
    $loadedProjects = $this->scanProjects();
    $availableProjects = array_diff($loadedProjects, $this->projects); 
    foreach ($availableProjects as $availableProject)
    {
    	$projectRows .= sprintf('<tr><td style="text-align:left;">%s</td></tr>', $availableProject);	
    }
    
    return sprintf('<table>%s</table>', $projectRows);;
  }
  
  protected function renderTemplates()
  {
    $templates = DbFinder::from('W3sTemplate')->  
		                      leftJoin('W3sProject')->
		                      where('W3sProject.InUse', '1')->
		                      find();   
    $templateRows = '';
    foreach ($templates as $template)
    {
    	$templateRows .= sprintf('<tr><td style="text-align:left;">%s</td></tr>', $template->getTemplateName());	
    }
    
    return sprintf('<table>%s</table>', $templateRows);;
  }*/
	
	
  
	
	 
}