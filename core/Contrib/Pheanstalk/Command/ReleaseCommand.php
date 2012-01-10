<?php

/**
 * The 'release' command.
 * Releases a reserved job back onto the ready queue.
 *
 * @author Paul Annesley
 * @package Pheanstalk
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
namespace Pheanstalk\Command;
class ReleaseCommand extends AbstractCommand implements \Pheanstalk\ResponseParser
{
    /**
     * @var \Pheanstalk\Job
     */
	private $_job;
	private $_priority;
	private $_delay;

	/**
     * @param $job \Pheanstalk\Job
     * @param $priority
     * @param $delay
     */
	public function __construct($job, $priority, $delay)
	{
		$this->_job = $job;
		$this->_priority = $priority;
		$this->_delay = $delay;
	}

	/* (non-phpdoc)
	 * @see Command::getCommandLine()
	 */
	public function getCommandLine()
	{
		return sprintf(
			'release %d %d %d',
			$this->_job->getId(),
			$this->_priority,
			$this->_delay
		);
	}

	/* (non-phpdoc)
	 * @see ResponseParser::parseRespose()
	 */
	public function parseResponse($responseLine, $responseData)
	{
		if ($responseLine == \Pheanstalk\Response::RESPONSE_BURIED)
		{
			throw new \Pheanstalk\Exception\ServerException(sprintf(
				'Job %s %d: out of memory trying to grow data structure',
				$this->_job->getId(),
				$responseLine
			));
		}

		if ($responseLine == \Pheanstalk\Response::RESPONSE_NOT_FOUND)
		{
			throw new \Pheanstalk\Exception\ServerException(sprintf(
				'Job %d %s: does not exist or is not reserved by client',
				$this->_job->getId(),
				$responseLine
			));
		}

		return $this->_createResponse($responseLine);
	}
}
