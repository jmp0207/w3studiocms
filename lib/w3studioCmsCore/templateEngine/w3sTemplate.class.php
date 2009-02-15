<?php
/*
 * This file is part of the w3studioCMS package library and it is distributed 
 * under the LGPL LICENSE Version 2.1. To use this library you must leave 
 * intact this copyright notice.
 *  
 * (c) 2007-2008 Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 *  
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.w3studiocms.com
 */
 
/**
 * Template class represents the page's template.
 * 
 * Note: This object is not completed yet
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sTemplateEngine
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
abstract class w3sTemplateEngine
{
  protected 
    $idPage,
    $idLanguage,
    $idTemplate,
    $languageName,								// Redonly
    $pageName,                    // Redonly
    $templateName,
    $projectName,
    $templateFileName, 
    $pageContents;
    
	abstract function drawSlot($contents);

	/**
   * Constructor.
   * 
   * @param int  The current language id
   * @param int  The current page id
   *
   */   
  public function __construct($language, $page)
  {
    
    // Checks that the two parameters are integers values
    $this->idLanguage = (int)$language;  
    $this->idPage = (int)$page;
    
    // Languages check. When the value is zero it means that the language name is passed 
    if ($this->idLanguage == 0)
    {
    	$oLanguage = ($language != '') ? W3sLanguagePeer::getFromLanguageName($language) : W3sLanguagePeer::getMainLanguage();
    	$this->idLanguage = ($oLanguage != null) ? $oLanguage->getId() : -1; 
    }
    else
    {
    	$oLanguage = W3sLanguagePeer::getMainLanguage();    	
    }
    $this->languageName = ($oLanguage != null) ? strtolower($oLanguage->getLanguage()) : 'none';
    
    // Pages check. When the value is zero it means that the page name is passed 
    if ($this->idPage == 0)
    {    	
    	$oPage = ($page != '') ? W3sPagePeer::getFromPageName($page) : W3sPagePeer::getHomePage();
    	$this->idPage = ($oPage != null) ? $oPage->getId() : -1;
    }
    else
    {    	
    	$oPage = W3sPagePeer::getHomePage();
    }
    $this->pageName = ($oPage != null) ? strtolower($oPage->getPageName()) : 'none';
    
    // Gets the template information
    $page = DbFinder::from('W3sPage')->
                      with('W3sTemplate', 'W3sProject')->  
                      leftJoin('W3sGroup')->
                      leftJoin('W3sTemplate')->
                      leftJoin('W3sProject')->
                      findPK($this->idPage);                        
    $this->setCurrentTemplate($page);
  }
  
  public function setIdPage($value){
    $this->idPage = $value;
  }
  
  public function getIdPage(){
    return $this->idPage;
  }

  public function setIdLanguage($value){
    $this->idLanguage = $value;
  }  
  
  public function getIdLanguage(){
    return $this->idLanguage;
  }
  
  public function getLanguageName(){
    return $this->languageName;
  }
  
  public function getPageName(){
    return $this->pageName;
  }
  
  public function getPageContents()
  {
    return $this->pageContents;  
  }
  
  /**
   * Checks if the requested page is not in use from another user, and free the 
   * previous page used by current user.
   * 
   * @param int  The previous page id
   *
   */   
  public function isPageFree($prevPage)
  {
    $operation = $this->idLanguage . $this->idPage;
    $prevOperation = $this->idLanguage . $prevPage;
    return semaphore::setRequestedOperation(sfContext::getInstance()->getUser()->getGuardUser()->getId(), $operation, $prevOperation);
  }

	/**
   * Reads all the site's template files and extracts the stylesheets' references. 
   * 
   * Note: It's not possibile to use the $this->response->addStyleSheet method as 
   * made in the webSite module, because w3studioCMS needs the title attribute to 
   * change the stylesheets in editor mode.
   * 
   * @return string  The html stylesheets 
   *
   */  
  public function retrieveSiteStylesheets()
  {    
    // Gets all the project's templates from the database
    $templates = DbFinder::from('W3sTemplate')->  
		                       leftJoin('W3sProject')->
		                       find();   // where('W3sProject.ProjectName', $this->projectName)->
    
    $result = ''; 
    foreach($templates as $template){
    	$templateContents = w3sCommonFunctions::readFileContents(w3sTemplateTools::getTemplateFile($template->getW3sProject()->getProjectName(), $template->getTemplateName()));
    	$stylesheets = $this->getStylesheetsFromContents($templateContents);  
    	foreach ($stylesheets[0] as $stylesheet){    
    		
    		// Set for every stylesheet the title that corresponds to the stylesheet's name.
    		// This is required by the function that changes the template's stylesheet    		
    		$stylesheetName = basename(w3sCommonFunctions::getTagAttribute($stylesheet, 'href'));
    		$stylesheetName = str_replace('.css', '', $stylesheetName);
	      $result .= str_replace('<link', sprintf('<link title="%s" ', $stylesheetName), $stylesheet) . "\n";    		
	    }
    }

    return $result; 
  }
  
  /**
   * Retrieves the stylesheets used by the current template. The result string will
   * be uses to change the template's stylesheet. 
   * 
   * @return string  The stylesheets name formatted as style1,[style2,style3,...]
   *
   */ 
  public function retrieveTemplateStylesheets($contents = null)
  {    
    $stylesheets = $this->getStylesheetsFromContents($this->pageContents);
    $this->pageContents = $this->removeStylesheetsFromTemplate($stylesheets, $this->pageContents);
    
    $stylesheetResults = '';
	  foreach ($stylesheets[1] as $stylesheet){
	    $stylesheetResults .= str_replace('.css', '',basename($stylesheet)) . ',';
	  }
    
    return $stylesheetResults; 
  }  
  
  /**
   * Renders the page
   * 
   * @return string  The rendered page 
   *
   */ 
  public function renderPage()
  {
    $slotNames = '';
    $slotContents = $this->getSlotContents($this->idLanguage, $this->idPage);
    
    foreach ($slotContents as $slot){ 
      $slotNames .= sprintf('"%s",', $slot['slotName']);
      $contents = $this->drawSlot($slot);      
      $this->pageContents = ereg_replace('\<\?php include_slot\(\'' . $slot['slotName'] . '\'\)\?\>', $contents, $this->pageContents);      
    }
    
    // Renders the W3StudioCMS Copyright button. Please do not remove. See the function to 
    // learn the best way to implement it in your web site. Thank you
    $this->pageContents = $this->renderCopyright($this->pageContents);
    //$listOptions = sprintf('dropOnEmpty:true,containment:[%s],constraint:false,', $slotNames);
    
    return $this->pageContents;
  }
    
  /**
   * Removes the stylesheets reference from the template
   * 
   * @param array    The template's stylesheets
   * @param string   The contents to process
   * 
   * @return string  The processed contents
   *
   */ 
  protected function removeStylesheetsFromTemplate($stylesheets, $contents)
  {
    foreach ($stylesheets[0] as $stylesheet)
    {
      $contents = str_replace($stylesheet, '', $contents); 
    }
    
    return $contents;
  }
  /**
   * Retrieves from the database the template associated to page requested
   * 
   * @return object  The retrieved page
   *
   */
  protected function setCurrentTemplate($page){
    if ($page != null){ 
	    $templateInfo = w3sTemplateTools::retrieveTemplateAttributesFromPage($page);
	    $this->idTemplate = $templateInfo["idTemplate"];
	    $this->templateName = $templateInfo["templateName"];
	    $this->projectName = $templateInfo["projectName"];
	    $this->pageContents = w3sCommonFunctions::readFileContents(w3sTemplateTools::getTemplateFile($this->projectName, $this->templateName));
    }
  }
  
  /**
   * Returns the page's contents grouped by slots, retrieved from database
   * 
   * @param int  The language id
   * @param int  The page id
   * 
   * @return array 
   *
   */
  protected function getSlotContents($idLanguage, $idPage){
    $slotName = '';
    $isRepeated = 0;
    $resultContents = array();
    
    $slots = DbFinder::from('W3sSlot')->
			                 where('TemplateId', $this->idTemplate)->
			                 where('ToDelete', 0)->
			                 orderBy('Id')->
			                 find();  
    foreach($slots as $slot)
    {            
	    $currentSlotContents = array();
	    $currentSlot = $slot->getId();
	    $slotName = $slot->getSlotName();
		  $isRepeated = $slot->getRepeatedContents();
	    $contents = DbFinder::from('W3sContent')->
	                where('LanguageId', $idLanguage)->
	                where('PageId', $idPage)->
	                where('SlotId', $currentSlot)->
	                where('ToDelete', 0)->
	                orderBy('ContentPosition')->
	                find();  
	    if ($contents != null)
	    {
		    foreach($contents as $content)
		    {
		      $currentSlotContents[] =  $content;
		    }
	    }
	    else
	    {
	    	$currentSlotContents[] = null; 
	    }  
	    
	    // Saves the last slot's content
	    $resultContents[] =  array('contents' => $currentSlotContents, 'idSlot' => $currentSlot, 'slotName' => $slotName, 'isRepeated' => $isRepeated);
    }
    
		return $resultContents;
  }
  
  /**
   * Returns the template's stylesheets 
   * 
   * @param string  The template contents
   * 
   * @return array
   *
   */
  protected function getStylesheetsFromContents($templateContents)
  {    
    preg_match_all('/<link.*?rel\s*=\s*["|\']stylesheet["|\'].*?href\s*=\s*["|\'](.*?)["|\'].*?\/>/', $templateContents, $stylesheets);
    
    return $stylesheets; 
  }
  
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
  final protected function renderCopyright($pageContents){
    $w3sCopyrightButton = sprintf('<a href="http://www.w3studiocms.com"><img src="%s/structural/w3scopyright.png" style="width:75px;height:30px;display:inline !important;visibility:visible !important;" /></a>', sfConfig::get('app_w3s_web_skin_images_dir'));   
    preg_match("/\<\?php include_slot\('w3s_copyright'\)\?\>/", $pageContents, $checkCopyright);
    if (count($checkCopyright) == 0){ 
      $pageContents .= sprintf('<div id="w3s_copyright" style="width=100%%;margin:8px 0;text-align:center;">%s</div>', $w3sCopyrightButton);
    }
    else{ 
      $pageContents = str_replace('<?php include_slot(\'w3s_copyright\')?>', $w3sCopyrightButton, $pageContents);  
    }
    
    return $pageContents;
  }
}