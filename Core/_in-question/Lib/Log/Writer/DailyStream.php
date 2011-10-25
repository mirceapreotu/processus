<?php
/**
 * Writes to log files, one for each calendar day
 *
 * @category	meetidaaa.com
 * @package		Lib_Log
 * @subpackage	Writer
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 * 
 */

/**
 * Writes to log files, one for each calendar day
 * 
 * @category	meetidaaa.com
 * @package    Lib_Log
 * @subpackage Writer
 */
class Lib_Log_Writer_DailyStream extends Zend_Log_Writer_Stream
{
    /**
     * Holds the PHP stream to log to.
     * @var null|stream
     */
    protected $_stream = null;

    /**
     * Class Constructor
     *
     * @param string  $streamOrUrl     Stream or URL to open as a stream
     * @param string  $mode Mode, only applicable if a URL is given [w|w+|...]
     */
    public function __construct($streamOrUrl, $mode = NULL)
    {
        $pathname = dirname($streamOrUrl);
        $filename = basename($streamOrUrl);

        $streamOrUrl =$pathname.'/'.date('Y-m-d').'_'.$filename;

        /** @noinspection PhpParamsInspection */
        parent::__construct($streamOrUrl, $mode); // zend has wrong type!
    }

}
