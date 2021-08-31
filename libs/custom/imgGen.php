<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ImgGenerator
{
	
	function __construct()
	{
	}

	public function Combine4by2_Basic($images, $imgOutput = 'test.png')
	{
		$imgOutput = __DIR__.'/../../dropImageUploadsProcessed/'.$imgOutput;

		echo $imgOutput;

		$homeUrl = AllSettings()['HomepageURL'];

		for ($i=0; $i < 8; $i++) { 
			$images[$i] = $homeUrl . $images[$i];
		}
		$sizeX = 1280;
		$sizeY = 1280;

		echo "X: " . $sizeX . " | Y: " . $sizeY;

		// Create empty image with size prepared
	 	$image = imagecreatetruecolor($sizeX, $sizeY);

	 	$pngImages = array_fill(0, 8, "");

		for ($i=0; $i < 8; $i++) { 
			echo $images[$i];
			$pngImages[$i] = imagecreatefrompng($images[$i]);
		}

		// Copy images into final one
		for ($y=0; $y < 2; $y++) { 
			for ($x=0; $x < 4; $x++) { 
				imagecopy(
					$image, 				//dest
					$pngImages[$y*4+$x],	//src
					$x*320,					//start_x
					$y*640, 				//start_y
					0,
					0, 
					320, 					//width  (x)
					640						//height (y)
				);
			}
		}

		// Save the resulting image to disk (as PNG)

		imagepng($image, $imgOutput);
		echo "<br><br>".$imgOutput."<br><br>";

		// Clean up
		for ($i=0; $i < 8; $i++) { 
			imagedestroy($pngImages[$i]);
		}
	}

	function GetValueByIndex($index, $array) {

	    $i=0;

	    foreach ($array as $key => $value) {
	    	if($i==$index) {
	            return $value;
	        }
	        $i++;
	    }
	    // may be $index exceedes size of $array. In this case NULL is returned.
	    return NULL;
	}

	function GetNewImageDimensions($img1, $img2, $img3, $img4, $img5, $img6, $img7, $img8)
	{
		list($width_1, $height_1) = getimagesize($img1);
		list($width_2, $height_2) = getimagesize($img2);
		list($width_3, $height_3) = getimagesize($img3);
		list($width_4, $height_4) = getimagesize($img4);
		list($width_5, $height_5) = getimagesize($img5);
		list($width_6, $height_6) = getimagesize($img6);
		list($width_7, $height_7) = getimagesize($img7);
		list($width_8, $height_8) = getimagesize($img8);

		$sizeX = 0;
		$sizeXprobability1 = $width_1+$width_2+$width_3+$width_4;
		$sizeXprobability2 = $width_5+$width_6+$width_7+$width_8;
		if($sizeXprobability1 > $sizeXprobability2)
			$sizeX = $sizeXprobability1;
		else
			$sizeX = $sizeXprobability2;

		$heights = array($height_1, $height_2, $height_3, $height_4, $height_5, $height_6, $height_7, $height_8);
		$sizeY = $this->SumTwoBiggestValues($heights);

		return array($sizeX, $sizeY);
	}

	function SumTwoBiggestValues($sizesArray)
	{
		sort($sizesArray);
		return $sizesArray[sizeof($sizesArray)-1] + $sizesArray[sizeof($sizesArray)-2];
	}

	function SumFourBiggestValues($sizesArray)
	{
		sort($sizesArray);
		return 
			$sizesArray[sizeof($sizesArray)-1] + 
			$sizesArray[sizeof($sizesArray)-2] + 
			$sizesArray[sizeof($sizesArray)-3] + 
			$sizesArray[sizeof($sizesArray)-4];
	}

	function CombineAboveEachOther($filename_x, $filename_y, $filename_result) {

	 // Get dimensions for specified images

	 list($width_x, $height_x) = getimagesize($filename_x);
	 list($width_y, $height_y) = getimagesize($filename_y);

	 // Create new image with desired dimensions

	 $image = imagecreatetruecolor($width_x + $width_y, $height_x);

	 // Load images and then copy to destination image

	 $image_x = imagecreatefromjpeg($filename_x);
	 $image_y = imagecreatefromgif($filename_y);

	 imagecopy($image, $image_x, 0, 0, 0, 0, $width_x, $height_x);
	 imagecopy($image, $image_y, $width_x, 0, 0, 0, $width_y, $height_y);

	 // Save the resulting image to disk (as JPEG)

	 imagejpeg($image, $filename_result);

	 // Clean up

	 imagedestroy($image);
	 imagedestroy($image_x);
	 imagedestroy($image_y);

	}
}

?>