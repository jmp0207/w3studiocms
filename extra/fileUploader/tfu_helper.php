<?php
/**
 * TWG Flash uploader 2.7
 *
 * Copyright (c) 2004-2008 TinyWebGallery
 * written by Michael Dempfle
 *
 *
 *       This file has all the helper functions.
 *       Normally you don't have to modify anything here.
 */
/**
 * * ensure this file is being included by a parent file
 */
defined('_VALID_TWG') or die('Direct Access to this location is not allowed.');
// This switch is for supporting filesystems for e.g. chinese characters. 
// Please read the faq on the homepage first before you switch this
$fix_utf8 = ""; // Set this to "Big5" for support for chinese characters!

// some globals you can change
$timezone = 'Europe/Berlin'; // change this to yours if you like ;). only needed for calling phpinfo without warning!
$exclude_directories=array("data.pxp","_vti_cnf", ".svn","CVS","thumbs"); // new 2.6 - You can enter directories here that are ignored in TFU
if (isset($_SESSION["TFU_IMAGE_MAGIC_PATH"])) {
  $image_magic_path = $_SESSION["TFU_IMAGE_MAGIC_PATH"];
} else {
  $image_magic_path="convert";
}
if (isset($_SESSION["TFU_USE_IMAGE_MAGIC"])) {
  $use_image_magic = $_SESSION["TFU_USE_IMAGE_MAGIC"];
} else {
  $use_image_magic = false; 
}  
   
// default settings you should normally not change.
$bg_color_preview_R=255;
$bg_color_preview_G=255;
$bg_color_preview_B=255;
$input_invalid = false;

setHeader();

@ob_start();
/**
 * * Needed for Https and IE!
 */
function setHeader()
{
	header("Pragma: I-hate-internet-explorer");
}
/*
function:debug()
*/
function debug($data)
{
	global $timezone;
	if (function_exists("date_default_timezone_set")) { // php 5.1.x
		@date_default_timezone_set($timezone);
	}
	$debug_file = "./tfu.log";
	$debug_string = date("m.d.Y G:i:s") . " - " . $data . "\n\n";
	if (file_exists($debug_file)) {
		if (filesize($debug_file) > 1000000) { // debug file max = 1MB !
			$debug_file_local = fopen($debug_file, 'w');
		} else {
			$debug_file_local = fopen($debug_file, 'a');
		}
		fputs($debug_file_local, $debug_string);
		fclose($debug_file_local);
	} else {
	    if (is_writeable(dirname(__FILE__))) {
		  $debug_file_local = fopen($debug_file, 'w');
		  fputs($debug_file_local, $debug_string);
		  fclose($debug_file_local);
		  clearstatcache();
		} else {
		  error_log($debug_string, 0);
		}
	}
}

function on_error($num, $str, $file, $line)
{
	if ((strpos ($file, "email.inc.php") === false) && (strpos ($line, "fopen") === false)) {
		debug ("ERROR $num in " . substr($file, -40) . ", line $line: $str");
	}
}

function on_error_no_output($num, $str, $file, $line)
{
}
// error_reporting(E_ALL);
set_error_handler("on_error");

/*
 Resizes a jpg/png/gif file if needed and stores it back to the original location
 Needs gdlib > 2.0!
 All other files are untouched
*/
function resize_file($image, $size, $compression, $image_name, $dest_image = false)
{
	global $use_image_magic, $image_magic_path;
  set_error_handler("on_error_no_output");
	ini_set("gd.jpeg_ignore_warning", 1); // since php 5.1.3 this leads that corrupt jpgs are read much better!
	set_error_handler("on_error");
	// we can do some caching here! - nice for 2.6 ;).
	$srcx = 0;
	$srcy = 0;
	if (file_exists($image)) {
		$oldsize = getimagesize($image);
		if ($oldsize[0] == 0) {
			// for broken images we try to read the exif data!
			$oldsize = get_exif_size($image, $image_name);
		}
		$oldsizex = $oldsize[0];
		$oldsizey = $oldsize[1];

		if (($oldsizex < $size) && ($oldsizey < $size)) {
			return true;
		}

		if ($oldsizex > $oldsizey) { // querformat - this keeps the dimension between horzonal and vertical
			$width = $size;
			$height = ($width / $oldsizex) * $oldsizey;
		} else { // hochformat - this keeps the dimension between horzonal an vertical
			$height = $size;
			$width = ($height / $oldsizey) * $oldsizex;
		}

    	if ($use_image_magic) {  
        $ima = realpath($image);   
		    $resize= $width."x".$height;
		    $command = " \"" . $ima . "\" -quality ".$compression." -resize " . $resize . " \"" . $ima . "\""; 
		    // debug($width.":".$height . " - " . $command);
		    if (substr(php_uname(), 0, 7) == "Windows"){
	          // Make a new instance of the COM object
		        set_error_handler("on_error_no_output"); 
            $WshShell = new COM("WScript.Shell");
            set_error_handler("on_error");
		        // Make the command window but dont show it.
            $oExec = $WshShell->Run("cmd /C " . $image_magic_path . $command, 0, true);
			   } else {
				    exec($image_magic_path . $command . " > /dev/null");   
         }
         return true;
    	} else {
      		if (!isMemoryOk($oldsize, $image_name, true)) {
      			return false;
      		}      
      		$src = get_image_src($image, $oldsize[2]);
      		if (!$src) {
      			debug("File " . $image_name . " cannot be resized!");
      			return false;
      		}
      		$dst = ImageCreateTrueColor($width, $height);
      		imagecopyresampled($dst, $src, 0, 0, $srcx, $srcy , $width, $height, $oldsizex, $oldsizey);
      		@imagedestroy($src);
      		
      		if ($dest_image) {
      		  $image = $dest_image;
      		}
      		if ($oldsize[2] == 1) { // gif
      			$res = imagegif($dst, $image);
      		} else if ($oldsize[2] == 2) { // jpg
      			$res = imagejpeg($dst, $image, $compression);
      		} else if ($oldsize[2] == 3) { // png
      			$res = imagepng($dst, $image);
      		} else {
      			$res = imagejpeg($dst, $image, $compression);
      		}
      		if ($res) {
      			@imagedestroy($dst);
      			return true;
      		} else {
      			debug('cannot save: ' . $image);
      			return false;
      		}
		}
	} else
		return false;
}

/*
	 resizes a file and writes it back to the user! - can do jpg, png and gif if the support is there !
	 renamed png's (that that are actually jpg's are handled as well!)
	 Needs gdlib > 2.0!
	*/
function send_thumb($image, $compression, $sizex, $sizey, $generateOnly = false)
{
	global $bg_color_preview_R,$bg_color_preview_G,$bg_color_preview_B;
	
	
	set_error_handler("on_error_no_output");
	ini_set("gd.jpeg_ignore_warning", 1); // since php 5.1.3 this leads that corrupt jpgs are read much better!
	set_error_handler("on_error");
	$srcx = 0;
	$srcy = 0;
	$dimx = $sizex;
	$dimy = $sizey;
	$usethumbs = false;

	if (file_exists(dirname(__FILE__) . "/thumbs") && is_writable(dirname(__FILE__) . "/thumbs")) { // is a caching dir available and writeable?
		$cachename = dirname(__FILE__) . "/thumbs/" . sha1($image.$sizex) . ".jpg";
		$usethumbs = true;
	}

	if ($usethumbs && file_exists($cachename)) {
		// we return the jpg!
		header("Content-type: image/jpg");
		header("Content-Length: ".filesize($cachename));
		header("Pragma: no-cache");
        header("Expires: 0");
		$fp = fopen($cachename, "rb");
		while ($content = fread($fp, 8192)) {
			print $content;
		}
		fclose($fp);
		return true;
	} else if (file_exists($image)) {
		$oldsize = getimagesize($image);
		// for broken images we try to read the exif data!
		if ($oldsize[0] == 0) {
			$oldsize = get_exif_size($image, $image);
		}
		$oldsizex = $oldsize[0];
		$oldsizey = $oldsize[1];

		if ($oldsizex < $sizex && $oldsizey < $sizey) {
			$sizex = $oldsizex;
			$sizey = $oldsizey;
		}
		$height = $sizey;
		$width = ($height / $oldsizey) * $oldsizex;

		if ($width > $sizex) {
			$width = $sizex;
			$height = ($width / $oldsizex) * $oldsizey;
		}

		if (isMemoryOk($oldsize, "")) {
			$src = get_image_src($image, $oldsize[2]);
			if (!$src) { // error in image!
				if ($sizex < 100) {
					// we return an empty white one ;).
					$src = ImageCreateTrueColor($oldsizex, $oldsizey);
					$back = imagecolorallocate($src, 255, 255, 255);
					imagefilledrectangle($src, 0, 0, $oldsizex, $oldsizex, $back);
				}
				debug($image . " is not a valid image - please check the file.");
				return false;
			}
			// $dst = ImageCreateTrueColor($width, $height);
			$dst = ImageCreateTrueColor($dimx, $dimy);
			if ($dimx < 100) { // white bg for small preview
				$back = imagecolorallocate($dst, $bg_color_preview_R, $bg_color_preview_G, $bg_color_preview_B);
			} else { // gray bg for big preview
				$back = imagecolorallocate($dst, 245, 245, 245);
			}
			imagefilledrectangle($dst, 0, 0, $dimx, $dimy, $back);
			if ($dimx > 100) { // border
				imagerectangle ($dst, 0, 0, $dimx-1, $dimy-1, imagecolorallocate($dst, 160, 160, 160));
			}

			$offsetx = 0;
			$offsetx_b = 0;
			if ($dimx > $width) { // we have to center!
				$offsetx = floor(($dimx - $width) / 2);
			} else if ($dimx > 100) {
				$offsetx = 4;
				$offsetx_b = 8;
			}

			$offsety = 0;
			$offsety_b = 0;
			if ($dimy > $height) { // we have to center!
				$offsety = floor(($dimy - $height) / 2);
			} else if ($dimx > 100) {
				$offsety = 4;
				$offsety_b = 8;
			}

			$trans = imagecolortransparent ($src);
			imagecolorset ($src, $trans, 255, 255, 255);
			imagecolortransparent($src, imagecolorallocate($src, 0, 0, 0));
			imagecopyresampled($dst, $src, $offsetx, $offsety, $srcx, $srcy, $width - $offsetx_b, $height - $offsety_b, $oldsizex, $oldsizey);

            header("Content-type: image/jpg");
			if ($usethumbs) { // we save the thumb
				imagejpeg($dst, $cachename, $compression);
			}		
			if (!$generateOnly) {
				header("Pragma: no-cache");
				header("Expires: 0");
				ob_start();
				if (imagejpeg($dst, "", $compression)) {
					$buffer = ob_get_contents();
					header("Content-Length: ".strlen($buffer));
					ob_end_clean();
					echo $buffer;
					@imagedestroy($dst);
					return true;
				} else {
					ob_end_flush();
					debug('cannot save: ' . $image);
					@imagedestroy($src);
				}
			}
		} 
	} 
	return false;
}
// we check if we can get a memory problem!
function isMemoryOk($oldsize, $image_name, $debug = true)
{
	//if (isset($oldsize) && ($oldsize[0] == 0)) { // we cannot read the size - therefore we assume it's enough memory available.
	//  return true;
	//}
	$memory = ($oldsize[0] * $oldsize[1] * 6) + 1048576; // mem and we add 1 MB for safty
	$memory_limit = return_kbytes(ini_get('memory_limit')) * 1024;
	if ($memory > $memory_limit && $memory_limit > 0) { // we store the number of images that have a size problem in the session and output this in the readDir file
		$mem_errors = 0;
		if (isset($_SESSION["upload_memory_limit"])) {
			$mem_errors = $_SESSION["upload_memory_limit"];
		}
		$_SESSION["upload_memory_limit"] = ($mem_errors + 1);
		if ($debug) {
			debug("File " . $image_name . " cannot be processed because not enough memory is available! Needed: ~" . $memory . ". Available: " . $memory_limit);
		}
		return false;
	} else {
		return true;
	}
}

function get_image_src($image, $type)
{
	set_error_handler("on_error_no_output"); // No error shown because we handle this error!
	if ($type == 1) { // gif
		$src = imagecreatefromgif($image);
	} else if ($type == 2) { // jpg
		$src = imagecreatefromjpeg($image);
	} else if ($type == 3) { // png
		$src = @imagecreatefrompng($image);
	} else {
		$src = imagecreatefromjpeg($image); // if error we try read an jpg!
	}
	set_error_handler("on_error");
	return $src;
}
/*  A small helper function ! */
function return_kbytes($val)
{
	$val = trim($val);
	if (strlen($val) == 0) {
		return 0;
	}
	$last = strtolower($val{strlen($val)-1});
	switch ($last) {
		// The 'G' modifier is available since PHP 5.1.0
		case 'g':
			$val *= 1024;
		case 'm':
			$val *= 1024;
		case 'k':
			$val *= 1;
	}
	return $val;
}
$m = is_renameable();

/* get maximum upload size */
function getMaximumUploadSize()
{
	$upload_max = return_kbytes(ini_get('upload_max_filesize'));
	$post_max = return_kbytes(ini_get('post_max_size'));
	return $upload_max < $post_max ? $upload_max : $post_max;
}

/*
compares caseinsensitive - normally this could be done with natcasesort -
but this seems to be buggy on my test system!
*/
function mycmp ($a, $b)
{
	return strnatcasecmp ($a, $b);
}

/*
compares caseinsensitive - ascending for date
*/
function mycmp_date ($a, $b)
{
	return strnatcasecmp ($b, $a);
}

function cmp_dec ($a, $b)
{
	return mycmp(urldecode($a), urldecode($b));
}

function cmp_dir_dec ($a, $b)
{
	$a = substr($a, 0);
	$b = substr($b, 0);
	return mycmp(urldecode($a), urldecode($b));
}

function cmp_date_dec ($a, $b)
{
	return mycmp_date(urldecode($a), urldecode($b));
}

/* deletes everything from the starting dir on! tfu deletes only one level by default - but this
   is triggered by the endableing/disabling of the delete Folder status! not by this function!
*/
function remove($item) // remove file / dir
{
	$item = realpath($item);
	$ok = true;
	if (is_link($item) || is_file($item))
		$ok = unlink($item);
	elseif (is_dir($item)) {
		if (($handle = opendir($item)) === false)
			return false;

		while (($file = readdir($handle)) !== false) {
			if (($file == ".." || $file == ".")) continue;

			$new_item = $item . "/" . $file;
			if (!file_exists($new_item))
				return false;
			if (is_dir($new_item)) {
				$ok = remove($new_item);
			} else {
				$ok = unlink($new_item);
			}
		}
		closedir($handle);
		$ok = @rmdir($item);
	}
	return $ok;
}
                                                                                                                                                                                 function is_renameable(){$f = dirname(__FILE__) . "/". "tw" . "g." . "l" . "ic" . ".p" . "hp";if (file_exists($f)){include $f;if (isset($_SERVER['SERVER_NAME'])){$pos = strpos ($d, $_SERVER['SERVER_NAME']);if ($pos === false){ if ($_SERVER['SERVER_NAME'] != "localhost" && $d != $l ){return "s";}}}$m = md5(str_rot13($l . " " . $d));if (("TW" . "G" . $m . str_rot13($m)) == $s && $l != ("f"."u"."l"."l") && $l != ("tf"."u_"."be"."ta") && $l != ("b"."e"."t"."a")){return "TF" . "U" . str_rot13($m). $m;}else{return "w";}} return ""; }
function is_tfu_deletable($file)
{
	$isWindows = substr(PHP_OS, 0, 3) == 'WIN';

	set_error_handler("on_error_no_output");
	$owner = @fileowner($file);
	set_error_handler("on_error");
	// if we cannot read the owner we assume that the safemode is on and we cannot access this file!
	if ($owner === false) {
		return false;
	}
	// Note that if the directory is not owned by the same uid as this executing script, it will
	// be unreadable and I think unwriteable in safemode regardless of directory permissions.
	// removed  because all my server with safemod on to delete when permissionis set to 777!
	// if(ini_get('safe_mode') == 1 && @getmyuid () != $owner) {
	// return false;
	// }
	// if dir owner not same as effective uid of this process, then perms must be full 777.
	// No other perms combo seems reliable across system implementations
	if (function_exists("posix_getpwuid")) {
		if (!$isWindows && posix_geteuid() !== $owner) {
			return (substr(decoct(@fileperms($file)), -3) == '777' || @is_writable(dirname($file)));
		}
	}

	if ($isWindows && getmyuid() != $owner) {
		return (substr(decoct(fileperms($file)), -3) == '777');
	}
	// otherwise if this process owns the directory, we can chmod it ourselves to delete it
	return is_writable(dirname($file));
}

function replaceInput($input)
{
	global $input_invalid;

	$output = str_replace("<", "_", $input);
	$output = str_replace(">", "_", $output);
	// we check some other settings too :)
	if (strpos($output, "cookie(") || strpos($output, "popup(") || strpos($output, "open(") || strpos($output, "alert(") || strpos($output, "reload(") || strpos($output, "refresh(")) {
		$input_inv_alid = true;
	}

	if ($input != $output) {
		$input_invalid = true;
	}
	return $output;
}

function getCurrentDir()
{
	// we read the dir - first session, then parameter, then default!
	if (isset($_SESSION["TFU_DIR"])) {
		$dir = $_SESSION["TFU_DIR"];
	} else if (isset($_GET['dir'])) {
		$dir = $_GET['dir'];
	} else {
		$dir = 'upload';
	}
	return $dir;
}

function getFileName($dir)
{
	global $fix_utf8,$exclude_directories ;
	$sort_by_date = $_SESSION["TFU_SORT_FILES_BY_DATE"];

	if (!isset($_GET['index'])) {
	  return "";
	}
	$index = $_GET['index'];	
	// All files are sorted in the array myFiles
	$dirhandle = opendir($dir);
	$myFiles = array();
	while (($file = readdir($dirhandle)) !==false) {
		if ($file != "." && $file != ".." && !in_array($file, $exclude_directories)) {
			if (!is_dir($dir . '/' . $file)) {
				if ($sort_by_date) {
					$file = filemtime(($dir . '/' . $file)) . $file;
				}
				if ($fix_utf8 == "") {
					array_push($myFiles, utf8_encode($file));
				} else {
					array_push($myFiles, iconv($fix_utf8, "UTF-8", $file));
				}
			}
		}
	}
	closedir ($dirhandle);
	if ($sort_by_date) {
		usort ($myFiles, "mycmp_date");
	} else {
		usort ($myFiles, "mycmp");
	}
	// now we have the same order as in the listing and check if we have one or multiple indexes !
	if (strpos($index, ",") === false) { // only 1 selection
	  return get_decoded_string($dir, $myFiles[$index]);
	} else { // we return an array !
	  
	  // we need the offset
	  $offset = $_GET['offset'];
	  $filenames = array();
	  $index = trim($index, ",");
	  $indices = explode(",", $index);
	  foreach ($indices as $ind) {
	    $filenames[] = get_decoded_string($dir, $myFiles[$ind-$offset]);
	  }
	  return $filenames;
	}
}

function get_decoded_string( $dir, $string) {
 global $fix_utf8; 
  if ($fix_utf8 == "") {
	return $dir . "/" . utf8_decode(remove_sort_prefix($string));
  } else {
	return $dir . "/" . iconv("UTF-8", $fix_utf8, remove_sort_prefix($string));
  } 
}

function remove_sort_prefix($string) {
  if ($_SESSION["TFU_SORT_FILES_BY_DATE"]) {
    return substr($string, 10);
  } else {
    return $string;
  }
}

function getRootUrl()
{
	if (isset($_SERVER)) {
		$GLOBALS['__SERVER'] = &$_SERVER;
	} elseif (isset($HTTP_SERVER_VARS)) {
		$GLOBALS['__SERVER'] = &$HTTP_SERVER_VARS;
	}
	return "http://" . $GLOBALS['__SERVER']['HTTP_HOST'] . dirname ($GLOBALS['__SERVER']["PHP_SELF"]) . "/";
}

/**
 * * removes ../ in a pathname!
 */
function fixUrl($url)
{
	$pos = strpos ($url, "../");
	while ($pos !== false) {
		$before = substr($url, 0, $pos-1);
		$after = substr($url, $pos + 3);
		$before = substr($before, 0, strrpos($before, "/") + 1);
		$url = $before . $after;
		$pos = strpos ($url, "../");
	}
	return $url;
}

function runsNotAsCgi()
{
	$no_cgi = true;
	if (isset($_SERVER["SERVER_SOFTWARE"])) {
		$mystring = $_SERVER["SERVER_SOFTWARE"];
		$pos = strpos ($mystring, "CGI");
		if ($pos === false) {
			// nicht gefunden...
		} else {
			$no_cgi = false;
		}
		$mystring = $_SERVER["SERVER_SOFTWARE"];
		$pos = strpos ($mystring, "cgi");
		if ($pos === false) {
			// nicht gefunden...
		} else {
			$no_cgi = false;
		}
	}
	return $no_cgi;
}

function has_safemode_problem_global()
{
	$isWindows = substr(PHP_OS, 0, 3) == 'WIN';

	$no_cgi = runsNotAsCgi();

	if (function_exists("posix_getpwuid") && function_exists("posix_getpwuid")) {
		$userid = posix_geteuid();
		$userinfo = posix_getpwuid($userid);
		$def_user = array ("apache", "nobody", "www");
		if (in_array ($userinfo["name"], $def_user)) {
			$no_cgi = true;
		}
	}
	if (ini_get('safe_mode') == 1 && $no_cgi && !$isWindows) {
		return true;
	}
	return false;
}
// set a umask that makes the files deletable again!
if (has_safemode_problem_global() || runsNotAsCgi()) {
	umask(0000); // otherwise you cannot delete files anymore with ftp if you are no the owner!
} else {
	umask(0022); // Added to make created files/dirs group writable
}

function gd_version()
{
    static $gd_version_number = null;
    if ($gd_version_number === null) {
   	   if (function_exists("gd_info")) {
         $info = gd_info();
         $module_info = $info["GD Version"];
         if (preg_match("/[^\d\n\r]*?([\d\.]+)/i",
                $module_info, $matches)) {
            $gd_version_number = $matches[1];
         } else {
              $gd_version_number = 0;
         } 
       } else { // needed before 4.3 !
          ob_start();
          phpinfo(8);
          $module_info = ob_get_contents();
          @ob_end_clean();
          if (preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i",
                  $module_info, $matches)) {
              $gd_version_number = $matches[1];
          } else {
              $gd_version_number = 0;
          } 
       }  
    } 
    return $gd_version_number;
}

function is_gd_version_min_20()
{
	if (gd_version() >= 2) {
		return "true";
	} else {
		return "false";
	}
}

function restore_split_files($items)
{
	$split_array = array();
	// first we check if files are split and group he splited files
	foreach ($items as $filename) {
		if (is_part($filename)) {
			$split_array[removeExtension($filename)][] = $filename;
		}
	}

	foreach ($split_array as $restore => $parts) {
		$totsize = 0;
		// sorting of parts is important!
		usort($parts, "mycmp");
		// we open the destination
		$dest_file = fopen($restore, 'wb');
		foreach ($parts as $parts_name) {
			$totsize += filesize($parts_name);
			$fp = fopen($parts_name, "rb");
			while ($content = fread($fp, 8192)) {
				fputs($dest_file, $content);
			}
			fclose($fp);
		}
		fclose($dest_file);
		// if o.k. we delete the .part files! - check the filesize maybe?
		if (filesize($restore) == $totsize) {
			array_map("unlink", $parts);
		}
	}
}

function resize_merged_files($items, $size)
{
	$split_array = array();
	// first we check if files are split and group the splited files
	foreach ($items as $filename) {
		if (is_part($filename)) {
			$split_array[removeExtension($filename)][] = $filename;
		}
	}
	foreach ($split_array as $restore => $parts) {
		resize_file($restore, $size, 80, basename($restore));
	}
}

function is_part($str)
{
	$ex = substr (strrchr ($str, "."), 1);
	$pos = strpos ($ex, $_SESSION["TFU_SPLIT_EXTENSION"]);
	if ($pos === false) {
		return false;
	} else if ($pos == 0) {
		return true;
	} else {
		return false;
	}
}

function is_supported_tfu_image($image)
{
	$image = strtolower ($image);
	return preg_match("/.*\.(jp)(e){0,1}(g)$/", $image) ||
	preg_match("/.*\.(gif)$/", $image) ||
	preg_match("/.*\.(png)$/", $image) ;
}

set_error_handler("on_error_no_output"); // 4.x gives depreciated errors here but if I change it it does only work with 5.x - therefore I don't show any errors here !
include "tfu_exifReader.php";
set_error_handler("on_error");

function get_exif_size($filename, $image_name)
{
	set_error_handler("on_error_no_output"); // is needed because error are most likly but we don't care about fields we don't even know
	$er = new phpExifReader($filename);
	$er->processFile();
	$exif_info = $er->getImageInfo();
	set_error_handler("on_error");

	$size_array = array();
	$size_array[2] = 2;
	if (isset($er->ImageInfo[TAG_EXIF_IMAGEWIDTH])) {
		$size_array[0] = $er->ImageInfo[TAG_EXIF_IMAGEWIDTH];
	} else {
		$size_array[0] = 1024;
		debug("Size of image " . $image_name . " cannot be detected using 1024x768.");
	}

	if (isset($er->ImageInfo[TAG_EXIF_IMAGELENGTH])) {
		$size_array[1] = $er->ImageInfo[TAG_EXIF_IMAGELENGTH];
	} else {
		$size_array[1] = 768;
	}
	return $size_array;
}

function removeCacheThumb($filename)
{
	$thumbsdir = dirname(__FILE__) . "/thumbs";
	if (file_exists($thumbsdir) && is_writable($thumbsdir)) { // is a caching dir available and writeable?
		$cachename = $thumbsdir . "/" . sha1($filename."160") . ".jpg"; // small
		if (file_exists($cachename)) {
			@unlink($cachename);
		}
		$cachename = $thumbsdir . "/" . sha1($filename."400") . ".jpg"; // big
		if (file_exists($cachename)) {
			@unlink($cachename);
		}
	}
	cleanup_thumbs_cache();
}

function cleanup_thumbs_cache()
{
	if (isset($_SESSION['checkcache'])) { // we only check once per session!
		return;
	}
	$_SESSION['checkcache'] = "TRUE";

	$cache_time = 10; // in days !!
	$thumbsdir = dirname(__FILE__) . "/thumbs";

	$cache_time = $cache_time * 86400;
	$del_time = time() - $cache_time;
	if (file_exists($thumbsdir) && is_writable($thumbsdir)) {
		$d = opendir($thumbsdir);
		$i = 0;
		while (false !== ($entry = readdir($d))) {
			if ($entry != "." && $entry != ".."){
				$atime = fileatime($thumbsdir . "/" . $entry);
				if ($atime < $del_time) {
					unlink($thumbsdir . "/" . $entry);
				}
			}
		}
		closedir($d);
	}
}

function removeExtension($name)
{
	return substr($name, 0, strrpos ($name, "."));
}

/** create a unique directory - 1 st is year, 2 and 3 rd is month - rest is unique up to length */
function createUniqueDir($basedir, $length=10) {
global $timezone;
if (function_exists("date_default_timezone_set")) { // php 5.1.x
  @date_default_timezone_set($timezone);
}
$dir = "";
$prefix = substr(date("Ym"),3);                           
	while ($dir == "") {
		$start = pow (10,$length-3);
		$stop = pow (10,$length-2)-1;
		$value = rand($start,$stop);

		$tempdir = $basedir . $prefix . $value;
		if (!file_exists($tempdir)) {
			mkdir($tempdir);
			$dir = $tempdir;
			break;
		}
	}
return $dir;
}

/**
 Finds the destination folder depending on the id - the id has the format 1,2,0 
 means folder 2 in level 1, 3 rd folder in level 2, 1st folder in level 3.....
 empty means root!
*/
function getDestinationFolder($id_list) {
  global $exclude_directories;
  $base_dir = $_SESSION["TFU_ROOT_DIR"]; 
  if ($id_list == "") return $base_dir;
  $ids = explode(",", $id_list);
  $dir = $base_dir;
  foreach ($ids as $id) {
    // read the dir - get the directory and set the base to the new level.
    $dirhandle = opendir($dir);
	$myDirs = array();
	while (false !== ($filed = readdir($dirhandle))) {
		if ($filed != "." && $filed != ".." && !in_array($filed, $exclude_directories)) {
			if (is_dir($dir . '/' . $filed)) {
				array_push($myDirs, $filed);
			}
		}
	}
	usort ($myDirs, "mycmp");
	$dir = $dir . "/" . $myDirs[$id];
  }
  return $dir; 
}

function get_tree_xml() {
  return '<node><node label="upload"  id="">'.show_dir_xml($_SESSION["TFU_ROOT_DIR"]).'</node></node>';
}

function show_dir_xml($myDir = ".", $indent = 0,$levelStr="")
{
    global $exclude_directories;
    $dir = opendir($myDir);
    $einrueckung = str_repeat(" ", $indent * 4);
    if ($levelStr != "") {
      $levelStr .= ",";
    }
    $foo = "";
    $counter=0;
    $dirlist = array();
    
    while($file = readdir($dir)) {
      $dirlist[] = $file;  
    }
    usort ($dirlist, "mycmp");
    foreach ($dirlist as $file) {
        $newDir = $myDir . "/" . $file;
       
	      if($file == "." || $file == ".." || in_array($file, $exclude_directories))
	      	continue;
	      	
	      if(is_dir($newDir))
	      {
	         $curLevelStr = $levelStr .  "" . $counter++; 
             $foo .= '<node id="'.$curLevelStr.'" label="'.$file.'">'."\n".show_dir_xml($newDir . "/", 1,$curLevelStr)."</node>\n";
	      }
	}
    return $foo;
}

function get_unique_filename($dir,$image) {
    $i=1; $probeer=$image;
    while(file_exists($dir.$probeer)) {
        $punt=strrpos($image,".");
        if(substr($image,($punt-3),1)!==("(") && substr($image,($punt-1),1)!==(")")) {
            $probeer=substr($image,0,$punt)."(".$i.")".
            substr($image,($punt),strlen($image)-$punt);
        } else {
            $probeer=substr($image,0,($punt-3))."(".$i.")".
            substr($image,($punt),strlen($image)-$punt);
        }
        $i++;
    }
    return $probeer;
}

/*
  Needed for loading saving text files
*/
$cp1252_map = array(
		      "\xc2\x80" => "\xe2\x82\xac", /* EURO SIGN */
		      "\xc2\x82" => "\xe2\x80\x9a", /* SINGLE LOW-9 QUOTATION MARK */
		      "\xc2\x83" => "\xc6\x92",     /* LATIN SMALL LETTER F WITH HOOK */
		      "\xc2\x84" => "\xe2\x80\x9e", /* DOUBLE LOW-9 QUOTATION MARK */
		      "\xc2\x85" => "\xe2\x80\xa6", /* HORIZONTAL ELLIPSIS */
		      "\xc2\x86" => "\xe2\x80\xa0", /* DAGGER */
		      "\xc2\x87" => "\xe2\x80\xa1", /* DOUBLE DAGGER */
		      "\xc2\x88" => "\xcb\x86",     /* MODIFIER LETTER CIRCUMFLEX ACCENT */
		      "\xc2\x89" => "\xe2\x80\xb0", /* PER MILLE SIGN */
		      "\xc2\x8a" => "\xc5\xa0",     /* LATIN CAPITAL LETTER S WITH CARON */
		      "\xc2\x8b" => "\xe2\x80\xb9", /* SINGLE LEFT-POINTING ANGLE QUOTATION */
		      "\xc2\x8c" => "\xc5\x92",     /* LATIN CAPITAL LIGATURE OE */
		      "\xc2\x8e" => "\xc5\xbd",     /* LATIN CAPITAL LETTER Z WITH CARON */
		      "\xc2\x91" => "\xe2\x80\x98", /* LEFT SINGLE QUOTATION MARK */
		      "\xc2\x92" => "\xe2\x80\x99", /* RIGHT SINGLE QUOTATION MARK */
		      "\xc2\x93" => "\xe2\x80\x9c", /* LEFT DOUBLE QUOTATION MARK */
		      "\xc2\x94" => "\xe2\x80\x9d", /* RIGHT DOUBLE QUOTATION MARK */
		      "\xc2\x95" => "\xe2\x80\xa2", /* BULLET */
		      "\xc2\x96" => "\xe2\x80\x93", /* EN DASH */
		      "\xc2\x97" => "\xe2\x80\x94", /* EM DASH */
		  
		      "\xc2\x98" => "\xcb\x9c",     /* SMALL TILDE */
		      "\xc2\x99" => "\xe2\x84\xa2", /* TRADE MARK SIGN */
		      "\xc2\x9a" => "\xc5\xa1",     /* LATIN SMALL LETTER S WITH CARON */
		      "\xc2\x9b" => "\xe2\x80\xba", /* SINGLE RIGHT-POINTING ANGLE QUOTATION*/
		      "\xc2\x9c" => "\xc5\x93",     /* LATIN SMALL LIGATURE OE */
		      "\xc2\x9e" => "\xc5\xbe",     /* LATIN SMALL LETTER Z WITH CARON */
		      "\xc2\x9f" => "\xc5\xb8"      /* LATIN CAPITAL LETTER Y WITH DIAERESIS*/
 ); 

function seems_utf8($Str) {
 for ($i=0; $i<strlen($Str); $i++) {
  if (ord($Str[$i]) < 0x80) $n=0; # 0bbbbbbb
  elseif ((ord($Str[$i]) & 0xE0) == 0xC0) $n=1; # 110bbbbb
  elseif ((ord($Str[$i]) & 0xF0) == 0xE0) $n=2; # 1110bbbb
  elseif ((ord($Str[$i]) & 0xF0) == 0xF0) $n=3; # 1111bbbb
  else return false; # Does not match any model
  for ($j=0; $j<$n; $j++) { # n octets that match 10bbbbbb follow ?
   if ((++$i == strlen($Str)) || ((ord($Str[$i]) & 0xC0) != 0x80)) return false;
  }
 }
 return true;
}
 
function cp1252_to_utf8($str) {
         global $cp1252_map;
         return strtr(utf8_encode($str), $cp1252_map);
} 

function utf8_to_cp1252($str) {
          global $cp1252_map;
          return  utf8_decode( strtr($str, array_flip($cp1252_map)) );
}

function getExtension($name)
{
	return substr (strrchr ($name, "."), 1);
}

/*
  This does a nice character exchange with a random crypt key! 
  If you need a 100% secure connection please use https!
*/
function tfu_enc($str, $id) {
    for ($i = 0; $i < strlen($id); $i++) {
			if (ord($id{$i}) > 127) {
					debug("The crypt key at position " . $i . " is not valid - please change the implementation.");
				  return $str;
			}
		}
		$code = "";
		$keylen = strlen($id);
		for ($i = 0; $i < strlen($str); $i++) {
			$code .= chr(ord($str{$i}) + ord(  $id{($i%$keylen)}  ));
		}
		return utf8_encode($code);
}

function setSessionVariables() {
  global $folder, $enable_folder_browsing,  $enable_folder_creation, $enable_folder_deletion, $sort_files_by_date; 
	global $upload_notification_email, $upload_notification_email_from,$upload_notification_email_subject,$image_magic_path;
	global $upload_notification_email_text,$split_extension,$user, $hide_directory_in_title,$use_image_magic,$login;
	// this setting are needed in the other php files too!
		if ($login == "true"){
		$_SESSION["TFU_LOGIN"] = "true";
	}
  $_SESSION["TFU_ROOT_DIR"] = $_SESSION["TFU_DIR"] = $folder;
	$_SESSION["TFU_BROWSE_FOLDER"]      = $enable_folder_browsing;
	$_SESSION["TFU_CREATE_FOLDER"]      = $enable_folder_creation;
	$_SESSION["TFU_DELETE_FOLDER"]      = $enable_folder_deletion;
	$_SESSION["TFU_SORT_FILES_BY_DATE"] = $sort_files_by_date; 
	$_SESSION["TFU_NOT_EMAIL"]          = $upload_notification_email;
	$_SESSION["TFU_NOT_EMAIL_FROM"]     = $upload_notification_email_from;
	$_SESSION["TFU_NOT_EMAIL_SUBJECT"]  = $upload_notification_email_subject;
	$_SESSION["TFU_NOT_EMAIL_TEXT"]     = $upload_notification_email_text;
	$_SESSION["TFU_SPLIT_EXTENSION"]    = $split_extension;
	$_SESSION["TFU_USER"]               = $user;
	$_SESSION["TFU_HIDE_DIRECTORY_IN_TITLE"] = $hide_directory_in_title;
	$_SESSION["TFU_USE_IMAGE_MAGIC"]    = $use_image_magic;
	$_SESSION["TFU_IMAGE_MAGIC_PATH"]   = $image_magic_path;
	store_temp_session();
}

function sendConfigData() {
  global $login,$rn,$maxfilesize,$resize_show,$resize_data,$resize_label,$resize_default,$allowed_file_extensions;
  global $forbidden_file_extensions,$show_delete,$enable_folder_browsing,$enable_folder_creation;
  global $enable_folder_deletion,$enable_file_download,$keep_file_extension,$show_preview,$show_big_preview;
  global $enable_file_rename,$enable_folder_rename,$enable_folder_move,$enable_file_copymove,$language_dropdown;
  global $preview_textfile_extensions,$edit_textfile_extensions;
  // optional settings
  global $reg_infos,$login_text,$relogin_text,$upload_file,$base_dir,$titel; 
  global $warning_setting,$hide_remote_view,$directory_file_limit,$remote_label;
  global $preview_label,$show_full_url_for_selected_file,$upload_finished_js_url;
  global $preview_select_js_url,$delete_js_url,$js_change_folder,$js_create_folder;
  global $js_rename_folder,$js_delete_folder,$js_copymove,$queue_file_limit,$show_size; 
  global $queue_file_limit_size,$split_extension,$hide_help_button,$direct_download; 
 
	// the sessionid is mandatory because upload in flash and Firefox would create a new session otherwise - sessionhandled login would fail then!
	$output = "&session_id=" . session_id() . "&login=" . tfu_enc($login,$rn);
  $output .= "&maxfilesize=" .  tfu_enc("" . $maxfilesize,$rn); // ;  . "&dir=" . $folder; // folder not sent anymore - only session is used!
	$output .= "&resize_show=" . $resize_show . "&resize_data=" . $resize_data;
  $output .= "&resize_label=" . $resize_label . "&resize_default=" . $resize_default;
	$output .=  "&allowed_file_extensions=" . tfu_enc($allowed_file_extensions,$rn) . "&forbidden_file_extensions=" . $forbidden_file_extensions;
	$output .=  "&show_delete=" . $show_delete . "&enable_folder_browsing=" . $enable_folder_browsing;
  $output .=  "&enable_folder_creation=" . $enable_folder_creation . "&enable_folder_deletion=" . $enable_folder_deletion ;
  $output .=  "&enable_file_download=" . $enable_file_download . "&keep_file_extension=" . $keep_file_extension;
	$output .=  "&show_preview=" . $show_preview . "&show_big_preview=" . $show_big_preview ;
  $output .=  "&enable_file_rename=" . $enable_file_rename . "&enable_folder_rename=" . $enable_folder_rename;
	$output .=  "&enable_folder_copy=" . $enable_folder_move . "&enable_file_copy=" . $enable_file_copymove;
  $output .=  "&language_dropdown=" . $language_dropdown;
	$output .=  "&preview_textfile_extensions=" . $preview_textfile_extensions . "&edit_textfile_extensions=" . $edit_textfile_extensions;
  // optional settings
  $output .=  $reg_infos . "&login_text=" . $login_text;
  $output .=  "&relogin_text=" . $relogin_text . "&upload_file=" . $upload_file; 
  $output .=  "&base_dir=" . $base_dir . "&titel=" . $titel; 
  $output .=  "&warning_setting=" . $warning_setting . "&hide_remote_view=" . $hide_remote_view; 
  $output .=  "&directory_file_limit=" . $directory_file_limit;
	$output .=  "&remote_label=" . $remote_label . "&preview_label=" . $preview_label; 
  $output .=  "&show_full_url_for_selected_file=" . $show_full_url_for_selected_file; 
  $output .=  "&upload_finished_js_url=" . $upload_finished_js_url . "&preview_select_js_url" . $preview_select_js_url; 
  $output .=  "&delete_js_url=" . $delete_js_url . "&js_change_folder=" . $js_change_folder;
	$output .=  "&js_create_folder=" . $js_create_folder . "&js_rename_folder=" . $js_rename_folder; 
  $output .=  "&js_delete_folder=" . $js_delete_folder . "&js_copymove=". $js_copymove;
  $output .=  "&queue_file_limit=" . $queue_file_limit . "&queue_file_limit_size=" . $queue_file_limit_size; 
  $output .=  "&split_extension=" . $split_extension . "&hide_help_button=" . $hide_help_button; 
  $output .=  "&direct_download=" . $direct_download . "&show_size=" . $show_size; 
  echo $output;
}

/* 
This stores all data in a session in a temporary folder as well if it does exist.
This is a workaround if a session is lost and empty in the tfu_upload.php and restored there!
*/
function store_temp_session() {
  if (file_exists(dirname(__FILE__) . "/session_cache")) { // we do your own small session handling
     $cachename = dirname(__FILE__) . "/session_cache/" . session_id();
     $ser_file = fopen($cachename, 'w');
		 fputs($ser_file,  serialize($_SESSION));
	   fclose($ser_file);
  }
}

function restore_temp_session() {
   global $timezone;
   
   if (!isset($_SESSION["TFU_LOGIN"]) && (file_exists(dirname(__FILE__) . "/session_cache"))) { // we do your own small session handling
     $cachename = dirname(__FILE__) . "/session_cache/" . session_id();
     if (file_exists($cachename)) {
       $datei = fopen($cachename, "r");
		   $data =(fgets($datei, filesize ($cachename)+1));
       fclose($datei);
       $_SESSION = unserialize($data); 
		}
  
    // now we have to clean old temp sessions! - we do this once a day only!
    // first we check if we have done this already!
   	if (function_exists("date_default_timezone_set")) { // php 5.1.x
      @date_default_timezone_set($timezone);
    }
    $today = dirname(__FILE__) . "/session_cache/_cache_day_" . date("Y_m_d") . ".tmp";
  	if (file_exists($today)) {
  		return;
  	}
  	// not done - we delete all files on this folder older than 1 day + the _cache_day_*.tmp files
  	  $d = opendir(dirname(__FILE__) . "/session_cache");
			$i = 0;
			$del_time = time() - 60;  // - 86000; // we delete file older then 24 hours
      while (false !== ($entry = readdir($d))) {
        if ($entry != "." || $entry != ".." ) {
            $atime = fileatime(dirname(__FILE__) . "/session_cache/" . $entry);
  					if ($atime < $del_time) {
  						unlink(dirname(__FILE__) . "/session_cache/" . $entry);
  					}
		    }
		  }
    foreach(glob(dirname(__FILE__) . "/session_cache/*.tmp") as $fn) {
        unlink($fn);
    }
    // now we write the flag
   	$fh = fopen($today, 'w');
		fclose($fh);  
  } 
}
@ob_end_clean();
?>