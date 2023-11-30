<?php
class Vkimage {
	private $file;
	private $image;
	private $info;
		
	public function __construct($file = false){
		if ($file) {
			$this->initialize($file);
		}
	}

	public function initialize($file){
		if (function_exists('realpath') AND @realpath($file) !== FALSE)
		{
			$full_source_path = str_replace("\\", "/", realpath($file));
		}
		else
		{
			$full_source_path = $full_source_path;
		}
		
		$this->file=$full_source_path;
		$info=getimagesize($file);
		$this->info=array("width" => $info[0], "height" => $info[1], "bits" => $info["bits"], "mime" => $info["mime"]);
    $this->image=$this->create($file);
	}
		
	private function create($image){
		$mime=$this->info["mime"];
		if($mime=="image/gif"){
			return imagecreatefromgif($image);
		}elseif($mime=="image/png"){
			return imagecreatefrompng($image);
		}elseif($mime=="image/jpeg"){
			return imagecreatefromjpeg($image);
		}
	}
	
	public function save($file, $quality=90){
		$info=pathinfo($file);
		$extension=strtolower($info["extension"]);
		if(!empty($this->image)){
			if($extension=="jpeg" || $extension=="jpg"){
				imagejpeg($this->image, $file, $quality);
			}elseif($extension=="png"){
				imagepng($this->image, $file, 0);
			}elseif($extension=="gif"){
				imagegif($this->image, $file);
			}
			imagedestroy($this->image);
		}
	}
	
	function output($extension="jpeg", $quality = 90){
		if(!empty($this->image)){
			if($extension=="jpeg" || $extension=="jpg"){
				imagejpeg($this->image, "", $quality);
			}elseif($extension=="png"){
				imagepng($this->image, "", 0);
			}elseif($extension=="gif"){
				imagegif($this->image);
			}
		}
	}
	
	public function resize($width=0, $height=0){
    if(!$this->info["width"] || !$this->info["height"]){
			return;
		}

		$xpos=0;
		$ypos=0;

		$scale=min($width/$this->info["width"], $height/$this->info["height"]);
		
		if($scale==1){
			return;
		}
		
		$newWidth=(int)($this->info["width"]*$scale);
		$newHeight=(int)($this->info["height"]*$scale);			
		$xpos=(int)(($width-$newWidth)/2);
		$ypos=(int)(($height-$newHeight)/2);
        		        
		$imageOld=$this->image;
		$this->image=imagecreatetruecolor($width, $height);
			
		if(isset($this->info["mime"]) && $this->info["mime"]=="image/png"){		
			imagealphablending($this->image, false);
			imagesavealpha($this->image, true);
			$background=imagecolorallocatealpha($this->image, 255, 255, 255, 127);
			imagecolortransparent($this->image, $background);
		}else{
			$background=imagecolorallocate($this->image, 255, 255, 255);
		}
		
		imagefilledrectangle($this->image, 0, 0, $width, $height, $background);
		imagecopyresampled($this->image, $imageOld, $xpos, $ypos, 0, 0, $newWidth, $newHeight, $this->info["width"], $this->info["height"]);
		imagedestroy($imageOld);
			 
		$this->info["width"]=$width;
		$this->info["height"]=$height;
	}
	
	public function watermark($file, $position = "bottomright"){
		$watermark=$this->create($file);
		
		$watermark_width=imagesx($watermark);
		$watermark_height=imagesy($watermark);
		
		switch($position){
				case "topleft":
						$watermarkPosX=0;
						$watermarkPosY=0;
						break;
				case "topright":
						$watermarkPosX=$this->info["width"]-$watermark_width;
						$watermarkPosY=0;
						break;
				case "bottomleft":
						$watermarkPosX=0;
						$watermarkPosY=$this->info["height"]-$watermark_height;
						break;
				case "bottomright":
						$watermarkPosX=$this->info["width"]-$watermark_width;
						$watermarkPosY=$this->info["height"]-$watermark_height;
						break;
		}
		
		imagecopy($this->image, $watermark, $watermarkPosX, $watermarkPosY, 0, 0, 120, 40);
		imagedestroy($watermark);
	}
	
	public function crop($topX, $topY, $bottomX, $bottomY){
		$imageOld=$this->image;
		$this->image=imagecreatetruecolor($bottomX-$topX, $bottomY-$topY);
		imagecopy($this->image, $imageOld, 0, 0, $topX, $topY, $this->info["width"], $this->info["height"]);
		imagedestroy($imageOld);
		$this->info["width"]=$bottomX-$topX;
		$this->info["height"]=$bottomY-$topY;
	}
	
	public function rotate($degree, $color="FFFFFF"){
		$rgb=$this->html2rgb($color);
		$this->image=imagerotate($this->image, $degree, imagecolorallocate($this->image, $rgb[0], $rgb[1], $rgb[2]));
		$this->info["width"]=imagesx($this->image);
		$this->info["height"]=imagesy($this->image);
	}
	
	private function filter($filter){
  	imagefilter($this->image, $filter);
	}
	
	private function text($text, $x=0, $y=0, $size=5, $color="000000"){
		$rgb=$this->html2rgb($color);
		imagestring($this->image, $size, $x, $y, $text, imagecolorallocate($this->image, $rgb[0], $rgb[1], $rgb[2]));
	}
	
	private function merge($file, $x=0, $y=0, $opacity=100){
		$merge=$this->create($file);
		$mergeWidth=imagesx($image);
		$mergeHeight=imagesy($image);
		imagecopymerge($this->image, $merge, $x, $y, 0, 0, $mergeWidth, $mergeHeight, $opacity);
	}
			
	private function html2rgb($color){
		if($color[0]=="#"){
			$color=substr($color, 1);
		}
		
		if(strlen($color)==6){
			list($r, $g, $b) = array($color[0].$color[1], $color[2].$color[3], $color[4].$color[5]);   
		}elseif(strlen($color)==3){
			list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);    
		}else{
			return false;
		}
		
		$r=hexdec($r); 
		$g=hexdec($g); 
		$b=hexdec($b);    
		
		return array($r, $g, $b);
	}	

	public function compressImage($source_image, $compress_image) {
	  $image_info = getimagesize($source_image);  
	  if ($image_info['mime'] == 'image/jpeg') { 
	    $source_image = imagecreatefromjpeg($source_image);
	    imagejpeg($source_image, $compress_image, 75);
	  } elseif ($image_info['mime'] == 'image/gif') {
	    $source_image = imagecreatefromgif($source_image);
	    imagegif($source_image, $compress_image, 75);
	  } elseif ($image_info['mime'] == 'image/png') {
	    $source_image = imagecreatefrompng($source_image);
	    imagepng($source_image, $compress_image, 7);
	  }     
	  return $compress_image;
	}

	public function correctImageOrientation($filename) {
	  if (function_exists('exif_read_data')) {
	    $exif = @exif_read_data($filename);
	    if($exif && isset($exif['Orientation'])) {
	      $orientation = $exif['Orientation'];
	      if($orientation != 1){
	        $img = imagecreatefromjpeg($filename);
	        $deg = 0;
	        switch ($orientation) {
	          case 3:
	            $deg = 180;
	            break;
	          case 6:
	            $deg = 270;
	            break;
	          case 8:
	            $deg = 90;
	            break;
	        }
	        if ($deg) {
	          $img = imagerotate($img, $deg, 0);        
	        }
	        // then rewrite the rotated image back to the disk as $filename 
	        imagejpeg($img, $filename, 95);
	      } // if there is some rotation necessary
	    } // if have the exif orientation info
	  } // if function exists      
	}
}