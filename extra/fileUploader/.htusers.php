<?php 
  /**
	 * TWG Flash uploader 2.5
	 * 
	 * Copyright (c) 2004-2007 TinyWebGallery
	 * written by Michael Dempfle
   *
   *  Basic password file for TFU. Used for the easy login implemented 
   *  in tfu_config.php
   */

	/** ensure this file is being included by a parent file */
	defined( "_VALID_TWG" ) or die( "Direct Access to this location is not allowed." );
	
	/* You can define users, passwords and their upload directories here! The directory has to exist!
	   Create one internal array for one user.
	
	   The array has the following structure
	   1. Loginname
	   2. Password (sha1 encrypted - use the password generator!)
	   3. Upload folder - The folder has to be created manually with the read/write permissions!
	   4. Show the delete button. "", "true","false" are valid values! "" keeps the setting from the config!
	   5. Enable folder handling. "", "true","false" are valid values! "" keeps the setting from the config!
	      this does set $enable_folder_browsing $enable_folder_creation, $enable_folder_deletion, 
	      $enable_folder_rename   
	*/
	$GLOBALS["users"]=array(
	array("test","a94a8fe5ccb19ba61c4c0873d391e987982fbbd3","upload","",""),
); ?>