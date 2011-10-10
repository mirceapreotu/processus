<?php
/**
 * Lib_Math_GeoHash Class
 *
 * Based on:
 *
 * Geohash generation class
 * http://blog.dixo.net/downloads/
 *
 * This file copyright (C) 2008 Paul Dixon (paul@elphin.com)
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @package     Lib_Geo
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id$
 *
 */

/**
 * Lib_Geo_GeoHash
 *
 * @package     Lib_Geo
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id$
 *
 */
class Lib_Geo_GeoHash
{

    /**
     * String length to box size (x,y):
     * 3
     * 4
     * 5 
     * 6
     */

    const T_EVEN    = 'even';
    const T_ODD     = 'odd';
    
    const DIR_TOP       = 'top';
    const DIR_BOTTOM    = 'bottom';
    const DIR_LEFT      = 'left';
    const DIR_RIGHT     = 'right';

    /**
     * @var string
     */
    private $_hash = null;

    /**
     * @var string
     */
	private $_coding = "0123456789bcdefghjkmnpqrstuvwxyz";

    /**
     * @var array|string
     */
	private $_codingMap = array();

    /**
     * @var array
     */
    private $_neighbors = array();

    /**
     * @var array
     */
    private $_borders = array();

    /**
     *
     */
	public function __construct($hash=null)
	{
        $this->_hash = $hash;

		//build map from encoding char to 0 padded bitfield
		for ($i=0; $i<32; $i++) {
			$this->_codingMap[substr($this->_coding, $i, 1)]
                = str_pad(decbin($i), 5, "0", STR_PAD_LEFT);
		}

        $this->_neighbors = array(
            self::DIR_RIGHT
                => array(self::T_EVEN => "bc01fg45238967deuvhjyznpkmstqrwx"),
            self::DIR_LEFT
                => array(self::T_EVEN => "238967debc01fg45kmstqrwxuvhjyznp"),
            self::DIR_TOP
                => array(self::T_EVEN => "p0r21436x8zb9dcf5h7kjnmqesgutwvy"),
            self::DIR_BOTTOM
                => array(self::T_EVEN => "14365h7k9dcfesgujnmqp0r2twvyx8zb")
        );
        $this->_borders = array(
            self::DIR_RIGHT     => array(self::T_EVEN => "bcfguvyz"),
            self::DIR_LEFT      => array(self::T_EVEN => "0145hjnp"),
            self::DIR_TOP       => array(self::T_EVEN => "prxz"),
            self::DIR_BOTTOM    => array(self::T_EVEN => "028b")
        );

        $this->_neighbors[self::DIR_BOTTOM][self::T_ODD]
            = $this->_neighbors[self::DIR_LEFT][self::T_EVEN];

        $this->_neighbors[self::DIR_TOP]   [self::T_ODD]
            = $this->_neighbors[self::DIR_RIGHT][self::T_EVEN];

        $this->_neighbors[self::DIR_LEFT]  [self::T_ODD]
            = $this->_neighbors[self::DIR_BOTTOM][self::T_EVEN];

        $this->_neighbors[self::DIR_RIGHT] [self::T_ODD]
            = $this->_neighbors[self::DIR_TOP][self::T_EVEN];

        $this->_borders[self::DIR_BOTTOM]  [self::T_ODD]
            = $this->_borders[self::DIR_LEFT][self::T_EVEN];

        $this->_borders[self::DIR_TOP]     [self::T_ODD]
            = $this->_borders[self::DIR_RIGHT][self::T_EVEN];

        $this->_borders[self::DIR_LEFT]    [self::T_ODD]
            = $this->_borders[self::DIR_BOTTOM][self::T_EVEN];

        $this->_borders[self::DIR_RIGHT]   [self::T_ODD]
            = $this->_borders[self::DIR_TOP][self::T_EVEN];
	}

    /**
     * @return null|string
     */
    public function __toString()
    {
        return $this->_hash;
    }

    /**
     * Decode a geohash and return an array with decimal lat,long in it
     *
     * @param string $hash
     * @return Lib_Geo_LatLon
     */
	public function decode($hash=null)
	{

        if ($hash === null) {

            $hash = $this->_hash; // use property of object if not specified
        }

		//decode hash into binary string
		$binary="";
		$hl=strlen($hash);
		for ($i=0; $i<$hl; $i++) {
			$binary.=$this->_codingMap[substr($hash, $i, 1)];
		}

		//split the binary into lat and log binary strings
		$bl=strlen($binary);
		$blat="";
		$blong="";
		for ($i=0; $i<$bl; $i++) {
			if ($i%2)
				$blat=$blat.substr($binary, $i, 1);
			else
				$blong=$blong.substr($binary, $i, 1);

		}

		//now concert to decimal
		$lat=$this->_binDecode($blat, -90, 90);
		$long=$this->_binDecode($blong, -180, 180);

		//figure out how precise the bit count makes this calculation
		$latErr=$this->_calcError(strlen($blat), -90, 90);
		$longErr=$this->_calcError(strlen($blong), -180, 180);

		//how many decimal places should we use? There's a little art to
		//this to ensure I get the same roundings as geohash.org
		$latPlaces=max(1, -round(log10($latErr))) - 1;
		$longPlaces=max(1, -round(log10($longErr))) - 1;

		//round it
		$lat=round($lat, $latPlaces);
		$long=round($long, $longPlaces);

        $latLon = new Lib_Geo_LatLon($lat, $long);

		return $latLon;
	}

    /**
     * Encode a hash from given lat and long
     * @param Lib_Geo_LatLon $latlon
     * @param int $minBits minimum bits to use for encoding (precision)
     * @return string
     */
	public function encode(Lib_Geo_LatLon $latlon, $minBits = 0)
	{

        $lat = $latlon->getLat();
        $long = $latlon->getLon();

		//how many bits does latitude need?
		$plat=$this->_precision($lat);
		$latbits=1;
		$err=45;

		while ($err>$plat) {

			$latbits++;
			$err/=2;
		}

		//how many bits does longitude need?
		$plong=$this->_precision($long);
		$longbits=1;
		$err=90;
		while ($err>$plong) {
			$longbits++;
			$err/=2;
		}

		// bit counts need to be equal
		$bits=max($latbits, $longbits);

        // minimum bitsize forced?
        $bits=max($bits, $minBits);

		//as the hash create bits in groups of 5, lets not
		//waste any bits - lets bulk it up to a multiple of 5
		//and favour the longitude for any odd bits
		$longbits=$bits;
		$latbits=$bits;
		$addlong=1;
		while (($longbits+$latbits)%5 != 0) {
			$longbits+=$addlong;
			$latbits+=!$addlong;
			$addlong=!$addlong;
		}


		//encode each as binary string
		$blat   = $this->_binEncode($lat, -90, 90, $latbits);
		$blong  = $this->_binEncode($long, -180, 180, $longbits);

		//merge lat and long together
		$binary="";
		$uselong=1;
		while (strlen($blat)+strlen($blong)) {

			if ($uselong) {

				$binary=$binary.substr($blong, 0, 1);
				$blong=substr($blong, 1);

			} else {
				$binary=$binary.substr($blat, 0, 1);
				$blat=substr($blat, 1);
			}
			$uselong=!$uselong;
		}

		//convert binary string to hash
		$hash="";
		for ($i=0; $i<strlen($binary); $i+=5) {
			$n=bindec(substr($binary, $i, 5));
			$hash=$hash.$this->_coding[$n];
		}


		return $hash;
	}

    /**
     * Returns an array of geohash objects, including and surrounding $this
     * 
     * @return array[Lib_Geo_GeoHash]
     */
    public function listAdjacent()
    {
        $top         = new Lib_Geo_GeoHash(
            $this->_calculateAdjacent((string)$this, self::DIR_TOP)
        );
        $bottom      = new Lib_Geo_GeoHash(
            $this->_calculateAdjacent((string)$this, self::DIR_BOTTOM)
        );
        $left        = new Lib_Geo_GeoHash(
            $this->_calculateAdjacent((string)$this, self::DIR_LEFT)
        );
        $right       = new Lib_Geo_GeoHash(
            $this->_calculateAdjacent((string)$this, self::DIR_RIGHT)
        );

        $topLeft     = new Lib_Geo_GeoHash(
            $this->_calculateAdjacent((string)$left, self::DIR_TOP)
        );
        $bottomLeft  = new Lib_Geo_GeoHash(
            $this->_calculateAdjacent((string)$left, self::DIR_BOTTOM)
        );
        $topRight    = new Lib_Geo_GeoHash(
            $this->_calculateAdjacent((string)$right, self::DIR_TOP)
        );
        $bottomRight = new Lib_Geo_GeoHash(
            $this->_calculateAdjacent((string)$right, self::DIR_BOTTOM)
        );

        $adjacent = array(
            $this, $left, $right, $top, $bottom,
            $topLeft, $topRight, $bottomLeft, $bottomRight
        );

        return $adjacent;
    }

    /**
     * @param string $srcHash
     * @param string $dir
     * @return string
     */
    protected function _calculateAdjacent($srcHash, $dir)
    {

        $srcHash = strtolower($srcHash);
        $lastChr = substr($srcHash, -1, 1); // .charAt(srcHash.length-1);
        $type = (strlen($srcHash) % 2) ? self::T_ODD : self::T_EVEN;
        $base = substr($srcHash, 0, strlen($srcHash)-1);
        if (strpos($this->_borders[$dir][$type], $lastChr) !== false) {
            $base = $this->_calculateAdjacent($base, $dir);
        }
        return
            $base
            . substr(
                $this->_coding,
                strpos($this->_neighbors[$dir][$type], $lastChr),
                1
            );
    }


    /**
     * What's the maximum error for $bits bits covering a range $min to $max
     * @param int $bits
     * @param  $min
     * @param  $max
     * @return int
     */
	private function _calcError($bits, $min, $max)
	{
		$err=($max-$min)/2;
		while ($bits--)
			$err/=2;
		return $err;
	}

    /**
     * returns precision of number
     * precision of 42 is 0.5
     * precision of 42.4 is 0.05
     * precision of 42.41 is 0.005 etc
     *
     * @param float $number
     * @return float
     */
	private function _precision($number)
	{
		$precision=0;
		$pt=strpos($number, '.');
		if ($pt!==false) {
			$precision=-(strlen($number)-$pt-1);
		}

		return pow(10, $precision)/2;
	}

    /**
     * create binary encoding of number as detailed in
     * http://en.wikipedia.org/wiki/Geohash#Example
     * removing the tail recursion is left an exercise for the reader
     * @param  $number
     * @param  $min
     * @param  $max
     * @param int $bitcount
     * @return string
     */
	private function _binEncode($number, $min, $max, $bitcount)
	{
		if ($bitcount==0)
			return "";

		#echo "$bitcount: $min $max<br>";

		//this is our mid point - we will produce a bit to say
		//whether $number is above or below this mid point
		$mid=($min+$max)/2;
		if ($number>$mid)
			return "1".$this->_binEncode($number, $mid, $max, $bitcount-1);
		else
			return "0".$this->_binEncode($number, $min, $mid, $bitcount-1);
	}

    /**
     * decodes binary encoding of number as detailed in
     * http://en.wikipedia.org/wiki/Geohash#Example
     * removing the tail recursion is left an exercise for the reader
     *
     * @param  $binary
     * @param  $min
     * @param  $max
     * @return
     */
	private function _binDecode($binary, $min, $max)
	{
		$mid=($min+$max)/2;

		if (strlen($binary)==0)
			return $mid;

		$bit=substr($binary, 0, 1);
		$binary=substr($binary, 1);

		if ($bit==1)
			return $this->_binDecode($binary, $mid, $max);
		else
			return $this->_binDecode($binary, $min, $mid);
	}

}
