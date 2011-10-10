<?php
/**
 * App_Manager_ImageUpload Class
 *
 * @category	meetidaaa.com
 * @package		App_Manager
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

/**
 * App_Manager_ImageUpload
 *
 * @category	meetidaaa.com
 * @package		App_Manager
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

class App_Manager_ImageUpload extends App_Manager_AbstractManager
{



    /**
     * @var App_Asset_ImageUpload_Server
     */
    protected $_server;


    /**
     * @var Lib_Asset_ImageUpload_Converter_ImageMagick
     */
    protected $_converter;


    /**
     * @var App_Manager_ImageUpload
     */
    private static $_instance;

    /**
     * @override
     * @static
     * @return App_Manager_ImageUpload
     */
    public static function getInstance()
    {
        if ((self::$_instance instanceof self)!==true) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++


    /**
     * @return App_Asset_ImageUpload_Server
     */
    public function getServer()
    {
        if (($this->_server instanceof App_Asset_ImageUpload_Server)
            !==true) {

            $instance = new App_Asset_ImageUpload_Server();
            $instance->init();
            $this->_server = $instance;
        }

        return $this->_server;
    }

    /**
     * @return Lib_Asset_ImageUpload_Converter_ImageMagick
     */
    public function getConverter()
    {
        if (($this->_converter instanceof
             Lib_Asset_ImageUpload_Converter_ImageMagick)!==true) {
            $this->_converter =
                    new Lib_Asset_ImageUpload_Converter_ImageMagick();
        }
        return $this->_converter;
    }


    // ++++++++++++++++++++++++++++++++++++++++++++++++++++

    /**
     * @param  $fileKey
     * @return array
     */
     public function newProcessUploadInfoUpload($fileKey)
     {
        $server = $this->getServer();
        $uploadInfo = $server->newProcessUploadInfoUpload($fileKey);
        return $uploadInfo;
     }

    /**
     * @param  string $data
     * @return array
     */
    public function newProcessUploadInfoSave($data)
    {
       $server = $this->getServer();
       $uploadInfo = $server->newProcessUploadInfoSave($data);
       return $uploadInfo;
    }


    /**
     * @throws Exception
     * @param  array $uploadInfo
     * @param  int|string $personId
     * @return array
     */
    public function processUploadInfo($uploadInfo, $personId)
    {
        $application = $this->getApplication();

        $profiler = $application->profileMethodStart(__METHOD__);

        $server = $this->getServer();

        if ($this->isValidId($personId)!==true) {
            throw new Exception("Invalid personId at ".__METHOD__);
        }


        $server->validateProcessUploadInfo($uploadInfo);

        // everything is fine? create asset id

        $dbClient = $this->getApplication()->getDbClient();
        $currentDate = $this->getApplication()->getCurrentDate();
        $dbTableAsset = "PersonImageUpload";
        $rowInsert = array(
            "personId" => $personId,
            "server" => get_class($server),
            "created" => $currentDate,
        );


        $id = null;
        try {
            $dbClient->beginTransaction();
            $id = $dbClient->insert($dbTableAsset, $rowInsert, true);
            $result = $server->processUploadInfoByAssetId($uploadInfo, $id);

            $folder = $result["folder"];
            $rowUpdate = array(
                "folder" => $folder,
            );
            $where = "id=:id;";
            $params = array(
                "id" => $id,
            );
            $dbClient->update($dbTableAsset, $rowUpdate, $where, $params);

            $dbClient->commitTransaction();


        } catch (Exception $e) {
            $dbClient->rollbackTransaction();
            throw $e;
        }


        // convert
/*
        $this->convertAssetToFormat(
            $id, Lib_Asset_ImageUpload_Server::FORMAT_MEDIUM
        );
//        return $result;
*/

        // convert into all formats defined
        $this->convertAsset(
            $id
        );

        $profiler->stop();

        //$result["profiler"] = $profiler->toArray();
        return $result;

    }






    /**
     * @throws Exception
     * @param  string|int $id
     * @param  string $format
     * @return void
     */
    public function convertAssetToFormat($id, $format)
    {

        $server = $this->getServer();
        $converter = $this->getConverter();

        if ($server->isValidAssetId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }

        if ($server->isValidAssetFormat($format)!==true) {
            throw new Exception("Invalid parameter 'format' at ".__METHOD__);
        }

        if ($server->isAssetFormatOrig($format)) {
            throw new Exception(
                "Invalid parameter 'format' at ".__METHOD__
                ." details: orig format must not be used as a convert target"
            );
        }

        $formatConfig = $server->getAssetFormatConfigByFormat($format);
        if (($formatConfig instanceof Zend_Config)!==true) {
            throw new Exception("No config for format at ".__METHOD__);
        }


        $sourceFileInfo = $server->newFileInfoUploadByAssetId(
            $id, Lib_Asset_ImageUpload_Server::FORMAT_ORIG
        );
        $targetFileInfo = $server->newFileInfoUploadByAssetId(
            $id, $format
        );

        /**
         * @var Zend_Config $formatConfig
         */


        $converterConfig = $formatConfig->converter;
        if (($converterConfig instanceof Zend_Config)!==true) {
            throw new Exception("Invalid config.converter at ".__METHOD__);
        }
        /**
         * @var Zend_Config $converterConfig
         */

        $configAsArray = $converterConfig->toArray();
        $configAsArray["width"] = $formatConfig->width;
        $configAsArray["height"] = $formatConfig->height;
        $configAsArray["sourcePath"] = $sourceFileInfo->getPathName();
        $configAsArray["targetPath"] = $targetFileInfo->getPathname();

        $converter->overrideConfig($configAsArray);
               

        $converter->validateSourcePath();
        $converter->validateTargetPath();


        $cmd = $converter->getConvertShellCommand();
        //var_dump($cmd);
        $converter->convert();

        

    }


    /**
     * @throws Exception
     * @param  string|int $id
     * @return void
     */
    public function convertAsset($id)
    {
        $server = $this->getServer();

        $application = $this->getApplication();
        $profiler = $application->profileMethodStart(__METHOD__);

        if ($server->isValidAssetId($id)!==true) {
            throw new Exception("Invalid parameter 'id' at ".__METHOD__);
        }

        $formatNames = $server->getAssetFormatsAvailable();
        foreach($formatNames as $format) {
            if ($server->isAssetFormatOrig($format)) {
                // since the orig format is converter source,
                // we can't us it as a converter target
                continue;
            }

            $this->convertAssetToFormat($id, $format);

        }

        $profiler->stop();

    }


}
