<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="/js/scriptaculous/lib/js/prototype.js"></script>
<script type="text/javascript">
function uploadFinished(loc) { 
   document.getElementById("status").innerHTML = "Status: uploadFinished(..) called. Parameter: loc=" + loc;
}
function previewSelect(loc,id) {
   document.getElementById("status").innerHTML = "Status: previewSelect(..) called. Parameter: loc=" + loc + ", id=" + id;
}

function deleteFile(loc) {
     document.getElementById("status").innerHTML = "Status: deleteFile(..) called. Parameter: loc=" + loc;
}

function changeFolder(loc) {
     document.getElementById("status").innerHTML = "Status: changeFolder(..) called. Parameter: loc=" + loc;
}

function createFolder(status,loc) {
     if (status == "exists")     statusstr="folder exists"; 
     else if (status == "true")  statusstr="folder created";
     else if (status == "false") statusstr="folder not created";
     else statusstr = "unknown status";
     document.getElementById("status").innerHTML = "Status: changeFolder(..) called. Parameter: loc=" + loc + ", status=" + statusstr;
}

function renameFolder(status,loc) {
     if (status == "exists")     statusstr="destination folder exists"; 
     else if (status == "true")  statusstr="folder renamed";
     else if (status == "false") statusstr="folder not renamed";
     else statusstr = "unknown status";
     document.getElementById("status").innerHTML = "Status: renameFolder(..) called. Parameter: loc=" + loc + ", status=" + statusstr;
}

function deleteFolder(status,loc) {
     if (status == "true")     statusstr="folder deleted"; 
     else if (status == "false") statusstr="folder not deleted";
     else statusstr = "unknown status";
     document.getElementById("status").innerHTML = "Status: deleteFolder(..) called. Parameter: loc=" + loc + ", status=" + statusstr;
}

function copymove(doCopyFolder,type,total,ok,error,exits,param) {
   targetstr = (doCopyFolder == "true") ?  "folder" : "file";
   typestr = (type == "m") ? "move" : "copy";
   document.getElementById("status").innerHTML = "Status: copymove(..) called. Parameter: loc=" + loc + ", target=" + targetstr + ", type=" + typestr + ", total=" + total + ", ok=" + ok + ", error=" + error + ", exists="+exists;
}

function windowClose(){
  opener.document.getElementById('w3s_overlay').style.display = 'none';  
}

function windowCloseAndLoadTemplate(){
  windowClose();
  new Ajax.Request('/templatesImport/extractTemplate',
      {asynchronous:true,
        evalScripts:false
       });    
}

function windowCloseAndRefreshImageList(){
  windowClose();
  opener.document.getElementById('w3s_images_refresher').click();
}
</script>

<style type="text/css">
.noflash { padding:10px; margin:10px; border: 1px solid #555555; background-color: #f8f8f8; text-align:center; width:330px; }
.small {  font-size: 11px; margin:2px; }
a { color: #000099; text-decoration: none;  font-weight: bold; }
a visited { color: #000099;}
a link { color: #000099;}
a hover { color: #999999;}
body,table { font-family : Verdana,Lucida,sans-serif; font-size: 12px; margin:10px;}
h1 { background-color : #3333ff; font-weight: bold; font-size: 16px; border: 1px solid #9999ff; padding: 4px 4px 4px 10px; color: #FFFFFF; }
h2 { background-color : #eeeeee; font-size: 12px; font-weight: bold; border-bottom: 1px solid #000000; padding: 2px 2px 2px 10px; }
</style>
<script type="text/javascript" src="swfobject.js"></script>
<script type="text/javascript">
   document.write('<div id="flashcontent"><div class="noflash">TWG Flash Uploader requires at least Flash 8.<br>Please update your browser.</div></div>');
   var so = new SWFObject("tfu_preloader.swf?config_file_nr=<?php echo $_GET["cfgFile"]?>", "mymovie", "650", "340", "8", "#ffffff");
   // needed that fullscreen works!
   so.addParam("scale","noScale");
   so.addParam("allowfullscreen","true");   
   so.write("flashcontent");
</script>
<noscript>
<object name="mymovie" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="650" height="340"  align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="/js/fileUploader/twg_flash_uploader.swf" />
<param name="quality" value="high" />
<param name="bgcolor" value="#ffffff" />
<param name="scale" value="noScale" />
<param name="allowFullScreen" value="true" />
<embed src="/js/fileUploader/twg_flash_uploader.swf" name="mymovie" quality="high" bgcolor="#ffffff" width="650" height="340" align="middle" scale="noScale" allowfullscreen="true" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
</noscript>

<script type="text/javascript">
<?php if($_GET["cfgFile"] != 2): ?>
Event.observe(window, 'unload', windowCloseAndRefreshImageList, false);
<?php else: ?>
Event.observe(window, 'unload', windowCloseAndLoadTemplate, false); 
<?php endif; ?>
</script>
</head>
<body>
</body>
</html>