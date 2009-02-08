<?php
/**
 * TWG Flash uploader 2.7
 * 
 * Copyright (c) 2004-2008 TinyWebGallery
 * written by Michael Dempfle
 * 
 *   This file is the main configuration file of the flash.
 * 
 *   Please read the documentation found in this file!
 * 
 * There are 2 interesting settings you should look at first:
 *   - $login  - you can implement your own autentification by setting this flag! 
 *               If you use "auth" a login screen appears.
 *   - $folder - The folder where your uploads will be saved!
 * 
 *   Have fun using TWG Flash Uploader
 */
define('_VALID_TWG', '42');

if (isset($_GET['TFUSESSID'])) { // this is a workaround if you set php_flag session.use_trans_sid=off + a workaround for some servers that don't handle sessions correctly if you open 2 instances of TFU
  session_id($_GET['TFUSESSID']);
}
session_start();

$install_path = ""; // Please read the howto 8 of the TFU FAQ what you have to do with this parameter! You need a / at the end if you set it!

include $install_path . "tfu_helper.php";
/*
    CONFIGURATION
*/
$login = "true"; // The login flag - has to set by yourself below "true" is logged in, "auth" shows the login form, "reauth" should be set if the authentification has failed. "false" if the flash should be disabled.
$folder = "upload"; // this is the root upload folder. 

$maxfilesize = getMaximumUploadSize(); // The max files size limit of the server in KB. You can specify your own limit here e.g. 512. This setting is restricted by your server settings! Please read FAQ 4 of the TFU FAQ how to set upload_max_filesize and post_max_size.

$resize_show = is_gd_version_min_20(); // Show the resize box! Valid is "true" and "false" (Strings!) - the function is_gd_version_min_20 checks if the minimum requirements for resizing images are there! 
$resize_data = "100000,1280,1024,800,640,320"; // The data for the resize dropdown
$resize_label = "Original,1280,1024,800,640,320"; // The labels for the resize dropdown
$resize_default = "0";                 // The preselected entry in the dropdown (1st = 0)
$allowed_file_extensions = "txt,zip,swf";  // Allowed file extensions! jpg,jpeg,gif,png are allowed by default. "all" allowes all types - this list is the supported files in the browse dropdown! Please note: The filter of the file chooser dialog is limited - I have found out that if you have more than 30 extensions + $split_extension == FALSE (= no support for splitted files) not all files are listed in the dropdown if "supported files " is used. If $split_extension == 'part' (= support for splitted files) the number is limited to 10. This does not mean that TFU would not process them correctlty! If you specify more TFU automatically uses "All Files" - Then all files are listed and not supported extensions are checked by the flash after pressing "Open"
$forbidden_file_extensions = "php";    // Forbidden file extensions! - only usefull if you use "all" and you want to skip some exensions!

// Enhanced features - this are only defaults! if TFU detects that this is not possible this functions are disabled!
$hide_remote_view = "";                 // If you want to disable the remote view add "&hide_remote_view=true" as value!
$show_preview = is_gd_version_min_20(); // Show the small preview. Valid is "true" and "false" (Strings!) - the function is_gd_version_min_20 checks if the minimum requirements for resizing images are there! 
$show_big_preview = "true";             // Show the big preview - clicking on the preview image shows a bigger preview 
$show_delete = "true";                  // Shows the delete button - if download is set to button this moves to the menu!
$enable_folder_browsing = "true";       // Without browsing creation and deletion is disabled by default!
$enable_folder_creation = "true";       // Show the menu item to create folders
$enable_folder_deletion = "true";       // Show the menu item to delete folders - this works recursive!
$enable_folder_rename = "true";         // Show the menu item to rename folders
$enable_file_rename = "false";          // Show the menu item to rename files - default is false because this is a securiy issue - check the point below - should only be activated in very save environments or if you keep the file extension in the registered version.!
$keep_file_extension = "true";          // You can disalow to change the file extension! - only available in the unlimited version! 
$language_dropdown="de,en,es,it,cn,da,fr,nl,no,pl,sk,pt";   // New 2.6 - You can enable a dropdown for the language selection. If you leave this empty no selector is shown (you can still change the language with the url parameter). Otherwise you specify the flags here. They are displayed in the given order! The default language is still given by the url parameter! 
// $timezone - This setting can be found at the top of tfu_helper.php
// $exclude_directories - This setting can be found at the top of tfu_helper.php
$use_image_magic = false;                // You can enable image magick support for the resize of the upload. If you know that you have image magic on your server you can set this to true. image magick uses less memory then gd lib.
$image_magic_path="convert";             // This is the image magick command used to convert the images. convert is the default of image magic.

// some optional things - can be removed as well - the defaults are entered below!
$login_text = "";                       // e.g. "Please login";  // Login Text
$relogin_text = "";                     // e.g. "Wrong Username/Password. Please retry"; // Retry login text
$upload_file = "tfu_upload.php";        // Upload php file - this is relative to he flash
$base_dir = $install_path;              // this is the base dir of the other files - tfu_read_Dir, tfu_file and the lang folder. since 2.6 there are no seperate settings for tfu_readDir and tfu_file anymore because it's actually not needed.
$sort_files_by_date = false;             // sort files that last uploaded files are shown on top
$warning_setting = "all";               // the warning is shown if remote files do already exist - can be set to all,once,none
$direct_download="";                    // true = "true" ; false = "" - If the downloads are corrupt from your server you can enable the direct download. Then the flash tries to get the files directly and not over a php script. The disadvantage is that the url has to be urlencoded - and not all filenames are possible then - do only use this if the download does not work with the default setting. 
$split_extension="FALSE";               // This is the extension when you upload splitted files - tfu can merge them after upload. A splited file has to ge like: file.extension.part1, file.extension.part2 ... - the file extension cannot be empty - if emptpy the default is part! to disable splited uploads use "FALSE";
$show_size="true";                      // true = "true" ; false = "" - by default the size of the files are shown - but you can disable this by removing the parameter and setting it to false;
$hide_directory_in_title="false";       // You can disable the display of the upload dir in the title bar if you set this to "true"

// the text of the email is stored in the tfu_upload.php if you like to change it :) 
$upload_notification_email = "";        // you can get an email everytime a fileupload was initiated! The mail is sent at the first file of an upload queue! "" = no emails - php mail has to be configured properly!
$upload_notification_email_from = "";   // the sender of the notification email!
$upload_notification_email_subject ="Files were uploaded by the TWG Flash Uploader"; // Subject of the email - you should set a nicer one after the login or in tfu_upload.php
$upload_notification_email_text ="The following files where uploaded by %s: %s";    // Text of the email - the first %s ist the username (if no is set "not set is used"), the 2nd %s is the list of files that where uploaded!

/**
 * Extra settings for the registered version
 */
$titel = "TWG Flash Uploader";          // This is the title of the flash - can not be set in the freeware version!
$remote_label     = "";                 // "Remote" This is a optional setting - you can change the display string above the file list if you want to use a different header - can only be changed in the unlimited version! - if you want to have a & you have to urlencode the & !
$preview_label    = "";                 // "Preview" This is a optional setting - you can change the display string of the header if you don't use the preview but maybe this function to determine the selection in the remote file list - can only be changed in the unlimited version!  - if you want to have a & you have to urlencode the & !
$upload_finished_js_url = "";           // "status.???" - You can specify a url that is called by the flash in the js function uploadFinished(param) This makes it possible e.g. to show a kind of result in a iframe below the flash. - only available in the unlimited version! Check the tfu.htm for examples of the Javascript function.
$preview_select_js_url = "";            // "preview.???" - You can specify a url that is called by the flash in the js function previewSelect(param) This makes it possible e.g. to show a kind of result in a iframe below the flash. this is only executed if show_preview=true - only available in the unlimited version! Check the tfu.htm for examples of the Javascript function.
$delete_js_url    = "";                 // "delete.???" - You can specify an url that is called by the flash in the js function deleteFile(param) This makes it possible e.g. to show a kind of result in a iframe below the flash is someone deletes a file. - only available in the unlimited version!
$js_change_folder = "";                 // "change_folder.???" - You can specify an url that is called by the flash in the js function changeFolder(param) This makes it possible e.g. to show a kind of result in a iframe below the flash is someone changes a folder. - only available in the unlimited version!
$js_create_folder = "";                 // "create_folder.???" - You can e.g. specify an url that is called by the flash in the js function createFolder(status,param). status is the status of the folder creation. Possible status values are: exists (folder exists), true (folder created), false (folder not created) - only available in the unlimited version!
$js_rename_folder = "";                 // "ren_folder.???" - You can e.g. specify an url that is called by the flash in the js function renameFolder(status,param). status is the status of the folder rename. Possible status values are: exists (destination folder exists), true (folder renamed), false (folder not renamed) - only available in the unlimited version!
$js_delete_folder = "";                 // "del_folder.???" - You can e.g. specify an url that is called by the flash in the js function deleteFolder(status,param). status is the status of the folder delete. Possible status values are: true (folder deleted), false (folder not deleted) - only available in the unlimited version!
$js_copymove      = "";                 // "copymove.???" - You can e.g. specify an url that is called by the flash in the js function copymove(doCopyFolder,type,total,ok,error,exits,param). Check the example in tfu.htm for a description of all parameters - only available in the unlimited version!

$show_full_url_for_selected_file = "";  // "true" - if you use this parameter the link to the selected file is shown - can be used for direct links - only available in the unlimited version!  
$directory_file_limit = "100000";       // you can specify a maximum number of files someone is allowed to have in a folder to limit the upload someone can make! - only available in the unlimited version!  
$queue_file_limit = "100000";           // you can specify a maximum number of files someone can upload at once! - only available in the unlimited version!  
$queue_file_limit_size = "100000";      // you can specify the limit of the upload queue in MB! - only available in the unlimited version!  
$hide_help_button="false";              // since TFU 2.5 it is possible to turn off the ? (no extra flash like before is needed anymore!) - it is triggered now by the license file! professional licenses and above and old licenses that contain a TWG_NO_ABOUT in the domain (=license for 20 Euro) enable that this switch is read - possible settings are "true" and "false" 
$enable_file_download = "button1";         // You can enable the download of files! valid entries "true", false", "button", "button1" - "button" show the dl button insted the menu button if all other elements of the menu are set to false - "button1" shows the download button instead of the delete button and the delete function is moved to the menu if enabled! - only available in the unlimited version!
$enable_folder_move="true";             // New 2.6 - Show the menu item to move folders - you need a professional license or above to use this
$enable_file_copymove="true";           // New 2.6 - Show the menu item to move and copy files - you need a professional license or above to use this
$preview_textfile_extensions = "out,log";  // New 2.7 - This are the files that are previewed in the flash as textfiles. Right now I only have "save" extensions. But you can have any extension here. If you don't use a . this settings are extensions. But you can restrict is to single files as well by using the full name. e.g. foldername.txt. * is supported as wildcard! Only available for registered users.
$edit_textfile_extensions = "txt";      // New 2.7 - This are the files that can be edited in the flash. But you can restrict is to single files as well by using the full name. e.g. foldername.txt. * is supported as wildcard! Only available for registered users. 


/*
     AUTHENTIFICATION

This part is interesting if you want to use the login!
*/
if (isset($_POST['twg_user'])){ // twg_user and twg_pass are always sent by the flash! - never remove this part! otherwise everyone can call tfu_config directly
  $user = $_POST['twg_user'];
  $pass = $_POST['twg_pass'];
  $rn   = $_POST['twg_rn'];
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     $rn = substr(session_id(),0,5) . $rn . session_id();

/*
  TFU has a very simply user managment included - 
  add users/folders/paths at .htusers.php.
  The password is encrypted - please use the password generator that is included.
*/
  if (($login == "auth" || $login == "reauth") && $user != "") {
    include ($install_path . ".htusers.php");
    foreach ($GLOBALS["users"] as $userarray){
        //
        // you have to use sha1 encrypted passwords if you want to use the 
        // included login mechanism - see the provided password generator.
      if ($user == $userarray[0] && $pass == $userarray[1]){ 
        $login = "true";
        $folder = $userarray[2]; 
        if ($userarray[3] != "") {
          $show_delete = $userarray[3];  
        }
        if ($userarray[4] != "") {
          $enable_folder_browsing = $enable_folder_creation = $userarray[4];
          $enable_folder_deletion = $enable_folder_rename   = $userarray[4];
        }
        break;
      } else {
        $login = "reauth";
      }
    }
  }
  
  // this setting are needed in the other php files too!
  setSessionVariables();
  
  // sending and checking the registration infos - check is done in the flash therefore
  // we have to send all the registration infos to the flash too!
  $license_file = $install_path . "twg.lic.php";
  if (file_exists($license_file)){
    include $license_file;
    // we encrypt the license data since 1.7 to enhance security!
    $d = tfu_enc($d, $rn);
    $l = tfu_enc($l, $rn);
    $s = tfu_enc($s, $rn);
    $m = tfu_enc($m, $rn);  
    $reg_infos = "&d=" . $d . "&s=" . $s . "&m=" . $m . "&l=" . $l;
  }else{
    $reg_infos = ""; // means freeware version!
  }
  
  // The following data is only sent enccypted:
  // - login                     - to disable unautorized access when the response is modified
  // - maxfilesize               - to disable that bigger files can be uploaded when the response is modified
  // - allowed_file_extensions   - to disable that other then the allowed extensions can be uploaded when the response is modified 
  // - registration data         - to secure your registration data that it can be monitored und used by someone else.
  // If you want to secure more of the sent parameters you have to change this here and in the flash
  sendConfigData();  
}else{
  echo '
  <style type="text/css">
  body {   font-family : Arial, Helvetica, sans-serif; font-size: 12px; background-color:#ffffff; }
  td { vertical-align: top; font-size: 12px; }
  .install {  margin-left: auto;  margin-right: auto;  margin-top: 3em;  margin-bottom: 3em; padding: 10px; border: 1px solid #cccccc;  width: 650px; background: #F1F1F1; }
</style>
';
  echo "<br> <p><center>Some info's about your server. This limits are not TFU limits. You have to change the php.ini.</center></p>";
  echo "<div class='install'>";
  echo "<table><tr><td>";
  echo "<tr><td width='400'>Server name:</td><td width='250'>" . $_SERVER['SERVER_NAME'] . "</td></tr>";
  echo "<tr><td>PHP upload limit (in KB): </td><td>" . getMaximumUploadSize(). "</td></tr>"; 
  echo "<tr><td>PHP memory limit (in KB):&nbsp;&nbsp;&nbsp;</td><td>" . return_kbytes(ini_get('memory_limit')) . "</td></tr>";   
  echo "<tr><td>Safe mode:</td><td>";
  echo  (ini_get('safe_mode') == 1) ? "ON<br>You maybe have some limitations creating folders or uploading<br>if the permissions are not set properly.<br>Please check the TWG FAQ 30 if you want to know more about<br>safe mode and the problems that comes with this setting." : "OFF";
  echo "</td></tr><tr><td>GD lib:</td><td>";
  echo (!function_exists("imagecreatetruecolor")) ? '<font color="red">GDlib is not installed properly.<br>TFU Preview does not work!</font>' : 'Available';
  echo "</td></tr>";
  echo "<tr><td>The times below have to be longer than the max. upload duration!<br>Otherwise the upload will fail.</td><td>&nbsp;</td></tr>";
  echo "<tr><td>PHP maximum execution time: </td><td>" . ini_get('max_execution_time') . " s</td></tr>";
  echo "<tr><td>PHP maximum input time: </td><td>" . ini_get('max_input_time') . " s</td></tr>"; 
  echo "</table>";
  echo "</div>";
}

?>