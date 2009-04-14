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
 * Publish the website.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sTemplateEnginePublisher
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sTemplateEnginePublisher extends w3sTemplateEngine
{
   /**
   * Overrides the standard contructor because this object doesn't need any parameter    *
   */
  public function __construct()
  {
  }

  /** 
   * Draws the contents' slot when in publish contents 
   * 
   * @param object   A slot object
   * 
   * @return string  The contents that belong to slot formatted as string
   * 
   */
  public function drawSlot($slot)
  {
  	$result = ''; 
    foreach ($slot['contents'] as $content)
    {
    	if ($content != null)
    	{	
	      $curContent = w3sContentManagerFactory::create($content->getContentTypeId(), $content);
	      $result .= $curContent->getDisplayContentForPublishMode($content);
    	}
    }   
    return $result;
  }

	/** 
   * Publish all the website's pages
   */
  public function publish()
  {
    if ($this->updateDb())
    {
	    
	    // Deletes the old directory with the previous published version
	  	w3sCommonFunctions::clearDirectory(sfConfig::get('app_w3s_web_published_dir'), array('.svn'));
	  
	  	// Retrieves all the website's languages and pages from the database
	    $languages = DbFinder::from('W3sLanguage')->find(); 
	    $pages = DbFinder::from('W3sPage')->
	    									 with('W3sTemplate', 'W3sProject')->  
					               leftJoin('W3sGroup')->
					               leftJoin('W3sTemplate')->
					               leftJoin('W3sProject')->
	    									 find();
	    
	    // Cycles all the website's languages
	    foreach($languages as $language)
      {
	    	
	    	// Creates the directory for the lanugage if doesn't exists
	    	if (!is_dir(sfConfig::get('app_w3s_web_published_dir') . '/' . $language->getLanguage())) mkdir(sfConfig::get('app_w3s_web_published_dir') . '/' . $language->getLanguage());
	    	
	    	// Cycles all the website's pages
	    	foreach($pages as $page)
        {
	    		
	    		// Retrieves the information needed to publish every single page
	    		$pageContents = '';
	    		$templateInfo = self::retrieveTemplateAttributesFromPage($page);
	    		$pageContents = w3sCommonFunctions::readFileContents(self::getTemplateFile($templateInfo["projectName"], $templateInfo["templateName"]));
	    		$this->idTemplate = $templateInfo["idTemplate"];
	    		
	    		// Renders ther page
	    		$slotContents = $this->getSlotContents($language->getId(), $page->getId()); 
	    		foreach ($slotContents as $slot)
          {
			      $contents = $this->drawSlot($slot);
            $pageContents = preg_replace('/\<\?php.*?include_slot\(\'' . $slot['slotName'] . '\'\).*?\?\>/', $contents, $pageContents);
            
            /*$pageContents = str_replace('<?php include_slot(\'' . $slot['slotName'] . '\')?>', $contents, $pageContents);*/
			    }
			    
			    // Renders the W3StudioCMS Copyright button. Please do not remove. See the function  
			    // declaration to learn the best way to implement it in your web site. 
			    // Thank you
			    $pageContents = $this->renderCopyright($pageContents);
			    
			    // Writes the page
		      $handle = fopen (sfConfig::get('app_w3s_web_published_dir') .  '/' . $language->getLanguage() . '/' . $page->getPageName() . '.php', "w");
		      fwrite($handle, $pageContents);
		      fclose($handle);	      
	   		}
	    }
    }
    else
    {
      return 0;
    }

    return 1;
  }
  
  /** 
   * Deletes from the database all the deleted languages, pages and contents 
   * during the edit phase
   * 
   * @return bool The operation result
   */
  private function updateDb(){
    try
    {
      $con = Propel::getConnection();
      $con = w3sPropelWorkaround::beginTransaction($con); 
      
      // Deletes the languages
      $c = new Criteria();
      $c->add(W3sLanguagePeer::TO_DELETE, 1);
      W3sLanguagePeer::doDelete($c);
      
      // Deletes the groups
      $c = new Criteria();
      $c->add(W3sGroupPeer::TO_DELETE, 1);
      W3sGroupPeer::doDelete($c);  
      
      // Deletes the pages
      $c = new Criteria();
      $c->add(W3sPagePeer::TO_DELETE, 1);
      W3sPagePeer::doDelete($c);
      
      // Deletes the contents
      $c = new Criteria();
      $c->add(W3sContentPeer::TO_DELETE, 1);
      W3sContentPeer::doDelete($c);
      
      $con->commit();
      
      return true;
    }
    catch(Exception $e)
    {
      w3sPropelWorkaround::rollBack($con);      
      sfContext::getInstance()->getLogger()->info('W3StudioCMS - ' . $this->setExceptionError($e->getMessage()));
      
      return false;
    }
  }
}