<?php
/**
 * App_Task_Beanstalk Class
 *
 * Simple test script for checking the beanstalkd queueing system.
 *
 * @category	meetidaaa.com
 * @package		App_Task
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

/**
 * App_Task_Beanstalk
 *
 * @category	meetidaaa.com
 * @package		App_Task
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */
class App_Task_Beanstalk extends Lib_Task_TaskAbstract
{
	/**
	 * @var string
	 */
	const DEFAULT_HOST = '127.0.0.1';

	/**
	 * @var int
	 */
	const DEFAULT_PORT = 11300;

	/**
	 * @var int
	 */
	const DEFAULT_WATCH_TIME = 30; // sek

	// Flags for the script parameter parsing:

	/**
	 * GetOpt options definitions
	 *
	 * @see Lib_Task_TaskAbstract
	 * @var array
	 */
	protected $_options = array(
		'%host:'    => 'Select beanstalkd host',
		'%watch:'   => 'Default watch time (in secs)',
		'%tube:'    => 'Select tube',
		'%list'     => 'List tubes',
		'%stats'    => 'Statistics (overall or tube selected tube)',
		'%put:'     => 'Put specified data in the tube',
		'%get'      => 'Get data from the tube',
	);

	/**
	 * Runs the task
	 *
	 * @see src/application/App/Task/App_Task_Abstract#run()
	 */
	public function run()
	{

		$host = coalesce($this->_getOption('h'), self::DEFAULT_HOST);

		try {

			$pheanstalk = new Pheanstalk_Connector($host);

			$tube = coalesce($this->_getOption('t'), 'default');
			$pheanstalk->useTube($tube);

			$watchTime = coalesce($this->_getOption('w'), self::DEFAULT_WATCH_TIME);


            /** @noinspection PhpAssignmentInConditionInspection */
            if ($data = $this->_getOption('p')) {

				$pheanstalk->put($data);
			}

			// worker?
			if ($this->_hasOption('g')) {

                /** @noinspection PhpAssignmentInConditionInspection */
                if (
					$job = $pheanstalk
						->watch($tube)
						->reserve($watchTime)
				) {
					$data = $job->getData();
					$pheanstalk->delete($job);
					echo "$data\n";

				} else {

					file_put_contents('/dev/stderr', "No job found.\n");
					exit(1);
				}
			}

			if ($this->_hasOption('l')) {

				$tubeList = $pheanstalk->listTubes();
				foreach ($tubeList as $tubeKey => $tubeName) {

					echo "tube-$tubeKey: $tubeName\n";
				}
			}

			if ($this->_hasOption('s')) {

				if ($this->_hasOption('t')) {

					$stats = $pheanstalk->statsTube($tube);

				} else {

					$stats = $pheanstalk->stats();
				}

				foreach ($stats as $statsKey => $statsValue) {

					echo "$statsKey: $statsValue\n";
				}

			}

		} catch (Exception $exception) {

			file_put_contents('/dev/stderr', "Server communication error.\n");
			file_put_contents('/dev/stderr', $exception->getMessage()."\n");
			exit(2);
		}
	}
}
