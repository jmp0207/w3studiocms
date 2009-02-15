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
  public static function setRequestedOperation($idUser, $operation, $prevOperation='')
  {

    // Checks the status of used pages
    self::checkInactiveOperations();

    // Verifies if the requested operation can be done
    if (self::checkRequestedOperation($idUser, $operation) == 0)
    {
      // Removes the previous operation if exists
      if ($prevOperation != '') self::deleteOperation($idUser, $prevOperation);

      // Sets the requested page as active page and returns the operation result
      $result = self::saveRequestedOperation($idUser, $operation);
      
      return $result;
    }
    else{
      return 0;
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
  public static function deleteOperation($idUser, $operation)
  {
    $semaphore = DbFinder::from('W3sSemaphore')->findPk($idUser, $operation);
    $result = (isset($semaphore)) ? ($semaphore->delete() > 0) ? 1 : 0 : 1;

    return $result;
  }
  
  /**
   * Deletes every page that has been inactive for a time greater that the $maxInactiveTime
   *
   * @param      int The max life a user can hold a page active without making any operation
   *                 in milliseconds
   */
  private static function checkInactiveOperations($maxInactiveTime = 300)
  {
    $selectedOperations = W3sSemaphorePeer::doSelect(new Criteria());
    foreach($selectedOperations as $selectedOperation)
    {
      $diff =  time() - w3sCommonFunctions::dateToTime($selectedOperation->getCreatedAt());
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
  private static function checkRequestedOperation($idUser, $operation)
  {
    $result = (DbFinder::from('W3sSemaphore')->
                    where('SfGuardUserId', '!=', $idUser)->
                    where('Operation', $operation)->
                    count() > 0) ? 1 : 0;
                  
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
  private static function saveRequestedOperation($idUser, $operation)
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

    return ($res > 0) ? 1 : 0;
  }
}