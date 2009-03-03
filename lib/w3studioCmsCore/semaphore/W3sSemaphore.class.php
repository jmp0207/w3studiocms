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

class semaphore
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
  public static function setRequestedOperation($idUser, $operation, $prevOperation='', $maxInactiveTime = 300)
  {

    if ($idUser == null)
    {
      throw new InvalidArgumentException('idUser cannot be null');
    }

    // Checks the status of used pages
    self::checkInactiveOperations($maxInactiveTime);

    $con = Propel::getConnection();

    $bCommit = true;
    $con = w3sPropelWorkaround::beginTransaction($con);

    // Verifies if the requested operation can be made
    if (self::isRequestedFree($idUser, $operation))
    {
      
      // Removes the previous operation if exists
      if ($prevOperation != '') $bCommit = (self::deleteOperation($idUser, $prevOperation) != 0) ? true : false;

      // Sets the requested page as active page and returns the operation result
      if ($bCommit) $bCommit = self::saveRequestedOperation($idUser, $operation);

    }
    else{
      $bCommit = false;
    }

    if ($bCommit)
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
   * Deletes the operation retrieved with the passed parameters for the semaphore table
   *
   * @param      int The id of the user
   * @param      str The operation requested
   * @return     bool True  - The page has been deleted succesfully
   *                  False - The page hasn't been deleted
   */
  public static function deleteOperation($idUser, $operation)
  {
    
    $semaphore = DbFinder::from('W3sSemaphore')->findPk($idUser, $operation);

    if (isset($semaphore))
    {
      $semaphore->delete();
      $result = ($semaphore->isDeleted()) ? 1 : 0;
    }
    else
    {
      $result = 2;
    }

    return $result;
  }
  
  /**
   * Deletes every page that has been inactive for a time greater that the $maxInactiveTime
   *
   * @param      int The max life a user can hold a page active without making any operation
   *                 in milliseconds
   */
  public static function checkInactiveOperations($maxInactiveTime = 300)
  {
    $c = new Criteria();
    $c->add(W3sSemaphorePeer::CREATED_AT, time() - $maxInactiveTime, CRITERIA::LESS_THAN);
    return W3sSemaphorePeer::doDelete($c);
  }
  
  /**
   * Checks if the current user can do the operation requested.
   *
   * @param      int The id of the user
   * @param      str The operation requested
   * @return     bool True  - The operation is currently in action
   *                  False - The operation can be performed
   */
  protected static function isRequestedFree($idUser, $operation)
  {
    $result = (DbFinder::from('W3sSemaphore')->
                    where('SfGuardUserId', '!=', $idUser)->
                    where('Operation', $operation)->
                    count() > 0) ? false : true ;
                  //echo DbFinder::from('W3sSemaphore')->getLatestQuery();
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
  protected static function saveRequestedOperation($idUser, $operation)
  {
    $semaphore = DbFinder::from('W3sSemaphore')->findPk($idUser, $operation);
    if($semaphore == null)
    {
      $semaphore = new W3sSemaphore();
      $semaphore->setSfGuardUserId($idUser);
      $semaphore->setOperation($operation);
      $res = $semaphore->save();
    }
    else
    {
      $semaphore->setCreatedAt(date("Y-m-d H:i:s"));
      $res = $semaphore->save();
    }
    
    return ($res > 0) ? true : false;
  }
}