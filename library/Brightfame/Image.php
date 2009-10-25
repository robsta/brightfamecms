<?php

/**
 * Brightfame Image
 *
 * @category    Brightfame
 * @package     Brightfame_Image
 * @license     New BSD {@link http://framework.zend.com/license/new-bsd}
 * @version     $Id: $
 */

class Brightfame_Image
{
    protected $originalPath = null;
    protected $original = null;
    protected $thumb = null;
    protected $type = null;
    protected $outputType = null;
    protected $originalDimensions = array();
    protected $maxWidth = 150;
    protected $maxHeight = 100;
    protected $quality = 100;
        
    const MAX_WIDTH = 1;
    const MAX_HEIGHT = 2;
    const BEST_FIT = 3;
    const EXACT = 4;
        
    /**
     * Constructor for the class, must be called
     *
     * @param string $imagePath The URL of the image file
     */
    public function __construct($imagePath)
    {
        if (!file_exists($imagePath) || !is_readable($imagePath)) {
            throw new Brightfame_Image_Exception("The file '{$imagePath}' does not exist or is not readable");
        }
        
        $this->originalPath = $imagePath;
        $this->populateImageInfo();
    }
        
    protected function getThumbnailDimensions($type)
    {
        $w = $this->getWidth();
        $h = $this->getHeight();
        $nw = null;
        $nh = null;
        switch ($type) {
            case Brightfame_Image::MAX_HEIGHT:
                $nh = ($h > $this->maxHeight) ? $this->maxHeight : $h;
                $nw = intval(($nh * $w) / $h);
                break;
            case Brightfame_Image::MAX_WIDTH:
                $nw = ($w > $this->maxWidth) ? $this->maxWidth : $w;
                $nh = intval(($nw * $h) / $w);
                break;
            case Brightfame_Image::BEST_FIT:
                if (($h / $this->maxHeight) >= ($w / $this->maxWidth)) {
                    $nh = ($h > $this->maxHeight) ? $this->maxHeight : $h;
                    $nw = intval(($nh * $w) / $h);
                } else {
                    $nw = ($w > $this->maxWidth) ? $this->maxWidth : $w;
                    $nh = intval(($nw * $h) / $w);
                }
                break;
            case Brightfame_Image::EXACT:
                $nw = $this->maxWidth;
                $nh = $this->maxHeight;
                break;
            default:
                throw new Brightfame_Image_Exception("Resize type is not valid. It must be one of the following class constants: MAX_HEIGHT, MAX_WIDTH, BEST_FIT, EXACT");
        }
        
        return array('width' => $nw, 'height' => $nh);
    }
        
        /**
         * Sets the output type of thumbnail images.  If not called, the output type will be the same as input type.
         *
         * @param constant $type Valid values include the constants: IMAGETYPE_JPEG, IMAGETYPE_GIF, or IMAGETYPE_PNG
         * @return Brightfame_Image The Brightfame_Image instance - used for 'fluent' access
         */
        public function setOutputType($type) {
            if ($type != IMAGETYPE_JPEG && $type != IMAGETYPE_GIF && $type != IMAGETYPE_PNG) {
                throw new Brightfame_Image_Exception('The output type must be one of the following constants IMAGETYPE_JPEG, IMAGETYPE_GIF, or IMAGETYPE_PNG');
            }
            
            $this->outputType = $type;
            return $this;
        }
        
        /**
         * Returns the image type of the image used to instantiate the class
         *
         * @return constant One of the following constants: IMAGETYPE_JPEG, IMAGETYPE_GIF, or IMAGETYPE_PNG
         */
        public function getImageType() {
            return $type;
        }
        
        /**
         * Returns the width of the image used to instantiate the class
         *
         * @return int The width in pixels
         */
        public function getWidth() {
            return $this->originalDimensions['width'];
        }
        
        /**
         * Returns the height of the image used to instantiate the class
         *
         * @return int The height in pixels
         */
        public function getHeight() {
            return $this->originalDimensions['height'];
        }
        
        /**
         * Sets the maximum height to be used in thumbnail creation
         *
         * @param int $maxHeight The height in pixels.  Defaults to 100.
         * @return Brightfame_Image The Brightfame_Image instance - used for 'fluent' access
         */
        public function setMaxHeight($maxHeight) {
            $this->maxHeight = $maxHeight;
            return $this;
        }
        
        /**
         * Sets the maximum width to be used in thumbnail creation
         *
         * @param int $maxWidth The width in pixels. Defaults to 150.
         * @return Brightfame_Image The Brightfame_Image instance - used for 'fluent' access
         */
        public function setMaxWidth($maxWidth) {
            $this->maxWidth = $maxWidth;
            return $this;
        }
        
        
        /**
         * Sends the proper content-type header to the browser and renders the thumbnail.  Brightfame_Image::resize must be called prior to this method.
         *
         * @return Brightfame_Image The Brightfame_Image instance - used for 'fluent' access
         */
        public function showThumbnail() {
            if (is_null($this->thumb)) {
                throw new Brightfame_Image_Exception('The image must be generated before you can show it');
            }
            
            header("Content-type: " . image_type_to_mime_type($this->outputType));
            $this->getThumb(NULL);
        }
        
        /**
         * Saves the thumbnail to the filesystem.  Brightfame_Image::resize must be called prior to this method.
         *
         * @param string $filename The full file path (relative or absolute) where the image should be saved
         * @return Brightfame_Image The Brightfame_Image instance - used for 'fluent' access
         */
        public function saveThumbnail($filename) {
            $this->getThumb($filename);
        }
        
        /**
         * Sets the quality level for JPEG images
         *
         * @param int $quality An integer between 0 and 100, where 100 is the best quality.  Defaults to 100.
         * @return Brightfame_Image The Brightfame_Image instance - used for 'fluent' access
         */
        public function setJpegQuality($quality) {
            if (!is_int($quality) || $quality < 0 || $quality > 100) {
                throw new Brightfame_Image_Exception("Quality must be a positive integer less than or equal to 100");
            }
            
            $this->quality = $quality;
            return $this;
        }
        
        /**
         * Resizes the image
         *
         * @param constant $type An image resize method.  Must be one of: Brightfame_Image::MAX_HEIGHT, Brightfame_Image::MAX_WIDTH, Brightfame_Image::BEST_FIT, Brightfame_Image::EXACT
         * @return Brightfame_Image The Brightfame_Image instance - used for 'fluent' access
         */
        public function resize($type = Brightfame_Image::MAX_HEIGHT) {
            $dimensions = $this->getThumbnailDimensions($type);
            $this->thumb = imagecreatetruecolor($dimensions['width'],$dimensions['height']);
            imagecopyresampled($this->thumb,$this->original,0,0,0,0,$dimensions['width'],$dimensions['height'],$this->getWidth(),$this->getHeight());
            return $this;
        }
        
        protected function populateImageInfo() {
            $img_info = getimagesize($this->originalPath);
            switch ($img_info[2]) {
                case IMAGETYPE_JPEG: 
                    $type = IMAGETYPE_JPEG;
                    $this->original = imagecreatefromjpeg($this->originalPath);
                    break;
                case IMAGETYPE_GIF: 
                    $type = IMAGETYPE_GIF;
                    $this->original = imagecreatefromgif($this->originalPath);
                    break;
                case IMAGETYPE_PNG: 
                    $type = IMAGETYPE_PNG;
                    $this->original = imagecreatefrompng($this->originalPath);
                    break;
                default:
                    throw new Brightfame_Image_Exception("Unkown file format - must be JPG, JPEG, GIF, or PNG");
                }
                $this->type = $type;
                $this->outputType = $type;
                $this->originalDimensions = array('width'=>$img_info[0],'height'=>$img_info[1]);
        }
        
        protected function getThumb($filename = NULL) {
            switch ($this->outputType) {
                case IMAGETYPE_JPEG: 
                    imagejpeg($this->thumb, $filename, $this->quality);
                    break;
                case IMAGETYPE_GIF: 
                    if (is_null($filename)) {
                        imagegif($this->thumb);
                    } else {
                        imagegif($this->thumb,$filename);
                    }
                    break;
                case IMAGETYPE_PNG: 
                    imagepng($this->thumb,$filename);
                    break;
            }
        }
        
        public function __destruct() {
            if (is_resource($this->original)) imagedestroy($this->original);
            if (is_resource($this->thumb)) imagedestroy($this->thumb);
        }

}