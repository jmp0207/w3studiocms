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
 * w3sContentManager extends the functionality of a w3sContent object,
 * givin it the ability to add, edit and delete it on the web page.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sContentManager
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
abstract class w3sContentManager extends w3sTemplateEngineEditor
{
  protected 
  	$content,									   // A w3sContent object. Can be null when adding a new one
  	$updateForeigns = true,      // Updates or not the contents for a slot with the contents repeated 
  	$exceptionMessage,           // Defines the standard exception message to write in the log file
  															 // and/or gave back to user 
  	$contentType,                
  	$defaultParams = array('LanguageId'     	=> '0', 
													 'PageId' 			  	=> '0', 
													 'SlotId' 			  	=> '0', 
                           'GroupId' 					=> '0', 
                           'ContentTypeId' 		=> '0', 
                           'Content' 					=> '', 
                           'ContentPosition' 	=> '0',
                           'Edited' 					=> '1'),
  	$errors = array();           // An array with the error messages 
  	
  
  // Every derived content must give back a default content
  abstract function getDefaultText();
  
  /**
   * Constructor.
   * 
   * @param int  The content's type
   * @param object  The w3sContent object. Can be null when adding
   *
   */                         
  public function __construct($type, $content = null)
  {
  	$this->contentType = $type;	
  	$this->content = $content;
  	$this->exceptionMessage = w3sCommonFunctions::toI18n("An Error occoured while adding a content. The generated exception message is:\n%s\n");
  	$this->setMessageErrors();
  }

	/**
   * Sets the value of the contentType variable.
   * 
   * @param int
   *
   */  
	public function setContentType($value)
  {
  	$this->contentType = $value;           
  }
  
  /**
   * Returns the value of the contentType variable.
   * 
   * @return int
   *
   */  
	public function getContentType()
  {
  	return $this->contentType;           
  }
  
  /**
   * Sets the value of the content variable.
   * 
   * @param object 
   *
   */  
  public function setContent($value)
  {
    $this->content = $value; 
  }
  
  /**
   * Returns the value of the content variable.
   * 
   * @return object 
   *
   */  
  public function getContent()
  {
    return $this->content; 
  }
  
  /**
   * Sets the value of the updateForeigns variable.
   * 
   * @param bool 
   *
   */  
  public function setUpdateForeigns($value)
  {
    $this->updateForeigns = $value; 
  }
  
  /**
   * Returns the value of the updateForeigns variable.
   * 
   * @return bool
   *
   */  
  public function getUpdateForeigns()
  {
    return $this->updateForeigns; 
  }
  
  /**
   * Sets the value of the exceptionMessage variable.
   * 
   * @param string 
   *
   */  
  public function setExceptionMessage($value)
  {
    $this->exceptionMessage = $value; 
  }
  
  /**
   * Returns the value of the exceptionMessage variable.
   * 
   * @return string
   *
   */  
  public function getExceptionMessage()
  {
    return $this->exceptionMessage; 
  }
  
  /**
   * Adds a content.
   * 
   * @param object  The content placed over the adding content 
   *  
   * @return int - 1 success / xx The error message number
   *
   */ 
  public function add($param)
  { 
	  if (is_array($param))
	  {
	  	if ($diff = array_diff(array_keys($param), array_keys($this->defaultParams)))
	    {
	      throw new InvalidArgumentException(sprintf('%s does not support the following options: \'%s\'.', get_class($this), implode('\', \'', $diff)));
	    }
	  	if ($diff = array_diff(array_keys($this->defaultParams), array_keys($param)))
	    { 
	      throw new InvalidArgumentException(sprintf('%s requires the following options: \'%s\'.', get_class($this), implode('\', \'', $diff)));
	    }
	    $values = $param;
	    if ($values["GroupId"] == 0)
	    {
	    	$page = DbFinder::from('W3sPage')->findPK($values["PageId"]);
	    	$values["GroupId"] = $page->getGroupId();
	    } 
	  }	
	  else if ($param instanceof w3sContent)
	  {
	  	$values = W3sContentPeer::contentToArray($param);
	  	$values["ContentTypeId"] = $this->contentType;
			$values["ContentPosition"]++;
			$values["Edited"] = 1;
	  }		
	  else
    {
	  	throw new RuntimeException(sprintf('%s requires an array with the following options: %s or an w3sContent object ', get_class($this), array_keys($this->defaultParams)));
	  }
	  
    try
    {   
      $con = Propel::getConnection();
      
      $bRollBack = false;
      $con = w3sPropelWorkaround::beginTransaction($con);
			
		  // Moves the contents placed below the adding content one position down
		  if ($values["ContentPosition"] > 0)
		  {		  	
		  	if (!$this->makeSpace($values, 'add')) $bRollBack = true;
		  }
		  else
		  {
		  	$values["ContentPosition"] = 1;
		  }
		  
		  // When the Content is null the dafault text is inserted
		  if ($values["Content"] == '') $values["Content"] = $this->getDefaultText();
		  
		  if (!$bRollBack)
		  {  
		    // Saves the content
		    $this->content = new W3sContent(); 
		    $this->content->fromArray($values);
		    $result = $this->content->save(); 
		    if ($this->content->isModified() && $result == 0)
		    {
		      $this->content = null;
		      $bRollBack = true;
		    }
		    else
		    {
		    	$this->setDefaultRelatedElements();
		    	
		    	// The content has been added: checks if it must be repeated on related pages
		    	if ($this->updateForeigns)
		    	{ 
		    		$repeatedContent = new w3sRepeatedForeignContentsAdd($values);
		    		if (!$repeatedContent->update()) $bRollBack = true;
		    	}
		    }
		  }
		  
      if (!$bRollBack)
      {
        $con->commit();
        $result = 1;
      }
      else{
        w3sPropelWorkaround::rollBack($con); 
        $result = 2;
      }
    }
    catch(Exception $e)
    {
      if(isset($con)) w3sPropelWorkaround::rollBack($con);
      $result = 0;
      sfContext::getInstance()->getLogger()->info('W3StudioCMS - ' . $this->setExceptionError($e->getMessage()));
    }
    
    return $result;
  }
   
  /**
   * Edits a content.
   * 
   * @param array  An array with the values to edit. 
   *  
   * @return int - 1 success / xx The error message number
   *
   */ 
  public function edit($params)
  {
	  $con = null;
	  // Intersects the values passed as reference with the needed values 
	  // stored in the defaultParams array. 
	  //$this->defaultParams["Id"] = 0;  
    $values = array_intersect_key($params, $this->defaultParams);
    $contentValues = (array_key_exists('Content', $values)) ? $this->formatContent($values) : $values;
    
   	if (!empty($contentValues))
    {	    	
		  try
    	{ 
		    $con = Propel::getConnection();
		    $bRollBack = false;
		    //$con->begin();
		    $con = w3sPropelWorkaround::beginTransaction($con); 
		    
		    // Updates the foreign contents before editing the current content, 
	    	// because the updating process needs to refer to the original source 
	    	// content. For this reason it must be left unchanged since the 
	    	// updating process ends.
	    	if ($this->updateForeigns)
	    	{ 
	    		$repeatedContent = new w3sRepeatedForeignContentsEdit(W3sContentPeer::contentToArray($this->content, true), $values);
	    		if (!$repeatedContent->update()) $bRollBack = true;
	    	}
		    
		    // Edits the source content
		    if (!$bRollBack)
		    { 
			    $this->content->fromArray($contentValues);
				  $result = $this->content->save();
				  if ($this->content->isModified() && $result == 0) $bRollBack = true;
		    }
		    
			  if (!$bRollBack)
			  {   // Everything was fine so W3StudioCMS commits to database
		      $con->commit();
		      $result = 1;
		    }
		    else
		    {   // Something was wrong so W3StudioCMS aborts the operation and restores to previous status
		      //$con->rollback();
		      w3sPropelWorkaround::rollBack($con);
		      $result = 4;
		    }
    	}
	    catch(Exception $e)
	    { 
	      //$con->rollback();
	      if(isset($con)) w3sPropelWorkaround::rollBack($con);
	      $result = 0;
	      $this->setExceptionError($e->getMessage());
	      sfContext::getInstance()->getLogger()->info('W3StudioCMS - ' . $this->setExceptionError($e->getMessage()));
	    }
    }
    else{
    	$result = 16;
    }  
    
    return $result;
  }
  
  /**
   * Deletes a content. The content will be marked for deletion but it will be really 
   * deleted from the database when the pages will be published 
   * 
   * @param int optional the operation to do. 
   * 										 	0 Marks the content for undeletion
   * 											1 [Default] Marks the content for deletion   									 
   *   
   * @return int 1 success / xx The error message number
   *
   */ 
  public function delete($op = 1)
  {    
	  try
    {
	  	$bRollBack = false;
	  	$con = Propel::getConnection();  
		  $con = w3sPropelWorkaround::beginTransaction($con); 
		  
		  // Marks for deletion
		  $this->content->setToDelete($op); 
		  $result = $this->content->save();
		  if ($this->content->isModified() && $result == 0){ 
		  	$bRollBack = true;
		  }
		  else{
		  	
		  	$contentAttributes = W3sContentPeer::contentToArray($this->content);
		  	// Moves the contents placed below the adding content one position up		  	
		  	if ($this->makeSpace($contentAttributes, 'del'))
			  {        
		    	
		    	// The content has been added: checks if it must be repeated on related pages
		    	if ($this->updateForeigns)
		    	{ 
		    		$repeatedContent = new w3sRepeatedForeignContentsDelete($contentAttributes);
		    		if (!$repeatedContent->update()) $bRollBack = true;
		    	}
			  }
			  else
			  {
			  	$bRollBack = true;
			  }    
		  }
		  
		  if (!$bRollBack)
		  {   // Everything was fine so W3StudioCMS commits to database
	      $con->commit();
	      $result = 1;
	    }
	    else
	    {   // Something was wrong so W3StudioCMS aborts the operation and restores to previous status
	      w3sPropelWorkaround::rollBack($con);
	      $result = 8;
	    }
    }
    catch(Exception $e)
    { 
      w3sPropelWorkaround::rollBack($con);
      $result = 0;
      $this->setExceptionError($e->getMessage());
      sfContext::getInstance()->getLogger()->info('W3StudioCMS - ' . $this->setExceptionError($e->getMessage()));
    }
    
    return $result;
  }
  
  /**
   * Returns the content formatted to be correctly displayed in the editor mode.
   * The default value returned is simply the stored content.
   *   
   * @return string
   *
   */ 
  public function getDisplayContentForEditorMode()
  {
  	// Sets every href link to # 
  	$text = $this->content->getContent();   
  	preg_match_all('/\<a.*?(href=.*?)\>.*?\<\/a\>?/', $text, $result);
  	foreach($result[1] as $link)
  	{
  		$text = str_replace($link, 'href="#"', $text);
  	}
  	return $text;           
  }
  
  /**
   * Returns the content formatted to be correctly displayed in the preview mode.
   * The default value returned is the stored content with its links formatted 
   * to open the pages with ajax.
   *   
   * @return string
   *
   */ 
  public function getDisplayContentForPreviewMode()
  {
  	return w3sCommonFunctions::linkConverter($this->content, 'preview') ;           
  }
  
  /**
   * Returns the content formatted to be correctly displayed in the editor mode.
   * The default value returned is simply the stored content.
   *   
   * @return string
   *
   */ 
  public function getDisplayContentForPublishMode()
  {
  	return w3sCommonFunctions::linkConverter($this->content);          
  }
  
	/**
   * Redraws all the contents that belongs to the current slot.
   *
   * @return   string An html string with the slot's contents.
   */
  public function redraw()
  {
	  $slot = DbFinder::from('W3sSlot')->findPK($this->content->getSlotId());
	  $currentSlotContents = array();
	  $contents = DbFinder::from('W3sContent')->
					                where('LanguageId', $this->content->getLanguageId())->
					                where('PageId', $this->content->getPageId())->
					                where('SlotId', $this->content->getSlotId())->
					                where('ToDelete', 0)->
					                orderBy('ContentPosition')->
					                find();
	  if ($contents != null)
    {
	    foreach($contents as $content)
	    {
	      $currentSlotContents[] = $content;
	    }
    }
    else
    {
    	$currentSlotContents[] = null; 
    }    
    
    // Redraws the sortables for moving contents
    $this->idLanguage = $this->content->getLanguageId();
	  $this->idPage = $this->content->getPageId();
	  $this->idTemplate = $this->content->getW3sGroup()->getTemplateId();
    $this->setSortables();
	  
	  // Redraws the slot              							  
		return $this->drawSlot(array('contents' => $currentSlotContents, 
																 'idSlot' => $slot->getId(),
																 'slotName' => $slot->getSlotName(), 
																 'isRepeated' => $slot->getRepeatedContents(),
																 'setEventForRedraw' => 1));
														
  }
  
  /**
   * Returns the html error message that corresponds to a predefined error number  
   *
   * @param      int The error number.
   * @param      bool optional If true displays extra information
   * 
   * @return     string 
   */
  public function displayError($errorNumber, $showExtraInfo = false)
  {
  	/*
  	$errorMessage = sprintf('<p class="error_message">%s</p>', $this->errors[$errorNumber]);
  	if ($showExtraInfo) $errorMessage .= sprintf('<p style="font: 11px Verdana, Arial, Helvetica, sans-serif; padding:10px; text-align:left;">%s</p>',w3sCommonFunctions::toI18n('You can try to change page, reenter in this page and redo the operation you made.<br /><br />If problem persists you can try to logout, signin again and redo the operation you made.<br /><br />If problem persists too, reports the error to W3StudioCMS web site \'s forum or write to W3StudioCMS\'s assistance.')); 
  	*/
  	
  	$errorMessage = $this->errors[$errorNumber];
  	if ($showExtraInfo) $errorMessage .= w3sCommonFunctions::toI18n('You can try to change page, reenter in this page and redo the operation you made.<br /><br />If problem persists you can try to logout, signin again and redo the operation you made.<br /><br />If problem persists too, reports the error to W3StudioCMS web site \'s forum or write to W3StudioCMS\'s assistance.');
  	return w3sCommonFunctions::displayMessage($errorMessage);
  }
  
  public function getRelatedElements(){}
  
  protected function setDefaultRelatedElements(){}
  
  /**
   * Makes a space between contents, moving their positions of one unit 
   * up or down, according to the op param 
   * 
   * @param array   An array with the following keys:
   *                array("PageId"          => value,
	 *			                "SlotId"          => value,
	 *			                "LanguageId"      => value,
	 * 			                "ContentPosition" => value)
   * @param str  		The operation to do. Permitted values are "add - del"
   *   
   * @return bool
   *
   */ 
  protected function makeSpace($params, $op)
  {
    
    // Checks the $params parameter. If doesn't match, throwns and exception
    $required = array("PageId", "SlotId", "LanguageId", "ContentPosition");
    if ($diff = array_diff($required, array_keys($params))){
    	throw new RuntimeException(sprintf('The variable $params requires the following options: \'%s\'.', implode('\', \'', $required))); 
    }
    
    // Checks the $op parameter. If doesn't match, throwns and exception
    $required = array("add", "del");
    if (!in_array($op, $required)){
    	throw new RuntimeException(sprintf('The variable $op requires the following options: \'%s\'.', implode('\', \'', $required))); 
    }
    
    $bRollBack = false;
    $con = Propel::getConnection();
    $con = w3sPropelWorkaround::beginTransaction($con); 

    // Retrieves all the contents placed below the current content
    $contents = DbFinder::from('W3sContent')->
							  where('LanguageId', $params["LanguageId"])->
							  where('PageId', $params["PageId"])->
							  where('SlotId', $params["SlotId"])->
							  where('ContentPosition', '>=', $params["ContentPosition"])->
							  where('ToDelete', 0)->	
							  orderBy('ContentPosition')->
							  find(); 
    
    // If there are no contents, the user is modifing the last content
    if (count($contents))
    {
      foreach ($contents as $content){        
        
        /* When user is adding a content W3StudioCMS needs to create a space between the position
         * of the over content and the position of the bottom content, to give the new content 
         * that place. This will be made increasing by 1 the positions for all contents placed  
         * below the content to add.
         * 
         * If user is deleting the content, this creates an empty space between the contents'
         * positions. W3StudioCMS will decrement by 1 the positions of the contents below the
         * one the user have deleted 
         */ 
        $newPosition = ($op == 'add') ? $content->getContentPosition() + 1 : $content->getContentPosition() - 1;
        $content->setContentPosition($newPosition);
        $result = $content->save();
        if ($content->isModified() && $result == 0)
        {
          $bRollBack = true;
          break;
        }
      }
    }

    if (!$bRollBack)
    { 
      $con->commit();
      $result = true;
    }
    else
    { 
      w3sPropelWorkaround::rollBack($con);
      $result = false;
    }

    return $result;
  } 
  
  /**
   * setMessageErrors. Fills the standard error messages
   */
  protected function setMessageErrors()
  {
  	$this->errors[2] = w3sCommonFunctions::toI18n('An error occoured while adding the new content.');
  	$this->errors[4] = w3sCommonFunctions::toI18n('An error occoured while editing the selected content.');
  	$this->errors[8] = w3sCommonFunctions::toI18n('An error occoured while deleting the selected content.');
  	$this->errors[16] = w3sCommonFunctions::toI18n('The content is the same of the one stored: nothing to do.');  	       
  }
  
  /**
   * The default behaviour of the formatContent function is "Nothing to do", so the content array
   * will be returned untouched .
   *
   * @param      array The array with contents.
   * @return     array The array with contents formatted.
   */
  protected function formatContent($contentValues)
  {
  	return $contentValues;
  }
  
  /**
   * The this->errors' array zero key is reserved for exceptions. Here is filled
   *
   * @param      string The exception message.
   * 
   * @return     array The formatted exception message.
   */
  protected function setExceptionError($exception)
  {
  	$this->errors[0] = sprintf($this->exceptionMessage, $exception);
  	
  	return $this->errors[0];
  }
}