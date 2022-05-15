<?php
// thumbimage.class.php
class ThumbImage
{
    private $source;

    public function __construct($sourceImagePath)
    {
        $this->source = $sourceImagePath;
    }

    // only accepts jpg, jpeg or png
    // based on  https://code.tutsplus.com/tutorials/how-to-create-a-thumbnail-image-in-php--cms-36421
    // and https://stackoverflow.com/questions/313070/png-transparency-with-php
    public function createThumb($destImagePath, $thumbHeight=125, $imagetype)
    {
        if ($imagetype === 'jpg' || $imagetype === 'jpeg') {
          $sourceImage = imagecreatefromjpeg($this->source);
        } else if ($imagetype === 'png') {
          $sourceImage = imagecreatefrompng($this->source);
        }
        $orgWidth = imagesx($sourceImage);
        $orgHeight = imagesy($sourceImage);
        //$thumbHeight = floor($orgHeight * ($thumbWidth / $orgWidth));
        $thumbWidth = floor($orgWidth * ($thumbHeight / $orgHeight));
        $destImage = imagecreatetruecolor($thumbWidth, $thumbHeight);
        imagealphablending($destImage, false);
        imagesavealpha($destImage, true);
        imagealphablending($sourceImage, true);

        imagecopyresampled($destImage, $sourceImage, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $orgWidth, $orgHeight);
        if ($imagetype === 'jpg' || $imagetype === 'jpeg') {
          imagejpeg($destImage, $destImagePath);
        } else if ($imagetype === 'png') {
          imagepng($destImage, $destImagePath);
        }
        imagedestroy($sourceImage);
        imagedestroy($destImage);
    }
}
?>
