<?php
class w3sTemplateTools
{
  /**
   * Retrieves from an instance of the page object the informations about the template
   * used in the current page.
   * 
   * @param obj  An instance of the w3sPage object
   * 
   * @return array 
   *
   */
  public static function retrieveTemplateAttributesFromPage($page){
    
    if (!$page instanceof W3sPage) throw new RuntimeException(sprintf('This function requires a W3sPage class object. You passed an instance of %s object', get_class($page)));
    return array("idTemplate"   => $page->getW3sGroup()->getW3sTemplate()->getId(),
								 "templateName" => strtolower($page->getW3sGroup()->getW3sTemplate()->getTemplateName()),
                 "projectName"  => strtolower($page->getW3sGroup()->getW3sTemplate()->getW3sProject()->getProjectName()));
  }
  
  /**
   * Returns the template file path. 
   * 
   * @param str  Project name
   * @param str  Template name
   * 
   * @return array 
   *
   */
  public static function getTemplateFile($projectName, $templateName)
  {
    return sprintf("%1\$s%2\$s%3\$s%2\$s%4\$s%2\$s%4\$s.php", sfConfig::get('app_w3s_web_templates_dir'), DIRECTORY_SEPARATOR, $projectName, $templateName);
  }
}