<?php
/**
 * App_Manager_Fb_ImageUpload Class
 *
 * @category	meetidaaa.com
 * @package		App_Manager_Fb
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

/**
 * App_Manager_Fb_ImageUpload
 *
 * @category	meetidaaa.com
 * @package		App_Manager_Fb
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

class App_Manager_Fb_ImageUpload extends App_Manager_ImageUpload
{





 


    // ++++++++++++++++++ override +++++++++++++++++++++++++++

    /**
     * @var App_Manager_Fb_ImageUpload
     */
    private static $_instance;

    /**
     * @override
     * @static
     * @return App_Manager_Fb_ImageUpload
     */
    public static function getInstance()
    {
        if ((self::$_instance instanceof self)!==true) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @return App_Facebook_Application
     */
    public function getApplication()
    {
        return App_Facebook_Application::getInstance();
    }

     /**
     * @return Lib_Facebook_Facebook
     */
    public function getFacebook()
    {
        return $this->getApplication()->getFacebook();
    }

    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++


    /**
     * @override
     * @return App_Facebook_Asset_ImageUpload_Server
     */
    public function getServer()
    {
        if (($this->_server instanceof App_Facebook_Asset_ImageUpload_Server)
            !==true) {

            $instance = new App_Facebook_Asset_ImageUpload_Server();
            $instance->init();
            $this->_server = $instance;
        }

        return $this->_server;
    }




    // ++++++++++++++++++++ facebook ++++++++++++++++++++++++++++++

    /**
     * @throws Exception|FacebookApiException
     *
     * requires permissions: publish_stream (or user_photos ?)
     *
     * @param SplFileInfo $fileInfo
     * @param null|string $message
     * @param null|array $additionalParameters
     * @return array
     */
    public function uploadAsPhotoToFacebook(
        SplFileInfo $fileInfo,
        $message=null,
        $additionalParameters=null

        // requires permissions: publish_stream (or user_photos ?)
    )
    {

        $application = $this->getApplication();
        $profiler = $application->profileMethodStart(__METHOD__);

        $result = array(
            "photo" => null,
        );

        $facebook = $application->getFacebook();

        // requires permission user_photos or publish_stream
        $apiPhoto = $facebook->uploadMePhoto(
            $fileInfo, $message, $additionalParameters
        );

        if (is_array($apiPhoto)!==true) {
            throw new Exception("Method returns invalid result at ".__METHOD__);
        }

        $apiPhotoId = $apiPhoto["id"];
        if ($apiPhotoId === null) {
            throw new Exception("Invalid photo.id at ".__METHOD__);
        }

        $result["photo"] = $apiPhoto;

        $profiler->stop();
        return $result;
    }

    /**
     * @throws Exception|FacebookApiException
     *
     * requires permissions: user_photos,publish_stream
     *
     * @param SplFileInfo $fileInfo
     * @param  bool $setAsProfilePicture
     * @param null|string $message
     * @param null|array $additionalParameters
     * @return array
     */
    public function uploadAsPhotoToFacebookAndFetchMetaData(
        SplFileInfo $fileInfo,
        $setAsProfilePicture,
        $message=null,
        $additionalParameters=null

        // requires permissions: user_photos,publish_stream
    )
    {
        if (is_bool($setAsProfilePicture)!==true) {
            throw new Exception(
                "Invalid parameter 'setAsProfilePicture' at ".__METHOD__
            );
        }

        $application = $this->getApplication();
        $profiler = $application->profileMethodStart(__METHOD__);

        $result = array(
            "photo" => null,
        );

        $facebook = $application->getFacebook();

        // requires permission user_photos or publish_stream
        $apiResult = $facebook->uploadMePhoto(
            $fileInfo, $message, $additionalParameters
        );
        $apiPhotoId = $apiResult["id"];
        if ($apiPhotoId === null) {
            throw new Exception("Invalid photo.id at ".__METHOD__);
        }

        // requires permission user_photos
        $apiPhoto = $facebook->api("/".$apiPhotoId, 'GET');
        if ($setAsProfilePicture === true) {
            $apiPhotoLink = Lib_Utils_Array::getProperty($apiPhoto, "link");
            if (Lib_Utils_String::isEmpty($apiPhotoLink)!==true) {
                // force set as profile picture dialog
                $apiPhotoLink .= "&makeprofile=1";
                $apiPhoto["link"] = $apiPhotoLink;
            }
        }

        $result["photo"] = $apiPhoto;

        $profiler->stop();
        return $result;
    }



    /**
     * @param  int|string $userId
     * @param null|int $x
     * @param null|int $y
     * @return array
     */
    public function newFacebookPhotoTag(
        // NOTICE fanpageId's and appId's are not supported yet by facebook api
        $userId,
        $x=null,
        $y=null
    )
    {
        $tag = array(
            'tag_uid' => $userId,
            'x' => $x,
            'y' => $y
        );

        if ($x === null) {
            unset($tag["x"]);
        }
        if ($y === null) {
            unset($tag["y"]);
        }
        return $tag;
    }


        /**
     * @throws Exception|FacebookApiException
     *
     * requires permissions: user_photos,publish_stream
     *
     * @param SplFileInfo $fileInfo
     * @param  bool $setAsProfilePicture
     * @param null|string $message
     * @param null|array $additionalParameters
     * @param null|array $tags
     * @return array
     */
    public function uploadAsPhotoToFacebookAndAddTagsAndFetchMetaData(
        SplFileInfo $fileInfo,
        $setAsProfilePicture,
        $message=null,
        $additionalParameters=null,
        $tags = null

        // requires permissions: user_photos,publish_stream
    )
    {
        if (is_bool($setAsProfilePicture)!==true) {
            throw new Exception(
                "Invalid parameter 'setAsProfilePicture' at ".__METHOD__
            );
        }

        if ((
                    ($additionalParameters===null)
                    ||(is_array($additionalParameters))
            )!==true) {
            throw new Exception(
                            "Invalid parameter 'additionalParameters' at "
                            .__METHOD__
                        );
        }


        if ($tags!==null) {

            if (is_array($tags)!==true) {
                throw new Exception("Invalid parameter 'tags' at ".__METHOD__);
            }

            if (count($tags)>0) {

                $additionalParameters = (array)$additionalParameters;
                $additionalParameters["tags"] = $tags;
            }

        }

        $application = $this->getApplication();
        $profiler = $application->profileMethodStart(__METHOD__);

        $result = array(
            "photo" => null,
        );

        $facebook = $application->getFacebook();

        // requires permission user_photos or publish_stream
        $apiResult = $facebook->uploadMePhoto(
            $fileInfo, $message, $additionalParameters
        );
        $apiPhotoId = $apiResult["id"];
        if ($apiPhotoId === null) {
            throw new Exception("Invalid photo.id at ".__METHOD__);
        }

        // requires permission user_photos
        $apiPhoto = $facebook->api("/".$apiPhotoId, 'GET');
        if ($setAsProfilePicture === true) {
            $apiPhotoLink = Lib_Utils_Array::getProperty($apiPhoto, "link");
            if (Lib_Utils_String::isEmpty($apiPhotoLink)!==true) {
                // force set as profile picture dialog
                $apiPhotoLink .= "&makeprofile=1";
                $apiPhoto["link"] = $apiPhotoLink;
            }
        }

        $result["photo"] = $apiPhoto;

        $profiler->stop();
        return $result;
    }


        /**
     * @throws Exception|FacebookApiException
     *
     * requires permissions: user_photos,publish_stream
     *
     * @param SplFileInfo $fileInfo
     * @param  int|string $albumId
     * @param  bool $setAsProfilePicture
     * @param null|string $message
     * @param null|array $additionalParameters
     * @param null|array $tags
     * @return array
     */
    public function uploadAsPhotoToFacebookAlbumAndAddTagsAndFetchMetaData(
        SplFileInfo $fileInfo,
        $albumId,
        $setAsProfilePicture,
        $message=null,
        $additionalParameters=null,
        $tags = null

        // requires permissions: user_photos,publish_stream
    )
    {

        if ($this->isValidId($albumId)!==true) {
            throw new Exception("Invalid parameter 'albumId' at ".__METHOD__);
        }


        if (is_bool($setAsProfilePicture)!==true) {
            throw new Exception(
                "Invalid parameter 'setAsProfilePicture' at ".__METHOD__
            );
        }

        if ((
                    ($additionalParameters===null)
                    ||(is_array($additionalParameters))
            )!==true) {
            throw new Exception(
                            "Invalid parameter 'additionalParameters' at "
                            .__METHOD__
                        );
        }


        if ($tags!==null) {

            if (is_array($tags)!==true) {
                throw new Exception("Invalid parameter 'tags' at ".__METHOD__);
            }

            if (count($tags)>0) {

                $additionalParameters = (array)$additionalParameters;
                $additionalParameters["tags"] = $tags;
            }

        }

        $application = $this->getApplication();
        $profiler = $application->profileMethodStart(__METHOD__);

        $result = array(
            "photo" => null,
        );

        $facebook = $application->getFacebook();

        // requires permission user_photos or publish_stream
        $apiResult = $facebook->uploadAlbumPhoto(
            $fileInfo, $albumId, $message, $additionalParameters
        );
        $apiPhotoId = $apiResult["id"];
        if ($apiPhotoId === null) {
            throw new Exception("Invalid photo.id at ".__METHOD__);
        }

        // requires permission user_photos
        $apiPhoto = $facebook->api("/".$apiPhotoId, 'GET');
        if ($setAsProfilePicture === true) {
            $apiPhotoLink = Lib_Utils_Array::getProperty($apiPhoto, "link");
            if (Lib_Utils_String::isEmpty($apiPhotoLink)!==true) {
                // force set as profile picture dialog
                $apiPhotoLink .= "&makeprofile=1";
                $apiPhoto["link"] = $apiPhotoLink;
            }
        }

        $result["photo"] = $apiPhoto;

        $profiler->stop();
        return $result;
    }



    /**
     * @param string $name
     * @param null|string $description
     * @return string|int
     */
    public function ensureFacebookUserPhotoAlbum(
        $name , $description = null
    )
    {

        if (Lib_Utils_String::isEmpty($name)) {
            throw new Exception("Invalid parameter 'name' at ".__METHOD__);
        }

        $name = trim($name);

        $album = array(
          'name' => $name,
          'message' => $description
        );

        if ($description === null) {
            unset($description);
        }


        $facebook = $this->getFacebook();

        $albumId = null;

        // check if album is available
        $albums = $facebook->api("/me/albums");
        foreach ($albums["data"] as $oldAlbum) {

            //var_dump($oldAlbum);

            $oldAlbumName = trim($oldAlbum["name"]);
            if($oldAlbumName === $name){
                $albumId = $oldAlbum["id"];
                break;
            }
        }

        if ($albumId === null) {
            // album is not available , create album
            $albumResult = $facebook->apiPOST('/me/albums', $album);
            $albumId = $albumResult["id"];
        }


        return $albumId;
    }

    



    
}
