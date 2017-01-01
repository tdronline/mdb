<?php 
header ("Content-type: image/jpg");
$im = trim($_GET['id']);
if(!file_exists("covers/".$im.".jpg")){$cover = 'covers/default.jpg';}else{$cover = "covers/".$im.".jpg";}
$im = imagecreatefromjpeg($cover);
			$ow = imagesx($im);
			$oh = imagesy($im);
		$maxw = 340;
		$maxh = 480;
		$new_h = $oh;
		$new_w = $ow;
		if($oh > $maxh || $ow > $maxw){
				$new_h = ($oh > $ow) ? $maxh : $maxw* ($oh/$ow);
				$new_w = $new_h*$ow/$oh;
		}
$dst_img = ImageCreateTrueColor($new_w, $new_h);
imagecopyresampled($dst_img,$im, 0 , 0 , 0 , 0, $new_w, $new_h, ImageSX($im), ImageSY($im));
imagejpeg($dst_img);
?>
