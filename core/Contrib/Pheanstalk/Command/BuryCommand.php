<?php

/**
 * The 'bury' command.
 * Puts a job into a 'buried' state, revived only by 'kick' command.
 *
 * @author Paul Annesley
 * @package Pheanstalk
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
namespace Pheanstalk\Command;
class BuryCommand extends AbstractCommand implements \Pheanstalk\ResponseParser
{
	private $_job;
	private $_priority;

	/**
	 * @param object $job Job
	 * @param int $priority From 0 (most urgent) to 0xFFFFFFFF (least urgent)
	 */
	public function __construct($job, $priority)
	{
		$this->_job = $job;
		$this->_priority = $priority;
	}

	/**
     * @return string
     */
	public function getCommandLine()
	{
		return sprintf(
			'bury %d %d',
			$this->_job->getId(),
			$this->_priority
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
				'%s: Job %d is not reserved or does not exist.',
				$responseLine,
				$this->_job->getId()
			));
		}
		elseif ($responseLine == \Pheanstalk\Response::RESPONSE_BURIED)
		{
			return $this->_createResponse(\Pheanstalk\Response::RESPONSE_BURIED);
		}
		else
		{
			throw new \Pheanstalk\Exception('Unhandled response: '.$responseLine);
		}
	}
}
