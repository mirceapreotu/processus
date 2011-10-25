<?php
/**
 * Lib_Asset_ImageUpload_Converter_ImageMagick Class
 *
 * @category	meetidaaa.com
 * @package        Lib_Asset_ImageUpload_Converter
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version        $Id$
 */

/**
 * Lib_Asset_ImageUpload_Converter_ImageMagick
 *
 * @category	meetidaaa.com
 * @package        Lib_Asset_ImageUpload_Converter
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version        $Id$
 */
class Lib_Asset_ImageUpload_Converter_ImageMagick
{

    const RESIZE_POLICY_CROP_CENTER = 1; // resize & crop to fit (center)
    const RESIZE_POLICY_FIT_BGCOLOR = 2; // resize to fit, fill bg
    const RESIZE_POLICY_FIT_WIDTH = 3; // resize to width
    const RESIZE_POLICY_CROP_WIDTH_MAGIC = 4; // resize to width, crop golden
    const RESIZE_POLICY_FIT_BOTH = 5; // resize to width and height

    const FILL_COLOR_WHITE = 'white';
    const FILL_COLOR_BLACK = 'black';

    /**
     * @var string
     */
    protected $_sourcePath;

     /**
     * @var string
     */
    protected $_targetPath;


    /**
     * @var string
     */
    protected $_overlayPath;
    
    /**
     * @var int
     */
    protected $_width;

    /**
     * @var int
     */
    protected $_height;


    /**
     * @var int
     */
    protected $_quality = 80;

    /**
     * @var int
     */
    protected $_resizePolicy = self::RESIZE_POLICY_CROP_CENTER;

    /**
     * @var string
     */
    protected $_fillColor = self::FILL_COLOR_WHITE;




    


    /**
     * @throws Exception
     * @param  $config
     * @return void
     */
    public function mixinConfig($config)
    {
        if ($config instanceof Zend_Config) {
            /**
             * @var Zend_Config $config
             */
            $config = $config->toArray();
        }
        if (is_array($config)!==true) {
            throw new Exception("invalid parameter 'config' at ".__METHOD__);
        }

        foreach($config as $key => $value) {

            switch($key) {

                case "sourcePath": {
                    $this->setSourcePath($value);
                    break;
                }
                case "targetPath": {
                    $this->setTargetPath($value);
                    break;
                }
                case "overlayPath": {
                    $this->setOverlayPath($value);
                    break;
                }
                case "width": {
                    $this->setWidth($value);
                    break;
                }
                case "height": {
                    $this->setHeight($value);
                    break;
                }
                case "quality": {
                    $this->setQuality($value);
                    break;
                }
                case "fillColor": {
                    $this->setFillColor($value);
                    break;
                }
                case "resizePolicy": {
                    $this->setResizePolicy($value);
                    break;
                }


                default: {
                    throw new Exception(
                        "Invalid key='".$key."' in config at ".__METHOD__
                    );
                    break;
                }

            }


        }

    }


    /**
     * @throws Exception
     * @param  $config
     * @return void
     */
    public function overrideConfig($config)
    {
        if ($config instanceof Zend_Config) {
            /**
             * @var Zend_Config $config
             */
            $config = $config->toArray();
        }
        if (is_array($config)!==true) {
            throw new Exception("invalid parameter 'config' at ".__METHOD__);
        }

        $keys = array(
            "sourcePath",
            "targetPath",
            "overlayPath",
            "width",
            "height",
            "quality",
            "fillColor",
            "resizePolicy"
        );

        foreach($keys as $key) {
            $value = Lib_Utils_Array::getProperty($config, $key);
            $config[$key] = $value;
        }

        $this->mixinConfig($config);

    }


    /**
     * @throws Exception
     * @param  string|null $value
     * @return void
     */
    public function setSourcePath($value)
    {
        if (((is_string($value))||($value===null))!==true) {
            throw new Exception("Invalid parameter 'value' at ".__METHOD__);
        }
        $this->_sourcePath = $value;
    }

    /**
     * @return string|null
     */
    public function getSourcePath()
    {
        return $this->_sourcePath;
    }


    /**
     * @throws Exception
     * @return
     */
    public function validateSourcePath()
    {
        $sourcePath = $this->getSourcePath();
        $isImage = false;
        try {
            if (Lib_Utils_String::isEmpty($sourcePath)) {
                throw new Exception("Property sourcePath cant be empty");
            }

            $isImage = $this->isImage($sourcePath);

            if ($isImage===true) {
                return;
            }
            throw new Exception("File is not an image type.");

        } catch(Exception $e) {
            $error = new Exception(
                "Property sourcePath is not an image at "
                .__METHOD__." details: ".$e->getMessage()
            );
            throw $error;
        }
    }


    /**
     * @throws Exception
     * @return void
     */
    public function validateTargetPath()
    {
        $targetPath = $this->getTargetPath();
       
        try {

            if (Lib_Utils_String::isEmpty($targetPath)) {
                throw new Exception("Property targetPath cant be empty");
            }

            $targetPathFileInfo = $this->newFileInfo($targetPath);
            if ($targetPathFileInfo->isDir()) {
                throw new Exception("Property targetPath is a directory");
            }
            if ($targetPathFileInfo->isLink()) {
                throw new Exception("Property targetPath is a link");
            }

            $directory =$targetPathFileInfo->getPath();
            
            $directoryInfo = $this->newFileInfo($directory);
            if ($directoryInfo->isDir()!==true) {
                throw new Exception(
                    "Property targetPath has not parent directory"
                );
            }
            if ($directoryInfo->isWritable() !== true) {
                throw new Exception(
                    "Property targetPath directory"
                    ." is not writeable"
                );
            }

        } catch(Exception $e) {
            $error = new Exception(
                "Property targetPath is invalid at "
                .__METHOD__." details: ".$e->getMessage()
            );
            throw $error;
        }
        
    }


    /**
     * @throws Exception
     * @param  string|null $value
     * @return void
     */
    public function setTargetPath($value)
    {
        if (((is_string($value))||($value===null))!==true) {
            throw new Exception("Invalid parameter 'value' at ".__METHOD__);
        }
        $this->_targetPath = $value;
    }

    /**
     * @return string|null
     */
    public function getTargetPath()
    {
        return $this->_targetPath;
    }

        /**
     * @throws Exception
     * @param  string|null $value
     * @return void
     */
    public function setOverlayPath($value)
    {
        if (((is_string($value))||($value===null))!==true) {
            throw new Exception("Invalid parameter 'value' at ".__METHOD__);
        }
        $this->_overlayPath = $value;
    }

    /**
     * @return string|null
     */
    public function getOverlayPath()
    {
        return $this->_overlayPath;
    }

    /**
     * @throws Exception
     * @param  int|string|null $value
     * @return
     */
    public function setWidth($value)
    {
        if ($value === null) {
            $this->_width = null;
            return;
        }

        $_value = Lib_Utils_TypeCast_String::asUnsignedInt($value, null);
        if ($_value === null) {
             throw new Exception("Invalid parameter 'value' at ".__METHOD__);
        }
        $value = (int)$_value;
        $this->_width = $value;

    }

    /**
     * @return int|null
     */
    public function getWidth()
    {
        return $this->_width;
    }

    /**
     * @throws Exception
     * @param  int|string|null $value
     * @return
     */
    public function setHeight($value)
    {
        if ($value === null) {
            $this->_height = null;
            return;
        }

        $_value = Lib_Utils_TypeCast_String::asUnsignedInt($value, null);
        if ($_value === null) {
             throw new Exception("Invalid parameter 'value' at ".__METHOD__);
        }
        $value = (int)$_value;
        $this->_height = $value;

    }

    /**
     * @return int|null
     */
    public function getHeight()
    {
        return $this->_height;
    }


    /**
     * @throws Exception
     * @param  int|string|null $value
     * @return
     */
    public function setQuality($value)
    {
        if ($value === null) {
            $this->_quality = null;
            return;
        }

        $_value = Lib_Utils_TypeCast_String::asUnsignedInt($value, null);
        if ($_value === null) {
             throw new Exception("Invalid parameter 'value' at ".__METHOD__);
        }
        $value = (int)$_value;
        $this->_quality = $value;

    }

    /**
     * @return int|null
     */
    public function getQuality()
    {
        return $this->_quality;
    }



    /**
     * @throws Exception
     * @param  int|string|null  $value
     * @return
     */
    public function setResizePolicy($value)
    {
        if ($value === null) {
            $this->_resizePolicy = null;
            return;
        }

        $_value = Lib_Utils_TypeCast_String::asUnsignedInt($value, null);
        if ($_value === null) {
             throw new Exception("Invalid parameter 'value' at ".__METHOD__);
        }
        $value = (int)$_value;
        $this->_resizePolicy = $value;
    }

    /**
     * @return int|null
     */
    public function getResizePolicy()
    {
        return $this->_resizePolicy;
    }


    /**
     * @throws Exception
     * @param  string|null $value
     * @return
     */
    public function setFillColor($value)
    {
        if ($value === null) {
            $this->_fillColor = null;
            return;
        }

        if (is_string($value)!==true) {
             throw new Exception("Invalid parameter 'value' at ".__METHOD__);
        }
        $this->_fillColor = $value;
    }

    /**
     * @return string|null
     */
    public function getFillColor()
    {
        return $this->_fillColor;
    }




    /**
     * @throws Exception
     * @return null|string
     */
    public function getConvertShellCommand()
    {
        $result = null;
        $resizePolicy = $this->getResizePolicy();



        $template = "";
        $data = array(
                    "sourcePath" => $this->getSourcePath(),
                    "targetPath" => $this->getTargetPath(),
                    "width" => $this->getWidth(),
                    "height" =>$this->getHeight(),
                    "fillColor" => $this->getFillColor(),
                    "width2" => ((int)$this->getWidth())*2,
                    "height2" => ((int)$this->getHeight())*2,
                );

        switch($resizePolicy) {

            case self::RESIZE_POLICY_FIT_WIDTH: {

                $template = 'convert '
                    . ' {sourcePath} '
                    . ' -resize {width}x> '
                    . ' -size {width}x '
                    . ' {targetPath}';




                break;
            }
                
            case self::RESIZE_POLICY_FIT_BOTH:

                $template =
                    'convert '
                    . ' {sourcePath} '
                    . ' -resize {width}x> '
                    . ' -size {width}x{height} '
                    . ' {targetPath}'
                    
                    . ' && '

                    . ' convert '
                    . ' {targetPath} '
                    . ' -resize x{height}> '
                    . ' {targetPath}'
                ;
                break;

            case self::RESIZE_POLICY_FIT_BGCOLOR:
                $template =
                    'convert '
                    . ' {sourcePath} '
                    . ' -resize {width}x{height}> '
                    . ' -size {width}x{height} '
                    . ' xc:{fillColor} +swap -gravity center -composite'
                    . ' {targetPath}'
                ;

                break;

            case self::RESIZE_POLICY_CROP_CENTER:



                $template =
                        'convert '
                        . ' {sourcePath} '
                        . ' -resize {width2}' . "x "
                        . " -resize 'x{height2}" . "<' "
                        . ' -resize 50% -gravity center -crop '
                        . ' {width}x{height}+0+0 +repage '
                        . '{targetPath} '
                ;
                break;

            case self::RESIZE_POLICY_CROP_WIDTH_MAGIC:

                throw new Exception(
                    "Implement ResizePolicy=".$resizePolicy." at ".__METHOD__
                );
                $sourcePathEscaped = (string)$this->getSourcePath();
                $sourcePathEscaped = escapeshellarg("".$sourcePathEscaped);
                // requires a shell command ....
                list($width, $height) = explode(
                    'x',`identify -format '%wx%h' $sourcePathEscaped`
                );

                if ((int)$height >= (int)$width) {

                    // portrait format: take resize + crop top magic

                    $factor = ($this->getWidth() / (int)$this->getHeight());

                    $newHeight = (int)((int)$height * $factor);

                    $offset = (

                            ((int)$height * (5/8)) +
                            (($this->getHeight()/$factor) * (3/8))
                    );
                    $offset = (int)($offset * $factor);

                    $offset = $newHeight - $offset; // invert axis

                    $data["offset"] = $offset;
                    $template =
                            'convert '
                            . '{sourcePath} '
                            . ' -resize {width2}' ."x "
                            . " -resize 'x{height2}" . "<' "
                            . ' -resize 50% -crop '
                            . ' {width}x{height}+0+{offset} +repage '
                            . ' {targetPath}'
                    ;

                } else {

                    // landscape format: take resize + crop center

                    $template =
                            'convert '
                            . '{sourcePath} '
                            . ' -resize {width2}' . "x "
                            . " -resize 'x{height2}". "<' "
                            . ' -resize 50% -gravity center -crop '
                            . ' {width}x{height}' .  '+0+0 +repage '
                            . ' {targetPath}'
                    ;
                }
                break;

            default: {
                return $result;
                break;
            }
        }


        foreach($data as $key => $value) {

            if ($value === null) {
                unset($data[$key]);
                // this will force an exception
                continue;
            }

            if (is_string($value)) {
                $data[$key] = escapeshellarg("".$value);
                continue;
            }

            if (is_bool($value)) {
                if ($value===true) {
                    $data[$key] = "true";
                } else {
                    $data[$key] = "false";
                }
                continue;
            }


            $value = (string)$value;
            $data[$key] =  escapeshellarg("".$value);
            continue;

        }


                    ;

        //var_dump($template);

        $parser = new Lib_Template_StringParser();
        $parser->setTemplate($template);
        $parser->setDelimiter(".");
        $parser->setMarshallExceptions(true);
//var_dump($parser->getTemplate());
        $cmd = $parser->parse($data);

        //echo $cmd;
//exit;
        $result = $cmd;
        return $result;

    }



    /**
     * @throws Exception
     * @return null
     */
    public function convert()
    {
        $cmd = $this->getConvertShellCommand();
        if (Lib_Utils_String::isEmpty($cmd)) {

            throw new Exception(
                "No convert command defined for resizePolicy="
                .$this->getResizePolicy()
                ." at ".__METHOD__
            );


        }
        return $this->_runConvert($cmd);
    }

     /**
     * @return void
     */
    public function composite()
    {
        throw new Exception("Implement at ".__METHOD__);
        $convertTaskString =
                'convert '
                . $this->_sourcePath
                . ' -gravity center '
                . $this->_overlayPath
                . ' -composite '
                . $this->_targetPath;

        $this->_runConvert($convertTaskString);
    }
    
    
    /**
     * @throws Exception
     * @param  string $convertShellCommand
     * @return null
     */
    protected function _runConvert($convertShellCommand)
    {
        $result = null;
        $output = array();
        $returnCode = 0;

        if (Lib_Utils_String::isEmpty($convertShellCommand)) {
            throw new Exception(
                "Invalid parameter 'convertShellCommand' at ".__METHOD__
            );
        }

        exec($convertShellCommand, $output, $returnCode);

        $returnCode = (int)$returnCode;
        if ($returnCode===0) {
            return $result;
        }

        $message = trim(join("\n", $output));
        if (($returnCode === 127) && (Lib_Utils_String::isEmpty($message))) {
            throw new Exception("ImageMagick convert command not found ("
                                .$returnCode.") at "
                                .__METHOD__
            );
        }

        throw new Exception("ImageMagick convert failed "
                            ." (".$returnCode.") "
                            ." at ".__METHOD__
                            ." details: ".$message
        );

    }

    

    /**
     * @throws Exception
     * @param  $path
     * @return bool
     */
    public function isImage($path)
    {
        $result = false;

        $fileInfo = $this->newFileInfo($path);
        if ($fileInfo->isFile() !== true) {
            return $result;
        }
        if ($fileInfo->isReadable() !== true) {
            throw new Exception(
                "Invalid permissions. file exists, but is not readable at "
                .__METHOD__
            );
        }

        $type = $this->getImageType($path);
        if ((is_int($type)) && ($type>0)) {
                return true;
        }

        return $result;
    }


    /**
     * @param  $path
     * @return SplFileInfo
     */
    public function newFileInfo($path)
    {
        return new SplFileInfo($path);
    }


    /**
     * @param  string $path
     * @return int
     */
    public function getImageType($path)
    {
        $result = -1;
        /*
        1 	IMAGETYPE_GIF
        2 	IMAGETYPE_JPEG
        3 	IMAGETYPE_PNG
        4 	IMAGETYPE_SWF
        5 	IMAGETYPE_PSD
        6 	IMAGETYPE_BMP
        7 	IMAGETYPE_TIFF_II (intel-Bytefolge)
        8 	IMAGETYPE_TIFF_MM (motorola-Bytefolge)
        9 	IMAGETYPE_JPC
        10 	IMAGETYPE_JP2
        11 	IMAGETYPE_JPX
        12 	IMAGETYPE_JB2
        13 	IMAGETYPE_SWC
        14 	IMAGETYPE_IFF
        15 	IMAGETYPE_WBMP
        16 	IMAGETYPE_XBM
        */

        try {
            $type = exif_imagetype($path);
            if ((is_int($type)) && ($type>0)) {
                return $type;
            }
        } catch (Exception $e) {

        }

        return $result;

    }

}
