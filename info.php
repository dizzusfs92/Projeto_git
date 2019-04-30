<?php
print_r("Não Entrei Aqui...");

function resize_image($file, $w, $h, $crop=FALSE) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
	print_r("Também Não Entrei Aqui...1");
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
}

//$img = resize_image('logo.jpg' 200, 200);


// Create a test source image for this example
$im = imagecreatetruecolor(300, 50);
$text_color = imagecolorallocate($im, 233, 14, 91);
imagestring($im, 1, 5, 5,  'A Simple Text String', $text_color);

// start buffering
ob_start();
// output jpeg (or any other chosen) format & quality
imagejpeg($im, NULL, 85);
// capture output to string
$contents = ob_get_contents();
// end capture
ob_end_clean();

// be tidy; free up memory
imagedestroy($im);

// lastly (for the example) we are writing the string to a file
$fh = fopen("logo3.png", "a+" );
    fwrite( $fh, $contents );
fclose( $fh );
?> 


<?php
phpinfo();
?>
