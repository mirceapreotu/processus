<?php


/**
 * Lib_Utils_File_ManagerHashedChunked
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Utils_File
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Utils_File_ManagerHashedChunked
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Utils_File
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_Utils_File_ManagerHashedChunked
{


    /**
     * @static
     * @throws Exception
     * @param  int $id
     * @param  string $salt
     * @return string
     */
    public static function getHashById($id,$salt)
    {
        if (((is_int($id)) && ($id>0))!==true) {
            $message = "Invalid parameter id must be int >0! at ".__METHOD__;
            throw new Exception($message);
        }
        if (Lib_Utils_String::isEmpty($salt)) {
            $message =
                "Invalid parameter salt must be string and cant be empty! at "
                . __METHOD__;
            throw new Exception($message);
        }

        $salt = md5($salt);

        $hash = md5(md5($id."_".$salt)."_".$id);

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
    public static function getDirectoryNameById($id, $salt, $chunkSize)
    {
        if (((is_int($chunkSize)) && ($chunkSize>0))!==true) {
            $message =
                "Invalid parameter chunkSize must be int >0! at ".__METHOD__;
            throw new Exception($message);
        }

        try {
            $hash = self::getHashById($id, $salt);
        } catch(Exception $e) {
            $message =
                "Error while trying to translate id to hash! at ".__METHOD__;
            $message .=" reason: ".$e->getMessage();
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
                . $chunkedPath." at ".__METHOD__
            );
        }

        $targetPath = $chunkedPath;//."/".$hash;

        return $targetPath;

    }


    /**
     * @static
     * @throws Exception
     * @param  string $rootPath
     * @param  int $id
     * @param  string $salt
     * @param  int $chunkSize
     * @param  int $chmod
     * @return string
     */
    public static function ensureDirectoryById(
        $rootPath,
        $id,
        $salt,
        $chunkSize,
        $chmod
    )
    {
        if (Lib_Utils_String::isEmpty($rootPath)) {
            $message =
                "Invalid parameter rootPath must be string and "
                . "cant be empty! at ".__METHOD__;
            throw new Exception($message);
        }

        $rootPathBlackList = array(
            ".","..",
        );
        if (in_array($rootPath, $rootPathBlackList, true)===true) {
            $message =
                "Invalid parameter rootPath contains a blacklisted value! at "
                . __METHOD__;
            throw new Exception($message);
        }

        if (is_dir($rootPath)!==true) {
            $message =
                "Invalid parameter rootPath must be a directory! at "
                . __METHOD__;
            throw new Exception($message);
        }

        if (((is_int($chmod)) && ($chmod>0))!==true) {
            $message = "Invalid parameter chmod must be int >0! at ".__METHOD__;
            throw new Exception($message);
        }


        try {
            $chunkedDirname = self::getDirectoryNameById(
                $id, $salt, $chunkSize
            );
        } catch (Exception $e){
            $message =
                "Error while trying to translate id to chunked dirname! at "
                . __METHOD__;
            $message .=" reason: ".$e->getMessage();
            throw new Exception($message);
        }

        $targetPath = $rootPath."/".$chunkedDirname;
        if (is_dir($targetPath)) {
            return $targetPath;
        }

         // create //
        $recursive=true;

        try {
            $created = mkdir($targetPath, $chmod, $recursive);
            clearstatcache();
        } catch (Exception $e) {
            
        }

        if (is_dir($targetPath)!==true) {
            $message =
                "Method returns invalid result! Create directory failed! at "
                . __METHOD__;
            throw new Exception($message);
        }

        return $targetPath;

    }



    /**
     * @static
     * @throws Exception
     * @param  string $path
     * @return DirectoryIterator
     */
    public static function getDirectoryIterator($path)
    {
        $pathBlackList = array(
            ".","..",
        );

        if (Lib_Utils_String::isEmpty($path)) {
            $message =
                "Invalid parameter path must be string and cant be empty! at "
                . __METHOD__;
            throw new Exception($message);
        }

        if (in_array($path, $pathBlackList, true)===true) {
            $message = "Invalid parameter; path contains blacklisted value at "
                       . __METHOD__;
            throw new Exception($message);
        }
        if (is_dir($path)!==true) {
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
     * @param null|array $blackList
     * @return array
     */
    public static function listFiles($path, $blackList = null)
    {

        try {
            $iterator = self::getDirectoryIterator($path);
        } catch(Exception $e){
            $message = "Error while trying to get Directory Iterator! at "
                       . __METHOD__;
            $message .= " reason: ".$e->getMessage();
            throw new Exception($message);
        }

        $_blackList = array("..",".");
        if (Lib_Utils_Array::isEmpty($blackList)!==true) {
            foreach ($blackList as $item) {
                $_blackList[] = $item;
            }
        }

        $childs = array();
        foreach ($iterator as $fileInfo) {
            $filename = $fileInfo->getFilename();
            $location = $fileInfo->getPathname();
            if (in_array($filename, $_blackList) === true) {
                continue;
            }
            if ( (is_file($location)) && (is_dir($location)!==true) ) {
                $childs[] = $filename;
            }
        }

        return $childs;
    }

    /**
     * @static
     * @throws Exception
     * @param  string $path
     * @param null|array $blackList
     * @return array
     */
    public static function listFolders($path, $blackList = null)
    {

        try {
            $iterator = self::getDirectoryIterator($path);
        } catch(Exception $e){
            $message = "Error while trying to get Directory Iterator! at "
                       . __METHOD__;
            $message .= " reason: ".$e->getMessage();
            throw new Exception($message);
        }

        $_blackList = array("..",".");
        if (Lib_Utils_Array::isEmpty($blackList)!==true) {
            foreach ($blackList as $item) {
                $_blackList[] = $item;
            }
        }

        $childs = array();
        foreach ($iterator as $fileInfo) {
            $filename = $fileInfo->getFilename();
            $location = $fileInfo->getPathname();
            if (in_array($filename, $_blackList) === true) {
                continue;
            }
            if ( (is_dir($location)===true) ) {
                $childs[] = $filename;
            }
        }

        return $childs;
    }
    
}
