<?php
/**
 * Lib_Geo_LatLon Class
 *
 * @package     Lib_Geo
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id$
 *
 */

/**
 * Lib_Geo_LatLon
 *
 * Represents a geo coordinate.
 *
 * @package     Lib_Geo
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id$
 *
 */
class Lib_Geo_LatLon
{

	/**
	 * @var float
	 */
	protected $_lat;

    /**
     * @var float
     */
    protected $_lon;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->_lat." ".$this->_lon;
    }

    /**
     * @param float $lat
     * @param float $lon
     */
    public function __construct($lat=0.0, $lon=0.0)
    {
        $this->_lat = $lat;
        $this->_lon = $lon;
    }

    /**
     * @return float
     */
    public function getLat()
    {
        return $this->_lat;
    }

    /**
     * @param float $lat
     * @return void
     */
    public function setLat($lat)
    {
        $this->_lat = $lat;
    }

    /**
     * @return float
     */
    public function getLon()
    {
        return $this->_lon;
    }

    /**
     * @param float $lon
     * @return void
     */
    public function setLon($lon)
    {
        $this->_lon = $lon;
    }

    /**
     * Compute distance to specified geo location in kilometers
     * 
     * @param Lib_Geo_LatLon $destination
     * @return float
     */
    public function getDistanceTo(Lib_Geo_LatLon $destination)
    {
        $lat1 = $this->getLat();
        $lon1 = $this->getLon();

        $lat2 = $destination->getLat();
        $lon2 = $destination->getLon();
        $distance = (
            3958 * pi() * sqrt(
                ($lat2 - $lat1) * ($lat2 - $lat1)
                + cos($lat2 / 57.29578)
                * cos($lat1 / 57.29578)
                * ($lon2 - $lon1)
                * ($lon2 - $lon1)
            ) / 180
        );
        return $distance;
    }
}
