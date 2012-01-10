<?php

/**
 * The 'list-tubes' command.
 * List all existing tubes.
 *
 * @author  Paul Annesley
 * @package Pheanstalk
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
namespace Pheanstalk\Command;
class ListTubesCommand extends AbstractCommand
{
    /**
     * @return string
     */
    public function getCommandLine()
    {
        return 'list-tubes';
    }

    /**
     * @return \Pheanstalk\YamlResponseParser
     */
    public function getResponseParser()
    {
        return new \Pheanstalk\YamlResponseParser(
            \Pheanstalk\YamlResponseParser::MODE_LIST
        );
    }
}
