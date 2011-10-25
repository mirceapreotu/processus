<?php
/**
 * Lib_Task_TaskAbstract Class
 *
 * @package     Lib_Task
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id$
 *
 */

/**
 * Lib_Task_TaskAbstract
 *
 * @package Lib_Task
 *
 * @abstract
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 *
 */
abstract class Lib_Task_TaskAbstract
{

	/**
	 * GetOpt option definition
	 * @var string
	 */
	protected $_opt = '';

	/**
	 * Aliases for GetOpt options
	 * @var array
	 */
	private $_aliases = array();

	/**
	 * Help text for GetOpt options
	 * @var array
	 */
	private $_help = array();

	/**
	 * The GetOpt object
	 * @var Zend_Console_Getopt
	 */
	private $_getopt;

	/**
	 * a string containing all required options
	 * @var string
	 */
	private $_requiredOptions='';

	/**
	 * a string containing options where only one must be specified
	 * @var string
	 */
	private $_oneOfOptions='';

	/**
	 * a string containing options where at least one must be specified
	 * @var string
	 */
	private $_anyOfOptions='';

	/**
	 * GetOpt options definitions
	 *
	 * Override/define these options for each task.
	 *
	 * The keys of the array contain the short/long options and an indicator if
	 * the option requires an additional parameter. The values of the array
	 * are the help (usage) texts for the options. A colon ":" at the end
	 * of the key indicates the option needs an additional parameter.
	 * The shortname of the option is the first letter OR the character after
	 * a percent "%" sign in the key. If the key starts with a bang "!",
	 * the parameter is required.
	 *
	 * Examples:
	 * A) '%file:'	=> 'file to import'
	 * 		- option shortname is "-f"
	 * 		- option longname is "--file"
	 * 		- the option _requires_ an additional parameter
	 *
	 * B) 'e%xport'	=> 'xml file to export/import'
	 * 		- option shortname is "-x"
	 * 		- option longname is "--export"
	 * 		- the option takes no additional parameter
	 *
	 * C) '!user:'	=> 'user name for the import'
	 * 		- option shortname is "-u"
	 * 		- option longname is "--user"
	 * 		- the option takes no additional parameter
	 * 		- this option is required for this task
	 *
	 * D) '*foo:'	=> 'foo option'
	 * 		- option shortname is "-f"
	 * 		- option longname is "--foo"
	 * 		- the option requires an additional parameter
	 * 		- at least ONE of the options with an asterisk "*" must be present
	 *
	 * E) '#ba%r'	=> 'bar option'
	 * 		- option shortname is "-r"
	 * 		- option longname is "--bar"
	 * 		- the option takes no additional parameter
	 * 		- at ONLY ONE of the options with an hash "#" must be present
	 *
	 *
	 * @var array
	 */
	protected $_options = array();

	/**
	 * @var Lib_Task_Logger
	 */
	public $logger;

	/**
	 * Parses options
	 *
	 * @return void
	 */
	private function _parseCompactOptionsDefiniton()
	{

		// parse the "condensed" option definiton (if any exists):

		foreach ($this->_options as $optionKey => $optionHelp) {

			$optionDefinition = strtolower($optionKey);

			$longOption = preg_replace(
				'/[^a-z0-9-]+/', '', $optionDefinition
			);

			// use character following the % .. OR the first character

			$match = '';
			if (preg_match('/%([a-z])/', $optionDefinition, $match)) {

				$shortOption = $match[1];

			} else {

				$shortOption = substr($longOption, 0, 1);
			}

			$requiresParameter = '';
			if (strpos($optionDefinition, ':') !== false) {

				$requiresParameter = ':';
			}

			// save the parsed info:

			$this->_opt				.= $shortOption.$requiresParameter;

			$this->_aliases[$shortOption]	= $longOption;
			$this->_help[$shortOption]		= $optionHelp;

			$first = substr($optionDefinition, 0, 1);
			if ($first == '!') {

				$this->_requiredOptions		.= $shortOption;
				$this->_help[$shortOption]	.= ' [REQUIRED]';
			}
			if ($first == '#') {

				$this->_oneOfOptions		.= $shortOption;
				$this->_help[$shortOption]	.= ' [ONE OF]';
			}
			if ($first == '*') {

				$this->_anyOfOptions		.= $shortOption;
				$this->_help[$shortOption]	.= ' [ANY OF]';
			}
		}
	}

	/**
	 * @throws Zend_Console_Getopt_Exception
     */
	private function _checkRequiredGetops()
	{

		// check the required parameters:

		$requiredOptions = str_split($this->_requiredOptions);
		foreach ($requiredOptions as $option) {

			if (trim($option) == '') {

				continue;
			}

			if (!isset($this->_getopt->$option)) {

				throw new Zend_Console_Getopt_Exception(
					"Required option \"$option\" is missing.\n",
					"Required option \"$option\" is missing.\n"
				);
			}
		}
	}

	/**
	 * @throws Zend_Console_Getopt_Exception
     */
	private function _checkRequiredAnyGetops()
	{

		// check the "any of" required parameters (if we need to check):

		$hasAnyOf = (strlen($this->_anyOfOptions) == 0);
		$anyOfOptions = str_split($this->_anyOfOptions);
		foreach ($anyOfOptions as $option) {

			if (trim($option) == '') {

				continue;
			}

			if (isset($this->_getopt->$option)) {

				$hasAnyOf = true;
			}
		}

		if (!$hasAnyOf) {

			throw new Zend_Console_Getopt_Exception(
				"A required option of \"".
				$this->_anyOfOptions."\" is missing.\n",
				"A required option of \"".
				$this->_anyOfOptions."\" is missing.\n"
			);
		}
	}

	/**
	 * @throws Zend_Console_Getopt_Exception
     */
	private function _checkRequiredOneOfGetops()
	{

		// check the "one of" required parameters:

		$hasOneOf		= (strlen($this->_oneOfOptions) > 0);
		$hasOneOfCnt	= 0;
		$oneOfOptions = str_split($this->_oneOfOptions);
		foreach ($oneOfOptions as $option) {

			if (trim($option) == '') {

				continue;
			}

			if (isset($this->_getopt->$option)) {

				$hasOneOfCnt++;
			}
		}

		if ($hasOneOf) {

			if ($hasOneOfCnt != 1) {

				throw new Zend_Console_Getopt_Exception(
					"There must be exactly one of these options: \"".
					$this->_oneOfOptions."\".\n",
					"There must be exactly one of these options: \"".
					$this->_oneOfOptions."\".\n"
				);
			}
		}
	}

	/**
	 * Determine, if a given command line option was specified
	 *
	 * @param string $optionChar An option character
	 * @return bool true if option was specified
	 */
	protected function _hasOption($optionChar)
	{
		return isset($this->_getopt->{$optionChar});
	}

	/**
	 * Return value of specified command line option or false if not set
	 *
	 * @param string $optionChar An option character
	 * @return bool|string false if option is not set, string value otherwise
	 */
	protected function _getOption($optionChar)
	{
		if (!$this->_hasOption($optionChar)) {
			return false;
		}
		
	    return $this->_getopt->{$optionChar};
	}

	/**
	 * Constructs a task
	 *
	 * @return Lib_Task_TaskAbstract
	 */
	public function __construct()
	{

		$this->logger = new Lib_Task_Logger();

        // add verbose option
        $this->_options['%verbose'] = 'log messages to stdout?';
        
		$this->_parseCompactOptionsDefiniton();

		// add the required "t" parameter of the parent (taskrunner)

		try {

		    $this->_getopt = new Zend_Console_Getopt($this->_opt);

			$this->_getopt->setHelp(
				$this->_help
			);

			$this->_getopt->setAliases(
				$this->_aliases
			);

			$this->_getopt->parse();

			$this->_checkRequiredGetops();

		    $this->_checkRequiredAnyGetops();

		    $this->_checkRequiredOneOfGetops();

		} catch (Zend_Console_Getopt_Exception $e) {

		    echo $e->getUsageMessage();
		    exit;
		}

        // activate verbose logger
        if ($this->_hasOption('v')) {

            $this->logger->setEcho(true);
        }

	}

	/**
	 * Runs the task
	 */
	public abstract function run();

}
