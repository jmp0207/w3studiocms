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
 * w3sButton is the base class to draw a button.
 *
 * @package    sfW3studioCMSPlugin
 * @subpackage w3sButton
 * @author     Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 */

  require_once sfConfig::get('sf_symfony_lib_dir') . '/helper/TagHelper.php';
  
  class w3sButton
  {
    protected 
      $image = '',
      $imageDisabled = '',
      $enabled = true,
      $caption = '',
      $action = '',
      $imageParams = array(),
      $captionParams = array(),
      $actionParams = array(),
      $actionDisabledParams = array(),
      $imageTextRelation = 2,
      $requiredCredentials = null,
      $linkedTo = '#',
      $currentUser,
      $imageText = '<a href="%s" %s %s>%s%s</a>',
      $imageBeforeText = "<td><a href=\"%1\$s\" %2\$s %3\$s>%4\$s</a></td><td><a href=\"#\" %2\$s %3\$s>%5\$s</a></td>",
      $imageAfterText = "<td><a href=\"%1\$s\" %2\$s return false;\" %3\$s>%5\$s</a></td><td><a href=\"#\" %2\$s %3\$s>%4\$s</a></td>",
      $imageAboveText = '<td><a href="%s" %s %s>%s<br />%s</a></td>',
      $imageUnderText = "<td><a href=\"%s\" %s %s>%5\$s<br />%4\$s</a></td>";

    /**
     * Constructor.
     * 
     * @param object  A reference to current user
     *
     */   
    public function __construct($user = null){
      if($user != null) $this->setCurrentUser($user);           
    }
    
    /**
     * Sets / Gets the value of the image property.
     * 
     * @param string
     *
     */    
    public function setImage($value)
    {
      $this->checkImage($value);
      $this->image = $value;
    }

    public function getImage()
    {
      return $this->image;
    }

    /**
     * Sets / Gets the value of the imageDisabled property.
     * 
     * @param string
     *
     */  
    public function setImageDisabled($value)
    {
      $this->checkImage($value);
      $this->imageDisabled = $value;
    }

    public function getImageDisabled(){
      return $this->imageDisabled;
    }

    /**
     * Sets / Gets the value of the enabled property.
     * 
     * @param boolean
     *
     */  
    public function setEnabled($value)
    {
      if (!is_bool($value))
      {
        throw new RuntimeException(sprintf('Enabled param must be true or false. The value you entered is %s.', $value));
      }

      $this->enabled = $value;
    }

    public function getEnabled()
    {
      return $this->enabled;
    }

    /**
     * Sets / Gets the value of the caption property.
     * 
     * @param string
     *
     */  
    public function setCaption($value)
    {
      $caption = (string)$value;
      $this->caption = $value;
    }

    public function getCaption()
    {
      return $this->caption;
    }

    /**
     * Sets / Gets the value of the action property.
     * 
     * @param string
     *
     */  
    public function setAction($value)
    {
      if (!is_string($value))
      {
        throw new RuntimeException(sprintf('Action param must be a string. The value you entered is %s.', $value));
      }
      $this->action = $value;
    }

    public function getAction()
    {
      return $this->action;
    }

    /**
     * Sets / Gets the value of the imageParams property.
     * 
     * @param array
     *
     */  
    public function setImageParams($value)
    {
      if (!is_array($value))
      {
        throw new RuntimeException(sprintf('ImageParams must be an array. The value you entered is %s.', $value));
      }
      $this->imageParams = $value;
    }

    public function getImageParams()
    {
      return $this->imageParams;
    }

    /**
     * Sets / Gets the value of the captionParams property.
     * 
     * @param array
     *
     */  
    public function setCaptionParams($value)
    {
      if (!is_array($value))
      {
        throw new RuntimeException(sprintf('CaptionParams %s param must be an array. The value you entered is %s.', $value));
      }
      $this->captionParams = $value;
    }

    public function getCaptionParams()
    {
      return $this->captionParams;
    }

    /**
     * Sets / Gets the value of the actionParams property.
     * 
     * @param array
     *
     */  
    public function setActionParams($value)
    {
      if (!is_array($value))
      {
        throw new RuntimeException(sprintf('ActionParams param must be an array. The value you entered is %s.', $value));
      }
      $this->actionParams = $value;
    }

    public function getActionParams()
    {
      return $this->actionParams;
    }
    
    /**
     * Sets / Gets the value of the actionParams property.
     * 
     * @param array
     *
     */  
    public function setActionDisabledParams($value)
    {
      if (!is_array($value))
      {
        throw new RuntimeException(sprintf('ActionParams param must be an array. The value you entered is %s.', $value));
      }
      $this->actionDisabledParams = $value;
    }

    public function getActionDisabledParams()
    {
      return $this->actionDisabledParams;
    }

    /**
     * Sets / Gets the value of the imageTextRelation property.
     * 
     * @param int
     *
     */  
    public function setImageTextRelation($value)
    {
      if (!is_int($value))
      {
        throw new RuntimeException(sprintf('ImageTextRelation param must be an array. The value you entered is %s.', $value));
      }

      if ($value < 0 && $value > 4)
      {
        throw new RuntimeException(sprintf('ImageTextRelation param must be an integer between 0 and 4. The value you entered is %s.', $value));
      }
      $this->imageTextRelation = $value;
    }

    public function getImageTextRelation()
    {
      return $this->imageTextRelation;
    }

    /**
     * Sets / Gets the value of the requiredCredentials property.
     * 
     * @param array
     *
     */      
    public function setRequiredCredentials($value)
    {
      if (!is_array($value))
      {
        throw new RuntimeException(sprintf('RequiredCredentials param must be an array. The value you entered is %s.', $value));
      }

      $this->requiredCredentials = $value;
    }

    public function getRequiredCredentials()
    {
      return $this->requiredCredentials;
    }
 
    /**
     * Sets / Gets the value of the linkedTo property.
     * 
     * @param string
     *
     */     
    public function setLinkedTo($value)
    {
      if (!is_string($value))
      {
        throw new RuntimeException(sprintf('LinkedTo param must be a string. The value you entered is %s.', $value));
      }

      $this->linkedTo = $value;
    }

    public function getLinkedTo()
    {
      return $this->linkedTo;
    }

    /**
     * Sets / Gets the value of the currentUser property.
     * 
     * @param object
     *
     */  
    public function setCurrentUser($value)
    {
      if (!is_object($value))
      {
        throw new RuntimeException(sprintf('CurrentUser param must be an object. The value you entered is %s.', $value));
      }

      $this->currentUser = $value;
    }

    public function getCurrentUser()
    {
      return $this->currentUser;
    }

    /**
     * Sets / Gets the value of the imageText property.
     * 
     * @param string
     *
     */  
    public function setImageText($value)
    {
      if (!is_string($value))
      {
        throw new RuntimeException(sprintf('ImageText param must be a string. The value you entered is %s.', $value));
      }
      
      $this->imageText = $value;
    }

    public function getImageText()
    {
      return $this->imageText;
    }
    
    /**
     * Sets / Gets the value of the imageBeforeText property.
     * 
     * @param string
     *
     */      
    public function setImageBeforeText($value)
    {
      if (!is_string($value))
      {
        throw new RuntimeException(sprintf('ImageBeforeText param must be a string. The value you entered is %s.', $value));
      }
      
      $this->imageBeforeText = $value;
    }

    public function getImageBeforeText()
    {
      return $this->imageBeforeText;
    }
    
    /**
     * Sets / Gets the value of the imageAfterText property.
     * 
     * @param string
     *
     */      
    public function setImageAfterText($value)
    {
      if (!is_string($value))
      {
        throw new RuntimeException(sprintf('ImageAfterText param must be a string. The value you entered is %s.', $value));
      }
      
      $this->imageAfterText = $value;
    }

    public function getImageAfterText()
    {
      return $this->imageAfterText;
    }
    
     /**
     * Sets / Gets the value of the imageAboveText property.
     * 
     * @param string
     *
     */     
    public function setImageAboveText($value)
    {
      if (!is_string($value))
      {
        throw new RuntimeException(sprintf('ImageAboveText param must be a string. The value you entered is %s.', $value));
      }
      
      $this->imageAboveText = $value;
    }

    public function getImageAboveText()
    {
      return $this->imageAboveText;
    }
    
    /**
     * Sets / Gets the value of the imageUnderText property.
     * 
     * @param string
     *
     */      
    public function setImageUnderText($value)
    {
      if (!is_string($value))
      {
        throw new RuntimeException(sprintf('ImageUnderText param must be a string. The value you entered is %s.', $value));
      }
      
      $this->imageUnderText = $value;
    }

    public function getImageUnderText()
    {
      return $this->imageUnderText;
    }

    /**
     * Sets the button's attributes from an array. No attributes are required.
     *
     * Example $button = array("image" => [value], "caption" => [value], ...)
     * 
     * @param array
     *
     */
    public function fromArray($array)
    {        
      if (isset($array["image"])) $this->setImage($array["image"]);
      if (isset($array["imageDisabled"])) $this->setImageDisabled($array["imageDisabled"]);
      if (isset($array["enabled"])) $this->setEnabled($array["enabled"]);
      if (isset($array["caption"])) $this->setCaption(sfContext::getInstance()->getI18N()->__($array["caption"]));
      if (isset($array["action"])) $this->setAction($array["action"]);
      if (isset($array["imageParams"])) $this->setImageParams($array["imageParams"]);
      if (isset($array["captionParams"])) $this->setCaptionParams($array["captionParams"]);
      if (isset($array["actionParams"])) $this->setActionParams($array["actionParams"]);
      if (isset($array["actionDisabledParams"])) $this->setActionDisabledParams($array["actionDisabledParams"]);
      if (isset($array["requiredCredentials"])) $this->setRequiredCredentials($array["requiredCredentials"]);
      if (isset($array["imageTextRelation"])) $this->setImageTextRelation($array["imageTextRelation"]);      
      if (isset($array["imageBeforeText"])) $this->setImageBeforeText($array["imageBeforeText"]);
      if (isset($array["imageAfterText"])) $this->setImageAfterText($array["imageAfterText"]);
      if (isset($array["imageAboveText"])) $this->setImageAboveText($array["imageAboveText"]);
      if (isset($array["imageUnderText"])) $this->setImageUnderText($array["imageUnderText"]);
      if (isset($array["imageText"])) $this->setImageText($array["imageText"]);      
      if (isset($array["linkedTo"])) $this->setLinkedTo($array["linkedTo"]);
    }
    
    /**
     * Sets the button's attributes from a yml file.
     * See the editorButtonClose.yml for a full example
     * 
     * @param array
     *
     */
    public function fromYml($fileName)
    {              
      $this->fromArray(w3sCommonFunctions::loadScript($fileName));   
    }
    
    /**
     * Renders the button
     * 
     * @return string
     *
     */
    public function renderButton()
    {
      // Disable the button if a reference to current user has been passed and
      // the user has not the required credentials to use that button
      $hasCredentials = true;
      if (isset($this->currentUser) && 
          isset($this->requiredCredentials) && !$this->currentUser->hasCredential($this->requiredCredentials))
      {
        $hasCredentials = false;
        $this->enabled = false;  
      }
      
      // Sets the image
      $this->image = ($this->enabled) ? $this->image : (($this->imageDisabled != '') ? $this->imageDisabled : $this->image);
      if ($this->image != '')
      {
        $image = sprintf('<img src="%s" %s />', $this->image, _tag_options($this->imageParams));
      }
      else
      {
        // No image specified, configuring a text button
        $image = '';
        $this->imageTextRelation = 4;
      }
        
      // action
      if ($this->action != '')
      {
        if (!$this->enabled)
        {
        	$this->action = 'alert(\'You don\\\'t have the require credentials for this function\')';
        	$this->actionParams = $this->actionDisabledParams;
        }
        $function = (substr($this->action, 1, 2) != 'on') ? sprintf('onclick="%s return false;"', w3sCommonFunctions::checkLastDirSeparator($this->action, ';')) : $this->action;
      }
      else
      {
      	$function = '';  
      }

      return sprintf($this->buttonSkeleton(), $this->linkedTo, $function, _tag_options($this->actionParams), $image, $this->caption);
    }
    
    /**
     * Checks that the file name passed as reference is a valid string and a valid filename.
     * 
     * @param string
     *
     */   
    protected function checkImage($image)
    {
      if (!is_string($image))
      {
        throw new RuntimeException(sprintf('%s is not a valid image.', $image));
      }

      if (!is_file(sfConfig::get('sf_web_dir') . $image))
      {
        throw new RuntimeException(sprintf('%s is not a valid image file.', $image));
      }
    }

    /**
     * Returns the button skeleton, according to the imageTextRelation param.
     * 
     * @param string
     *
     */  
    protected function buttonSkeleton(){
      switch ($this->imageTextRelation)
      {
        case 0: return $this->imageBeforeText;
        case 1: return $this->imageAfterText;
        case 2: return $this->imageAboveText;
        case 3: return $this->imageUnderText;
        case 4: return $this->imageText;
      }  
    }
  }