<?php

/**
 * The 'list-tubes-watched' command.
 * Lists the tubes on the watchlist.
 *
 * @author Paul Annesley
 * @package Pheanstalk
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
namespace Pheanstalk\Command;
class ListTubesWatchedCommand extends AbstractCommand
{
	/* (non-phpdoc)
	 * @see Command::getCommandLine()
	 */
	public function getCommandLine()
	{
		return 'list-tubes-watched';
	}

	/* (non-phpdoc)
	 * @see Command::getResponseParser()
	 */
	public function getResponseParser()
	{
		return new \Pheanstalk\YamlResponseParser(
			\Pheanstalk\YamlResponseParser::MODE_LIST
		);
	}
}
