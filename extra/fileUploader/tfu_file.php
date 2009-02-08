<?php
/**
 * TWG Flash uploader 2.7
 *
 * Copyright (c) 2004-2008 TinyWebGallery
 * written by Michael Dempfle
 *
 *    This file does the following:
 *      - Rename a file
 *      - Delete a file
 *      - Preview an image
 *      - Get the file size of an file
 *      - Download an image
 *
 *    If an image was detected: jpj, png or gif the images are resized to fit in
 *    the preview box (90 x 55). For all other files not image is returned!
 *
 *    Authentification is done by the session $_SESSION["TFU_LOGIN"]. you can set
 *    this in the tfu_config.php or implement your own way!
 */
define('_VALID_TWG', '42');

if (isset($_GET['TFUSESSID'])) { // this is a workaround if you set php_flag session.use_trans_sid=off + a workaround for some servers that don't handle sessions correctly if you open 2 instances of TFU
  session_id($_GET['TFUSESSID']);
}
session_start();

include "tfu_helper.php";

if (isset($_SESSION["TFU_LOGIN"])) { //
	$dir = getCurrentDir();
	// if you have more complex filenames you can use the index
	if (isset($_GET['index'])) {
	   if (isset($_GET['copyfolder']) && ($_GET['copyfolder'] == "true")) {
		  $file = ""; // not needed for this task
		} else {
		  $file = getFileName($dir); // returns an array if more than one is selected!
		}
			$action = $_GET['action'];
			if ($action == "rename") { // rename a file
			  $newName = str_replace("/", "", str_replace("\\", "", $_GET['newfilename'])); // fixes that file can be renamed to an upper dir.
				if ($fix_utf8 == "") {
					$newName = $dir . "/" . utf8_decode($newName);
				} else {
					$newName = $dir . "/" . iconv("UTF-8", $fix_utf8, $newName);
				}
				if (!file_exists($newName)) {
					if (is_writeable($file)) {
						$result = @rename($file, $newName);
						if ($result) {
							echo "&result=true";
						} else {
							echo "&result=false";
						}
					} else {
						echo "&result=perm";
					}
				} else {
					echo "&result=exists";
				}
			} else if ($action == "delete") { // delete a file
				if (is_tfu_deletable($file)) {
					set_error_handler("on_error_no_output");
					@chmod($file , 0777);
					set_error_handler("on_error");
					$result = @unlink($file);
					if ($result) {
						echo "&result=true";
					} else {
						echo "&result=false";
					}
				} else {
					echo "&result=perm";
				}
			} else if ($action == "xdelete") { // delete several files!
				$deleted = 0;
				$perm = 0;
				$notdel = 0;
				foreach ($file as $ff) {
					if (is_tfu_deletable($ff)) {
						set_error_handler("on_error_no_output");
						@chmod($ff , 0777);
						set_error_handler("on_error");
						$result = @unlink($ff);
						if ($result) {
						    $deleted++;
						} else {
							$notdel++;
						}
					} else {
						$perm++;
					}
				}
				
				echo "&result=multiple&nr_del=" . $deleted . "&nr_perm=". $perm. "&nr_not_del=" .$notdel;
	
			} else if ($action == "copymove") { // copy move files!
				$done = 0;
				$total = 0;
				$error = 0;
				$exists = 0;
				$overwrite = $_GET['overwrite']; 
				$folder = getDestinationFolder( $_GET['target']);
				$dest_folder = $folder . "/" . basename($dir); 
				if  ($_GET['copyfolder'] == "true") {
				  if ($folder == $dir) {
				    $error = 1;
				  } else if (strpos ($folder,$dir) !== false) {
				    $error = 2;
				  } else if ($overwrite == "false" && file_exists($dest_folder)) {
				    $error = 3;
				  } else {
				    if (@rename($dir, $dest_folder)) {
					  $done=1;
					  $upperdir = substr($dir, 0, strrpos ($dir, "/"));
            $_SESSION["TFU_DIR"] = $upperdir;  
					} else {
					  $error=4; 
					}
				  }  
				} else {
					foreach ($file as $ff) {
						$total++;						
						$dest = $folder . "/" . basename($ff);
						if ($_GET['type'] == 'c') {
						 	if ($folder == $dir) {
							  $u_file=get_unique_filename($folder,basename($ff));
							  $dest = $folder . "/" .$u_file;
							}
							if (file_exists($dest) && $overwrite == "false") { // if file exists and not overwrite = error
								 $exists++; 
							} else {
							    if (@copy($ff, $dest)) {
								  $done++;
								} else {
								  $error++;
								}
							} 
						} else {
							if (file_exists($dest) && $overwrite) {
							  @unlink($dest);
							}
							if (!file_exists($dest)) {
								if (@rename($ff, $dest)) {
								  $done++;
								} else {
								  $error++; 
								}
							}
						}
					}
				}
				echo  "&total=".$total."&ok=" . $done . "&error=" . $error . "&exists=" . $exists ;
			} else if ($action == "preview") { // preview image
				// we store the url of the last preview image in the session - use it if you need it ;).
				// we generate thumbs for jpge,png and gif!
				if (preg_match("/.*\.(j|J)(p|P)(e|E){0,1}(g|G)$/", $file) ||
						preg_match("/.*\.(p|P)(n|N)(g|G)$/", $file) ||
						preg_match("/.*\.(g|G)(i|I)(f|F)$/", $file)) {
					if (isset($_GET['big'])) {
						send_thumb($file, 90, 440, 280); // big preview 4x bigger!
					} else {
						send_thumb($file, 90, 80, 55); // small preview
					}
				} else {
					return; // we return nothing if no image.
				}
			} else if ($action == "info") { // get infos about a file
				unset($_SESSION["TFU_LAST_UPLOADS"]);
				$_SESSION["TFU_LAST_PREVIEW"] = fixUrl(getRootUrl() . $file);
				echo "&size=" . filesize($file);
				// we check if the image can be resized
				if (is_supported_tfu_image($file)) {
					set_error_handler("on_error_no_output"); // is needed because error are most likly but we don't care about fields we don't even know
					$oldsize = @getimagesize($file);
					set_error_handler("on_error");
					if ($oldsize) {
						if (isMemoryOk($oldsize, "")) {
						    echo "&hasPreview=true&tfu_x=" . $oldsize[0] . "&tfu_y=" . $oldsize[1] ; // has preview!
						} else {
							echo "&hasPreview=error"; // too big! - same error massage as hasPreview=false
						}
						return;
					} 
				 echo "&hasPreview=false"; // no image!
				}
			}  else if ($action == "text") { // get infos about a file		
			  if (is_writable($file)) {
          echo "&writeable=true";
        } else {
          echo "&writeable=false";
        } 
        echo "&data=";
				$enc = "UTF-8";
				$format = "UNIX";
				$fp = fopen($file, "rb");
				$content = fread ($fp, filesize ($file));
				// we replace \r with nothing
				$content_new = str_replace("\r","", $content);
				if ($content_new != $content) {
           $format = "DOS";
        }
        if (!seems_utf8($content_new)) {
				  $content_new = cp1252_to_utf8($content_new); 
				  $enc = "ANSI";
				}
				print $content_new;
				echo "&encoding=" . $enc;
				echo "&format=" . $format;
				fclose($fp);
			}  else if ($action == "savetext") { // save a textfile
				$content = urldecode($_POST['data']);
        if ($_POST['encoding'] == "ANSI") {
           $content = utf8_to_cp1252($content);  
        }
        if ($_POST['format'] == "DOS") {
           $content = preg_replace('/\r\n|\r|\n/', chr(13).chr(10), $content);
        } else {
           $content = preg_replace('/\r\n|\r|\n/', chr(10), $content);
        }
        // now we write the file again
        $file_local = fopen($file, 'w');
        if (getExtension($file)== "php") { // we remove leading and trailing spaces returns if it is a php file!
         $content = trim($content);
        }
        fputs($file_local, $content); 
        fclose($file_local);
			} else if ($action == "download") { // download a file - we set the header !			  
        header("Content-type: application/octet-stream");
			  header("Content-disposition: attachment; filename=".basename($file));
				header("Content-Length: ".filesize($file));
			  header("Pragma: no-cache");
        header("Expires: 0");
				$fp = fopen($file, "rb");
				while ($content = fread($fp, 8192 * 128)) { //
					print $content;
				}
				fclose($fp);
			}
	} else {
		echo "&result=index";
	}
	store_temp_session();
} else {
	echo "Not logged in!";
}
?>