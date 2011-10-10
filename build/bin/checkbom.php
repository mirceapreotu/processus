#!/usr/bin/php -q
<?php
/**
 * Scans all project files for illegal BOM prefixed files.
 * Exits with error code 1 if any file was found
 *
 * To fix files in the shell:
 *   sed -i '1 s/^\xef\xbb\xbf//' FILENAME
 *
 */


	$path = trim($argv[1]);

	if ($path == '') {

		die('Error: Specify a path to check!');
	}

	$suffixes = array(
		'php',
		'css',
		'js',
		'html',
		'xml'
	);

	$isBomFilesFound = false;
	$cnt=0;
	$cntBom=0;

	$files = explode("\n", `find $path -type f`);

	foreach ($files as $fileName) {

		if (!preg_match('/\.([a-z0-9]+)$/', strtolower($fileName), $match)) {

			continue; // skip files w/o suffix
		}

		$suffix = $match[1];

		// skip unknown suffixes

		if (!in_array($suffix, $suffixes)) {

			continue;
		}

		if (!is_readable($fileName)) {
			echo "ERROR - $fileName is not readable\n";
			exit(1);
		}

		$cnt++;

		$str = file_get_contents($fileName);
		$bom = pack('CCC', 0xef, 0xbb, 0xbf);
		if (0 == strncmp($str, $bom, 3)) {

			echo "!BOM: ".$fileName."\n";
			$isBomFilesFound = true;
			$cntBom++;
		} else {
			#echo "OK    - ".$fileName."\n";
		}
	}

	if ($isBomFilesFound) {
		echo "ERROR: $cntBom of $cnt files with BOM found!\n";
		exit(1);
	}

	echo "OK: $cnt files checked, no BOM detected.\n";
	exit(0); // we are clean.