<?php

/**
 * The 'stats-job' command.
 * Gives statistical information about the specified job if it exists.
 *
 * @author  Paul Annesley
 * @package Pheanstalk
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
namespace Pheanstalk\Command;
class StatsJobCommand extends AbstractCommand
{
    private $_jobId;

    /**
     * @param \Pheanstalk\Job $job
     */
    public function __construct($job)
    {
        $this->_jobId = is_object($job) ? $job->getId() : $job;
    }

    /**
     * @return string
     */
    public function getCommandLine()
    {
        return sprintf('stats-job %d', $this->_jobId);
    }

    /**
     * @return \Pheanstalk\YamlResponseParser
     */
    public function getResponseParser()
    {
        return new \Pheanstalk\YamlResponseParser(
            \Pheanstalk\YamlResponseParser::MODE_DICT
        );
    }
}
