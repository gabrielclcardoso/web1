<?php

class ImageProcessor {
    private $sourceImg = null;
    private $overlayImg = null;

    public function __destruct() {
        if ($this->sourceImg) imagedestroy($this->sourceImg);
        if ($this->overlayImg) imagedestroy($this->overlayImg);
    }

    public function loadFromBase64($base64String) {
        $imgBase64 = preg_replace('#^data:image/\w+;base64,#i', '', $base64String);
        $imgBinary = base64_decode($imgBase64);
        
        if (!$imgBinary) throw new Exception('Error decoding image.');

        $this->sourceImg = @imagecreatefromstring($imgBinary);
        if (!$this->sourceImg) throw new Exception('Invalid image.');
        
		imageflip($this->sourceImg, IMG_FLIP_HORIZONTAL);
        return $this;
    }

    public function applyOverlay($overlayPath) {
        if (!file_exists($overlayPath)) throw new Exception('Overlay not found.');
        
        $this->overlayImg = imagecreatefrompng($overlayPath);
        
        imagealphablending($this->sourceImg, true);
        imagesavealpha($this->sourceImg, true);

        $ovrWidth = imagesx($this->overlayImg);
        $ovrHeight = imagesy($this->overlayImg);

		$srcWidth = imagesx($this->sourceImg);
		$srcHeight = imagesy($this->sourceImg);
		
		imagecopyresampled(
		    $this->sourceImg,
		    $this->overlayImg,
		    0, 0,
		    0, 0,
		    $srcWidth,
		    $srcHeight,
		    $ovrWidth,
		    $ovrHeight
		);

        return $this;
    }

    public function save($outputPath) {
        if (!$this->sourceImg) throw new Exception('No image uploaded to save.');
        
        $success = imagepng($this->sourceImg, $outputPath);
        if (!$success) throw new Exception('Error uploading image.');
        
        return true;
    }
}
?>
