<?php
function ChangeImgSrc($c) {
  if (!is_array($c)) return;
  for ($i=0;$i<=$c["contentpos"];$i++) {
    if ($c[$i]["type"]=="tag") {
      if($c[$i]["data"]["type"]=="open") {
// we are scaning img tag
        if ($c[$i]["data"]["name"]=="img") {
// we are scaning src parameter
          if (isset($c[$i]["pars"]["src"])) {
            $c[$i]["pars"]["src"]["value"]="new value of src parameter";
          }
        }
      }
    }
    if (isset($c[$i]["content"])) ChangeImgSrc(&$c[$i]["content"]);
  }
}

/*
you need to parse file like in testhtmlparser.php
and after this to call

ChangeImgSrc(&$p->content);

back transformation into HTML will be

$src="";
GetPageSrc(&$p->content,&$src);

After it in $src variable you will have changed HTML
*/