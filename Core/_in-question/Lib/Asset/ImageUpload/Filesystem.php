<?php
/**
 * Lib_Asset_ImageUpload_Filesystem
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Asset_ImageFileManager
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Asset_ImageUpload_Filesystem
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Asset_ImageFileManager
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
 
class Lib_Asset_ImageUpload_Filesystem
{

    const CHMOD_DEFAULT = 0755;  //0777; 0666; //owner/group/everyone: r = 4 w = 2 x = 1


    // +++++++++++++++++++++++ assets managemenet +++++++++++++++++++++

    /**
     * @var string|null
     */
    protected $_hashSalt = null;

    /**
     * @var int
     */
    protected $_hashChunkSize = 4;

    /**
     * @throws Exception
     * @param  string|null $value
     * @return
     */
    public function setHashSalt($value)
    {
        if ($value === null) {
            $this->_hashSalt = $value;
            return;
        }

        if (Lib_Utils_String::isEmpty($value)) {
            throw new Exception("Invalid parameter 'value' at ".__METHOD__);
        }

        $this->_hashSalt = $value;

    }

    /**
     * @return string|null
     */
    public function getHashSalt()
    {
       
        return $this->_hashSalt;
    }


    /**
     * @throws Exception
     * @param  int|null $value
     * @return
     */
    public function setHashChunkSize($value)
    {
        if ($value === null) {
            $this->_hashChunkSize = $value;
            return;
        }

        if (is_int($value)!==true) {
            throw new Exception("Invalid parameter 'value' at ".__METHOD__);
        }
        if ($value<0) {
            throw new Exception("Invalid parameter 'value' at ".__METHOD__);
        }

        $this->_hashChunkSize = $value;
    }

    /**
     * @return int|null
     */
    public function getHashChunkSize()
    {
        return $this->_hashChunkSize;
    }

    /**
     * @param  int|string $id
     * @return bool
     */
    public function isValidId($id)
    {
        $result = false;
        $_id = Lib_Utils_TypeCast_String::asUnsignedBigIntString($id, null);
        if ($_id === null) {
            return $result;
        }

        $_id = (int)$_id;
        if ($_id>0) {
            return true;
        }

        return false;
    }

    /**
     * @param  string|int $id
     * @return string|null
     */
    public function castId($id)
    {
        $result = Lib_Utils_TypeCast_String::asUnsignedBigIntString($id, null);
        return $result;
    }


    /**
     * @throws Exception
     * @param  int|string $id
     * @return string
     */
    public function newHashById($id)
    {
        if ($this->isValidId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }

        $id = $this->castId($id);
        if ($this->isValidId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }

        $salt = $this->getHashSalt();
        if ($salt === null) {
            $salt = md5("dkjdjdh3763dhdh".md5("fejsfdjfdfdj".get_class($this)));
        }


        if (Lib_Utils_String::isEmpty($salt)) {
            $message =
                "Invalid class property hashSalt ! at "
                . __METHOD__;
            throw new Exception($message);
        }

        $salt = md5($salt);
        $hash = md5(md5($id."_".$salt)."_".$id);

        $hash .= md5($salt."_".md5($id."_".$salt)."_".$id);

        if (Lib_Utils_String::isEmpty($hash)) {
            throw new Exception(
                "Method returned invalid result =".json_encode($hash)." at "
                . __METHOD__
            );
        }

        return $hash;
    }


    /**
     * @static
     * @throws Exception
     * @param  int $id
     * @param  string $salt
     * @param  int $chunkSize
     * @return string
     */
    public function newDirectoryNameById($id)
    {
        if ($this->isValidId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }

        $defaultChunkSize = 0;
        $chunkSize = $this->getHashChunkSize();
        if ($chunkSize === null) {
            $chunkSize = $defaultChunkSize;
        }

        if (((is_int($chunkSize)) && ($chunkSize>0))!==true) {
            $message =
                "Invalid class property hashChunkSize! at ".__METHOD__;
            throw new Exception($message);
        }

        try {
            $hash = $this->newHashById($id);
        } catch(Exception $e) {
            $message =
                "Error while trying to translate id to hash! at ".__METHOD__;
            $message .=" details: ".$e->getMessage();
            throw new Exception($message);
        }

        $chunks = (array)str_split($hash, $chunkSize);
        if ((count($chunks)>0)!==true) {
            throw new Exception(
                "Method returns invalid result! chunks="
                . json_encode($chunks)." at ".__METHOD__
            );
        }


        $chunkedPath = implode("/", $chunks);
        if (Lib_Utils_String::isEmpty($chunkedPath)) {
            throw new Exception(
                "Method returns invalid result! chunkedPath="
                . json_encode($chunkedPath)." at ".__METHOD__
            );
        }

        $targetPath = $chunkedPath;//."/".$hash;

        return $targetPath;

    }


    // +++++++++++++++++++++++ filsesytem ++++++++++++++++++++++++++++

    /**
     * @param array|Zend_Config|null $config
     * @return Lib_Fnmatch_Filter
     */
    public function newFilter($config = null)
    {
        $filter = new Lib_Fnmatch_Filter();

        if ((
                    ($config===null)
                    ||(is_array($config))
                    ||($config instanceof Lib_Fnmatch_Filter)
            )!==true) {
            throw new Exception("Invalid Parameter 'config' at ".__METHOD__);
        }

        if ($config !== null) {
            $filter->applyConfig($config);
        }
        return $filter;
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
     * @param  $path
     * @return bool
     */
    public function isDirectory($path)
    {
        $result = false;
        try {
            $fileInfo = $this->newFileInfo($path);
            if ($fileInfo->isDir()) {
                return true;
            }
        } catch(Exception $e) {
            //NOP
        }
        return $result;
    }
    /**
     * @param  $path
     * @return bool
     */
    public function isFile($path)
    {
        $result = false;
        try {
            $fileInfo = $this->newFileInfo($path);
            if ($fileInfo->isFile() !== true) {
                return false;
            }
            if ($fileInfo->isDir()) {
                return false;
            }
            return true;
        } catch(Exception $e) {
            //NOP
        }
        return $result;
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
     * @static
     * @throws Exception
     * @param  string $path
     * @return DirectoryIterator
     */
    public function newDirectoryIterator($path)
    {
       if (Lib_Utils_String::isEmpty($path)) {
            $message =
                "Invalid parameter path must be string and cant be empty! at "
                . __METHOD__;
            throw new Exception($message);
        }

        if ($this->isDirectory($path)!==true) {
            $message = "Invalid parameter path must be a directory! at "
                       . __METHOD__;
            throw new Exception($message);
        }

        $iterator = new DirectoryIterator($path);
        return $iterator;
    }


    /**
     * @static
     * @throws Exception
     * @param  string $path
     * @return array
     */
    public function listFiles(
        $path, Lib_Fnmatch_Filter $filter = null
        // returns array of SplFileInfo
    )
    {
        if ((
                ($filter === null)
                ||($filter instanceof Lib_Fnmatch_Filter)
                  )!==true) {
            throw new Exception("Invalid Parameter 'filter' at ".__METHOD__);
        }

        $isDir = $this->isDirectory($path);
        if ($isDir !== true) {
            throw new Exception(
                "Invalid parameter 'path' is not a valid directory at "
                .__METHOD__
            );
        }


        try {
            $iterator = $this->newDirectoryIterator($path);
        } catch(Exception $e){
            $message = "Error while trying to get Directory Iterator! at "
                       . __METHOD__;
            $message .= " reason: ".$e->getMessage();
            throw new Exception($message);
        }



        $childs = array();
        foreach ($iterator as $iteratorItem) {
            /**
             * @var DirectoryIterator $iteratorItem
             */

            if ($iteratorItem->isFile() !== true) {
                continue;
            }
            if ($iteratorItem->isDot()) {
                continue;
            }


            $filename = $iteratorItem->getFilename();
            $location = $iteratorItem->getPathname();

            if ($filter instanceof Lib_Fnmatch_Filter) {
                $isWhitelisted = $filter->isWhitelisted($filename, null);
                $isBlacklisted = $filter->isBlacklisted($filename, null);
                $isAllowed = (
                        ($isWhitelisted===true) && ($isBlacklisted !==true)
                );
                if ($isAllowed !== true) {
                    continue;
                }
            }

            $childs[] = $iteratorItem->getFileInfo();

        }

        return $childs;
    }


    /**
     * @static
     * @throws Exception
     * @param  string $path
     * @return array
     */
    public function listFolders(
        $path, Lib_Fnmatch_Filter $filter = null
        // returns array of SplFileInfo
    )
    {
        if ((
                ($filter === null)
                ||($filter instanceof Lib_Fnmatch_Filter)
                  )!==true) {
            throw new Exception("Invalid Parameter 'filter' at ".__METHOD__);
        }

        $isDir = $this->isDirectory($path);
        if ($isDir !== true) {
            throw new Exception(
                "Invalid parameter 'path' is not a valid directory at "
                .__METHOD__
            );
        }

        try {
            $iterator = $this->newDirectoryIterator($path);
        } catch(Exception $e){
            $message = "Error while trying to get Directory Iterator! at "
                       . __METHOD__;
            $message .= " reason: ".$e->getMessage();
            throw new Exception($message);
        }



        $childs = array();
        foreach ($iterator as $iteratorItem) {
            /**
             * @var DirectoryIterator $iteratorItem
             */
            $filename = $iteratorItem->getFilename();
            $location = $iteratorItem->getPathname();

            if ($iteratorItem->isDir() !== true) {
                continue;
            }
            if ($iteratorItem->isDot()) {
                continue;
            }


            if ($filter instanceof Lib_Fnmatch_Filter) {
                $isWhitelisted = $filter->isWhitelisted($filename, null);
                $isBlacklisted = $filter->isBlacklisted($filename, null);
                $isAllowed = (
                        ($isWhitelisted===true) && ($isBlacklisted !==true)
                );
                if ($isAllowed !== true) {
                    continue;
                }
            }


            $childs[] = $iteratorItem->getFileInfo();

        }

        return $childs;
    }



    /**
     * @throws Exception
     * @param  string $path
     * @param  int|null $chmod
     * @param  bool $recursive
     * @return SplFileInfo
     */
    public function ensureDirectory($path, $chmod, $recursive)
    {

        $defaultChmod = self::CHMOD_DEFAULT;

        if (is_string($path)!==true) {
            throw new Exception("Invalid parameter 'path' at ".__METHOD__);
        }

        if ($chmod === null) {
            $chmod = $defaultChmod;
        }
        if (is_int($chmod) !== true) {
            throw new Exception("Invalid parameter 'chmod' at ".__METHOD__);
        }

        if (is_bool($recursive) !== true) {
            throw new Exception("Invalid parameter 'recursive' at ".__METHOD__);
        }

        $fileInfo = $this->newFileInfo($path);
        if ($fileInfo->isDir()) {
            return $fileInfo;
        }

        $recursive = ($recursive===true);

        try {
            /*
            $oldUmask = umask(0);
            mkdir($path, $chmod, $recursive);
            clearstatcache();
            umask($oldUmask);
            */

            //$chmod=0777;
            mkdir($path, $chmod, $recursive);
        } catch (Exception $e) {
            throw new Exception(
                "mkdir failed at ".__METHOD__." details: "
                .$e->getMessage()
               // ." mod=".$chmod
               // ." recursive="
               // .$recursive
               // ." path=".$path
            );
        }

        $fileInfo = $this->newFileInfo($path);
        if ($fileInfo->isDir() !== true) {
            throw new Exception("create directory failed at ".__METHOD__);
        }

        return $fileInfo;


    }
    



}
