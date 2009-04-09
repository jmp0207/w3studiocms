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
 * w3sPageManager extends the functionality of a w3sPage object,
 * givin it the ability to add, edit, delete a page.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sPageManager
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sPageManager
{
  protected $page;
  	  
  /**
   * Constructor.
   * 
   * @param int  The content's type
   * @param object  The w3sContent object. Can be null when adding
   *
   */                         
  public function __construct($page = null)
  {
  	$this->page = ($page == null) ? new W3sPage() : $page;
  }
  
  public function getPage()
  {
  	return $this->page;
  }
   
  /**
   * Adds a page and inserts default contents where content is available
   *
   * @param  string The name of the page to save
   * @param  int The id of the related group
   * 
   * @return int 0 - The save operation failed
   *             1 - Success
   *             2 - Page already exists
   */
  public function add($pageName, $idGroup){
  
  	try
    {
	    $pageName = trim($pageName);
	    if ($pageName != '')
	    {
		    // Checks if page already exists
        $pageName = w3sCommonFunctions::slugify($pageName);
		    $oPage = W3sPagePeer::getFromPageName($pageName);
		    if ($oPage == null)
		    {
		      if ($idGroup != null)
	    		{ 
			      if (DbFinder::from('W3sGroup')->findPK($idGroup) != null)
		    		{
				      $con = Propel::getConnection();
				      
				      // We assure that all the operations W3StudioCMS makes will be successfully done
				      $rollBack = false;
				      $con = w3sPropelWorkaround::beginTransaction($con); 
				    
				      // Save page
				      $this->page->setGroupId($idGroup);
				      $this->page->setPageName($pageName);
				      $result = $this->page->save();
				      if ($this->page->isModified() && $result == 0)
				      {
				        $rollBack = true;
				      }      
				      else
				      {
				        
				        // Getting the page's id inserted yet
				        $idPage = $this->page->getId();
				      	$attributes = w3sTemplateEngine::retrieveTemplateAttributesFromPage($this->page);
				      	$templateContents = w3sCommonFunctions::readFileContents(sprintf("%1\$s%2\$s%3\$s%2\$sdata%2\$s%4\$s.yml", sfConfig::get('app_w3s_web_templates_dir'), DIRECTORY_SEPARATOR, $attributes["projectName"], $attributes["templateName"]));
				      	
				      	$defaultContents = sfYaml::load($templateContents);
				      	$defaultContents = $defaultContents["W3sContent"];
				      	
				        // Inserts the contents for every language
				        $oLanguages =  DbFinder::from('W3sLanguage')->
				        												 where('ToDelete', '0')->
				        												 find(); 
				        foreach ($oLanguages as $language)
				        {
				          $idLanguage = $language->getId();
				          
				          // Cycles the slot's three repeated contents status 
				          for ($i=0; $i<=2; $i++)
				          {
					          // Retrieve the slots with the $i status for the page's template
					          $slots = W3sSlotPeer::getTemplateSlots($attributes["idTemplate"], $i);
					          foreach ($slots as $slot)
					        	{
					        		echo $slot->getRepeatedContents();

                      // Retrieves a content that belongs to the current slot
                      $baseContent = DbFinder::from('W3sContent')->
                                               where('SlotId', $slot->getId())->
                                               where('LanguageId', $idLanguage)->
                                               findOne();
                      // When no repeated content, the content is simply copied
                      if (($slot->getRepeatedContents() == 0) || ($baseContent == null))
                      {
                        $slotName = $slot->getSlotName();
                        foreach ($defaultContents as $defaultContent)
                        {
                          if ($defaultContent['slotId'] == $slotName)
                          {
                            $content = w3sContentManagerFactory::create($defaultContent["contentTypeId"]);
                            $contentValue = array(
                              "PageId"          => $idPage,
                              "SlotId"          => $slot->getId(),
                              "LanguageId"      => $idLanguage,
                              "GroupId"         => $idGroup,
                              "Content"         => $defaultContent["content"],
                              "ContentTypeId"   => $defaultContent["contentTypeId"],
                              "ContentPosition" => $defaultContent["contentPosition"],
                              "Edited"          => 1
                            );
                            $content->setUpdateForeigns(false);
                            $content->add($contentValue);
                          }
                        }
                      }
                      else
                      {
                        // Retrive base page, if any, but NOT current one
                        $pageFinder = DbFinder::from('W3sPage')->whereToDelete(0)->whereIdNot($idPage);
                        if ($basePage = $pageFinder->findOne())
                        {
                          // Retrieve a content that belongs to the current slot
                          $baseContents = DbFinder::from('W3sContent')->whereToDelete(0)->
                            relatedTo($basePage)->relatedTo($slot)->
                            whereLanguageId($idLanguage)->find();

                          foreach ($baseContents as $baseContent)
                          {
                            // Creates a new content from the base content
                            $newContent = w3sContentManagerFactory::create($baseContent->getContentTypeId(), $baseContent);

                            // Converts the content in array and changes the page's id and the group's id
                            $contentValue = W3sContentPeer::contentToArray($newContent->getContent());
                            $contentValue["PageId"] = $idPage;
                            $contentValue["GroupId"] = $idGroup;

                            // Updates the content and copies the related elements
                            $newContent->setUpdateForeigns(false);
                            $newContent->add($contentValue);
                            w3sContentManagerMenuPeer::copyRelatedElements($baseContent->getId(), $newContent->getContent()->getId());
                          }
                        }
					        		}
					        	}
				          }
				        }
				      }
				      if (!$rollBack)
				      {   // Everything was fine so W3StudioCMS commits to database
				        $con->commit();
				        $result = 1;
				      }
				      else
				      {              // Something was wrong so W3StudioCMS aborts the operation and restores to previous status
				        w3sPropelWorkaround::rollBack($con);
				        $result = 0;
				      }
				    }
				    else
				    {
				      $result = 16;
				    }
			    }
			    else
			    {
			      $result = 8;
			    }
		    }
		    else
		    {
		      $result = 2;
		    }  
		  }
	    else
	    {
	      $result = 4;
	    }       
    }
    catch(Exception $e)
    { 
      w3sPropelWorkaround::rollBack($con);
      $result = 0;
      sfContext::getInstance()->getLogger()->info("W3StudioCMS - An Error occoured while addin a language. The exception message is:\n" . $e->getMessage() . "\n");
    } 

    return $result;
  }
  
  /**
   * Deletes the current page object 
   * 
   * @parameter  int The value related to the operation to perform.
   *                 0 - Restore content
   * 								 1 - Delete content 
   *
   * @return     bool false - The save operation failed
   *                  true  - Operation success
   */
  public function delete($op = 1)
  {
    $con = Propel::getConnection();

    // We assure that all the operations W3StudioCMS makes will be successfully done
    $rollBack = false;
    $con = w3sPropelWorkaround::beginTransaction($con); 
    //$page = W3sPagePeer::retrieveByPk($idPage);
    if ($this->page != null)
    {
      $this->page->setToDelete($op);
      $result = $this->page->save();
      if ($this->page->isModified() && $result == 0)
      {
        $rollBack = true;
      }
      else
      {
        $rollBack = ($this->deletePageContents($op)) ? false : true;
      }
    }

    if (!$rollBack)
    {   // Everything was fine so W3StudioCMS commits to database
      $con->commit();
      $result = true;
    }
    else
    {   // Something was wrong so W3StudioCMS aborts the operation and restores to previous status
      w3sPropelWorkaround::rollBack($con);
      $result = false;
    }
   
    return $result;
  }
  
  /**
   * Deletes the contents that belongs to the current page object 
   *
   * @parameter  int The value related to the operation to perform.
   *                 0 - Restore content
   * 								 1 - Delete content 
   * 
   * @return     bool false - The save operation failed
   *                  true  - Operation success
   */
  protected function deletePageContents($op = 1)
  {
  	$result = true;
  	$contents = $this->page->getW3sContents();
    foreach($contents as $content)
    {
      $content->setToDelete($op);
      $saveResult = $content->save();
      if ($content->isModified() && $saveResult == 0)
      {
        $result = false;
        break;
      }
    }
    
    return $result;
  }
}
