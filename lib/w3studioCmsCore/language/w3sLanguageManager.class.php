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
 * w3sLanguageManager extends the functionality of a w3sLanguage object,
 * givin it the ability to add, edit, delete a language.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sLanguageManager
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sLanguageManager
{
  protected $language;
  	  
  /**
   * Constructor.
   * 
   * @param object  The w3sLanguage object. Can be null when adding
   *
   */                         
  public function __construct($language = null)
  {
  	$this->language = ($language == null) ? new W3sLanguage() : $language;
  }
  
  public function getLanguage()
  {
  	return $this->language;
  }
  
  /**
   * Adds a new language to w3studioCMS
   * 
   * @param  array An array with the following options:
   * 								languageName - The new language's name
   * 								isMain - 1 to set the new language as main language
   * 
   * @return int - The result of the add operation
   *  
   */
  public function add($params)
  {
    if(is_array($params))
    {  
    	
	    if ($diff = array_diff_key(array('languageName' => ''), $params))
	    {
	      throw new InvalidArgumentException(sprintf('%s requires the following options: \'%s\'.', get_class($this), implode('\', \'', $diff)));
	    }
		    
	    try
	    {
		    
		    $con = Propel::getConnection();
		  
		    // We assure that all the operations W3StudioCMS makes will be successfully done
		    $bRollBack = false;
		    $con = w3sPropelWorkaround::beginTransaction($con); 
		    
		    // Retrieves the site's main language 
		    $mainLanguage = W3sLanguagePeer::getMainLanguage();
		    
		    // Resets the column mainLanguage is the new one will be the main language
		    if ($params["isMain"] == 1) $bRollBack = !$this->resetMain($mainLanguage);
		    
		    if (!$bRollBack){
	        
	        // Saves the language
          $languageName = w3sCommonFunctions::slugify($params["languageName"]);
	        $this->language->setLanguage($languageName);
	        $this->language->setMainLanguage($params["isMain"]);
	        $result = $this->language->save();
	        if ($this->language->isModified() && $result == 0)
	        {
	          $bRollBack = true;
	        }
	        else{
	          
	          // Copies the contents from the main language to the new one
	          $contents = $this->getReleatedContents($mainLanguage); 
	          foreach($contents as $content)
	          {
	          	$params = array("LanguageId" 			=> $this->language->getId(),
		                          "PageId" 					=> $content->getPageId(),
		                          "GroupId" 				=> $content->getGroupId(),
		                          "SlotId" 					=> $content->getSlotId(),
		                          "ContentTypeId" 	=> $content->getContentTypeId(),
		                          "Content" 				=> $content->getContent(),
		                          "ContentPosition" => $content->getContentPosition(),
		                          "Edited" 					=> '1'); 
		          $contentManager = w3sContentManagerFactory::create($content->getContentTypeId(), $content);
		  				$contentManager->setUpdateForeigns(false);
		  				$result =  $contentManager->add($params);
		          if ($result == 1)
		          { 
		          	if (!w3sContentManagerMenuPeer::copyRelatedElements($content->getId(), $contentManager->getContent()->getId()))
		          	{
		          		$bRollBack = true;
		          		break;
		          	}
		          }
		          else
		          {
		          	$bRollBack = true;
		          	break;
		          }
	          }
	        }
		    } 
		    
		    if (!$bRollBack){   // Everything was fine so W3StudioCMS commits to database
		      $con->commit();
		      $result = 1;
		    }
		    else{               // Something was wrong so W3StudioCMS aborts the operation and restores to previous status
		      w3sPropelWorkaround::rollBack($con);
		      $result = 0;
		    }
	    }
	    catch(Exception $e)
	    { 
	      $result = 0;
	      w3sPropelWorkaround::rollBack($con);	      
	      sfContext::getInstance()->getLogger()->info("W3StudioCMS - An Error occoured while addin a language. The exception message is:\n" . $e->getMessage() . "\n");
	    }
    }
	  else
	  {   
		  $result = 0;
		  throw new InvalidArgumentException('Add needs an array as parameter with the required option languageName');
    }   
    
    return $result;    
  }
  
  /**
   * Edits an existing language
   * 
   * @param  array An array with the following options:
   * 								languageName - The new language's name
   * 								isMain - 1 to set the new language as main language
   * 
   * @return int The result of the edit operation
   *  
   */
  public function edit($params)
  {
    try
    {
	    if($this->language != null)
      {
	    	$con = Propel::getConnection();
	  
		    // We assure that all the operations W3StudioCMS makes will be successfully done
		    $bRollBack = false;
		    $con = w3sPropelWorkaround::beginTransaction($con); 

        if(isset($params["isMain"]))
        {         
          if($this->language->getMainLanguage() != $params["isMain"] && $params["isMain"] == 1)
          {
            
            // If the language is declared as main, resets the previuos
            $bRollBack = !$this->resetMain();
            if (!$bRollBack) $this->language->setMainLanguage($params["isMain"]);
          }
          else
          {
            w3sPropelWorkaround::rollBack($con);
            return 4;
          }
        }

        if (!$bRollBack)
        {
          if(isset($params["languageName"]) && $this->language->getLanguage() != $params["languageName"]) $this->language->setLanguage($params["languageName"]);

          $result = $this->language->save(); 
          if ($this->language->isModified() && $result == 0) $bRollBack = true;
        }
        
	      if (!$bRollBack){   // Everything was fine so W3StudioCMS commits to database
		      $con->commit();
		      $result = 1; 
		    }
		    else{               // Something was wrong so W3StudioCMS aborts the operation and restores to previous status
		      w3sPropelWorkaround::rollBack($con);
		      $result = 0;
		    }    
	    }
	    else{
	      $result = 2; 
	    }
    }
    catch(Exception $e){ 
      w3sPropelWorkaround::rollBack($con);
      $result = 0;
      sfContext::getInstance()->getLogger()->info("W3StudioCMS - An Error occoured while addin a language. The exception message is:\n" . $e->getMessage() . "\n");
    }
    
    return $result;    
  }
  
  /**
   * Deletes the current language 
   * 
   * @param  int The value related to the operation to perform.
   *                 0 - Restore content
   * 								 1 - Delete content 
   *
   * @return int - The result of the delete operation
   *                  
   */
  public function delete($op = 1)
  {
    $result = false;  
    if ($this->language != null)
    {
      if ($this->language->getMainLanguage() != 1)
	    {
	    
	      // We assure that all the operations W3StudioCMS makes will be successfully done
	      $con = Propel::getConnection();
	      
	      $rollBack = false;
	    	$con = w3sPropelWorkaround::beginTransaction($con); 
	      $this->language->setToDelete($op);
	      $result = $this->language->save();
	      if ($this->language->isModified() && $result == 0)
	      {
	        $rollBack = true;
	      }
	      else
	      {
	        $rollBack = ($this->deleteRelatedContents($op)) ? false : true;
	      }
	      
	      if (!$rollBack)
		    {   // Everything was fine so W3StudioCMS commits to database
		      $con->commit();
		      $result = 1;
		    }
		    else
		    {   // Something was wrong so W3StudioCMS aborts the operation and restores to previous status
		      w3sPropelWorkaround::rollBack($con);
		      $result = 0;
		    }
	    
	    }
	    else
	    {
	    	$result = 2;
	    }
    }
   
    return $result;
  }
  
  /**
   * Deletes the language's contents that belongs to the current language object 
   *
   * @param  int The value related to the operation to perform.
   *                 0 - Restore content
   * 								 1 - Delete content 
   * 
   * @return bool false - The save operation failed
   *              true  - Operation success
   */
  protected function deleteRelatedContents($op = 1)
  {
  	$result = true;
  	$contents = $this->getReleatedContents($this->language); 
    foreach($contents as $content)
    {
      $contentManager = w3sContentManagerFactory::create($content->getContentTypeId(), $content);
			$contentManager->setUpdateForeigns(false);
			$result =  $contentManager->delete($op);
      if ($result != 1)
      { 
      	$bRollBack = false;
      	break;
      }
    }
    
    return $result;
  }
  
  /**
   * Changes the current main language as normal language
   *
   * @param  object optional The current main language 
   * 
   * @return bool false - The save operation failed
   *              true  - Operation success
   */
  private function resetMain($mainLanguage = null)
  {
  	$result = true;
  	if ($mainLanguage == null) $mainLanguage = W3sLanguagePeer::getMainLanguage();
  	$mainLanguage->setMainLanguage(0);
	  $result = $mainLanguage->save(); 
	  if ($mainLanguage->isModified() && $result == 0) $result = false;

	  return $result;
  }
  
  /**
   * Retrieves the contents related to the language passed as parameter
   *
   * @param  object The language which retrieve the contents 
   * 
   * @return object The related contents
   *              
   */
  private function getReleatedContents($language)
  {
  	$c = new Criteria();
	  $c->add(W3sContentPeer::TO_DELETE, '0');
	  return $language->getW3sContents($c); 
  }
}