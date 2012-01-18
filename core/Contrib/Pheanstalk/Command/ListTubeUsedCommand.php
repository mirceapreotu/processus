<?php

/**
 * The 'list-tube-used' command.
 * Returns the tube currently being used by the client.
 *
 * @author  Paul Annesley
 * @package Pheanstalk
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
namespace Pheanstalk\Command;
class ListTubeUsedCommand extends AbstractCommand implements \Pheanstalk\ResponseParser
{
    /**
     * @return string
     */
    public function getCommandLine()
    {
        return 'list-tube-used';
    }

    /**
     * @param $responseLine
     * @param $responseData
     *
     * @return \Pheanstalk\Response\ArrayResponse
     */
    public function parseResponse($responseLine, $responseData)
    {
        return $this->_createResponse('USING', array(
                                                    'tube' => preg_replace('#^USING (.+)$#', '$1', $responseLine)
                                               ));
    }
}
