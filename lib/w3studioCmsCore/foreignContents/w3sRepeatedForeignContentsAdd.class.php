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
 * w3sRepeatedForeignContentsAdd class is the object used to add the 
 * content on a foreign page that corresponds to the one added in the 
 * current page.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sForeignContentsAdd
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
  
class w3sRepeatedForeignContentsAdd extends w3sRepeatedForeignContents{
  
  /**
   * Adds the content on the foreign page 
   *  
   * @return int The operation result
   *
   */  
  public function updateForeignContent(){		
    $content = w3sContentManagerFactory::create($this->baseContentAttributes["ContentTypeId"]);
    
    // Blocks the content's update on foreign page because this operation is alreay called 
    // during the update on foreign pages. 
    $content->setUpdateForeigns(false);
    $foreignContent = $this->getForeignContent();
    if ($foreignContent == null)
    {
    	$param = $this->baseContentAttributes;
    	$param["PageId"] = $this->targetPageId;
    }
    else
    {
    	$param = $foreignContent;
    } 
    
    return $content->add($param);    
	}
}