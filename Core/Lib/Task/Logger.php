<?php
/**
 * Lib_Task_Logger Class
 *
 * @package Lib
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Task_Logger
 *
 * @package Lib_Task
 *
 * @abstract
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
class Lib_Task_Logger
{

	/**
	 * @var Zend_Log
	 */
	protected $_logger;

	/**
	 * @var boolean
	 */
	protected $_logEcho = false;	

	/**
	 * Log via Zend_Logger
	 *
	 * @param string $str A status string
	 */
	public function info($message)
	{
		if ($this->_logEcho) {
			echo $message."\n";
		}
		$this->_logger->log($message, Zend_Log::INFO);
	}

	/**
	 * Log via Zend_Logger
	 *
	 * @param string $str A status string
	 */
	public function critical($message)
	{

		if ($this->_logEcho) {
			echo $message."\n";
		}
		$this->_logger->log($message, Zend_Log::CRIT);
	}

	/**
	 * Echos a status line using VT100 codes
	 * to StdErr - if stderr is defined!
	 *
	 * @param string $str A status string
	 */
	public function status($str='')
	{
        try {
    		$stdErr = fopen('/dev/stderr', 'w');
            fputs($stdErr, chr(27)."[2K".$str."\n".chr(27)."[1A");
            fclose($stdErr);
        } catch (Exception $exception) {
            // nop - does not matter that much ...
        }
	}

	/**
	 * Log via Zend_Logger
	 *
	 * @param string $str A status string
	 */
	public function setBasename($basename)
	{
		/* @todo rethink basename setting! (probs w/ syslogng)
        $config = Zend_Registry::get('CONFIG')->toArray();
        if (
            array_key_exists('log', $config)
            && array_key_exists('adapter', $config['log'])
            && $config['log']['adapter'] == 'syslogng'
        ) {
            return; // basename setting for sysloggers not available
        }
		*/

		$writerDefault = new Lib_Log_Writer_DailyStream(
			SRC_PATH.'/../var/log/app/'.$basename.'_default.log'
		);
		$writerCritical = new Lib_Log_Writer_DailyStream(
			SRC_PATH.'/../var/log/app/'.$basename.'_critical.log'
		);

		$format = '%timestamp%;%priorityName%;%priority%;%message%'.PHP_EOL;
		$formatter = new Zend_Log_Formatter_Simple($format);

		$writerDefault->setFormatter($formatter);
		$writerCritical->setFormatter($formatter);

		$filterCritical = new Zend_Log_Filter_Priority(Zend_Log::CRIT);
		$writerCritical->addFilter($filterCritical);

		$this->_logger = new Zend_Log();
		$this->_logger->addWriter($writerDefault);
		$this->_logger->addWriter($writerCritical);
	}

	/**
	 * Log via stdout
	 *
	 * @param boolean $mode true, if all messages should be printed via echo
	 */
	public function setEcho($mode=true)
	{
		$this->_logEcho = $mode;
	}

	/**
	 * Constructs a task logger
	 */
	public function __construct()
	{

		// set default logger .. may be overwritten
		$this->_logger = Zend_Registry::get('LOG');		
	}

}
