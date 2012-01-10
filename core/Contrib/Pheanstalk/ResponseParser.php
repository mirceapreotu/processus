<?php

/**
 * A parser for response data sent from the beanstalkd server
 *
 * @author Paul Annesley
 * @package Pheanstalk
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
namespace Pheanstalk;
interface ResponseParser
{
	/**
	 * Parses raw response data into a \Pheanstalk\Response object
	 * @param string $responseLine Without trailing CRLF
	 * @param string $responseData (null if no data)
	 * @return object \Pheanstalk\Response
	 */
	public function parseResponse($responseLine, $responseData);
}
