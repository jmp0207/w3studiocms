<?php
class ImageEditor {
  var $x;
  var $y;
  var $type;
  var $img;  
  var $font;
  var $error;
  var $size;

  ########################################################
  # CONSTRUCTOR
  ########################################################
  function ImageEditor($filename, $path, $col=NULL) 
  {
    $this->font = false;
    $this->error = false;
    $this->size = 25;
    if(is_numeric($filename) && is_numeric($path))
    ## IF NO IMAGE SPECIFIED CREATE BLANK IMAGE
    {
      $this->x = $filename;
      $this->y = $path;
      $this->type = "jpg";
      $this->img = imagecreatetruecolor($this->x, $this->y);
      if(is_array($col)) 
      ## SET BACKGROUND COLOUR OF IMAGE
      {
        $colour = ImageColorAllocate($this->img, $col[0], $col[1], $col[2]);
        ImageFill($this->img, 0, 0, $colour);
      }
    }
    else
    ## IMAGE SPECIFIED SO LOAD THIS IMAGE
    {
      ## FIRST SEE IF WE CAN FIND IMAGE

      if(file_exists($path . $filename))
      {
        $file = $path . $filename;
      }
      else if (file_exists($path . "/" . $filename))
      {
        $file = $path . "/" . $filename;
      }
      else
      {
        $this->errorImage("File Could Not Be Loaded");
      }
      
      if(!($this->error)) 
      {
        ## LOAD OUR IMAGE WITH CORRECT FUNCTION
        $arrImage = explode('.', $filename);
        $this->type = strtolower(end($arrImage));
        if ($this->type == 'jpg' || $this->type == 'jpeg') 
        {
          $this->img = @imagecreatefromjpeg($file);
        } 
        else if ($this->type == 'png') 
        {
          $this->img = @imagecreatefrompng($file);
        } 
        else if ($this->type == 'gif') 
        {
          $this->img = @imagecreatefromgif($file);
        }
        ## SET OUR IMAGE VARIABLES
        $this->x = imagesx($this->img);
        $this->y = imagesy($this->img);
      }
    }
  }

  ########################################################
  # RESIZE IMAGE GIVEN X AND Y
  ########################################################
  function resize($width, $height=0)
  {
    if(!$this->error) 
    {
      if ($width == 0) $width = floor($this->x/($this->y/$height));
      if ($height == 0) $height = floor($this->y/($this->x/$width));
      $tmpimage = imagecreatetruecolor($width, $height);
      imagecopyresampled($tmpimage, $this->img, 0, 0, 0, 0,
                           $width, $height, $this->x, $this->y);
      imagedestroy($this->img);
      $this->img = $tmpimage;
      $this->y = $height;
      $this->x = $width;
    }
  }
  
  ########################################################
  # RESIZE IMAGE GIVEN X AND Y
  ########################################################
  function rotate($degrees)
  {
    if(!$this->error) 
    {
      if (($degrees != 90 && $degrees != 180 && $degrees != 270)) { exit; }
      if ($degrees == 180){
        $tmpimage = imagerotate($this->img, $degrees, 180);
      }else{
        $max = max($this->x, $this->y);
  
        $square = imagecreatetruecolor($max, $max);
        imagecopy($square, $this->img, 0, 0, 0, 0, $this->x, $this->y);
        $square = imagerotate($square, $degrees, 0);
  
        $tmpimage = imagecreatetruecolor($this->y, $this->x);
        if ($degrees == 90) {
          imagecopy($tmpimage, $square, 0, 0, 0, $max - $this->x, $this->y, $this->x);
        } elseif ($degrees == 270) {
          imagecopy($tmpimage, $square, 0, 0, $max - $this->y, 0, $this->y, $this->x);
        }
        imagedestroy($square);
      }
    }
    imagedestroy($this->img);
    $this->img = $tmpimage;
  }
  
  ########################################################
  # CROPS THE IMAGE, GIVE A START CO-ORDINATE AND
  # LENGTH AND HEIGHT ATTRIBUTES
  ########################################################
  function crop($x, $y, $width, $height) 
  {
    if(!$this->error) 
    {
      $tmpimage = imagecreatetruecolor($width, $height);
      imagecopyresampled($tmpimage, $this->img, 0, 0, $x, $y,
                           $width, $height, $width, $height);
      imagedestroy($this->img);
      $this->img = $tmpimage;
      $this->y = $height;
      $this->x = $width;
    }
  }
  
  ########################################################
  # ADDS TEXT TO AN IMAGE, TAKES THE STRING, A STARTING
  # POINT, PLUS A COLOR DEFINITION AS AN ARRAY IN RGB MODE
  ########################################################
  function addText($str, $x, $y, $col)
  {
    if(!$this->error) 
    {
      if($this->font) {
        $colour = ImageColorAllocate($this->img, $col[0], $col[1], $col[2]);
        if(!imagettftext($this->img, $this->size, 0, $x, $y, $colour, $this->font, $str)) {
          $this->font = false;
          $this->errorImage("Error Drawing Text");
        }
      }
      else {
        $colour = ImageColorAllocate($this->img, $col[0], $col[1], $col[2]);
        Imagestring($this->img, 5, $x, $y, $str, $colour);
      }
    }
  }
  
  function shadowText($str, $x, $y, $col1, $col2, $offset=2) {
   $this->addText($str, $x, $y, $col1);
   $this->addText($str, $x-$offset, $y-$offset, $col2);   
  
  }
  
  ########################################################
  # ADDS A LINE TO AN IMAGE, TAKES A STARTING AND AN END
  # POINT, PLUS A COLOR DEFINITION AS AN ARRAY IN RGB MODE
  ########################################################
  function addLine($x1, $y1, $x2, $y2, $col) 
  {
    if(!$this->error) 
    {
      $colour = ImageColorAllocate($this->img, $col[0], $col[1], $col[2]);
      ImageLine($this->img, $x1, $y1, $x2, $y2, $colour);
    }
  }

  ########################################################
  # RETURN OUR EDITED FILE AS AN IMAGE
  ########################################################
  function outputImage() 
  {
    if ($this->type == 'jpg' || $this->type == 'jpeg') 
    {
      if ($this->type != '')
      header("Content-type: image/jpeg");
      imagejpeg($this->img);
    } 
    else if ($this->type == 'png') 
    {
      header("Content-type: image/png");
      imagepng($this->img);
    } 
    else if ($this->type == 'gif') 
    {
      header("Content-type: image/gif");
      imagegif($this->img);
    }
  }

  ########################################################
  # CREATE OUR EDITED FILE ON THE SERVER
  ########################################################
  function outputFile($filename, $path, $newFileType=0, $quality=80) 
  {
    if ($newFileType != 0){
      switch($newFileType){
        case 1:
          $this->type = 'gif';
          break;
        case 2:
          $this->type = 'jpg';
          break;
        case 3:
          $this->type = 'png';          
          break;          
      }
      $start_extension = strrpos($filename, '.');
      $filename_lenght = strlen($filename);
      if ($filename_lenght - $start_extension == 4 || $filename_lenght - $start_extension == 5) $filename = substr($filename, 0, $start_extension);
      $filename .= '.' . $this->type;
    }
    
    if ($this->type == 'jpg' || $this->type == 'jpeg') 
    {
      @imagejpeg($this->img, ($path . $filename), $quality);
    } 
    else if ($this->type == 'png') 
    {
      @imagepng($this->img, ($path . $filename));
    } 
    else if ($this->type == 'gif') 
    {
      @imagegif($this->img, ($path . $filename));
    }
    
    return $filename;
  }


  ########################################################
  # SET OUTPUT TYPE IN ORDER TO SAVE IN DIFFERENT
  # TYPE THAN WE LOADED
  ########################################################
  function setImageType($type)
  {
    $this->type = $type;
  }
  
  ########################################################
  # ADDS TEXT TO AN IMAGE, TAKES THE STRING, A STARTING
  # POINT, PLUS A COLOR DEFINITION AS AN ARRAY IN RGB MODE
  ########################################################
  function setFont($font) {
    $this->font = $font;
  }

  ########################################################
  # SETS THE FONT SIZE
  ########################################################
  function setSize($size) {
    $this->size = $size;
  }
  
  ########################################################
  # GET VARIABLE FUNCTIONS
  ########################################################
  function getWidth()                {return $this->x;}
  function getHeight()               {return $this->y;} 
  function getImageType()            {return $this->type;}

  ########################################################
  # CREATES AN ERROR IMAGE SO A PROPER OBJECT IS RETURNED
  ########################################################
  function errorImage($str) 
  {
    $this->error = false;
    $this->x = 235;
    $this->y = 50;
    $this->type = "jpg";
    $this->img = imagecreatetruecolor($this->x, $this->y);
    $this->addText("AN ERROR OCCURED:", 10, 5, array(250,70,0));
    $this->addText($str, 10, 30, array(255,255,255));
    $this->error = true;
  }
} 
?>