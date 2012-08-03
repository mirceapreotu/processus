<?php

/**
 * The 'pause-tube' command.
 * Temporarily prevent jobs being reserved from the given tube.
 *
 * @author Paul Annesley
 * @package Pheanstalk
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
namespace Pheanstalk\Command;
class PauseTubeCommand extends AbstractCommand implements \Pheanstalk\ResponseParser
{
	private $_tube;
	private $_delay;

	/**
	 * @param string $tube The tube to pause
	 * @param int $delay Seconds before jobs may be reserved from this queue.
	 */
	public function __construct($tube, $delay)
	{
		$this->_tube = $tube;
		$this->_delay = $delay;
	}

	/* (non-phpdoc)
	 * @see Command::getCommandLine()
	 */
	public function getCommandLine()
	{
		return sprintf(
			'pause-tube %s %d',
			$this->_tube,
			$this->_delay
		);
	}

	/* (non-phpdoc)
	 * @see ResponseParser::parseRespose()
	 */
	public function parseResponse($responseLine, $responseData)
	{
		if ($responseLine == \Pheanstalk\Response::RESPONSE_NOT_FOUND)
		{
			throw new \Pheanstalk\Exception\ServerException(sprintf(
				'%s: tube %d does not exist.',
				$responseLine,
				$this->_tube
			));
		}
		elseif ($responseLine == \Pheanstalk\Response::RESPONSE_PAUSED)
		{
			return $this->_createResponse(\Pheanstalk\Response::RESPONSE_PAUSED);
		}
		else
		{
			throw new \Pheanstalk\Exception('Unhandled response: '.$responseLine);
		}
	}
}
