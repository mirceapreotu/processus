<?php
/**
 * Lib_Utils_Date
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Utils
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Utils_Date
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Utils
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_Utils_Date
{

    const T_DAY     = 'd';
    const T_MONTH   = 'm';
    const T_YEAR    = 'Y';
    const T_HOUR    =  'H';
    const T_MINUTE  = 'i';
    const T_SECOND  = 's';

	/**
	 * @static
	 * @return string
	 */
	public static function getDefaultFormat()
	{
		return 'Y-m-d';
	}

	/**
     * checks, if a given string is a valid date
     *
	 * @static
	 * @param string $string
     * @param string|null $format
	 * @return bool
	 */
	public static function exists($string, $format = null)
	{

        try {

            $dateObject = new DateTime($string);
            if (Lib_Utils_String::isEmpty($format)) {
                $format = self::getDefaultFormat();
            }

            if (
                $dateObject->format($format)
                === $string
            ) {

                return true;

            } else {

                return false;
            }

        } catch (Exception $exception) {

            return false;
        }
	}

	/**
	 * @static
     * @param string $string
	 * @return string|null
	 */
	public static function toString($string)
	{

		if (!self::exists($string)) {
            return null;
        }

        try {

            $dateObject = new DateTime($string);

            return $dateObject->format(self::getDefaultFormat());

        } catch (Exception $exception) {

            return null;
        }
	}


	/**
     * returns the full year e.g. 2011
     *
	 * @static
	 * @param string $string
	 * @return int|null
	 */
	public static function getFullYear($string)
	{
        try {

            if (!self::exists($string)) {
                return null;
            }

            $dateObject = new DateTime($string);
            return (int)$dateObject->format(self::T_YEAR);

        } catch (Exception $exception) {

            return null;
        }
	}

	/**
	 * @static
	 * @param string $string
	 * @return int|null
	 */
	public static function getMonth($string)
	{
        try {

            if (!self::exists($string)) {
                return null;
            }
            $dateObject = new DateTime($string);
            return (int)$dateObject->format(self::T_MONTH);

        } catch (Exception $exception) {

            return null;
        }
	}

	/**
	 * @static
	 * @param string $string
	 * @return null|int
	 */
	public static function getDay($string)
	{
        try {

            if (!self::exists($string)) {
                return null;
            }
            $dateObject = new DateTime($string);
            return (int)$dateObject->format(self::T_DAY);

        } catch (Exception $exception) {

            return null;
        }
	}


	/**
	 * @static
	 * @param  $string
	 * @return null|int
	 */
	public static function getHours($string)
    {
        try {

            if (!self::exists($string)) {
                return null;
            }
            $dateObject = new DateTime($string);
            return (int)$dateObject->format(self::T_HOUR);

        } catch (Exception $exception) {

            return null;
        }
    }

	/**
	 * @static
	 * @param string $string
	 * @return null|int
	 */
	public static function getMinutes($string)
    {
        try {

            if (!self::exists($string)) {
                return null;
            }
            $dateObject = new DateTime($string);
            return (int)$dateObject->format(self::T_MINUTE);

        } catch (Exception $exception) {

            return null;
        }
    }

	/**
	 * @static
	 * @param string $string
	 * @return null|int
	 */
	public static function getSeconds($string)
	{
        try {

            if (!self::exists($string)) {
                return null;
            }
            $dateObject = new DateTime($string);
            return (int)$dateObject->format(self::T_SECOND);

        } catch (Exception $exception) {

            return null;
        }

	}

    /**
     * @static
     * @param string $date
     * @param string $now
     * @return int|null
     */
    public static function getAge($date, $now='now')
    {
        try {

            if (!self::exists($date)) {
                return null;
            }

            $birthDate = new DateTime($date);
            $nowDate = new DateTime($now);

            /* only in php 5.3!
            $timeDiff = $nowDate->diff($birthDate);
            $years = $timeDiff->y;
            */

            $years = (int)$nowDate->format(self::T_YEAR)
                - (int)$birthDate->format(self::T_YEAR);

            if (
                (int)$nowDate->format(self::T_MONTH . self::T_DAY)
                < (int)$birthDate->format(self::T_MONTH . self::T_DAY)
            ) {
                $years = $years - 1; // its too early in the year ..
            }

            return $years;

        } catch (Exception $exception) {

            return null;
        }
    }



    /**
     * @static
     * @param  string $date
     * @param int $hours
     * @return string
     */
    public static function addHours($date, $hours = 0)
    {
        $targetDate = strtotime($date) + ($hours * 3600);
        return date("Y-m-d H:i:s", $targetDate);
    }


    /**
     * @static
     * @param  string $date
     * @return string
     */
    public static function stripTime($date)
    {
        $today = date("y-m-d", strtotime($date));
        return $today;
    }


    /**
     * @static
     * @param string $date
     * @return array|null
     */
    public static function getAstroZodiacItem($date)
    {
        try {

            $dateObject = new DateTime($date);

            $day    = (int)$dateObject->format(self::T_DAY);
            $month  = (int)$dateObject->format(self::T_MONTH);

            $zodiacItem = Lib_Utils_AstroZodiac::getItemByDayAndMonth(
                $day, $month
            );
            return $zodiacItem;

        } catch (Exception $exception) {

            return null;
        }
    }
}
