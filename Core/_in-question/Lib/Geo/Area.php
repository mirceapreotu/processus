<?php
/**
 * Lib_Geo_Area Class
 *
 * @package     Lib_Geo
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id$
 *
 */

/**
 * Lib_Geo_Area
 *
 * @package     Lib_Geo
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id$
 *
 */
class Lib_Geo_Area
{

    /**
     * Area types
     */
    const T_UNKNOWN = 0;
    const T_BOX     = 1;
    const T_CIRCLE  = 2;

    /**
     * @var Lib_Geo_LatLon
     */
    protected $_boxTopLeft;

    /**
     * @var Lib_Geo_LatLon
     */
    protected $_boxBottomRight;

    /**
     * @var Lib_Geo_LatLon
     */
    protected $_center = null;

    /**
     * Radius in meters
     * @var float
     */
    protected $_radius = null;

    /**
     * One of self::T_*
     * @var int
     */
    protected $_type = self::T_UNKNOWN;

    /**
     *
     */
	public function __construct()
    {
	}

    /**
     * @param Lib_Geo_LatLon $topLeft
     * @param Lib_Geo_LatLon $bottomRight
     * @return void
     */
    public function setBox(
        Lib_Geo_LatLon $topLeft, Lib_Geo_LatLon $bottomRight
    )
    {
        $this->_boxTopLeft = $topLeft;
        $this->_boxBottomRight = $bottomRight;
        $this->setType(self::T_BOX);
    }

    /**
     * @param Lib_Geo_LatLon $center
     * @param float $radius
     * @return void
     */
    public function setCircle(
        Lib_Geo_LatLon $center, $radius
    )
    {
        $this->_center = $center;
        $this->_radius = $radius;
        $this->setType(self::T_CIRCLE);
    }

    /**
     * @return Lib_Geo_LatLon
     */
    public function getTopLeft()
    {
        return $this->_boxTopLeft;
    }

    /**
     * @return Lib_Geo_LatLon
     */
    public function getBottomRight()
    {
        return $this->_boxBottomRight;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param int $type One of self::T_*
     * @return void
     */
    public function setType($type)
    {
        $this->_type = $type;
    }

    /**
     * @return float
     */
    public function getRadius()
    {
        return $this->_radius;
    }

    /**
     * @param float $radius
     * @return void
     */
    public function setRadius(float $radius)
    {
        $this->_radius = $radius;
    }

    /**
     * @return Lib_Geo_LatLon|null
     */
    public function getCenter()
    {
        return $this->_center;
    }

    /**
     * @param Lib_Geo_LatLon $center
     * @return void
     */
    public function setCenter(Lib_Geo_LatLon $center)
    {
        $this->_center = $center;
    }

}
