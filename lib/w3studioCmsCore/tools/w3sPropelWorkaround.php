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
 * w3sPropelWorkaround is a workaround class designed to make W3studioCMS compatible
 * with propel 1.2 and propel 1.3. W3studioCMS doesn't use directly Creole
 * so it was quietly easy to migrate to propel 1.3, instead for one thing:
 * the methods name begin and rollback changed respectly in beginTransaction and 
 * rollBack. 
 * 
 * This static class has two methods, called beginTransaction
 * and rollBack. Both checks if the connection object passed as reference
 * has the method begin or rollback. If it has, user is using symfony 1.1 else he's
 * using symfony 1.2. The connection is passed as reference because when
 * the 1.1 support will be discontinued it will be quietly easy tho change the call
 * to this class to the correct propel's method and have the CMS working, replacing 
 * only the $con = w3sPropelWorkaround::beginTransaction($con); instruction with
 * $con->beginTransaction(); 
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sPropelWorkaround
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
class w3sPropelWorkaround
{
	public static function beginTransaction($con)
	{
		if (method_exists($con, 'begin'))
		{
    	//$con = w3sPropelWorkaround::beginTransaction($con);
    	$con->begin();
    }	
    else
    {
    	$con->beginTransaction(); 
    }	
    
    return $con;
	}
	
	public static function rollBack($con)
	{
		if (method_exists($con, 'rollback'))
		{
    	//w3sPropelWorkaround::rollBack($con);
    	$con->rollBack(); 
    }	
    else
    {
    	$con->rollBack(); 
    }
	}
}
