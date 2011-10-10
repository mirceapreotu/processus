<?php
/**
 * Lib_Utils_Convert_FFMPEG
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Utils_Convert
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Utils_File_MimeType
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Utils_Convert
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_Utils_Convert_FFMPEG
{

	const FFMPEG_COMMAND = '/usr/bin/ffmpeg';
	/**
	 * get info about file
	 *
	 * @param string $pathname
	 * @return array
	 */
	public static function getFileInformation($pathname)
	{
		$infoArray = array(
			'mime_type' => null,
			'file_size' => null,
			'ffmpeg_codec' => null,
			'duration' => null,
			'ffmpeg_bitrate' => null,
			'width' => null,
			'height' => null,
			'video_codec' => null,
			'video_bitrate' => null,
			'fps' => null,
			'audio_codec' => null,
			'audio_frequenz' => null,
			'audio_bitrate' => null,
			'audio_channels' => null,
			'message' => null,
			'aspect_ratio' => null
		);

		// check file is readable
		if (!is_readable($pathname)) {
			$infoArray['message'] = 'File not readable!';
			return $infoArray;
		}

		$infoArray['mime_type'] = Lib_Utils_File_MimeType::getByPathname($pathname);
		$filezize = filesize($pathname);

		$filezizeSufix = 'byte';
		if ($filezize / 1024 > 1) {
			$filezize = round($filezize / 1024, 2);
			$filezizeSufix = 'KB';
		}

		if ($filezize / 1024 > 1) {
			$filezize = round($filezize / 1024, 2);
			$filezizeSufix = 'MB';
		}

		if ($filezize / 1024 > 1) {
			$filezize = round($filezize / 1024, 2);
			$filezizeSufix = 'GB';
		}

		$infoArray['file_size'] = $filezize . ' ' . $filezizeSufix;
		unset($filezize, $filezizeSufix);

		$infoCommand = self::FFMPEG_COMMAND . ' -i "' . str_replace('"', '\"', $pathname) . '"';
		$outline = `$infoCommand 2>&1`;
		$outlineArray = explode("\n", $outline);

		foreach ($outlineArray as &$line) {
			$line = trim($line);

			// check on errors
			if (strpos($line, 'Unknown format') !== false
			    || strpos($line, 'Error') !== false) {
				$infoArray['message'] = 'FFMPEG: Unknown format!';
				return $infoArray;
			}

			// example:
			//FFmpeg version SVN-rUNKNOWN, Copyright (c) 2000-2007 Fabrice Bellard, et al.
			//  configuration: --enable-gpl --enable-pp --enable-swscaler --enable-pthreads --enable-libvorbis
			// --enable-libtheora --enable-libogg --enable-libgsm --enable-dc1394 --disable-debug --enable-shared
			// --prefix=/usr
			//  libavutil version: 1d.49.3.0
			//  libavcodec version: 1d.51.38.0
			//  libavformat version: 1d.51.10.0
			//  built on Jul 23 2008 22:38:24, gcc: 4.2.3 (Ubuntu 4.2.3-2ubuntu7)
			//Input #0, mpeg, from 'culchacandelanextgenerationdtveruni.mpeg':
			//  Duration: 00:03:55.7, start: 0.181978, bitrate: 15402 kb/s
			//  Stream #0.0[0x1e0]: Video: mpeg2video, yuv420p, 720x576, 15000 kb/s, 25.00 fps(r)
			//  Stream #0.1[0x1c0]: Audio: mp2, 44100 Hz, stereo, 192 kb/s

			// input line
			// example: Input #0, mpeg, from 'culchacandelanextgenerationdtveruni.mpeg':
			if (strpos($line, 'Input') === 0) {
				$help = explode(', ', $line);
				if (array_key_exists(1, $help)) {
					$infoArray['ffmpeg_codec'] = trim($help[1]);
				}
				unset($help);
			}

			// example: Duration: 00:03:55.7, start: 0.181978, bitrate: 15402 kb/s
			if (strpos($line, 'Duration') === 0) {

				// duration
				$timeArray = explode(':', substr($line, strpos($line, 'Duration:') + 10, 8));
				if (count($timeArray) == 3) {
					$infoArray['duration'] = ((int)$timeArray[0]) * 60 * 60
					                         + ((int)$timeArray[1]) * 60
					                         + ((int)$timeArray[2]);
				}
				unset($timeArray);

				// bitrate
				$bitrate = substr($line, strpos($line, 'bitrate:') + 7);
				if (strpos($bitrate, ',')) {
					$bitrate = substr($bitrate, 0, strpos($bitrate, ','));
				}
				$infoArray['ffmpeg_bitrate'] = $bitrate;
				unset($bitrate);
			}

			// stream analyse
			if (strpos($line, 'Stream') === 0) {

				// example: Stream #0.0[0x1e0]: Video: mpeg2video, yuv420p, 720x576, 15000 kb/s, 25.00 fps(r)
				if (strpos($line, 'Video:') !== false) {
					$dataArray = explode(', ', substr($line, strpos($line, 'Video:') + 7));

					// example:
					//Array
					//(
					//    [$line] => Stream #0.0[0x1e0]: Video: mpeg2video, yuv420p, 720x576, 15000 kb/s, 25.00 fps(r)
					//    [$dataArray] => Array
					//        (
					//            [0] => mpeg2video
					//            [1] => yuv420p
					//            [2] => 720x576 [PAR 16:15 DAR 4:3]
					//            [3] => 15000 kb/s
					//            [4] => 25.00 fps(r)
					//        )
					//
					//)

					// codec
					if (array_key_exists(0, $dataArray)) {
						$infoArray['video_codec'] = $dataArray[0];
					}

					// rest
					foreach ($dataArray as &$dataLine) {
						$dataLine = trim($dataLine);

						// fps
						if (preg_match('/fps|tb\(r\)/', $dataLine)) {
							$infoArray['fps'] = $dataLine;
						} else

							// bitrate
						{
							if (preg_match('/ [kmg]b\/s/i', $dataLine)) {
								$infoArray['video_bitrate'] = $dataLine;
							} else

								// resolution
							{
								if (preg_match('/^[0-9]+x[0-9]+( .+)?/', $dataLine)) {

									$resolutionArrayHelp = explode(' ', $dataLine);
									$resolutionArray = explode('x', $resolutionArrayHelp[0]);
									$infoArray['width'] = $resolutionArray[0];
									$infoArray['height'] = $resolutionArray[1];
									unset($resolutionArray);
								}
							}
						}

						// aspectratio
						if (@preg_match('/DAR ([0-9]+:[0-9]+)/', $dataLine, $match)) {
							$infoArray['aspect_ratio'] = @$match[1];
						}
						unset($dataLine);
					}
					unset($dataArray);

					// example: Stream #0.1[0x1c0]: Audio: mp2, 44100 Hz, stereo, 192 kb/s
				} else {
					if (strpos($line, 'Audio') !== false) {
						$dataArray = explode(', ', substr($line, strpos($line, 'Audio:') + 7));

						// example:
						//Array
						//(
						//    [$line] => Stream #0.1[0x1c0]: Audio: mp2, 44100 Hz, stereo, 192 kb/s
						//    [$dataArray] => Array
						//        (
						//            [0] => mp2
						//            [1] => 44100 Hz
						//            [2] => stereo
						//            [3] => 192 kb/s
						//        )
						//
						//)

						if (array_key_exists(0, $dataArray)) {
							$infoArray['audio_codec'] = $dataArray[0];
						}

						// rest
						foreach ($dataArray as &$dataLine) {
							$dataLine = trim($dataLine);

							// frequenz
							if (preg_match('/Hz/', $dataLine)) {
								$infoArray['audio_frequenz'] = $dataLine;
							} else

								// bitrate
							{
								if (preg_match('/ [kmg]b\/s/i', $dataLine)) {
									$infoArray['audio_bitrate'] = $dataLine;
								} else

									// channels
								{
									if (in_array($dataLine, array('mono', 'stereo'))) {
										$infoArray['audio_channels'] = $dataLine;
									}
								}
							}

							unset($dataLine);
						}

						unset($dataArray);
					}
				}
			}
		}

		return $infoArray;
	}

	/**
	 * parse a logfile output
	 *
	 * @param string &$content
	 * @return string
	 */
	public static function getLogfileErrorFromVideoConvert(&$content)
	{
		// FEHLERSUCHE
		$contentArray = explode("\n", $content);
		foreach ($contentArray as &$contentLine) {
			$contentLine = trim($contentLine);

			if (strpos($contentLine, 'Command building failed!') !== false) {
				return 'Command building failed!';
			}
			if (strpos($contentLine, 'no such file or directory') !== false) {
				return 'no such file or directory';
			}
			if (strpos($contentLine, 'Unknown format') !== false) {
				return 'Unknown format';
			}
			if (strpos($contentLine, 'Unsupported codec') !== false) {
				return 'Unsupported codec';
			}
			if (strpos($contentLine, 'Unknown format') !== false) {
				return 'Unsupported codec';
			}
			if (strpos($contentLine, 'I/O error occured') !== false) {
				return 'I/O error occured';
			}
			if (strpos($contentLine, 'Incorrect frame size') !== false) {
				return 'Incorrect frame size';
			}
			if (strpos($contentLine, 'Number of stream maps must match number of output streams') !== false) {
				return 'Number of stream maps must match number of output streams';
			}
			if (strpos($contentLine,
			           'WARNING: The bitrate parameter is set too low. It takes bits/s as argument, not kbits/s') !== false) {
				return 'WARNING: The bitrate parameter is set too low. It takes bits/s as argument, not kbits/s';
			}

			if (strpos($contentLine, 'Frame size must be a multiple of 2') !== false) {
				return 'WARNING: Frame size must be a multiple of 2';
			}

			if (strpos($contentLine, 'Codec type mismatch for mapping') !== false) {
				return 'WARNING: Codec type mismatch!';
			}

			if (strpos($contentLine, 'Error') !== false
			    || strpos($contentLine, 'error') !== false) {
				return 'Error';
			}
			unset($contentLine);
		}
		return null;
	}

	/**
	 * parse a logfile output
	 *
	 * @param string &$conent
	 * @return string
	 */
	public static function getLogfileErrorFromVideoSnapshot(&$content)
	{
		return self::getLogfileErrorFromVideoConvert($content);
	}
}
