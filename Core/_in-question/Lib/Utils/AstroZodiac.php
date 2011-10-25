<?php
/**
 * Lib_Utils_AstroZodiac
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
 * Lib_Utils_AstroZodiac
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
class Lib_Utils_AstroZodiac
{

    /**
     * @var array
     */
    protected static $_dataProvider = array(
        array(
                "id" => 1,
                "name" => "Capricorn", //Steinbock
                "name_de" => "Steinbock",
            ),
        array(
                "id" => 2,
                "name" => "Aquarius", // Wassermann
                "name_de" => "Wassermann",
            ),
        array(
                "id" => 3,
                "name" => "Pisces", // Fische
                "name_de" => "Fische",
            ),
        array(
                "id" => 4,
                "name" => "Aries",//Widder
                "name_de" => "Widder",
            ),
        array(
                "id" => 5,
                "name" => "Taurus",//Stier
                "name_de" => "Stier",
            ),
        array(
                "id" => 6,
                "name" => "Gemini", //Zwillinge
                "name_de" => "Zwillinge",
            ),
        array(
                "id" => 7,
                "name" => "Cancer", //Krebs
                "name_de" => "Krebs",
            ),
        array(
                "id" => 8,
                "name" => "Leo", //Löwe
                "name_de" => "Löwe",
            ),
        array(
                "id" => 9,
                "name" => "Virgo", //Jungfrau
                "name_de" => "Jungfrau",
            ),
        array(
                "id" => 10,
                "name" => "Libra", //Waage
                "name_de" => "Waage",
            ),
        array(
                "id" => 11,
                "name" => "Scorpio", //Skorpion
                "name_de"=>"Skorpion",
            ),
        array(
                "id" => 12,
                "name" => "Sagittarius", // Schütze
                "name_de" => "Schütze"
            ),
    );

    /**
     * @static
     * @return array
     */
    public static function getDataProvider()
    {
        return self::$_dataProvider;
    }

    /**
     * @static
     * @return array
     */
    public static function getItemList()
    {
        return self::getDataProvider();
    }

    /**
     * @var array
     */
    protected static $_dictionary;

    /**
     * @static
     * @throws Exception
     * @return array
     */
    public static function getDictionary()
    {
        if (Lib_Utils_Array::isEmpty(self::$_dictionary)!==true) {
            return (array)self::$_dictionary;
        }
        $dataProvider = self::getDataProvider();

        $dict = Lib_Utils_Array::groupBy($dataProvider, "id");

        $_dict = array();
        foreach ($dict as $id => $items) {
            $firstItem = Lib_Utils_Array::getProperty($items, 0, true);
            
            $firstItemId = Lib_Utils_Array::getProperty($firstItem, "id", true);
            if (is_int($firstItemId) !==true) {
                $message = "Invalid model at ".__METHOD__;
                throw new Exception($message);
            }
            $_dict[$firstItemId] = $firstItem;
        }

        self::$_dictionary = $_dict;

        return (array)self::$_dictionary;
    }

    /**
     * @static
     * @throws Exception
     * @param  $id
     * @return null|type
     */
    public static function getItemById($id)
    {
        if (is_int($id)!==true) {
            $message = "Parameter id must be int! at ".__METHOD__;
            throw new Exception($message);
        }
        $dict = self::getDictionary();
        $item = Lib_Utils_Array::getProperty($dict, $id, true);
        if (is_array($item)) {
            return $item;
        }

        return null;
    }


    

    /**
     * @static
     * @throws Exception
     * @param  int $day
     * @param  int $month
     * @return array|null
     */
    public static function getItemByDayAndMonth($day, $month)
    {
        if (is_int($day)!==true) {
            $message = "Parameter day must be int! at ".__METHOD__;
            throw new Exception($message);
        }

        if (is_int($month)!==true) {
            $message = "Parameter month must be int! at ".__METHOD__;
            throw new Exception($message);
        }

        $result = null;

        $id = self::_getItemIdByDayAndMonth($day, $month);
        if (is_int($id)!==true) {
            return $result;
        }

        $item = self::getItemById($id);
        if (is_array($item)) {
            return $item;
        }

        return $result;
    }


    /**
     * @static
     * @throws Exception
     * @param  int $day
     * @param  int $month
     * @return int|null
     */
    protected static function _getItemIdByDayAndMonth($day, $month)
    {
        if (is_int($day)!==true) {
            $message = "Parameter day must be int! at ".__METHOD__;
            throw new Exception($message);
        }

        if (is_int($month)!==true) {
            $message = "Parameter month must be int! at ".__METHOD__;
            throw new Exception($message);
        }

        $result = null;
        //http://en.wikipedia.org/wiki/Astrological_sign
        if (($month==12 && $day>=23)||($month==1 && $day<=19)) {
             return 1;//"Capricorn"; //Steinbock
        }


        if (($month==1 && $day>=20)||($month==2 && $day<=19)) {
            // Aquarius / Wassermann
            return 2;//"Aquarius";
        }

        if (($month==2 && $day>=20 )||($month==3 && $day<=20)) {
            /*
            Pisces / Fische
            */
            return 3;// "Pisces";
        }

        if (($month==3 && $day>=21)||($month==4 && $day<=20)) {
            // Aries
            return 4;//"Aries";//Widder
        }
        if (($month==4 && $day>=21)||($month==5 && $day<=19)) {

            return 5;//"Taurus";//Stier
        }

        if (($month==5 && $day>=20)||($month==6 && $day<=21)) {
            return 6;//"Gemini"; //Zwillinge
        }


        if (($month==6 && $day>=22)||($month==7 && $day<=21)) {
            return 7;//"Cancer"; //Krebs
        }

        if (($month==7 && $day>=22)||($month==8 && $day<=23)) {
            return 8;//"Leo"; //LÖwe
        }

        if (($month==8 && $day>=24)||($month==9 && $day<=22)) {
            return 9;//"Virgo"; //Jungfrau
        }

        if (($month==9 && $day>=23)||($month==10 && $day<=23)) {
            return 10;//"Libra"; //Waage
        }

        if (($month==10 && $day>=24)||($month==11 && $day<=22)) {
            return 11;//"Scorpio"; //Skorpion
        }

        if (($month==11 && $day>=23)||($month==12 && $day<=22)) {
            return 12;//"Sagittarius"; //Schütze
        }

        return $result;

    }



}
