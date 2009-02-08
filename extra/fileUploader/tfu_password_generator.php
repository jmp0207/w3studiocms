<?php
/*************************  
  Copyright (c) 2004-2008 TinyWebGallery
  written by Michael Dempfle
 
  This program is free software; you can redistribute it and/or modify 
  it under the terms of the TinyWebGallery license (based on the GNU  
  General Public License as published by the Free Software Foundation;  
  either version 2 of the License, or (at your option) any later version. 
  See license.txt for details.
 
  TTU version: 2.7
 
  $Date: 2007-02-16 09:17:41 +0100 (Fr, 16 Feb 2007) $
  $Revision: 41 $
**********************************************/

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Password generator for TFU</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
.noflash { padding:10px; margin:10px; border: 1px solid #555555; background-color: #f8f8f8; text-align:center; width:430px; }
body { font-family : Verdana,Lucida,sans-serif; font-size: 11px; margin:10px; background-color: #ffffff;}
h2 { background-color : #eeeeee; font-size: 12px; font-weight: bold; border-bottom: 1px solid #000000; padding: 2px 2px 2px 10px; }
</style>
</head>
<body>
<h2>Password generator for TWG Flash Uploader</h2>
<center>
<div class="noflash">
<p>Since version 2.7 all passwords are sent sha-1 encrypted. 
<br>Therefore you need to use encrypted passwords in .htusers.php.</p> 
<p>Not encrypted passwords are not supported anymore!</p>
Enter password and press generate:
<form action="tfu_password_generator.php" method="post">
<input style="margin-top:5px;" name="password" type="text" size="30" maxlength="30">
<input name="" type="submit" value="Generate">
</form>
<?php
if (isset($_POST['password'])) {

     $pas = replaceInput($_POST['password']);
     echo "<p>";
	 if (function_exists("sha1")) {
      echo "SHA1 hash value for '" . $pas . "': '" . sha1($pas) . "'";
	 } else {
	 echo "SHA1 does not exist - Please use a newer php version that supports this.";
	 }
	 echo "</p>";
	 echo "<p>Use the generated value in your your password file .htusers.php.</p>";
}
?>
</div>
</center>
</body>
</html>
<?php 
function replaceInput($input)
{
	$output = str_replace("<", "_", $input);
	$output = str_replace(">", "_", $output);
	$output = str_replace(";", "_", $output);
	$output = str_replace("'", "_", $output);
	// we check some other settings too :)
	if (strpos($output, "cookie(") || strpos($output, "popup(") || strpos($output, "open(") || strpos($output, "alert(") || strpos($output, "reload(") || strpos($output, "refresh(")) {
		$output = "error";
	}
	// we check for security if a .. is in the path we remove this!	and .// like in http:// is invalid too!
	$output = ereg_replace("\.\.", "", $output);
	$output = ereg_replace("://", "___", $output);
	return $output;
}
?>