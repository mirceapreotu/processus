<?php
/**
 * Lib_Math_GeoHashBox Class
 *
 * Based on:
 *
 * @package     Lib_Geo
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license
 * @version     $Id$
 *
 */

/**
 * Lib_Geo_GeoHashBox
 *
 * @package     Lib_Geo
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id$
 *
 */
class Lib_Geo_GeoHashBox
{

    /**
     * @var string
     */
    protected $_geohash = '';

    /**
     * @var Lib_Geo_LatLon
     */
    protected $_box;

    /**
     * @var Lib_Geo_LatLon
     */
    protected $_boxTopLeft;

    /**
     * @var Lib_Geo_LatLon
     */
    protected $_boxBottomRight;

    /**
     * @var array[Lib_Geo_LatLon]
     */
    protected $_corners;

    /**
     * @var Lib_Geo_LatLon
     */
    protected $_center;

    /**
     *
     */
	public function __construct($geohash)
	{
        $gh = new Lib_Geo_GeoHash();

  	    $this->_geohash = $geohash;
	    $this->_box = $gh->decode($geohash);

        $this->_corners = array();
        $this->_corners['topleft']
            = new Lib_Geo_LatLon(
                $this->_boxTopLeft->getLat(), $this->_boxTopLeft->getLon()
            );

        $this->_corners['topright']
            = new Lib_Geo_LatLon(
                $this->_boxBottomRight->getLat(), $this->_boxTopLeft->getLon()
            );

        $this->_corners['bottomright']
            = new Lib_Geo_LatLon(
                $this->_boxBottomRight->getLat(),
                $this->_boxBottomRight->getLon()
            );

        $this->_corners['bottomleft']
            = new Lib_Geo_LatLon(
                $this->_boxTopLeft->getLat(), $this->_boxBottomRight->getLon()
            );

        $this->_center
            = new Lib_Geo_LatLon(
                (
                    $this->_boxTopLeft->getLat()
                    + $this->_boxBottomRight->getLat()
                ) / 2,
                (
                    $this->_boxTopLeft->getLon()
                    + $this->_boxBottomRight->getLon()
                ) / 2
            );
	}


}
