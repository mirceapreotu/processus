<?php
/**
 * Lib_Asset_ImageUpload_Server
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Asset_ImageUpload
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Asset_ImageUpload_Server
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Asset_ImageUpload
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
 
class Lib_Asset_ImageUpload_Server
{

    const FORMAT_ORIG = "orig";
    const FORMAT_SMALL = "small";
    const FORMAT_MEDIUM = "medium";
    const FORMAT_LARGE = "large";

    const CHMOD_DEFAULT = 0755;//0777; 0666;

    /**
    * @var Lib_Asset_ImageUpload_Filesystem
    */
    protected $_filesystem;


    /**
     * @var string|null
     */
    protected $_filesystemNamespace;




    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    /**
     * @return App_Application
     */
    public function getApplication()
    {
        return App_Application::getInstance();
    }


    /**
     * @return void
     */
    public function init()
    {
        
    }


    /**
     * @return Zend_Config
     */
    public function getConfig()
    {
        $config = array(
            "namespace" => null, //autodetect,
            "filesystem" => array(
                "assetIdHashSalt" => null, //autodetect,
                "directoryChunkSize" => 8
            ),
            "folders" => array(
                "tmp" => "tmp",
                "upload" => "htdocs/upload",
                "default" => "htdocs/default",
            ),

            "servers" => array(
                "master" => "upload/image.php",
                "slaves" => array(
                   // "http://imageslave1.meetidaaa.com",
                   // "http://imageslave2.meetidaaa.com",
                   // "http://imageslave3.meetidaaa.com",
                ),
            ),
        );
        return new Zend_Config(
            $config
        );
    }


    /**
     * @return Zend_Config
     */
    public function getAssetFormatsConfig()
    {
        $config = array(

            self::FORMAT_ORIG => array(
                "width" => null,
                "height" => null,
                "converter" => null,
            ),
            self::FORMAT_SMALL => array(
                "width" => 100,
                "height" => 100,
                "converter" => array(
                    "fillColor" =>
                            Lib_Asset_ImageUpload_Converter_ImageMagick::
                                FILL_COLOR_WHITE,
                    "resizePolicy" =>
                        Lib_Asset_ImageUpload_Converter_ImageMagick::
                            RESIZE_POLICY_CROP_CENTER,
                ),
            ),
            self::FORMAT_MEDIUM => array(
                "width" => 200,
                "height" => 200,
                "converter" => array(
                    "fillColor" =>
                            Lib_Asset_ImageUpload_Converter_ImageMagick::
                                FILL_COLOR_WHITE,
                    "resizePolicy" =>
                        Lib_Asset_ImageUpload_Converter_ImageMagick::
                            RESIZE_POLICY_CROP_CENTER,
                ),
            ),
            self::FORMAT_LARGE => array(
                "width" => 400,
                "height" => 400,
                "converter" => array(
                    "fillColor" =>
                            Lib_Asset_ImageUpload_Converter_ImageMagick::
                                FILL_COLOR_WHITE,
                    "resizePolicy" =>
                        Lib_Asset_ImageUpload_Converter_ImageMagick::
                            RESIZE_POLICY_CROP_CENTER,
                ),
            ),
        );

        return new Zend_Config($config);
    }



    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    /**
     * @throws Exception
     * @return Zend_Config
     */
    public function getServersConfig()
    {
        $config = $this->getConfig()->servers;
        if (($config instanceof Zend_Config)!==true) {
            throw new Exception("Invalid config.servers at ".__METHOD__);
        }
        return $config;
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getServersMaster()
    {
        $value = $this->getServersConfig()->master;
        if (Lib_Utils_String::isEmpty($value)) {
            throw new Exception("Invalid config.servers.master at ".__METHOD__);
        }
        return $value;
    }

    /**
     * @throws Exception
     * @return array
     */
    public function getServersSlaves()
    {
        $result = array();
        $value = $this->getServersConfig()->slaves;
        if ($value === null) {
            return $result;
        }
        if ($value instanceof Zend_Config) {
            /**
             * @var Zend_Config $value
             */
            $value = $value->toArray();
            return $value;
        }

        if (is_array($value)) {
            return $value;
        }


        throw new Exception("Invalid config.servers.slaves at ".__METHOD__);

        
    }


    // ++++++++++++++++++++++ gdlib & exif +++++++++++++++++++++++++++++++++

    /**
     * @return array
     */
    public function exifGetImageTypesAvailable()
    {

        $result = array(
            IMAGETYPE_GIF => "GIF",
     	    IMAGETYPE_JPEG => "JPEG",
            IMAGETYPE_PNG => "PNG",
            IMAGETYPE_SWF => "SWF",
            IMAGETYPE_PSD => "PSD",
            IMAGETYPE_BMP => "BMP",
            IMAGETYPE_TIFF_II => "TIFF_II",// (intel byte order)
     	    IMAGETYPE_TIFF_MM => "TIFF_MM",// (motorola byte order)
     	    IMAGETYPE_JPC => "BMP",
            IMAGETYPE_JP2 => "JP2",
            IMAGETYPE_JPX => "JPX",
            IMAGETYPE_JB2 => "JB2",
            IMAGETYPE_SWC => "SWC",
            IMAGETYPE_IFF => "IFF",
            IMAGETYPE_WBMP => "WBMP",
            IMAGETYPE_XBM => "XBM",
            IMAGETYPE_ICO => "ICO",
        );

        return $result;
    }


    /**
     * @return array
     */
    public function gdGetImageTypesAvailable()
    {
        $result = array();

        $bits = array(
            IMG_GIF=>'GIF', // 1
            IMG_JPG=>'JPEG', //2  / NOT(!) 'JPG' we want exif-compatibílity
            IMG_PNG=>'PNG', // 4
            IMG_WBMP=>'WBMP', //8
            IMG_XPM =>'XPM', //16
        );

        $imageTypes = imagetypes();

        foreach ($bits as $key => $value) {
            if ($imageTypes & $key) {
                $result[$key] = $value;
            }
        }

        return $result;
    }


    /**
     * @return array
     */
    public function gdGetExifTypesSupported()
    {
        $gd = $this->gdGetImageTypesAvailable();

        $exif = $this->exifGetImageTypesAvailable();


        $exifFlipped = (array)array_flip($exif);

        $result = array();
        foreach($gd as $key => $value) {
            $name = $value;

            $exifId = Lib_Utils_Array::getProperty($exifFlipped, $name);
            if ($exifId !== null) {
                $result[$exifId] = $name;
            }
        }

        return $result;

    }


    /**
     * @Experimental
     * @TODO: test
     * @throws Exception
     * @param SplFileInfo $sourceFileInfo
     * @param SplFileInfo $targetFileInfo
     * @param null|int $quality
     * @param  bool $overwrite
     * @return void
     */
    public function saveImageFileAsJpeg(
            SplFileInfo $sourceFileInfo,
            SplFileInfo $targetFileInfo,
            $quality=null,
            $overwrite
    ) {



        if (is_bool($overwrite)!==true) {
            throw new Exception(
                "Invalid parameter 'overwrite' at ".__METHOD__
            );
        }
        if ($quality !== null) {

            if (((is_int($quality)) && ($quality>0) && ($quality<100))!==true) {
                throw new Exception(
                    "Invalid parameter 'quality' at ".__METHOD__
                );

            }
        }

        if ($sourceFileInfo->isFile() !== true) {
            throw new Exception(
                "Invalid parameter 'sourceFileInfo' is not a file at "
                .__METHOD__
            );
        }
        if ($sourceFileInfo->isReadable() !== true) {
            throw new Exception(
                "Invalid parameter 'sourceFileInfo' is not readable at "
                .__METHOD__
            );
        }

        if ($targetFileInfo->isDir()) {
            throw new Exception(
                "Invalid parameter 'targetFileInfo' is a directory at "
                .__METHOD__
            );
        }
        if ($targetFileInfo->isLink()) {
            throw new Exception(
                "Invalid parameter 'targetFileInfo' is a link at "
                .__METHOD__
            );
        }

        if ($overwrite !== true) {

            if ($targetFileInfo->isFile()) {
                throw new Exception(
                    "Invalid parameter 'targetFileInfo' already exists at "
                    .__METHOD__
                );
            }

        }

        $targetFileDir = new SplFileInfo($targetFileInfo->getPathname());
        if ($targetFileDir->isLink()) {
            throw new Exception(
                "Invalid parameter 'targetFileInfo'. parent dir is a link at "
                .__METHOD__
            );
        }
        if ($targetFileDir->isDir()) {
            throw new Exception(
                "Invalid parameter 'targetFileInfo'."
                ." parent is not a directory at "
                .__METHOD__
            );
        }
        if ($targetFileDir->isWritable()) {
            throw new Exception(
                "Invalid parameter 'targetFileInfo'."
                ." parent is not writeable at "
                .__METHOD__
            );
        }





        $isSaved = false;
        try {

            $sourceFileRealPath = $sourceFileInfo->getRealPath();
            $exifType = (int)exif_imagetype($sourceFileRealPath);
            if ($exifType<1) {
                throw new Exception("sourceFile is not an image (exiftype)");
            }

            $typesSupported = $this->gdGetExifTypesSupported();
            if (Lib_Utils_Array::getProperty(
                    $typesSupported, $exifType, true
                )!==true) {
                throw new Exception(
                    "gdlib does not support the sourceFile's exiftype"
                );
            }

            $targetFileLocation = $targetFileInfo->getPath();
            if ($quality === null) {
                $isSaved = imagejpeg(
                    $sourceFileRealPath, $targetFileLocation
                );
            } else {
                $isSaved = imagejpeg(
                    $sourceFileRealPath, $targetFileLocation, $quality
                );
            }

            if ($isSaved !== true) {
                throw new Exception("imagejpeg returns invalid result");
            }

            $exifType = (int)exif_imagetype($targetFileLocation);
            if ($exifType<1) {
                throw new Exception("invalid exiftype after imagejpeg");
            }

            $exifType = (int)$exifType;
            $exifTypeJpeg = (int)IMAGETYPE_JPEG;
            if ($exifType !== $exifTypeJpeg) {
                throw new Exception(
                    "invalid exiftype after imagejpeg is not jpeg"
                );
            }

        } catch(Exception $e) {

            throw new Exception(
                "Save as jpeg failed at "
                .__METHOD__
                ." details: ".$e->getMessage()
            );

        }

    }


    /**
     * @Experimental
     * @TODO: test
     * @throws Exception
     * @param bool $imagepath
     * @return resource
     */
    public function gdImageCreateFromFile($imagepath=false)
    {
        throw new Exception("Implement ".__METHOD__);
       // if(!$imagepath || !is_readable($imagepath) return false;
        return @imagecreatefromstring(file_get_contents($imagepath));
    }

    /**
     * @Experimental
     * @TODO: test
     * @throws Exception
     * @param  $data
     * @return resource
     */
    public function gdImageCreateFromString($data)
    {
        throw new Exception("Implement ".__METHOD__);
        $resource = imagecreatefromstring($data);
        $valid = ($resource != FALSE);
        //imagedestroy($resource);
        return $resource;
    }

    /**
     * @Experimental
     * @TODO: test
     * @throws Exception
     * @param  $data
     * @return resource
     */
    public function gdImageCreateFromStringBase64($data)
    {
        throw new Exception("Implement ".__METHOD__);
        $data = base64_decode($data);
        return imagecreatefromstring($data);
    }

    /**
     * @Experimental
     * @TODO: test
     * @throws Exception
     * @param  $resource
     * @return bool
     */
    public function gdImageDestroy($resource)
    {
        throw new Exception("Implement ".__METHOD__);
        return imagedestroy($resource);
    }

    /*
    public function gdImageCreateFromFile($file)
    {
        throw new Exception("Implement ".__METHOD__);
        $resource = imagecreatefromstring(file_get_contents($file));
        return $resource;
    }
    */

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    /**
     * @return null|string
     */
    public function getFilesystemNamespace()
    {
        $value = $this->getConfig()->namespace;
        if ($value === null) {
            $value = "".get_class($this);
        }


        if (is_string($value)!==true) {
            throw new Exception("Invalid parameter 'value' at ".__METHOD__);
        }

        if (strpos($value, " ") !== false) {
            throw new Exception("
                Invalid parameter 'value' must not have whitespaces at "
                                .__METHOD__
            );
        }
        if (strpos($value, ".") !== false) {
            throw new Exception("
                Invalid parameter 'value' must not have dots at "
                                .__METHOD__
            );
        }
        if (strpos($value, "/") !== false) {
            throw new Exception("
                Invalid parameter 'value' must not have slashes at "
                                .__METHOD__
            );
        }

        $value = trim($value);
        if (strlen($value)<1) {
            throw new Exception("
                Invalid parameter 'value' cant be empty at "
                                .__METHOD__
            );
        }

        return $value;
    }







    /**
     * @return Lib_Asset_ImageUpload_Filesystem
     */
    public function getFilesystem()
    {
        if ((
                ($this->_filesystem instanceof
                    Lib_Asset_ImageUpload_Filesystem)

        )!==true) {

            $instance = new Lib_Asset_ImageUpload_Filesystem();

            $config = $this->getConfig()->filesystem;
            if (($config instanceof Zend_Config)!==true) {
                throw new Exception("Invalid config.filesystem at ".__METHOD__);
            }

            /**
             * @var Zend_Config $config
             */
            $directoryChunkSize = $config->directoryChunkSize;
            $assetIdHashSalt = $config->assetIdHashSalt;

            $instance->setHashSalt($assetIdHashSalt);
            $instance->setHashChunkSize($directoryChunkSize);
            $this->_filesystem = $instance;
        }

        return $this->_filesystem;
    }


    // ++++++++++++++++++++++++++++ asset id's ++++++++++++++++++++++


    /**
     * @param  string|int $id
     * @return bool
     */
    public function isValidAssetId($id)
    {
        return ($this->getFilesystem()->isValidId($id) === true);
    }

    /**
     * @param  string|int $id
     * @return string|null
     */
    public function castAssetId($id)
    {
        return $this->getFilesystem()->castId($id);
    }


    // +++++++++++++++++ asset formats +++++++++++++++++++++++++++++


    /**
     * @param  $format
     * @return bool
     */
    public function isValidAssetFormat($format)
    {
        $list = $this->getAssetFormatsAvailable();
        return (in_array($format, $list, true)===true);
    }

    /**
     * @return array
     */
    public function getAssetFormatsAvailable()
    {
        $config = $this->getAssetFormatsConfig();
        if (($config instanceof Zend_Config)!==true) {
            throw new Exception("Invalid assetsFormatsConfig at ".__METHOD__);
        }

        $configArray = $config->toArray();

        $list = array_keys($configArray);

        $origFormat = self::FORMAT_ORIG;

        $list[] = $origFormat;
        $list = (array)array_unique($list);


        return $list;
    }

    /**
     * @param  string $format
     * @return bool
     */
    public function isAssetFormatOrig($format)
    {
        return (bool)($format===self::FORMAT_ORIG);
    }

    /**
     * @param  string $format
     * @return bool
     */
    public function isAssetFormatSmall($format)
    {
        return (bool)($format===self::FORMAT_SMALL);
    }
    /**
     * @param  string $format
     * @return bool
     */
    public function isAssetFormatMedium($format)
    {
        return (bool)($format===self::FORMAT_MEDIUM);
    }
    /**
     * @param  string $format
     * @return bool
     */
    public function isAssetFormatLarge($format)
    {
        return (bool)($format===self::FORMAT_LARGE);
    }

    /**
     * @throws Exception
     * @param  string $format
     * @return Zend_Config
     */
    public function getAssetFormatConfigByFormat($format)
    {
        if (is_string($format)!==true) {
            throw new Exception("Invalid parameter 'format' at ".__METHOD__);
        }


        $config = $this->getAssetFormatsConfig();
        if (($config instanceof Zend_Config )!==true) {
            throw new Exception("assets config is invalid at ".__METHOD__);
        }

        $formatConfig = $config->$format;

        if (($formatConfig instanceof Zend_Config)!==true) {
            throw new Exception("Invalid format at ".__METHOD__);
        }

        return $formatConfig;
    }
    



    // ++++++++++++++++++++++ filesystem basics +++++++++++++++++++++++++

    /**
     * @param  string $path
     * @return string
     */
    protected function _getFilesystemVarUploadPath($path)
    {
        return Bootstrap::getRegistry()->getVarUploadPath($path);
    }

    /**
     * you may want to override in subclass
     * @return string
     */
    public function getFilesystemRootPath($path)
    {
        $filesystemNamespace = $this->getFilesystemNamespace();
        if (Lib_Utils_String::isEmpty($filesystemNamespace)) {
            throw new Exception(
                "Invalid class property filesystemNamespace at ".__METHOD__
            );
        }

        $result = $this->_getFilesystemVarUploadPath(
            "/".$filesystemNamespace
        );

        if (Lib_Utils_String::isEmpty($path)) {
            return $result;
        }
        if (Lib_Utils_String::startsWith($path, "/", true) !== true) {
            $result .= "/";
        }
        $result .= $path;


        return $result;
    }

    /**
     * you may want to override in subclass
     * @return string
     */
    public function getFilesystemUploadPath($path)
    {
        $config = $this->getConfig()->folders;
        if (($config instanceof Zend_Config)!==true) {
            throw new Exception("Invalid config.folders at ".__METHOD__);
        }

        $localPath = $config->upload;
        if (Lib_Utils_String::isEmpty($localPath)) {
            throw new Exception("Invalid config.folders.upload at ".__METHOD__);
        }
        if (strpos($localPath, ".")!==false) {
            throw new Exception(
                "Invalid config.folders.upload must not contain dots at "
                .__METHOD__
            );
        }
        if (strpos($localPath, "htdocs/")===false) {
            throw new Exception(
                "Invalid config.folders.upload must contain expression"
                ." 'htdocs/' at "
                .__METHOD__
            );
        }

        $result = $this->getFilesystemRootPath(
            trim($localPath)
        );
        if (Lib_Utils_String::isEmpty($path)) {
            return $result;
        }
        if (strpos($path, ".")!==false) {
            throw new Exception(
                "Invalid parameter 'path' must not contain dots at ".__METHOD__
            );
        }

        if (Lib_Utils_String::startsWith($path, "/", true) !== true) {
            $result .= "/";
        }
        $result .= $path;


        return $result;
    }

    /**
     * you may want to override in subclass
     * @return string
     */
    public function getFilesystemTmpPath($path)
    {
        $config = $this->getConfig()->folders;
        if (($config instanceof Zend_Config)!==true) {
            throw new Exception("Invalid config.folders at ".__METHOD__);
        }

        $localPath = $config->tmp;
        if (Lib_Utils_String::isEmpty($localPath)) {
            throw new Exception("Invalid config.folders.tmp at ".__METHOD__);
        }
        if (strpos($localPath, ".")!==false) {
            throw new Exception(
                "Invalid config.folders.tmp must not contain dots at "
                .__METHOD__
            );
        }

        $result = $this->getFilesystemRootPath(
            trim($localPath)
        );
        if (Lib_Utils_String::isEmpty($path)) {
            return $result;
        }
        if (strpos($path, ".")!==false) {
            throw new Exception(
                "Invalid parameter 'path' must not contain dots at ".__METHOD__
            );
        }

        if (Lib_Utils_String::startsWith($path, "/", true) !== true) {
            $result .= "/";
        }
        $result .= $path;


        return $result;
    }
    /**
     * you may want to override in subclass
     * @return string
     */
    public function getFilesystemDefaultPath($path)
    {
        $config = $this->getConfig()->folders;
        if (($config instanceof Zend_Config)!==true) {
            throw new Exception("Invalid config.folders at ".__METHOD__);
        }

        $localPath = $config->default;
        if (Lib_Utils_String::isEmpty($localPath)) {
            throw new Exception(
                "Invalid config.folders.default at ".__METHOD__
            );
        }
        if (strpos($localPath, ".")!==false) {
            throw new Exception(
                "Invalid config.folders.default must not contain dots at "
                .__METHOD__
            );
        }
        if (strpos($localPath, "htdocs/")===false) {
            throw new Exception(
                "Invalid config.folders.default must contain expression"
                ." 'htdocs/' at "
                .__METHOD__
            );
        }

        $result = $this->getFilesystemRootPath(
            trim($localPath)
        );
        if (Lib_Utils_String::isEmpty($path)) {
            return $result;
        }

        if (strpos($path, ".")!==false) {
            throw new Exception(
                "Invalid parameter 'path' must not contain dots at ".__METHOD__
            );
        }

        if (Lib_Utils_String::startsWith($path, "/", true) !== true) {
            $result .= "/";
        }
        $result .= $path;


        return $result;
    }

    



    /**
     * @throws Exception
     * @param  $errorMethod
     * @return void
     */
    public function ensureFilesystem($errorMethod)
    {
        if (Lib_Utils_String::isEmpty($errorMethod)) {
            $errorMethod = __METHOD__;
        }

        $varUploadPath = $this->_getFilesystemVarUploadPath("");

        $rootPath = $this->getFilesystemRootPath("");

        if ($rootPath === $varUploadPath) {
            $e = new Lib_Application_Exception(
                "Invalid configuration! rootpath must not equal varuploadpath"
                ." at ".__METHOD__
            );
            $e->setMethod($errorMethod);
            throw $e;
        }

        // rootPath, e.g. : var/upload/Lib_foo/
        // uploadPath, e.g.: var/upload/Lib_foo/upload/
        // tmpPath, e.g.: var/upload/Lib_foo/tmp/

        // root
        $rootPathInfo = $this->getFilesystem()->newFileInfo($rootPath);
        if ($rootPathInfo->isDir() !== true) {
            $this->getFilesystem()->ensureDirectory($rootPath, null, false);
        }

        // root/upload
        $uploadPath = $this->getFilesystemUploadPath("");
        if (Lib_Utils_String::startsWith(
                $uploadPath, $rootPath, false
            )!==true) {
            throw new Exception(
                "upload path must be a child directory of rootpath at "
                .__METHOD__
            );
        }
        $uploadPathInfo = $this->getFilesystem()->newFileInfo($uploadPath);
        if ($uploadPathInfo->isDir() !== true) {
            $this->getFilesystem()->ensureDirectory($uploadPath, null, true);
        }

        // root/tmp
        $tmpPath = $this->getFilesystemTmpPath("");
        if (Lib_Utils_String::startsWith(
                $tmpPath, $rootPath, false
            )!==true) {
            throw new Exception(
                "tmp path must be a child directory of rootpath at "
                .__METHOD__
            );
        }
        $tmpPathInfo = $this->getFilesystem()->newFileInfo($tmpPath);
        if ($tmpPathInfo->isDir() !== true) {
            $this->getFilesystem()->ensureDirectory($tmpPath, null, true);
        }


        // root/default
        $defaultPath = $this->getFilesystemDefaultPath("");
        if (Lib_Utils_String::startsWith(
                $uploadPath, $rootPath, false
            )!==true) {
            throw new Exception(
                "default path must be a child directory of rootpath at "
                .__METHOD__
            );
        }
        $defaultPathInfo = $this->getFilesystem()->newFileInfo($defaultPath);
        if ($defaultPathInfo->isDir() !== true) {
            $this->getFilesystem()->ensureDirectory($defaultPath, null, true);
        }



        $rootPathInfo = $this->getFilesystem()->newFileInfo($rootPath);
        $this->_validateDirectoryInfo(
            $rootPathInfo, "rootPath", $errorMethod
        );
        $this->_validateDirectoryInfo(
            $uploadPathInfo, "defaultPath", $errorMethod
        );
        $this->_validateDirectoryInfo(
            $uploadPathInfo, "uploadPath", $errorMethod
        );
        $this->_validateDirectoryInfo(
            $tmpPathInfo, "tmpPath", $errorMethod
        );

    }



    /**
     * @throws Lib_Application_Exception
     * @param SplFileInfo $dirInfo
     * @param  string $errorKey
     * @param  string $errorMethod
     * @return void
     */
    protected function _validateDirectoryInfo(
        SplFileInfo $dirInfo, $errorKey, $errorMethod
    )
    {
         if ($dirInfo->isDir() !== true) {
            $e = new Lib_Application_Exception(
                "path ".$errorKey." is not a directory at "
                .__METHOD__
            );
            $e->setMethod($errorMethod);
            throw $e;
        }
        if ($dirInfo->isReadable() !== true) {
            $e = new Lib_Application_Exception(
                "invalid ".$errorKey." permission is not readable at "
                .__METHOD__
            );
            $e->setMethod($errorMethod);
            throw $e;
        }
        if ($dirInfo->isWritable() !== true) {
            $e = new Lib_Application_Exception(
                "invalid ".$errorKey." permission is not writable at "
                .__METHOD__
            );
            $e->setMethod($errorMethod);
            throw $e;
        }
    }




    // ++++++++++++++++++ construct directory info ++++++++++++++++++++++

    public function newDirectoryNameById($id)
    {
        if ($this->isValidAssetId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }
        $id = $this->castAssetId($id);
        $dirname = $this->getFilesystem()->newDirectoryNameById($id);
        return $dirname;
    }


    /**
     * directory: /upload
     * @throws Exception
     * @param  $id
     * @return SplFileInfo
     */
    public function newDirectoryInfoUploadByAssetId($id)
    {
        if ($this->isValidAssetId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }

        $id = $this->castAssetId($id);
        $dirname = $this->newDirectoryNameById($id);


        //$this->ensureFilesystem(__METHOD__);

        $fulldirname = $this->getFilesystemUploadPath("".$dirname);
        return $this->getFilesystem()->newFileInfo($fulldirname);
    }

    /**
     * directory: /tmp
     * @throws Exception
     * @param  $id
     * @return SplFileInfo
     */
    public function newDirectoryInfoTmpByAssetId($id)
    {
        if ($this->isValidAssetId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }

        $id = $this->castAssetId($id);
        $dirname = $this->newDirectoryNameById($id);


        //$this->ensureFilesystem(__METHOD__);

        $fulldirname = $this->getFilesystemTmpPath("".$dirname);
        return $this->getFilesystem()->newFileInfo($fulldirname);
    }

    /**
     * directory: /default
     * @throws Exception
     * @param  $id
     * @return SplFileInfo
     */
    public function newDirectoryInfoDefaultByAssetId($id)
    {
        if ($this->isValidAssetId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }

        $id = $this->castAssetId($id);

        $dirnameDefault = "";


        //$this->ensureFilesystem(__METHOD__);

        $fulldirname = $this->getFilesystemDefaultPath("".$dirnameDefault);
        return $this->getFilesystem()->newFileInfo($fulldirname);
    }







    public function ensureDirectoryDefaultByAssetId($id)
    {
        if ($this->isValidAssetId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }

        $id = $this->castAssetId($id);
        $filesystem = $this->getFilesystem();

        $targetPathInfo = $this->newDirectoryInfoDefaultByAssetId($id);



        $targetPathIsValid = (
                        ($targetPathInfo->isDir())
                        && ($targetPathInfo->isReadable())
                        && ($targetPathInfo->isWritable())
                    );
        if ($targetPathIsValid == true) {
            return;
        }


        try {
            $this->ensureFilesystem(__METHOD__);

            $filesystem->ensureDirectory(
                $targetPathInfo->getPath(), self::CHMOD_DEFAULT, true
            );


        } catch(Exception $error) {

            throw new Exception(
                "ensureDirectory failed at ".__METHOD__
                ." details: ".$error->getMessage()
            );

        }

    }


    public function ensureDirectoryTmpByAssetId($id)
    {
        if ($this->isValidAssetId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }

        $id = $this->castAssetId($id);
        $filesystem = $this->getFilesystem();

        $targetPathInfo = $this->newDirectoryInfoTmpByAssetId($id);



        $targetPathIsValid = (
                        ($targetPathInfo->isDir())
                        && ($targetPathInfo->isReadable())
                        && ($targetPathInfo->isWritable())
                    );
        if ($targetPathIsValid == true) {
            return;
        }


        try {
            $this->ensureFilesystem(__METHOD__);

            $filesystem->ensureDirectory(
                $targetPathInfo->getPath(), self::CHMOD_DEFAULT, true
            );

        } catch(Exception $error) {

            throw new Exception(
                "ensureDirectory failed at ".__METHOD__
                ." details: ".$error->getMessage()
            );

        }

    }


    public function ensureDirectoryUploadByAssetId($id)
    {
        if ($this->isValidAssetId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }

        $id = $this->castAssetId($id);
        $filesystem = $this->getFilesystem();

        $targetPathInfo = $this->newDirectoryInfoUploadByAssetId($id);



        $targetPathIsValid = (
                        ($targetPathInfo->isDir())
                        && ($targetPathInfo->isReadable())
                        && ($targetPathInfo->isWritable())
                    );
        if ($targetPathIsValid == true) {
            return;
        }


        try {
            $this->ensureFilesystem(__METHOD__);

            $filesystem->ensureDirectory(
                $targetPathInfo->getPath(), self::CHMOD_DEFAULT, true
            );

        } catch(Exception $error) {

            throw new Exception(
                "ensureDirectory failed at ".__METHOD__
                ." details: ".$error->getMessage()
            );
        }
    }




    // +++++++++++++++++++++ construct fileInfo +++++++++++++++++++++++++++

    public function getPublicUriByFileInfo(SplFileInfo $fileInfo)
    {

        $location = $fileInfo->getPathname();
        $path = $fileInfo->getPath();
        $filename = $fileInfo->getFilename();

        if (is_string($location)!==true) {
            throw new Exception(
                "Invalid parameter 'fileInfo.pathName' at ".__METHOD__
            );
        }
        $location = "".$location;
        if (Lib_Utils_String::isEmpty($location)) {
            return "";
        }
        if (strpos($path, ".")===true) {
            throw new Exception(
                "Invalid parameter 'fileInfo.path' must not contain dots"
                ." at ".__METHOD__
            );
        }

        // remove everything before htdocs/ (and including htdocs/ )

        $prefix = $this->getFilesystemRootPath("htdocs/");
        if (Lib_Utils_String::startsWith($location, $prefix, false)!==true) {
            throw new Exception(
                "Invalid parameter 'fileInfo.pathName' does not contain"
                ." a recognized prefix"
                ." at ".__METHOD__
            );
        }

        $uri = Lib_Utils_String::removePrefix($location, $prefix, false);
        if (Lib_Utils_String::startsWith($uri, $prefix, false)===true) {
            throw new Exception(
                "Remove prefix failed! "
                ." at ".__METHOD__
            );
        }
        return $uri;

    }


    /**
     * @throws Exception
     * @param  string $uri
     * @return SplFileInfo
     */
    public function getFileInfoByPublicUri($uri)
    {
        // find the file by
        //  e.g.: "upload/9f618e41/99cb1faa/3c85b97b/a8518d3c/71bfea4e/a038ce1e/9f56ed2f/orig.jpg"

        if ($uri === null) {
            $uri = "";
        }

        if (is_string($uri)!==true) {
            throw new Exception("Invalid parameter 'uri' at ".__METHOD__);
        }


        if (Lib_Utils_String::startsWith($uri, "/")) {
            $location = $this->getFilesystemRootPath("htdocs".$uri);
        } else {
            $location = $this->getFilesystemRootPath("htdocs/".$uri);
        }

        $fileInfo = $this->getFilesystem()->newFileInfo($location);
        $location = $fileInfo->getPathname();
        $path = $fileInfo->getPath();
        $filename = $fileInfo->getFilename();

        if (strpos($path, ".")===true) {
            throw new Exception(
                "Invalid parameter 'fileInfo.path' must not contain dots"
                ." at ".__METHOD__
            );
        }

        return $fileInfo;

    }



    /**
     * directoy: /upload
     * @throws Exception
     * @param  $id
     * @return SplFileInfo
     */
    public function newFileInfoUploadByAssetId($id, $format)
    {
        if ($this->isValidAssetId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }
        if ($this->isValidAssetFormat($format)!==true) {
            throw new Exception("Invalid parameter 'format' at ".__METHOD__);
        }

        $id = $this->castAssetId($id);

        $dirInfo = $this->newDirectoryInfoUploadByAssetId($id);

        $path = $dirInfo->getPath();

        $filename = $this->newFilenameUploadByAssetId($id, $format);
        if (Lib_Utils_String::isEmpty($filename)) {
            throw new Exception(
                "method returns invalid result for 'filename' at ".__METHOD__
            );
        }
        $path .= "/".$filename;

        return $this->getFilesystem()->newFileInfo($path);

    }



    /**
     * directoy: /tmp
     * @throws Exception
     * @param  $id
     * @return SplFileInfo
     */
    public function newFileInfoTmpByAssetId($id, $format)
    {
        if ($this->isValidAssetId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }
        if ($this->isValidAssetFormat($format)!==true) {
            throw new Exception("Invalid parameter 'format' at ".__METHOD__);
        }

        $id = $this->castAssetId($id);

        $dirInfo = $this->newDirectoryInfoTmpByAssetId($id);

        $path = $dirInfo->getPath();

        $filename = $this->newFilenameTmpByAssetId($id, $format);
        if (Lib_Utils_String::isEmpty($filename)) {
            throw new Exception(
                "method returns invalid result for 'filename' at ".__METHOD__
            );
        }
        $path .= "/".$filename;

        return $this->getFilesystem()->newFileInfo($path);

    }


    /**
    * directoy: /default
    * @throws Exception
    * @param  $id
    * @return SplFileInfo
    */
    public function newFileInfoDefaultByAssetId($id, $format)
    {
        if ($this->isValidAssetId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }
        if ($this->isValidAssetFormat($format)!==true) {
            throw new Exception("Invalid parameter 'format' at ".__METHOD__);
        }

        $id = $this->castAssetId($id);

        $dirInfo = $this->newDirectoryInfoDefaultByAssetId($id);

        $path = $dirInfo->getPath();

        $filename = $this->newFilenameDefaultByAssetId($id, $format);
        if (Lib_Utils_String::isEmpty($filename)) {
            throw new Exception(
                "method returns invalid result for 'filename' at ".__METHOD__
            );
        }
        $path .= "/".$filename;

        return $this->getFilesystem()->newFileInfo($path);

    }


    

    /**
     * @throws Exception
     * @param  $id
     * @return SplFileInfo
     */
    public function newFileInfoUploadOrFileInfoDefaultByAssetId($id, $format)
    {
        if ($this->isValidAssetId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }
        if ($this->isValidAssetFormat($format)!==true) {
            throw new Exception("Invalid parameter 'format' at ".__METHOD__);
        }

        $id = $this->castAssetId($id);
        $filesystem = $this->getFilesystem();


        $fileInfoUpload = $this->newFileInfoUploadByAssetId($id, $format);

        $fileInfoUploadIsValid = ($fileInfoUpload->isFile())
                && ($fileInfoUpload->isReadable())
                && ($filesystem->isImage($fileInfoUpload->getPathname())
                );

        if ($fileInfoUploadIsValid===true) {
            return $fileInfoUpload;
        }


        // find default image
        $this->ensureDirectoryDefaultByAssetId($id);
        $fileInfoDefault = $this->newFileInfoDefaultByAssetId($id, $format);
        return $fileInfoDefault;

    }



    // ++++++++++++++++++++ construct filename +++++++++++++++++++++



    /**
     * dirctory: /upload
     * @throws Exception
     * @param  int|string $id
     * @param  string $format
     * @return string
     */
    public function newFilenameUploadByAssetId($id, $format)
    {
        if ($this->isValidAssetId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }
        if ($this->isValidAssetFormat($format)!==true) {
            throw new Exception("Invalid parameter 'format' at ".__METHOD__);
        }

        $id = $this->castAssetId($id);

        $filename = "".$format.".jpg";
        return $filename;

    }

    /**
     * directory: /tmp
     * @throws Exception
     * @param  int|string $id
     * @param  string $format
     * @return string
     */
    public function newFilenameTmpByAssetId($id, $format)
    {
        if ($this->isValidAssetId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }
        if ($this->isValidAssetFormat($format)!==true) {
            throw new Exception("Invalid parameter 'format' at ".__METHOD__);
        }

        $id = $this->castAssetId($id);

        $filename = "".$format.".jpg";
        return $filename;
    }

    /**
     * directory: /tmp
     * @throws Exception
     * @param  int|string $id
     * @param  string $format
     * @return string
     */
    public function newFilenameDefaultByAssetId($id, $format)
    {
        if ($this->isValidAssetId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }
        if ($this->isValidAssetFormat($format)!==true) {
            throw new Exception("Invalid parameter 'format' at ".__METHOD__);
        }

        $id = $this->castAssetId($id);

        $filename = "".$format.".jpg";
        return $filename;
    }


    // +++++++++++++++ show image ++++++++++++++++++++++++++++

    public function showPublicImage(SplFileInfo $fileInfo)
    {
        try {
            $location = $fileInfo->getPathname();
            $path = $fileInfo->getPath();
            $filename = $fileInfo->getFilename();

            if (is_string($location)!==true) {
                throw new Exception(
                    "Invalid parameter 'fileInfo.pathName' at ".__METHOD__
                );
            }
            $location = "".$location;
            if (Lib_Utils_String::isEmpty($location)) {
                return "";
            }
            if (strpos($path, ".")===true) {
                throw new Exception(
                    "Invalid parameter 'fileInfo.path' must not contain dots"
                    ." at ".__METHOD__
                );
            }

            // remove everything before htdocs/ (and including htdocs/ )

            $prefix = $this->getFilesystemRootPath("htdocs/");
            if (Lib_Utils_String::startsWith($location, $prefix, false)!==true) {
                throw new Exception(
                    "Invalid parameter 'fileInfo.pathName' does not contain"
                    ." a recognized prefix"
                    ." at ".__METHOD__
                );
            }

            if ($fileInfo->isFile() !== true) {
                header("Status: 404 Not Found");
                return;
            }


            $imageType = (int)exif_imagetype($location);
            if ($imageType<1) {
                throw new Exception("file is not an image");
            }

            $size = (int)filesize($location);
            $mimeType = image_type_to_mime_type($imageType);

            header("Content-Type: " .$mimeType);
            header('Content-Length: '.$size);
            readfile($location);


        } catch(Exception $e) {

            header("Status: 404 Not Found");
            return;

        }



    }



    // ++++++++++++++++ process uploads +++++++++++++++++++++++



    /**
     * @throws Exception|Lib_Application_Exception
     * @param  string|int $id
     * @param  string $uploadedfilename
     * @param  bool $overwrite
     * @return void
     */
    public function moveHttpPostUploadedImageToTmpByAssetId(
        $id, $uploadedfilename, $overwrite
    )
    {
        if ($this->isValidAssetId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }
        
        $id = $this->castAssetId($id);

        if (is_bool($overwrite)!==true) {
            throw new Exception("Invalid parameter 'overwrite' at ".__METHOD__);
        }

        $format = self::FORMAT_ORIG;

        $filesystem = $this->getFilesystem();

        $destinationInfo = $this->newFileInfoTmpByAssetId(
            $id, $format
        );
        $destination = $destinationInfo->getPathname();

        $exists = ($destinationInfo->isFile())||($destinationInfo->isLink());
        if ($overwrite !== true) {
            if ($exists === true) {
                throw new Exception(
                    "destination in tmp already exists at ".__METHOD__
                );
            }
        }


        $this->ensureDirectoryTmpByAssetId($id);
        if (is_uploaded_file($uploadedfilename)!==true) {
            throw new Exception(
                "uploaded file is not a file at ".__METHOD__
            );
        }


        $isMoved = false;
        $movedError = null;
        try {
            $isMoved = move_uploaded_file(
                $uploadedfilename, $destination
            );
        } catch(Exception $error) {
            $movedError = $error;
        }
        if ($isMoved !== true) {
            $error = new Lib_Application_Exception("isMoved result is false");
            $error->setMethod(__METHOD__);
            $error->setFault($movedError);
            throw $error;
        }


        $destinationInfo = $filesystem->newFileInfo($destination);
        $destination = $destinationInfo->getPathname();
        $isImage = $filesystem->isImage($destination);
        if ($isImage!==true) {

            if ($destinationInfo->isFile()) {
                try {
                    $isDeleted = unlink($destination);
                    if ($isDeleted !== true) {
                        throw new Exception("isDeleted = false");
                    }
                } catch (Exception $e) {
                    $error = new Lib_Application_Exception(
                        "Error while trying to delete invalid file"
                    );
                    $error->setMethod(__METHOD__);
                    $error->setFault($e);
                    throw $error;
                }
            }

            throw new Exception("uploaded file is not an image at ".__METHOD__);
        }

    }



    /**
     * @throws Exception|Lib_Application_Exception
     * @param  int|string $id
     * @param  string $data
     * @param  bool $overwrite
     * @return void
     */
    public function saveHttpPostUploadedImageDataBase64ToTmpByAssetId(
        $id, $data, $overwrite
    )
    {
        if ($this->isValidAssetId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }

        $id = $this->castAssetId($id);

        if (is_bool($overwrite)!==true) {
            throw new Exception("Invalid parameter 'overwrite' at ".__METHOD__);
        }

        if (is_string($data)!==true) {
            throw new Exception("Invalid parameter 'data' at ".__METHOD__);
        }

        $data = base64_decode($data);
        if (is_string($data)!==true) {
            throw new Exception(
                "Invalid parameter 'data'  base64decode failed at ".__METHOD__
            );
        }


        $format = self::FORMAT_ORIG;

        $filesystem = $this->getFilesystem();

        $destinationInfo = $this->newFileInfoTmpByAssetId(
            $id, $format
        );
        $destination = $destinationInfo->getPathname();

        $exists = ($destinationInfo->isFile())||($destinationInfo->isLink());
        if ($overwrite !== true) {
            if ($exists === true) {
                throw new Exception(
                    "destination in tmp already exists at ".__METHOD__
                );
            }
        }


        $this->ensureDirectoryTmpByAssetId($id);



        $isSaved = false;
        $savedError = null;
        try {
            $bytesSaved = file_put_contents($destination, $data, LOCK_EX);
            $isSaved = ((is_int($bytesSaved)) && ($bytesSaved>0));
        } catch(Exception $error) {
            $savedError = $error;
        }
        if ($isSaved !== true) {
            $error = new Lib_Application_Exception("isSaved result is false");
            $error->setMethod(__METHOD__);
            $error->setFault($savedError);
            throw $error;
        }


        $destinationInfo = $filesystem->newFileInfo($destination);
        $destination = $destinationInfo->getPathname();
        $isImage = $filesystem->isImage($destination);
        if ($isImage!==true) {

            if ($destinationInfo->isFile()) {
                try {
                    $isDeleted = unlink($destination);
                    if ($isDeleted !== true) {
                        throw new Exception("isDeleted = false");
                    }
                } catch (Exception $e) {
                    $error = new Lib_Application_Exception(
                        "Error while trying to delete invalid file"
                    );
                    $error->setMethod(__METHOD__);
                    $error->setFault($e);
                    throw $error;
                }
            }

            throw new Exception("uploaded file is not an image at ".__METHOD__);
        }

    }



    /**
     * @throws Exception
     * @param  string|int $id
     * @param  string $uploadedfilename
     * @return void
     */
    public function moveTmpImageToUploadByAssetId(
        $id, $overwrite
    )
    {
        if ($this->isValidAssetId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }
        $id = $this->castAssetId($id);

        if (is_bool($overwrite)!==true) {
            throw new Exception("Invalid parameter 'overwrite' at ".__METHOD__);
        }


        $filesystem = $this->getFilesystem();

        $srcFormat = self::FORMAT_ORIG;
        $targetFormat = self::FORMAT_ORIG;


        $tmpFileInfo = $this->newFileInfoTmpByAssetId(
            $id, $srcFormat
        );
        $tmpFileLocation = $tmpFileInfo->getPathname();
        if ($filesystem->isImage($tmpFileLocation)!==true) {

            $error = new Lib_Application_Exception("tmpfile is not an image");
            $error->setMethod(__METHOD__);
            $error->setFault(array(
                "location" => $tmpFileLocation
                             ));

            throw $error;
        }


        $uploadFileInfo = $this->newFileInfoUploadByAssetId(
            $id, $targetFormat
        );

        if ($overwrite !== true) {
            if ($uploadFileInfo->isFile()) {
                throw new Exception(
                    "target file already exists at ".__METHOD__
                );
            }
        }

        $uploadFileLocation = $uploadFileInfo->getPathname();
        $this->ensureDirectoryUploadByAssetId($id);

        $isMoved = false;
        $movedError = null;
        try {
            $isMoved = rename($tmpFileLocation, $uploadFileLocation);
        } catch(Exception $error) {
            $movedError = $error;
        }
        if ($isMoved !== true) {
            $error = new Lib_Application_Exception("isMoved result is false");
            $error->setMethod(__METHOD__);
            $error->setFault($movedError);
            throw $error;
        }


        $destinationInfo = $filesystem->newFileInfo($uploadFileLocation);
        $destination = $destinationInfo->getPathname();
        $isImage = $filesystem->isImage($destination);
        if ($isImage!==true) {

            if ($destinationInfo->isFile()) {
                try {
                    $isDeleted = unlink($destination);
                    if ($isDeleted !== true) {
                        throw new Exception("isDeleted = false");
                    }
                } catch (Exception $e) {
                    $error = new Lib_Application_Exception(
                        "Error while trying to delete invalid file"
                    );
                    $error->setMethod(__METHOD__);
                    $error->setFault($e);
                    throw $error;
                }
            }

            throw new Exception("uploaded file is not an image at ".__METHOD__);
        }

    }





    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    // ++++++++++++++++++++++++++ urls ++++++++++++++++++++++++++++

    /**
     * @throws Exception
     * @param  $id
     * @param  $format
     * @return string
     */
    public function getUriUploadOrDefaultByAssetId($id, $format)
    {

        $server = $this;
        //$server = $this->getServer();

        if ($server->isValidAssetId($id) !== true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }
        if ($server->isValidAssetFormat($format) !== true) {
            throw new Exception("Invalid parameter 'format' at ".__METHOD__);
        }


        $fileInfo = $server->newFileInfoUploadOrFileInfoDefaultByAssetId(
            $id, $format
        );


        $uri = $server->getPublicUriByFileInfo($fileInfo);
        return $uri;
    }

    /**
     * @throws Exception
     * @param  string|int $id
     * @param  string $format
     * @return string
     */
    public function getUriUploadByAssetId($id, $format)
    {

        $server = $this;
        //$server = $this->getServer();
        if ($server->isValidAssetId($id) !== true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }
        if ($server->isValidAssetFormat($format) !== true) {
            throw new Exception("Invalid parameter 'format' at ".__METHOD__);
        }


        $fileInfo = $server->newFileInfoUploadByAssetId(
            $id, $format
        );


        $uri = $server->getPublicUriByFileInfo($fileInfo);

        return $uri;
    }





    /**
     * @throws Exception
     * @param  string|null $uri
     * @param  null|string $scheme
     * @param  bool $requireMaster
     * @return string
     */
    public function getUrlByUri($uri, $scheme, $requireMaster)
    {
        // master & slaves:
        $server = $this;
        //$server = $this->getServer();

        $master = $server->getServersMaster();
        $slaves = $server->getServersSlaves();

        if ($scheme === null) {
            $scheme = Lib_Url_Uri::SCHEME_HTTP;
        }

        $schemes = array(
            Lib_Url_Uri::SCHEME_HTTP,
            Lib_Url_Uri::SCHEME_HTTPS,
        );
        if (in_array($scheme, $schemes, true)!==true) {
            throw new Exception(
                "Invalid parameter 'scheme' at ".__METHOD__
            );
        }


        if (is_bool($requireMaster)!==true) {
            throw new Exception(
                "Invalid parameter 'requireMaster' at ".__METHOD__
            );
        }
        if ($uri === null) {
            $uri = "";
        }
        if (is_string($uri)!==true) {
            throw new Exception(
                "Invalid parameter 'uri' at ".__METHOD__
            );
        }

        $uri = trim($uri);
        if (Lib_Utils_String::startsWith($uri, "/", false)) {
            $uri = Lib_Utils_String::removePrefix($uri, "/" ,false);
        }

        $useMaster = ($requireMaster===true);

        if (is_array($slaves) !== true) {
            $useMaster = true;
        }
        if (count($slaves)<1) {
            $useMaster = true;
        }

        // master
        if ($useMaster === true) {

            if (Lib_Utils_String::isEmpty($master)) {
                throw new Exception("Invalid config.master at ".__METHOD__);
            }

            $urlMaster = $this->getApplication()->getUrl($master);
            $zendUriMaster = new Lib_Url_Uri();
            if ($zendUriMaster->isValidUri($urlMaster)!==true) {
                throw new Exception("Invalid uriMaster at ".__METHOD__);
            }
            $zendUriMaster->setUri($urlMaster);
            $zendUriMaster->setQueryParameter("resource", $uri);
            $urlMaster = $zendUriMaster->toString($scheme);
            return (string)$urlMaster;
        }


        // pick a slave
        $numSlaves = count($slaves);
        $useSlaveId = 0;
        if ($numSlaves>0) {
            $useSlaveId = rand(0, $numSlaves-1);
        }

        $urlSlave = Lib_Utils_Array::getProperty($slaves, $useSlaveId);
        if (Lib_Utils_String::isEmpty($urlSlave)) {
            throw new Exception(
                "Invalid uriSlave at index=".$useSlaveId." cant be empty"
                ." at ".__METHOD__
            );
        }
        $urlSlave = trim($urlSlave);

        $zendUriSlave = new Lib_Url_Uri();
        if ($zendUriSlave->isValidUri($urlSlave)!==true) {
            throw new Exception(
                "Invalid uriSlave at index=".$useSlaveId." at ".__METHOD__
            );
        }

        if (Lib_Utils_String::endsWith($urlSlave, "/") !== true) {
            $urlSlave .= "/";
        }

        $urlSlave .= $uri;
        $zendUriSlave->setUri($urlSlave);
        $urlSlave = (string)$zendUriSlave->toString($scheme);
        return $urlSlave;

    }


    // +++++++++++++++++++++ show / render image ++++++++++++++++++++++++

    /**
     * @throws Exception
     * @param  array|null $request
     * @return void
     */
    public function showPublicImageByHttpRequest($request)
    {
        if ($request === null) {
            $request = array();
        }
        if (is_array($request)!==true) {
            throw new Exception("invalid parameter 'request' at ".__METHOD__);
        }

        $uri = Lib_Utils_Array::getProperty($request, "resource");
        $this->showPublicImageByUri($uri);
    }

    /**
     * @throws Exception
     * @param  string|null $uri
     * @return void
     */
    public function showPublicImageByUri($uri)
    {
        // e.g.: "upload/9f618e41/99cb1faa/3c85b97b/a8518d3c/71bfea4e/a038ce1e/9f56ed2f/orig.jpg"

        $server = $this;
        //$server = $this->getServer();

        if ($uri === null) {
            $uri = "";
        }

        if (is_string($uri)!==true) {
            throw new Exception("Invalid parameter 'uri' at ".__METHOD__);
        }

        $fileInfo = $server->getFileInfoByPublicUri($uri);
        $server->showPublicImage($fileInfo);
    }



    // +++++++++++++++++++++++++++++++++ process uploads +++++++++++++
    /**
     * @param  string|null $fileKey
     * @return array
     */
    public function newProcessUploadInfoUpload($fileKey)
    {
        if ((($fileKey===null)||(is_string($fileKey))) !==true) {
            throw new Exception("Invalid parameter 'fileKey' at ".__METHOD__);
        }
        $uploadInfo = array(
            "type" => "upload",
            "data" => null,
            "fileKey" => $fileKey,
            "meta" => null,
            "id" => null,

        );
        return $uploadInfo;
    }

    /**
     * @param  string|null $data
     * @return array
     */
    public function newProcessUploadInfoSave($data)
    {
        if ((($data===null)||(is_string($data))) !==true) {
            throw new Exception("Invalid parameter 'data' at ".__METHOD__);
        }
        $uploadInfo = array(
            "type" => "save",
            "data" => $data,
            "fileKey" => null,
            "meta" => null,
            "id" => null,
        );
        return $uploadInfo;
    }



    /**
     * @throws Exception
     * @param  array $uploadInfo
     * @return array
     */
    public function validateProcessUploadInfo($uploadInfo)
    {
        $type = $uploadInfo["type"];
        if (in_array($type, array("save","upload"), true) !== true) {
            throw new Exception(
                "Invalid parameter 'uploadInfo.type' at ".__METHOD__
            );
        }


        if ($type === "save") {
            $data = $uploadInfo["data"];
            if (is_string($data)!==true) {
                throw new Exception("Invalid uploadInfo.data");
            }
            if (strlen($data)<1) {
                throw new Exception("Invalid uploadInfo.data is empty");
            }

            return $uploadInfo;
        }

        if ($type === "upload") {

            $fileMeta = null;

            if (Lib_Utils_String::isEmpty($uploadInfo["fileKey"])) {
                if (isset($_FILES) !== true) {
                    throw new Exception("No files uploaded (001)");
                }
                foreach($_FILES as $meta) {

                    $tmpName = Lib_Utils_Array::getProperty($meta, "tmp_name");
                    if (Lib_Utils_String::isEmpty($tmpName)!==true) {
                        $fileMeta = $meta;
                        break;
                    }
                }
            } else {

                if (isset($_FILES) !== true) {
                    throw new Exception("No files uploaded (001)");
                }
                $fileKey = $uploadInfo["fileKey"];

                $fileMeta = Lib_Utils_Array::getProperty(
                    (array)$_FILES, $fileKey
                );
            }
            $uploadInfo["meta"] = $fileMeta;


            if (is_array($fileMeta)!==true) {
                throw new Exception("filemeta is empty");
            }
            $tmpFilename = Lib_Utils_Array::getProperty($fileMeta, "tmp_name");
            if (Lib_Utils_String::isEmpty($tmpFilename)===true) {
                throw new Exception("No files uploaded (002)");
            }

            if (is_uploaded_file($tmpFilename)!==true) {
                throw new Exception("uploaded file is not a file");
            }

            $tmpFilesize = (int)Lib_Utils_Array::getProperty($fileMeta, "size");
            if ($tmpFilesize<1) {
                throw new Exception("uploaded file is empty (003)");
            }

            $tmpFileError = (int)Lib_Utils_Array::getProperty(
                $fileMeta, "error"
            );
            if ($tmpFileError>0) {
                throw new Exception("uploaded file is empty");
            }
            $tmpFileTitle = Lib_Utils_Array::getProperty($fileMeta, "name");


            $uploadInfo["meta"] = $fileMeta;
            return $uploadInfo;

        }


        throw new Exception("Method returns invalid result at ".__METHOD__);

    }



    /**
     * YOU MAY WANT TO OVERRIDE IN SUBCLASSES
     * @throws Exception
     * @param  $uploadInfo
     * @return array
     */
    public function processUploadInfoByAssetId(
        $uploadInfo,
        $id
        // YOU MAY WANT TO OVERRIDE IN SUBCLASSES
    )
    {
        $result = array(
            "id" => null,
            "folder" => null,
        );

        if ($this->isValidAssetId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }
        $id = $this->castAssetId($id);



        try {
            $uploadInfo = $this->validateProcessUploadInfo($uploadInfo);
        } catch (Exception $e) {
            throw new Exception(
                "uploadInfo is invalid at "
                .__METHOD__." details: ".$e->getMessage()
            );
        }


        if ($this->isValidAssetId($id)!==true) {
            throw new Exception("Invalid var 'id' at ".__METHOD__);
        }
        $id = $this->castAssetId($id);
        $result["id"] = $id;
        $result["folder"] = $this->newDirectoryNameById($id);


        $this->_processUploadInfoByAssetId($uploadInfo, $id );

        return $result;

    }


    /**
     * @throws Exception
     * @param  array $uploadInfo
     * @param  int|string $id
     * @return void
     */
    protected function _processUploadInfoByAssetId($uploadInfo, $id)
    {
        $result = array(
            "id" => null,
        );
        if ($this->isValidAssetId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }
        $id = $this->castAssetId($id);
        $result["id"] = $id;

        try {
            $uploadInfo = $this->validateProcessUploadInfo($uploadInfo);
        } catch (Exception $e) {
            throw new Exception(
                "uploadInfo is invalid at "
                .__METHOD__." details: ".$e->getMessage()
            );
        }

        if ($uploadInfo["type"]==="save") {
            $this->saveHttpPostUploadedImageDataBase64ToTmpByAssetId(
                    $id, $uploadInfo["data"], false
            );

            $this->moveTmpImageToUploadByAssetId($id, false);

            return $result;
        }

        if ($uploadInfo["type"]==="upload") {

            

            $this->moveHttpPostUploadedImageToTmpByAssetId(
                $id, $uploadInfo["meta"]["tmp_name"], false
            );

            $this->moveTmpImageToUploadByAssetId($id, false);

            return $result;
        }

        throw new Exception("Methods returns invalid result at ".__METHOD__);
    }


}
