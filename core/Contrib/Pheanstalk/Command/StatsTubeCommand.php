<?php

/**
 * The 'stats-tube' command.
 * Gives statistical information about the specified tube if it exists.
 *
 * @author Paul Annesley
 * @package Pheanstalk
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
namespace Pheanstalk\Command;
class StatsTubeCommand extends AbstractCommand
{
	private $_tube;

	/**
	 * @param string $tube
	 */
	public function __construct($tube)
	{
		$this->_tube = $tube;
	}

	/* (non-phpdoc)
	 * @see Command::getCommandLine()
	 */
	public function getCommandLine()
	{
		return sprintf('stats-tube %s', $this->_tube);
	}

	/* (non-phpdoc)
	 * @see Command::getResponseParser()
	 */
	public function getResponseParser()
	{
		return new \Pheanstalk\YamlResponseParser(
			\Pheanstalk\YamlResponseParser::MODE_DICT
		);
	}
}
