<?php

/**
 * The 'touch' command.
 *
 * The "touch" command allows a worker to request more time to work on a job.
 * This is useful for jobs that potentially take a long time, but you still want
 * the benefits of a TTR pulling a job away from an unresponsive worker.  A worker
 * may periodically tell the server that it's still alive and processing a job
 * (e.g. it may do this on DEADLINE_SOON).
 *
 * @author Paul Annesley
 * @package Pheanstalk
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
namespace Pheanstalk\Command;
class TouchCommand extends AbstractCommand implements \Pheanstalk\ResponseParser
{
    /**
     * @var \Pheanstalk\Command\Job
     */
	private $_job;

	/**
	 * @param \Pheanstalk\Job $job
	 */
	public function __construct($job)
	{
		$this->_job = $job;
	}

	/* (non-phpdoc)
	 * @see Command::getCommandLine()
	 */
	public function getCommandLine()
	{
		return sprintf('touch %d', $this->_job->getId());
	}

	/* (non-phpdoc)
	 * @see ResponseParser::parseRespose()
	 */
	public function parseResponse($responseLine, $responseData)
	{
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
