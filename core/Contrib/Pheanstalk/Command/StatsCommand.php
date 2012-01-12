<?php

/**
 * The 'stats' command.
 * Statistical information about the system as a whole.
 *
 * @author  Paul Annesley
 * @package Pheanstalk
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
namespace Pheanstalk\Command;
class StatsCommand extends AbstractCommand
{
    /**
     * @return string
     */
    public function getCommandLine()
    {
        return 'stats';
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
