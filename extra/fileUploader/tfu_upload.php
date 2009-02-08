<?php
/**
 * TWG Flash uploader 2.7
 *
 * Copyright (c) 2004-2008 TinyWebGallery
 * written by Michael Dempfle
 *
 *
 *    This file uploads the images to your webspace.
 *
 *    The sessionid is always sent to this file because otherwise the
 *    session is lost in Firefox and Opera!
 *
 *    The uploaded files are resized if this is possible (jpg,png,gif).
 *
 *    The current build can write debug information to the file tfu.log. The number of
 *    files that are uploaded and the filenames! You can uncomment the debug lines if
 *    you have a problem.
 *
 *    Authentification is done by the session $_SESSION["TFU_LOGIN"]. You can set
 *    this in the tfu_config.php or implement your own way!
 */
define('_VALID_TWG', '42');

if (isset($_GET['TFUSESSID'])) { // this is a workaround if you set php_flag session.use_trans_sid=off + a workaround for some servers that don't handle sessions correctly if you open 2 instances of TFU
  session_id($_GET['TFUSESSID']);
}
session_start();

include "tfu_helper.php";
restore_temp_session(); // this restores a lost session if your server handles sessions wrong!

/* This is some debug information - please uncomment this if I ask for it in a debug session ;).
debug("session id : " . session_id());
debug("session TFU: " . $_GET['TFUSESSID']);
debug("login: " . $_SESSION["TFU_LOGIN"]);
debug("dir: " . $_SESSION["TFU_DIR"]);
*/

if (isset($_SESSION["TFU_LOGIN"]) && isset($_GET['remaining'])) {
	$dir = getCurrentDir();

	if (isset($_GET['size'])) {
		$size = $_GET['size'];
	} else {
		$size = 100000; // no resize
	}

	$remaining = -1 + $_GET['remaining'];
	if ($remaining < 0) { // not valid! ew expect at least 1
		return;
	}

	if (!isset($_SESSION["TFU_LAST_UPLOADS"]) || isset($_GET['firstStart'])) {
		// we delete the info of the last upload items!
		unset($_SESSION["TFU_LAST_UPLOADS"]);
		$_SESSION["TFU_LAST_UPLOADS"] = array();		
	}
	
	foreach ($_FILES as $fieldName => $file) {
		$store = true;
		if (is_supported_tfu_image($file['name']) && $size < 100000) {
			$store = resize_file($file['tmp_name'], $size, 80, $file['name']);
		}
		if ($store) {
			if ($fix_utf8 == "") {
			  $image = utf8_decode(str_replace("\\'", "'", $file['name'])); // fix for special characters like öäüÖÄÜñé...
			} else {
			  $image = str_replace("\\'", "'", iconv("UTF-8", $fix_utf8, $file['name']));		
			}
			$filename = $dir . "/" . $image;
			$uploaded = false;
			if (@move_uploaded_file($file['tmp_name'], $filename)) {
			    if (file_exists( $filename)) {
            $uploaded = true;
          }
			}
			if (!$uploaded) { // bad file name - I try to fix this and save it anyway!
			   $filename = $dir . "/" . str_replace("\\'", "'", iconv("UTF-8", "", $file['name']));
			   if (@move_uploaded_file($file['tmp_name'], $filename)) {
			     if (file_exists( $filename)) {
            $uploaded = true;
          }
			   }
			}
			if ($uploaded) {
				@chmod($filename, 0777); // we change the file to 777 - if you like more restrictions - change the mode here!
				array_push($_SESSION["TFU_LAST_UPLOADS"], $filename);
				removeCacheThumb($filename);
				// this actually generates the two thumbnails ... set this to true if you like this ;).
				if (false) {
				  send_thumb($filename, 90, 400, 275, true);
				  send_thumb($filename, 90,  80,  55, true);
				}
			}
		}
	}
	if (count($_SESSION["TFU_LAST_UPLOADS"]) > 0 && $remaining == 0 && $_SESSION["TFU_SPLIT_EXTENSION"] != "FALSE") { // last item in the upload AND we have stored stuff!
		restore_split_files($_SESSION["TFU_LAST_UPLOADS"]);
		resize_merged_files($_SESSION["TFU_LAST_UPLOADS"], $size);
	}
	
	// we only send an email for the last item of an upload cycle
		if (isset ($_SESSION["TFU_NOT_EMAIL"]) && $_SESSION["TFU_NOT_EMAIL"] != "" && $remaining == 0) {
			$youremail = $_SESSION["TFU_NOT_EMAIL_FROM"];
			$email = $_SESSION["TFU_NOT_EMAIL"];
			$submailheaders = "From: $youremail\n";
			$submailheaders .= "Reply-To: $youremail\n";
			$subject = $_SESSION["TFU_NOT_EMAIL_SUBJECT"];
			$filestr = "\n\n";
				foreach ($_SESSION["TFU_LAST_UPLOADS"] as $filename) {
				$filestr = $filestr . str_replace("./", "",str_replace("../", "", $filename)) . "\n";
			}
			if ($filestr == "\n\n") {
        $filestr .= "Please check your setup. No files where uploaded.";
      }
			$username = "not set";
			if (isset($_SESSION["TFU_USER"])) {
			  $username = $_SESSION["TFU_USER"];
			}
			$mailtext = sprintf($_SESSION["TFU_NOT_EMAIL_TEXT"], $username , $filestr );
			@mail ($email, html_entity_decode ($subject), html_entity_decode ($mailtext), $submailheaders);
		}
	  store_temp_session();
} else if (isset($_GET['remaining'])) { // seems like the session is lost! therefore we create a temp dir that enables TFU session handling
  debug("It seems that the session handling of the server is not o.k. Therefore TFU simulates a basic session handling and uses the session_cache folder for that.");
  if(!mkdir(dirname(__FILE__) . "/session_cache")) {
    debug("Directory session_cache could no be created! Please create the sub directoy session_cache and set the permissions to 777.");
  } else {
    debug("Directory session_cache could be created! TFU does now an internal session handling.");
  }  
} else {
	echo "Not logged in!";
}
echo " "; // important - solves bug for Mac!
flush();

?>