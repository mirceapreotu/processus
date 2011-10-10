<?php

/**
 * Lib_Utils_File_Injector
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Utils_File
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Utils_File_Injector
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Utils_File
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_Utils_File_Injector
{

    /**
     * @static
     * @var Lib_Utils_File_Injector
     */
    private static $_instance;

    /**
     * @static
     * @return Lib_Utils_File_Injector
     */
    public static function getInstance()
    {
        if ((self::$_instance instanceof self)!==true) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    /**
     * @throws Exception
     * @param  string $location
     * @param bool $once
     * @return string
     */
    public function getIncludeContent($location, $once=false)
    {
        $content = '';
        try {
            ob_start();
            
            if ($once === true) {
                include $location;
            } else {
                include_once $location;
            }
            $content = ob_get_contents();
            ob_clean();

        } catch(Exception $e) {
            ob_clean();
            throw $e;
        }
        return $content;
    }


    /**
     * @throws Exception
     * @param  string $location
     * @param bool $once
     * @return string
     */
    public function getIncludeJavascript($location, $once=false)
    {
        $content = '';
        try {
            ob_start();

            if ($once === true) {
                include $location;
            } else {
                include_once $location;
            }
            $content = ob_get_contents();
            ob_clean();

        } catch(Exception $e) {
            ob_clean();
            throw $e;
        }

        $result = '<script type="text/javascript">'.PHP_EOL;
        $result .= $content .PHP_EOL;
        $result .= '</script>';

        return $result;
    }


}