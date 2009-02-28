<?php
/**
 * w3sMenuEditor extends the w3sContentsEditor to build the editor to manage 
 * a navigation menu content.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sMenuEditor
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */
 
class w3sMenuEditor extends w3sContentsEditor
{
	protected 
		$jsMenu = '',
		$editorSkeleton = '
      <table>
		    <tr>
		      <td valign="top">
		        <div id="w3s_mb_menu">%s</div>
					  <div id="w3s_mb_commands">%s&nbsp;%s</div>
		      </td>
		      <td valign="top">
		        <div id="w3s_mb_properties">%s</div>
		        <div id="w3s_menu_options_header">%s</div>
		        <div id="w3s_menu_options">%s</div>
		        <div id="w3s_feedback">&nbsp;</div>
            <div><textarea id="pipo"></textarea><a href="#" onclick="$(\'pipo\').value = objMenuBuilder.setLinks();">clic</a></div>
		      </td>
		    </tr>
		    <tr>
		      <td valign="top" colspan="2">
		        <div style="text-align:center;margin-top:10px;">
		          <a href="#" class="link_button" id="w3s_edit_content_btn" onclick="currentEditor.edit(); return false;">%s</a>
		        </div>
		      </td>
		    </tr>
		  </table>%s',
		
		$menuListSkeleton = '
			<div id="w3s_menu_header">%s</div>
			<div id="w3s_menu_items">
				<ul id="w3s_mb_list">%s</ul>
			</div>',
			
		$menuListItem = "
			<li id=\"item_%1\$s\" value=\"%1\$s\" class=\"menu_items\">
		    <a href=\"#\" onclick=\"objMenuBuilder.loadMenuProperties(%1\$s);return false;\">
		      <span id=\"item_text_%1\$s\">%2\$s</span>
		    </a>
		  </li>",
		
	  $optionsSkeleton = '
	  	<form id="w3s_options_form"> 
				<table cellspacing="0" cellpadding="0">
			    <tr>
			      <td class="border_bottom_dotted">%s</td>
			      <td class="border_bottom_dotted">%s</td>
			    </tr>
			    <tr>
			      <td class="border_bottom_dotted">%s</td>
			      <td class="border_bottom_dotted">%s</td>
			    </tr>
			    <tr>
			      <td class="border_bottom_dotted">%s</td>
			      <td class="border_bottom_dotted">%s</td>
			    </tr>
			    <tr>
			      <td colspan="2">%s</td>
			    </tr>
			  </table>
			</form>';
  
  public function getJsMenu()
  {
  	return $this->jsMenu;
  }
    	
	public function drawEditor()
	{			
		return sprintf($this->editorSkeleton, $this->drawMenuItems(),
																					link_to_function(image_tag(sfConfig::get('app_w3s_web_skin_images_dir') . '/menu_builder/add_link.png', 'id="w3s_add_link"'), 'objMenuBuilder.addLink()'),
																					link_to_function(image_tag(sfConfig::get('app_w3s_web_skin_images_dir') . '/menu_builder/del_link.png', ''), 'objMenuBuilder.deleteLink(\'' . __('Do you want to delete the selected link?') . '\')'),
																					$this->drawProperties(),
																					__('Save options'),
																					$this->drawOptions(),
																					__('Save Menu'),
																					javascript_tag($this->jsMenu));		     
	}
	
	public function drawMenuItems()
  {
  	$drawMenu = ''; 
		$this->jsMenu = 'objMenuBuilder.objMenu.clear();';
  	$menuItems = DbFinder::from('W3sMenuElement')->
								           where('ContentId', $this->content->getId())->  
								           orderBy('Position')->
								           find(); 		
  	foreach($menuItems as $menu)
  	{
  		$drawMenu .= $this->drawMenuItem($menu); 
  		$this->jsMenu	.= $this->setJsMenuItem($menu);
  	}
  	$this->jsMenu	.= 'Sortable.create("w3s_mb_list", {});';
  	
    return sprintf($this->menuListSkeleton, __('Menu links'), $drawMenu);
  }
  
  protected function drawMenuItem($menu)
  {
    return sprintf($this->menuListItem, $menu->getId(), $menu->getLink());
  }
  
  protected function setJsMenuItem($menu)
  {
  	$JsMenuItem = 'objMenuBuilder.objMenu[' . $menu->getId() . ']={';
    $JsMenuItem .= 'w3s_ppt_link: \'' . $menu->getLink() . '\',';
    $JsMenuItem .= 'w3s_ppt_image: \'' . $menu->getImage() . '\',';
    $JsMenuItem .= 'w3s_ppt_rollover_image: \'' . $menu->getRolloverImage() . '\',';
    $JsMenuItem .= 'w3s_ppt_int_link: \'' . $menu->getPageId() . '\',';
    $JsMenuItem .= 'w3s_ppt_ext_link: \'' . $menu->getExternalLink() . '\'};';
    
    return $JsMenuItem;
  }
  
	protected function drawProperties()
  {
    $params = 
      array(                                                                                                 
        array('name' => 'w3s_ppt_link', 'label' => 'Link', 'type' => 'input', 'options' => array('onchange' => 'objMenuBuilder.saveMenuLinkImage(1)')),
        array('name' => 'w3s_ppt_button1', 'button_for' => 'ppt_image', 'type' => 'submit', 'options' => array('value' => '...', 'class' => 'combined_button', 'onclick' => 'objMenuBuilder.showImages(\'w3s_ppt_image\'); return false;')),
        array('name' => 'w3s_ppt_image', 'label' => 'Image', 'type' => 'input', 'options' => array('class' => 'combined_input', 'onblur' => 'objMenuBuilder.saveMenuLinkImage()')),
        array('name' => 'w3s_ppt_button2', 'button_for' => 'ppt_rollover_image', 'type' => 'submit', 'options' => array('value' => '...', 'class' => 'combined_button', 'onclick' => 'objMenuBuilder.showImages(\'w3s_ppt_rollover_image\');; return false;')),
        array('name' => 'w3s_ppt_rollover_image', 'label' => 'Rollover', 'type' => 'input', 'options' => array('class' => 'combined_input', 'onblur' => 'objMenuBuilder.saveMenuLinkImage()')),
        array('name' => 'w3s_ppt_int_link', 'label' => 'Int. Link', 'choices' => $this->getSitePages(), 'options' => array('onchange' => '$(\'w3s_ppt_ext_link\').value=\'\';objMenuBuilder.saveMenuLinkImage(1);')),
        array('name' => 'w3s_ppt_ext_link', 'label' => 'Ext. link', 'type' => 'input', 'options' => array('onblur' => '$(\'w3s_ppt_int_link\').selectedIndex=0;objMenuBuilder.saveMenuLinkImage();'))
      );
      
    $properties = new w3sProperties($params);
    return $properties->render();
  }
  
  protected function drawOptions()
  {
    $slot = new w3sSlotTemplateEditor($this->content->getLanguageId(), $this->content->getPageId());
    return sprintf($this->optionsSkeleton, __('Set as active class'),
    																			 select_tag('w3s_assigned_class', options_for_select($slot->findStylesheetClasses($this->content->getW3sSlot()->getSlotName()), '')),
    																			 __('Set the class on each page'),
    																			 checkbox_tag('w3s_class_page_assign', false),
    																			 __('Assign active class to'),
    																			 select_tag('w3s_assigned_to', options_for_select(array('li' => __('List [LI]'), 'a' => __('Link [A]')))),
    																			 __('Use these options to highlight the active link that corresponds to the active page, the user is navigating into.'));    
  }
  
  public function saveLinks($params)
  {
  	$con = Propel::getConnection();
    $bRollBack = false;
    $con = w3sPropelWorkaround::beginTransaction($con); 

    $position = 1; 
    foreach($params as $key => $menuValues)
    {
      if (is_array($menuValues))
      {
      	$oMenu = DbFinder::from('W3sMenuElement')->findPK($key);
      	if ($oMenu != null)
      	{
					foreach($menuValues as $menuValue)
					{
	          $values = explode("=", $menuValue); 
	          switch ($values[0]){
	            case 'w3s_ppt_link': 
	              $oMenu->setLink($values[1]);
	              break;
	            case 'w3s_ppt_image':
	              $oMenu->setImage($values[1]);
	              break;
	            case 'w3s_ppt_rollover_image':
	              $oMenu->setRolloverImage($values[1]);
	              break;
	            case 'w3s_ppt_int_link':
	              $oMenu->setPageId($values[1]);
	              break;
	            case 'w3s_ppt_ext_link':
	              $oMenu->setExternalLink($values[1]);
	              break;
	          }
	        }
      	
	        $oMenu->setPosition($position);
	        $result = $oMenu->save();
	        if ($oMenu->isModified() && $result == 0){ 
	          $bRollBack = true;
	          break;
	        }
	        $position++;
      	}
      }
    }
    
    if (!$bRollBack){ // Everything was fine so W3StudioCMS commits to database
      $con->commit();
      $result = 1;
    }
    else{             // Something was wrong so W3StudioCMS aborts the operation and restores to previous status
      w3sPropelWorkaround::rollBack($con);
      $result = 0;
    }
  }
  
  public function saveMenuLink($linkText)
  {
  	$menu = new W3sMenuElement();
    $menu->setContentId($this->content->getId());
    $menu->setLink($linkText);
    $menu->setPosition(W3sMenuElementPeer::getMaxPosition($this->content->getId()) + 1);
    return $menu->save();
  }
}
