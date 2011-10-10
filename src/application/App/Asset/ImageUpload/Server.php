<?php
/**
 * App_Asset_ImageUpload_Server
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_Asset_ImageUpload
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * App_Vz_Asset_ImageUpload_Server
 *
 *
 *
 * @category	meetidaaa.com
 * @package		App_Asset_ImageUpload
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
 
class App_Asset_ImageUpload_Server
    extends Lib_Asset_ImageUpload_Server
{

    /**
     * @override
     * @return App_Application
     */
    public function getApplication()
    {
        return App_Application::getInstance();
    }

    /**
     * @return Zend_Config
     */
    protected function getFormatsConfig()
    {
        $config = array(
            
            self::FORMAT_SMALL => array(
                "width" => 100,
                "height" => 100,
                "converter" => array(
                    "resizePolicy" =>
                        Lib_Asset_ImageUpload_Converter_ImageMagick::
                            RESIZE_POLICY_CROP_CENTER,
                ),
            ),
            self::FORMAT_MEDIUM => array(
                "width" => 200,
                "height" => 200,
                "converter" => array(
                    "resizePolicy" =>
                        Lib_Asset_ImageUpload_Converter_ImageMagick::
                            RESIZE_POLICY_CROP_CENTER,
                ),
            ),
            self::FORMAT_LARGE => array(
                "width" => 400,
                "height" => 400,
                "converter" => array(
                    "resizePolicy" =>
                        Lib_Asset_ImageUpload_Converter_ImageMagick::
                            RESIZE_POLICY_CROP_CENTER,
                ),
            ),
        );

        return new Zend_Config($config);
    }

    

    /**
     * @override
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




}
