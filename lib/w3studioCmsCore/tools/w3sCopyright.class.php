<?php

class w3sCopyright
{
/** 
 * Renders the W3StudioCMS Copyright button.
 * This is the only way I have to let the world to know this software,  
 * for this reason I ask you to leave the copyright intact. Consider 
 * this favour as a little price for the free use of this software.
 *  
 * If you don't like the button or if you think that it isn't 
 * harmonized with your website and you still want to remove it, 
 * I hope you will consider the possibility to draw a new button 
 * or to give me a link back to http://www.w3studiocms.com. 
 * 
 * You can place the copyright everywhere on your template, simply adding an
 * include slot statement, as follows:
 * 
 * <?php include_slot('w3s_copyright')?> 
 * 
 * Suggested example
 * <div id="w3s_copyright"><?php include_slot('w3s_copyright')?></div>
 * 
 * If you don't specify that slot in your template, ws3studioCMS renders the 
 * copyright at the end of the page.
 * 
 * Thank you!
 */
  public static function renderCopyright($pageContents)
  {
    $buttonText = (is_file(sfConfig::get('sf_web_dir') . sfConfig::get('app_w3s_web_skin_images_dir') . '/structural/w3scopyright.png')) ? sprintf('<img src="%s/structural/w3scopyright.png" style="width:75px;height:30px;display:inline !important;visibility:visible !important; border:0;" alt="Visit www.w3studiocms.com and download W3studioCMS for free to get your website" title="W3studioCMS a powerful ajax CMS" />', sfConfig::get('app_w3s_web_skin_images_dir')) : 'Powered by W3studioCMS';
    $w3sCopyrightButton = sprintf('<a href="http://www.w3studiocms.com">%s</a>', $buttonText);
    preg_match("/\<\.*?php include_slot\('w3s_copyright'\)\.*?\>/", $pageContents, $checkCopyright);
    if (count($checkCopyright) == 0)
    {
      $pageContents .= sprintf("\n" . '<div id="w3s_copyright" style="width=100%%;margin:8px 0;text-align:center;">%s</div>' . "\n", $w3sCopyrightButton);
    }
    else
    {
      $pageContents = preg_replace('/\<\?php.*?include_slot\(\'w3s_copyright\'\).*?\?\>/', $w3sCopyrightButton, $pageContents);

      /*$pageContents = str_replace('<?php include_slot(\'w3s_copyright\')?>', $w3sCopyrightButton, $pageContents);*/
    }
    
    return $pageContents;
  }
}
