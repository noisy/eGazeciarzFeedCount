<?php

//bg=99CCFF&amp;fg=444444&amp;anim=0
function isset_or(&$check, $alternate = NULL){return (isset($check)) ? (empty($check) ? $alternate : $check) : $alternate;} 
function getGETPOST($var, $default){return isset_or($_GET[$var],isset_or($_POST[$var], $default));}

function Hex2RGB($color){
    $color = str_replace('#', '', $color);
    if (strlen($color) != 6){ return array(0,0,0); }
    $rgb = array();
    for ($x=0;$x<3;$x++){
        $rgb[$x] = hexdec(substr($color,(2*$x),2));
    }
    return $rgb;
}


function getNumberImage($number, $color)
{
	$im_digits = imagecreatefromgif('digits.gif');
	
	$tmp = $number;
	
	$number_of_digits=0;
	
	if($tmp == 0)
		$number_of_digits=1;
	
	$number_of_digits=0;
	
	while($tmp != 0)
	{
		$tmp = intval($tmp / 10);
		$number_of_digits++;
	}
	
	$w_d = 5; //width of digit
	$h_d = 7; //height of digit
	$s_d = 2; //space between digits
	
	$w = $number_of_digits*$w_d + ($number_of_digits-1)*$s_d;
	$h = 7;

	$im = imagecreatetruecolor($w, $h);

    $bg_color = imagecolorallocatealpha($im, $color["red"], $color["green"], $color["blue"], $color["alpha"]);
	imagefilledrectangle($im, 0, 0, $w, $h, $bg_color);

	for($i=0; $i < $number_of_digits; $i++)
	{
		$digit = $number%10;
		$number = intval($number / 10);
		
		$dest_x = $w - ((($i+1)*$w_d) + $i*$s_d);
		$src_x = $digit*$w_d;
		
		imagecopyresampled($im, $im_digits, $dest_x, 0, $src_x, 0, $w_d, $h_d, $w_d, $h_d);
	}

	$f_w = 36;
	$f_h = 12;
	
	$frame = imagecreatetruecolor($f_w, $f_h);
	
	imagefilledrectangle($frame, 0, 0, $f_w, $f_h, $bg_color);
	imagecopyresampled($frame, $im, $f_w-$w-2, 3, 0, 0, $w, $h, $w, $h);

	return $frame;

}



$bg		= getGETPOST('bg', '99ccff');
$fg 	= getGETPOST('fg', '000000');
$anim 	= getGETPOST('anim', 0);
$nr 	= getGETPOST('nr', 122);


copy('http://feeds.feedburner.com/~fc/ekundelek/ZBkX?bg='. $bg .'&fg='. $fg .'&anim=0', 'src_feed.gif');
copy('src_feed.gif', 'feed.gif');

$color_source = imagecreatefromgif('src_feed.gif');
$image = imagecreatefromgif('feed.gif');
$width = imagesx($image);
$height = imagesy($image);
$pixel = imagecreatetruecolor(1, 1);


imagecopyresampled($image, $color_source, 4, 3, 5, 5, 36, 12, 1, 1);
imagecopyresampled($pixel, $color_source, 0, 0, 5, 5, 1, 1, 1, 1);
$rgb = imagecolorat($pixel, 0, 0);
$inside_frame_color = imagecolorsforindex($pixel, $rgb);

imagecopyresampled($image, $color_source, 42, 3, 42, 3, 43, 13, 1, 1);
$transpatent =  imagecolorallocatealpha ($image , 255 , 255 , 255 , 127 );
imagefilledrectangle ($image, 0, 20, 88, 26, $transpatent);

$number_img = getNumberImage($nr, $inside_frame_color);
imagecopyresampled($image, $number_img, 4, 3, 0, 0, 36, 12, 36, 12);

$botom_text = imagecreatefromgif('przez_egazeciarz.gif');
imagecopyresampled($image, $botom_text, 0, 19, 0, 0, 88, 7, 88, 7);

$text = imagecreatefromgif('czytuje.gif');
imagecopyresampled($image, $text, 41, 2, 0, 0, 45, 14, 45, 14);



header("Content-type: image/png");
imagepng($image);

?>