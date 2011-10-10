<?php
/**
 * Lib_Facebook_OpenGraphEndpoints Class
 *
 * @package Lib_Facebook
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Facebook_OpenGraphEndpoints
 *
 *
 * @package Lib_Facebook
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
class Lib_Facebook_OpenGraphEndpoints
{
    const IMAGE_FORMAT_LARGE = "large";
    const IMAGE_FORMAT_MEDIUM = "medium";
    const IMAGE_FORMAT_SMALL = "small";

    // introspection of any object: add ?metadata=1
    // e.g. https://graph.facebook.com/331218348435?metadata=1

    /**
     * @var string
     */
    protected $_id;

    /**
     * @return string
     */
    public function getId()
    {
        return (string)$this->_id;
    }

    /**
     * @param  string $id
     * @return void
     */
    public function setId($id)
    {
        $this->_id = (string)$id;

    }

    /**
     * @return string
     */
    public function getSelf()
    {
        $id = $this->getId();
        return "https://graph.facebook.com/".$id."";
    }

    /**
     * @return string
     */
    public function getConnections()
    {
        $id = $this->getId();
        return "https://graph.facebook.com/".$id."?metadata=1";
    }

    /**
     * @param null $accessToken
     * @return string
     */
    public function getFriends($accessToken = null)
    {
        //* Friends: https://graph.facebook.com/me/friends
		// https://graph.facebook.com/me/friends?access_token=2227470867|2.KSf9uqYLD_qMaSDNkVgrSg__.3600.1285059600-1013680688|A3dSMpDGNAOWF4YpOtfn1yu09Fg
        $id = $this->getId();
        if (Lib_Utils_String::isEmpty($accessToken)) {
            return "https://graph.facebook.com/".$id."/friends";
        } else {
            return "https://graph.facebook.com/".$id."/friends?access_token="
                    . $accessToken;
        }

    }

    /**
     * @return string
     */
    public function getHome()
    {
        //* News feed: https://graph.facebook.com/me/home
        $id = $this->getId();
        return "https://graph.facebook.com/".$id."/home";
    }

    /**
     * @return string
     */
    public function getFeed()
    {
        //* Profile feed (Wall): https://graph.facebook.com/me/feed
        $id = $this->getId();
        return "https://graph.facebook.com/".$id."/feed";
    }

    /**
     * @return string
     */
    public function getLikes()
    {
        $id = $this->getId();
        return "https://graph.facebook.com/".$id."/likes";
    }

    /**
     * @return string
     */
    public function getMovies()
    {
        $id = $this->getId();
        return "https://graph.facebook.com/".$id."/movies";
    }

    /**
     * @return string
     */
    public function getBooks()
    {
        //* Movies: https://graph.facebook.com/me/movies
        $id = $this->getId();
        return "https://graph.facebook.com/".$id."/books";
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        $id = $this->getId();
        return "https://graph.facebook.com/".$id."/notes";
    }

    /**
     * @return string
     */
    public function getPhotos()
    {
        $id = $this->getId();
        return "https://graph.facebook.com/".$id."/photos";
    }
    /**
     * @return string
     */
    public function getVideos()
    {
        $id = $this->getId();
        return "https://graph.facebook.com/".$id."/videos";
    }
    /**
     * @return string
     */
    public function getEvents()
    {
        $id = $this->getId();
        return "https://graph.facebook.com/".$id."/events";
    }
    /**
     * @return string
     */
    public function getGroups()
    {
        $id = $this->getId();
        return "https://graph.facebook.com/".$id."/groups";
    }

    /**
     * @param null $imageFormat
     * @return string
     */
    public function getPicture($imageFormat = null)
    {
        // ?type=large
        $id = $this->getId();
        if (Lib_Utils_String::isEmpty($imageFormat)) {
            return "https://graph.facebook.com/".$id."/picture";
        }

        return "https://graph.facebook.com/".$id."/picture?type=".$imageFormat;
    }




	
}