<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ImgGenerator
{
	
	function __construct()
	{
	}

	public function Combine4by2_Basic($img1, $img2, $img3, $img4, $img5, $img6, $img7, $img8, $imgOutput = 'test.png')
	{
		$imgOutput = __DIR__.'/../../dropImageUploadsProcessed/'.$imgOutput;

		echo $imgOutput;

		$homeUrl = AllSettings()['HomepageURL'];
		$img1 = $homeUrl . $img1;
		$img2 = $homeUrl . $img2;
		$img3 = $homeUrl . $img3;
		$img4 = $homeUrl . $img4;
		$img5 = $homeUrl . $img5;
		$img6 = $homeUrl . $img6;
		$img7 = $homeUrl . $img7;
		$img8 = $homeUrl . $img8;

		list($wdth_1, $hght_1) = getimagesize($img1);
		list($wdth_2, $hght_2) = getimagesize($img2);
		list($wdth_3, $hght_3) = getimagesize($img3);
		list($wdth_4, $hght_4) = getimagesize($img4);
		list($wdth_5, $hght_5) = getimagesize($img5);
		list($wdth_6, $hght_6) = getimagesize($img6);
		list($wdth_7, $hght_7) = getimagesize($img7);
		list($wdth_8, $hght_8) = getimagesize($img8);

		echo "<br>wdth: " . $wdth_1 . "hght: " . $hght_1;
		echo "<br>wdth: " . $wdth_2 . "hght: " . $hght_2; 
		echo "<br>wdth: " . $wdth_3 . "hght: " . $hght_3; 
		echo "<br>wdth: " . $wdth_4 . "hght: " . $hght_4; 
		echo "<br>wdth: " . $wdth_5 . "hght: " . $hght_5; 
		echo "<br>wdth: " . $wdth_6 . "hght: " . $hght_6; 
		echo "<br>wdth: " . $wdth_7 . "hght: " . $hght_7; 
		echo "<br>wdth: " . $wdth_8 . "hght: " . $hght_8 . "<br>";

		$wdths = array(
			$img1=>$wdth_1,
			$img2=>$wdth_2, 
			$img3=>$wdth_3, 
			$img4=>$wdth_4, 
			$img5=>$wdth_5, 
			$img6=>$wdth_6, 
			$img7=>$wdth_7, 
			$img8=>$wdth_8);

		asort($wdths);

		$wdthsKeys = array_keys($wdths);
		$wdthsValues = array_values($wdths);

		list($sizeX, $sizeY) = $this->GetNewImageDimensions(
			$wdthsKeys[7],
			$wdthsKeys[5],
			$wdthsKeys[3],
			$wdthsKeys[1],
			$wdthsKeys[0],
			$wdthsKeys[7],
			$wdthsKeys[4],
			$wdthsKeys[6],
		);
		echo "X: " . $sizeX . " | Y: " . $sizeY;

		// Create empty image with size prepared
	 	$image = imagecreatetruecolor($sizeX, $sizeY);

		$image1 = imagecreatefrompng($wdthsKeys[7]);
		$image2 = imagecreatefrompng($wdthsKeys[5]);
		$image3 = imagecreatefrompng($wdthsKeys[3]);
		$image4 = imagecreatefrompng($wdthsKeys[1]);
		$image5 = imagecreatefrompng($wdthsKeys[0]);
		$image6 = imagecreatefrompng($wdthsKeys[2]);
		$image7 = imagecreatefrompng($wdthsKeys[4]);
		$image8 = imagecreatefrompng($wdthsKeys[6]);

		list($width_1, $height_1) = getimagesize($wdthsKeys[7]);
		list($width_2, $height_2) = getimagesize($wdthsKeys[5]);
		list($width_3, $height_3) = getimagesize($wdthsKeys[3]);
		list($width_4, $height_4) = getimagesize($wdthsKeys[1]);
		list($width_5, $height_5) = getimagesize($wdthsKeys[0]);
		list($width_6, $height_6) = getimagesize($wdthsKeys[2]);
		list($width_7, $height_7) = getimagesize($wdthsKeys[4]);
		list($width_8, $height_8) = getimagesize($wdthsKeys[6]);

		$heights = array($height_1, $height_2, $height_3, $height_4, $height_5, $height_6, $height_7, $height_8);

		$wdthsLine1 = $wdthsValues[7] + $wdthsValues[5] + $wdthsValues[3] + $wdthsValues[1];
		$wdthsLine2 = $wdthsValues[0] + $wdthsValues[2] + $wdthsValues[4] + $wdthsValues[6];

		$wdthsLineDiff = abs($wdthsLine1 - $wdthsLine2);

		// Copy images into final one
		$finalStartY = $heights[sizeof($heights)-1];
		imagecopy($image, $image1, 0, 0, 0, 0, $width_1, $height_1);
		imagecopy($image, $image2, $width_1, 0, 0, 0, $width_2, $height_2);
		imagecopy($image, $image3, $width_1 + $width_2, 0, 0, 0, $width_3, $height_3);
		imagecopy($image, $image4, $width_1 + $width_2 + $width_3, 0, 0, 0, $width_4, $height_4);

		imagecopy($image, $image5, $wdthsLineDiff/5, $finalStartY, 0, 0, $width_5, $height_5);
		imagecopy($image, $image6, 2*$wdthsLineDiff/5 +$width_5, $finalStartY, 0, 0, $width_6, $height_6);
		imagecopy($image, $image7, 3*$wdthsLineDiff/5 +$width_5 + $width_6, $finalStartY, 0, 0, $width_7, $height_7);
		imagecopy($image, $image8, 4*$wdthsLineDiff/5 +$width_5 + $width_6 + $width_7, $finalStartY, 0, 0, $width_8, $height_8);

		// Save the resulting image to disk (as PNG)

		imagepng($image, $imgOutput);
		echo "<br><br>".$imgOutput."<br><br>";

		// Clean up

		imagedestroy($image);
		imagedestroy($image1);
		imagedestroy($image2);
		imagedestroy($image3);
		imagedestroy($image4);
		imagedestroy($image5);
		imagedestroy($image6);
		imagedestroy($image7);
		imagedestroy($image8);
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