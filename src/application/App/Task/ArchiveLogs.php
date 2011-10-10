<?php
/**
 * App_Task_ArchiveLogs Class
 *
 * @category	meetidaaa.com
 * @package		App_Task
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */

/**
 * App_Task_ArchiveLogs
 *
 * @category	meetidaaa.com
 * @package		App_Task
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */
class App_Task_ArchiveLogs extends Lib_Task_TaskAbstract
{

	// Flags for the script parameter parsing:

	/**
	 * GetOpt options definitions
	 *
	 * @see Lib_AbstractTask
	 * @var array
	 */
	protected $_options = array(
		'%logpath:'     => 'path to the log files (/var/log/app)',
		'%archivepath:' => 'path to the archives (/var/log/app/archive)',
		'%keepdays:'    => 'days to keep logs uncompressed (7)',
		'%prunedays:'   => 'age (days) of archived to delete (30)',
		'%suffix:'      => 'suffix of log files (log)',
		'%verbose'      => 'log messages to stdout?',
	);

	/**
	 * Name of the directory to scan for logs
	 * @var string
	 */
	protected $_logpath = '.';

	/**
	 * Name of the directory for the compressed files
	 * @var string
	 */
	protected $_archivePath = '.';

	/**
	 * How many days to keep uncompressed log files
	 * @var string
	 */
	protected $_keepDays = 7;

	/**
	 * How many days to keep compressed log files
	 * @var string
	 */
	protected $_pruneDays = 30;

	/**
	 * Suffix of the log files
	 * This is used for logfile detection.
	 * @var string
	 */
	protected $_suffix = 'log';

	/**
	 * Sub-pattern of the log file names
	 *
	 * Used to detect the age (in days) of the files. Must be
	 * suitable for strtotime() to timestamp parsing
	 *
	 * @var string
	 */
	protected $_datePattern = '[0-9]{4}-[0-9]{2}-[0-9]{2}';

	/**
	 * Checks if a given filename is a valid logfile
	 *
	 * @param string $filename
	 * @return boolean
	 */
	protected function _isLogFilename($filename)
	{
		$pattern = '/^('.$this->_datePattern.').*\\.'.$this->_suffix.'$/';

	    return (preg_match($pattern, $filename));
	}

	/**
	 * Checks if a given filename is a valid archive file
	 *
	 * @param string $filename
	 * @return boolean
	 */
	protected function _isArchiveFilename($filename)
	{
		$pattern =
			'/^('.$this->_datePattern.').*\\.'.$this->_suffix.'\\.gz$/';

	    return (preg_match($pattern, $filename));
	}

	/**
	 * @param string $filename
	 * @return bool|int
	 */
	protected function _getDateFromFilename($filename)
	{

		$pattern = '/^('.$this->_datePattern.')/';

		if (!preg_match($pattern, $filename, $match)) {

			return false;
		}

	    return strtotime($match[1]);
	}

	/**
	 * Parses the comandline options
	 *
	 * @return void
	 */
	protected function _parseOptions()
	{

		$this->_logpath = coalesce($this->_getOption('l'), $this->_logpath);

        $this->_archivePath = coalesce(
            $this->_getOption('a'), $this->_archivePath
        );

        $this->_keepDays = coalesce($this->_getOption('k'), $this->_keepDays);

        $this->_pruneDays = coalesce($this->_getOption('p'), $this->_pruneDays);

        $this->_suffix = coalesce($this->_getOption('s'), $this->_suffix);

		if (!is_dir($this->_archivePath)) {

			mkdir($this->_archivePath);
		}
	}

	/**
	 * Compresses old log files with gzip
	 *
	 * @return void
	 */
	protected function _zipOldLogFiles()
	{

		/*
		 *  rule set:
		 * - keep all files the last 7 days
		 * - move files older than 7 days to archive/ and gzip them
		 * - delete archive files older than 1 month
		 */

		$countLogs      = 0;
		$modifiedLogs   = 0;
		foreach (
			new DirectoryIterator($this->_logpath)
			as /** @var DirectoryIterator $fileInfo */	$fileInfo
		) {

			if ($fileInfo->isDot()) continue;
			if ($fileInfo->isDir()) continue;

			// use only log files in right format ...

			if ($this->_isLogFilename($fileInfo->getFilename())) {

				$countLogs++;

				$fileDay = $this->_getDateFromFilename(
					$fileInfo->getFilename()
				);
				$nowDay = time();
				$age = ($nowDay-$fileDay);
				$ageDays = floor(($age/60/60/24));

				if ($ageDays > $this->_keepDays) {

					// too old, zip & move to archive

					$fileName = $fileInfo->getPathname();

					$this->logger->info('compressing old log file: '.$fileName);

					`gzip $fileName`;

					$this->logger->info(
						'moving old log file to archive: '.$fileName
					);

					rename(
						$fileName.'.gz',
						$this->_archivePath.'/'.$fileInfo->getFilename().'.gz'
					);

					$modifiedLogs++;
				}

			}
		}

		$this->logger->info(
			'Archived '.$modifiedLogs.' of '.$countLogs.' log files'
		);
	}

	/**
	 * Deletes very old compressed (archived) log files
	 *
	 * @return void
	 */
	protected function _purgeOldLogFiles()
	{

		$countLogs = 0;
		$modifiedLogs   = 0;
		foreach (
			new DirectoryIterator($this->_archivePath)
			as /** @var DirectoryIterator $fileInfo */ $fileInfo
		) {

			if ($fileInfo->isDot()) continue;
			if ($fileInfo->isDir()) continue;

			// consider only archived log files in right format ...

			if ($this->_isArchiveFilename($fileInfo->getFilename())) {

				$countLogs++;

				$fileDay = $this->_getDateFromFilename(
					$fileInfo->getFilename()
				);
				$nowDay = time();
				$age = ($nowDay-$fileDay);
				$ageDays = floor(($age/60/60/24));

				if ($ageDays > $this->_pruneDays) {

					$this->logger->info(
						'Removing old archive file: '.
						$fileInfo->getPathname()
					);
					unlink($fileInfo->getPathname());
					$modifiedLogs++;
				}
			}
		}

		$this->logger->info(
			'Pruned '.$modifiedLogs.' of '.$countLogs.' compressed log files'
		);
	}

	/**
	 * Runs the task
	 *
	 * @see src/application/App/Task/App_Task_Abstract#run()
	 */
	public function run()
	{

		$this->_logpath     = ROOT_PATH.'/var/log/app';
		$this->_archivePath = ROOT_PATH.'/var/log/app/archive';

		$this->_parseOptions();

		$this->logger->info('Start archiving logs ');

		$this->_zipOldLogFiles();
		$this->_purgeOldLogFiles();

	}
}

