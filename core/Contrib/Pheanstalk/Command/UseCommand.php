<?php

/**
 * The 'use' command.
 *
 * The "use" command is for producers. Subsequent put commands will put jobs into
 * the tube specified by this command. If no use command has been issued, jobs
 * will be put into the tube named "default".
 *
 * @author  Paul Annesley
 * @package Pheanstalk
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
namespace Pheanstalk\Command;
class UseCommand extends AbstractCommand implements \Pheanstalk\ResponseParser
{
    private $_tube;

    /**
     * @param string $tube The name of the tube to use
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
        return 'use ' . $this->_tube;
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
