<?php
require_once(dirname(__FILE__).'/markdown.php');
class w3sMarkdown
{
	public function fromFile($source)
	{
		$fp = fopen ($source, "r");
    $text = fread($fp, filesize($source));
    fclose($fp);
    
		return $this->fromText($text);
	}
	
	public function fromText($text)
	{
		return Markdown($text);
	}
}