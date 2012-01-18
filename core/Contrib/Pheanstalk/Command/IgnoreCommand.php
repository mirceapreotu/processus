<?php

/**
 * The 'ignore' command.
 * Removes a tube from the watch list to reserve jobs from.
 *
 * @author  Paul Annesley
 * @package Pheanstalk
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
namespace Pheanstalk\Command;
class IgnoreCommand extends AbstractCommand implements \Pheanstalk\ResponseParser
{
    /**
     * @var string
     */
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
        return 'ignore ' . $this->_tube;
    }

    /**
     * @param $responseLine
     * @param $responseData
     *
     * @return \Pheanstalk\Response\ArrayResponse
     * @throws Pheanstalk_Exception|ServerException
     */
    public function parseResponse($responseLine, $responseData)
    {
        if (preg_match('#^WATCHING (\d+)$#', $responseLine, $matches)) {
            return $this->_createResponse('WATCHING', array(
                                                           'count' => (int)$matches[1]
                                                      ));
        }
        elseif ($responseLine == \Pheanstalk\Response::RESPONSE_NOT_IGNORED)
        {
            throw new ServerException($responseLine .
                ': cannot ignore last tube in watchlist');
        }
        else
        {
            throw new \Pheanstalk\Exception('Unhandled response: ' . $responseLine);
        }
    }
}
