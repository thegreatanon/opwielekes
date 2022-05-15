<?php
require "thumbimage.class.php";
session_start();
$ds = DIRECTORY_SEPARATOR;

$storeFolder = 'uploads';

if (!empty($_FILES)) {
      $tempFile = $_FILES['file']['tmp_name'];
      // set paths
      $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds . $_SESSION["account"]["AccountCode"];  //4
      if (!file_exists($targetPath)) {
        mkdir($targetPath, 0777, true);
      }
      $thumbPath = $targetPath . $ds  . 'thumb';
      if (!file_exists($thumbPath)) {
        mkdir($thumbPath, 0777, true);
      }
      // set filenames
      //$path_parts = pathinfo($_FILES['file']['name']);
      $targetFile = $targetPath . $ds . $_FILES['file']['name'];
      $thumbFile = $thumbPath . $ds . $_FILES['file']['name'];
      $imgextension = pathinfo( $_FILES['file']['name'], PATHINFO_EXTENSION );
      // transfer file to correct file
      move_uploaded_file($tempFile,$targetFile);
      // thumbnail generation
      $objThumbImage = new ThumbImage($targetFile);
      $objThumbImage->createThumb($thumbFile, 125,$imgextension);
}
?>
