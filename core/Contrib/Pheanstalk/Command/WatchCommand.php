<?php

/**
 * The 'watch' command.
 * Adds a tube to the watchlist to reserve jobs from.
 *
 * @author  Paul Annesley
 * @package Pheanstalk
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
namespace Pheanstalk\Command;
class WatchCommand extends AbstractCommand implements \Pheanstalk\ResponseParser
{
    private $_tube;

    /**
     * @param string $tube
     */
    public function __construct($tube)
    {
        $this->_tube = $tube;
    }

    /**
     * @return string
     */
    public function getCommandLine()
    {
        return 'watch ' . $this->_tube;
    }

    /**
     * @param $responseLine
     * @param $responseData
     *
     * @return \Pheanstalk\Response\ArrayResponse
     */
    public function parseResponse($responseLine, $responseData)
    {
        return $this->_createResponse('WATCHING', array(
                                                       'count' => preg_replace('#^WATCHING (.+)$#', '$1', $responseLine)
                                                  ));
    }
}
