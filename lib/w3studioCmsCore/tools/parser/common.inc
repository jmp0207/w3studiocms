<?php
function PrintArray($array,$str="") {
  if (!strlen($str))
    print "\r\n<pre>";
  $keys=array_keys($array); 
  for($i=0;$i<sizeof($keys);$i++) { 
    $str1=$str."[".$keys[$i]."]"; 
    if (is_array($array[$keys[$i]])) {
      if ($keys[$i]==="hash")
        print "element$str1=hashtable\r\n"; 
      else
        PrintArray($array[$keys[$i]],$str1);
    } else 
      print "element$str1=".htmlspecialchars($array[$keys[$i]])."\r\n"; 
  } 
  if (!strlen($str))
    print "</pre>";
} 

function GetPageSrc($c,$src) {
  if (!is_array($c)) return;
  for ($i=0;$i<=$c["contentpos"];$i++) {
    switch ($c[$i]["type"]) {
      case "comment":
        $src.=$c[$i]["data"];
        break;
      case "text":
        $src.=$c[$i]["data"];
        break;
      case "tag":
        switch($c[$i]["data"]["type"]) {
          case "open":
            $src.="<".$c[$i]["data"]["name"];
            if (isset($c[$i]["pars"])) {
              $keys=array_keys($c[$i]["pars"]);
              for ($j=0;$j<sizeof($keys);$j++)
                if (isset($c[$i]["pars"][$keys[$j]]["single"])) {
                  $src.=" ".$keys[$j];
                } else
                  $src.=" ".$keys[$j]."=".$c[$i]["pars"][$keys[$j]]["quot"].$c[$i]["pars"][$keys[$j]]["value"].$c[$i]["pars"][$keys[$j]]["quot"];
            }
            if (isset($c[$i]["xmlclose"])) $src.=" /";
            $src.=">";
            break;
          case "close":
            $src.="</".$c[$i]["data"]["name"].">";
            break;
        }
        break;
    }
    if (isset($c[$i]["content"])) GetPageSrc($c[$i]["content"],$src);
  }
}

function Read_File($name,$type="r") {
  if(!$fp=@fopen($name,$type)) return false;
  flock($fp,1);
  $buf=fread($fp,filesize($name));
  flock($fp,3);
  fclose($fp);
  return $buf;
}

function Write_File($name,$buf,$type="w") {
  if(!$fp=@fopen($name,$type)) return false;
  flock($fp,2);
  $res=fwrite($fp,$buf,strlen($buf));
  flock($fp,3);
  fclose($fp);
  return $res;
}
?>