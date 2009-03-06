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
 * w3sPageManager extends the functionality of a w3sGroup object,
 * givin it the ability to add, edit, delete a page.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sGroupManager
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sGroupManager
{
  protected $group;
  	  
  /**
   * Constructor.
   * 
   * @param int  The content's type
   * @param object  The w3sContent object. Can be null when adding
   *
   */                         
  public function __construct($group = null)
  {
  	$this->group = ($group == null) ? new W3sGroup() : $group;
  }
  
  public function getGroup()
  {
  	return $this->group;
  }
  
  /**
   * Adds a new group
   *
   * @param  string The new group's name
   * @param  string The template's id which must be assigned the new group
   * 
   * @return     int 0 - The save operation failed
   *                 1 - Success
   */
  public function add($groupName, $idTemplate)
  {
    $groupName = w3sCommonFunctions::slugify($groupName);
    if ($groupName != '')
    {
	    if ($idTemplate != null)
	    {
		    if (W3sGroupPeer::getFromName($groupName) == null)
		    {
			    if (DbFinder::from('W3sTemplate')->findPK($idTemplate) != null)
		    	{
				    $this->group->setTemplateId($idTemplate);
						$this->group->setGroupName($groupName);
						$this->group->setEdited(1);
						$result = ($this->group->isModified() && $this->group->save() > 0) ? 1 : 0;
		    	}		
		    	else
			    {
				    // The template doesn't exist
				    $result = 16;
			    }    
		    }
		    else
		    {
			    // Group name exists
			    $result = 2;
		    }
	    }
	    else
	    {
		    // The template id is null
		    $result = 8;
	    }
	  }
	  else{
	  
	    // Group name is empty
	    $result = 4;
	  }
	  
	  return $result;
  }
  
  /**
   * Deletes the current page object 
   * 
   * @param  int The value related to the operation to perform.
   *                 0 - Restore content
   * 								 1 - Delete content 
   *
   * @return bool false - The save operation failed
   *              true  - Operation success
   */
  public function delete($op = 1)
  {
    $result = false; 
    if ($this->group != null)
    {
      // We assure that all the operations W3StudioCMS makes will be successfully done
      $con = Propel::getConnection();
      
      $rollBack = false;
    	$con = w3sPropelWorkaround::beginTransaction($con); 
      $this->group->setToDelete($op);
      $result = $this->group->save();
      if ($this->group->isModified() && $result == 0)
      {
        $rollBack = true;
      }
      else
      {
        $rollBack = ($this->deleteRelatedPages($op)) ? false : true;
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
    }
   
    return $result;
  }
  
  /**
   * Deletes the pages that belongs to the current group object 
   *
   * @param  int The value related to the operation to perform.
   *                 0 - Restore content
   * 								 1 - Delete content 
   * 
   * @return bool false - Operation have failed
   *              true  - Operation correctly done
   */
  protected function deleteRelatedPages($op = 1)
  {
  	$result = true;
  	$pages = $this->group->getW3sPages();
    foreach($pages as $page)
    {
      $pageManager = new w3sPageManager($page); 
      if (!$pageManager->delete())
      { 
      	$result = false;	
      	break;
      }
    }
    
    return $result;
  }
}