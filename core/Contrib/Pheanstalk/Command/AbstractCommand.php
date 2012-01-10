<?php

/**
 * Common functionality for Command implementations.
 *
 * @author  Paul Annesley
 * @package Pheanstalk
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
namespace Pheanstalk\Command;
abstract class AbstractCommand implements \Pheanstalk\Command
{
    /**
     * @return bool
     */
    public function hasData()
    {
        return false;
    }

    /**
     * @throws \Pheanstalk\Exception\CommandException
     */
    public function getData()
    {
        throw new \Pheanstalk\Exception\CommandException('Command has no data');
    }

    /**
     * @throws \Pheanstalk\Exception\CommandException
     */
    public function getDataLength()
    {
        throw new \Pheanstalk\Exception\CommandException('Command has no data');
    }

    /**
     * @return \Pheanstalk\Exception\AbstractCommand
     */
    public function getResponseParser()
    {
        // concrete implementation must either:
        // a) implement ResponseParser
        // b) override this getResponseParser method
        return $this;
    }

    /**
     * The string representation of the object.
     * @return string
     */
    public function __toString()
    {
        return $this->getCommandLine();
    }

    // ----------------------------------------
    // protected

    /**
     * @param       $name
     * @param array $data
     *
     * @return \Pheanstalk\Response\ArrayResponse
     */
    protected function _createResponse($name, $data = array())
    {
        return new \Pheanstalk\Response\ArrayResponse($name, $data);
    }
}
