<?php
/**
 * Lib_Io_File Class
 *
 * @package		Lib_Io
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 *
 */

/**
 * Lib_IO_File
 *
 * @package Lib_Io
 *
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */
class Lib_Io_File
{

	/**
	 * Works like file_put_contents but safe.
	 *
	 * First writes to a .tmp file, then moves to final file.
	 * If filename does not exist, the file is created.
	 * Otherwise, the existing file is overwritten, unless the
	 * FILE_APPEND flag is set.
	 *
	 * @static
	 * @param string $filename
	 * @param mixed $contents   The data to write.
	 *                          Can be either a string, an array or a
	 *                          stream resource.
	 *                          If data is a stream resource, the remaining
	 *                          buffer of that stream will be copied to the
	 *                          specified file. This is similar with using
	 *                          stream_copy_to_stream().
	 *                          You can also specify the data parameter as a
	 *                          single dimension array. This is equivalent
	 *                          to file_put_contents(
	 *                              $filename, implode('', $array)
	 *                          ).
	 * @param int $flags        Same flags as with file_put_contents.
	 *                          FILE_USE_INCLUDE_PATH
	 *                              - Search for filename in the include
	 *                                directory. See include_path for more
	 *                                information.
	 *                          FILE_APPEND
	 *                              - If file filename already exists, append
	 *                                the data to the file instead of
	 *                                overwriting it.
	 *                          LOCK_EX
	 *                              - Acquire an exclusive lock on the file
	 *                                while proceeding to the writing.
	 * @param resource $context       A valid context resource created with
	 *                          stream_context_create().
	 * @return int|boolean      The function returns the number of bytes that
	 *                          were written to the file, or FALSE on failure.
	 */
	public static function putContents(
		$filename, $contents, $flags=0, $context=null
	)
	{

		if ($context == null) {
			$res = file_put_contents(
				$filename.'.tmp', $contents, $flags
			);
		} else {
			$res = file_put_contents(
				$filename.'.tmp', $contents, $flags, $context
			);
		}
		rename($filename.'.tmp', $filename);

	    return $res;
	}

}
