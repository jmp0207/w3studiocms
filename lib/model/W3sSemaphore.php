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

class W3sSemaphore extends BaseW3sSemaphore
{
  /**
   * Checks if requested page if free. If everything is fine sets it as active
   *
   * @param      int The id of the user
   * @param      str The operation requested
   * @param      str The previous operation
   * @return     bool 0 - An errour occoured during saving process
   *                  1 - The page has been setted as active page
   *                  2 - The page is in use by another user
   */
  public function setRequestedOperation($idUser, $operation, $prevOperation=''){

    // Checks the status of used pages
    $this->checkInactiveOperations();

    // Verifies if the requested operation can be done
    if ($this->checkRequestedOperation($idUser, $operation) == 0){

      // Removes the previous operation if exists
      if ($prevOperation != '') $this->deleteOperation($idUser, $prevOperation);

      // Sets the requested page as active page and returns the operation result
      $result = $this->saveRequestedOperation($idUser, $operation);
      if ($result == 1) $this->currentOperation = $operation; 
      
      return $result;
    }
    else{
      return 2;
    }
  }
  
  /**
   * Deletes the operation retrieved with the passed parameters for the semaphore table
   *
   * @param      int The id of the user
   * @param      str The operation requested
   * @return     bool True  - The page has been deleted succesfully
   *                  False - The page hasn't been deleted
   */
  public function deleteOperation($idUser, $operation){
    $oSemaphore = W3sSemaphorePeer::retrieveByPk($idUser, $operation);
    $result = (isset($oSemaphore)) ? ($oSemaphore->delete() > 0) ? 1 : 0 : 1;

    return $result;
  }
  
  /**
   * Deletes every page that has been inactive for a time greater that the $maxInactiveTime
   *
   * @param      int The max life a user can hold a page active without making any operation
   *                 in milliseconds
   */
  private function checkInactiveOperations($maxInactiveTime = 300){
    $cf = new w3sCommonFunctions();
    $selectedOperations = W3sSemaphorePeer::doSelect(new Criteria());
    foreach($selectedOperations as $selectedOperation){
      $diff =  time() - $cf->dateToTime($selectedOperation->getCreatedAt());
      if ($diff > $maxInactiveTime) $selectedOperation->delete();
    }
  }
  
  /**
   * Checks if the current user can do the operation requested.
   *
   * @param      int The id of the user
   * @param      str The operation requested
   * @return     bool True  - The operation is currently in action
   *                  False - The operation can be performed
   */
  private function checkRequestedOperation($idUser, $operation){
    $c = new Criteria();
    $c->add(W3sSemaphorePeer::OPERATION, $operation);
    $c->add(W3sSemaphorePeer::SF_GUARD_USER_ID, $idUser, CRITERIA::NOT_EQUAL);
    $result = (W3sSemaphorePeer::doCount($c) > 0) ? 1 : 0;

    return $result;
  }
  
  /**
   * Add or edit the operation to the table
   *
   * @param      int The id of the user
   * @param      str The operation requested
   * @return     bool True  - The page has been saved succesfully
   *                  False - The page hasn't been saved
   */
  private function saveRequestedOperation($idUser, $operation){
    $oSemaphore = W3sSemaphorePeer::retrieveByPk($idUser, $operation);
    if(empty($oSemaphore)){
      $this->setSfGuardUserId($idUser);
      $this->setOperation($operation);
      $res = $this->save();
    }
    else{
      $oSemaphore->setCreatedAt(date("Y-m-d H:i:s"));
      $res = $oSemaphore->save();
    }

    return ($res > 0) ? 1 : 0;
  }
}