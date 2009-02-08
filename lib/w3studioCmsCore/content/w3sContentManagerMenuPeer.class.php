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
 * w3sContentManagerMenuPeer contais static methods related 
 * to the w3sContentManagerMenu object
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sContentManagerMenuPeer
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sContentManagerMenuPeer
{
  
  /**
   * Copies the menu links for the sourceContent to the targetContent
   *  
   * @parameter  int The id of the source content
   * @parameter  int The id of the target content
   * 
   * @return     bool false - The save operation has failed
   *                  true  - The save operation has correctly done 
   */
  public static function copyRelatedElements($sourceContent, $targetContent)
  {
    $bRollBack = false;
    $con = Propel::getConnection();
    $con = w3sPropelWorkaround::beginTransaction($con); 
    
    // Deletes all the target menus 
    $targetMenus = W3sMenuElementPeer::getContentMenu($targetContent);
    foreach($targetMenus as $targetMenu)
    {
    	$targetMenu->delete();
    }
    
    // Retrieves the menu rows related to source content
    $sourceMenus = DbFinder::from('W3sMenuElement')->
												     where('contentId', $sourceContent)->
												     orderBy('position')->
												     find();
    foreach($sourceMenus as $sourceMenu)
    {
      $oTargetMenu= new W3sMenuElement();
      $contentValues = array("ContentId"      => $targetContent,
	                           "PageId"         => $sourceMenu->getPageId(),
	                           "Link"           => $sourceMenu->getLink(),
	                           "ExternalLink"   => $sourceMenu->getExternalLink(),
	                           "Image"          => $sourceMenu->getImage(),
	                           "RolloverImage"  => $sourceMenu->getRolloverImage(),
	                           "Position"       => $sourceMenu->getPosition());
      $oTargetMenu->fromArray($contentValues);  
           
      // Saves      
      $result = $oTargetMenu->save();
      if ($oTargetMenu->isModified() && $result == 0){
        $bRollBack = true;
        break;
      }
    }

    if (!$bRollBack){ // Everything was fine so W3StudioCMS commits to database
      $con->commit();
      $result = true;
    }
    else{             // Something was wrong so W3StudioCMS aborts the operation and restores to previous status
      w3sPropelWorkaround::rollBack($con);
      $result = false;
    }

    return $result;
  }
  
}