<?php

/**
 * Lib_Utils_File_MimeType
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Utils_File
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
 * @package		Lib_Utils_File
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_Utils_File_MimeType {
	const  FILECOMMAND = '/usr/bin/file ';

	/**
	 * Liste der Mimetypes
	 *
	 * @var array
	 */
	private static $mimeTypeList = array(

		// Document
		'text/html' => array('type' => 'Document', 'label' => 'HTML File', 'extensions' => array('html', 'htm', 'shtml')),
		'application/xhtml+xml' => array('type' => 'Document', 'label' => 'XHTML File', 'extensions' => array('html', 'htm', 'shtml', 'xhtml')),
		'text/plain' => array('type' => 'Document', 'label' => 'Text file', 'extensions' => array('txt')),
		'text/richtext' => array('type' => 'Document', 'label' => 'RTF File', 'extensions' => array('rtf')),
		'text/rtf' => array('type' => 'Document', 'label' => 'RTF File', 'extensions' => array('rtf')),
		'application/rtf' => array('type' => 'Document', 'label' => 'RTF File', 'extensions' => array('rtf')),
		'text/tab-separated-values' => array('type' => 'Document', 'label' => 'Tabulator Separated File', 'extensions' => array('tsv')),
		'text/comma-separated-values' => array('type' => 'Document', 'label' => 'Comma Separated File', 'extensions' => array('csv')),
		'text/x-setext' => array('type' => 'Document', 'label' => 'SeText File', 'extensions' => array('etx')),
		'text/server-parsed-html' => array('type' => 'Document', 'label' => 'SSI File', 'extensions' => array('ssi')),
		'application/pdf' => array('type' => 'Document', 'label' => 'PDF File', 'extensions' => array('pdf')),
		'application/postscript' => array('type' => 'Document', 'label' => 'PostScript File', 'extensions' => array('pdf', 'ps', 'eps')),
		'application/groupwise' => array('type' => 'Document', 'label' => 'Groupwise File', 'extensions' => array('vew')),
		'application/envoy' => array('type' => 'Document', 'label' => 'Envoy File', 'extensions' => array('evy')),
		'application/x-envoy' => array('type' => 'Document', 'label' => 'Envoy File', 'extensions' => array('evy')),
		'application/msword' => array('type' => 'Document', 'label' => 'Microsoft Word File', 'extensions' => array('doc', 'dot')),
		'application/ms-word' => array('type' => 'Document', 'label' => 'Microsoft Word File', 'extensions' => array('doc')),
		'application/vnd.ms-word' => array('type' => 'Document', 'label' => 'Microsoft Word File', 'extensions' => array('doc')),
		'application/msexcel' => array('type' => 'Document', 'label' => 'Microsoft Excel File', 'extensions' => array('xls', 'xla')),
		'application/ms-excel' => array('type' => 'Document', 'label' => 'Microsoft Excel File', 'extensions' => array('xls', 'xla')),
		'application/vnd.ms-excel' => array('type' => 'Document', 'label' => 'Microsoft Excel File', 'extensions' => array('xls', 'xla')),
		'application/mspowerpoint' => array('type' => 'Document', 'label' => 'Microsoft Powerpoint File', 'extensions' => array('ppt', 'ppz', 'pps', 'pot')),
		'application/ms-powerpoint' => array('type' => 'Document', 'label' => 'Microsoft Powerpoint File', 'extensions' => array('ppt')),
		'application/vnd.ms-powerpoint' => array('type' => 'Document', 'label' => 'Microsoft Powerpoint File', 'extensions' => array('ppt')),
		'application/msvisio' => array('type' => 'Document', 'label' => 'Microsoft Visio File', 'extensions' => array('vsd')),
		'application/ms-visio' => array('type' => 'Document', 'label' => 'Microsoft Visio File', 'extensions' => array('vsd')),
		'application/vnd.ms-visio' => array('type' => 'Document', 'label' => 'Microsoft Visio File', 'extensions' => array('vsd')),
		'application/oda' => array('type' => 'Document', 'label' => 'Oda File', 'extensions' => array('oda')),
		'application/acad' => array('type' => 'Document', 'label' => 'AutoCAD File', 'extensions' => array('dwg')),
		'application/dxf' => array('type' => 'Document', 'label' => 'AutoCAD File', 'extensions' => array('dxf')),
		'application/dsptype' => array('type' => 'Document', 'label' => 'TSP File', 'extensions' => array('tsp')),
		'application/mbedlet' => array('type' => 'Document', 'label' => 'Mbedlet File', 'extensions' => array('mbd')),
		'application/mshelp' => array('type' => 'Document', 'label' => 'Microsoft Windows Help File', 'extensions' => array('hlp', 'chm')),
		'application/rtc' => array('type' => 'Document', 'label' => 'RTC File', 'extensions' => array('rtc')),
		'application/studiom' => array('type' => 'Document', 'label' => 'Studiom File', 'extensions' => array('smp')),
		'application/toolbook' => array('type' => 'Document', 'label' => 'Toolbook File', 'extensions' => array('tbk')),
		'application/mif' => array('type' => 'Document', 'label' => 'FrameMaker Interchange Format File', 'extensions' => array('mif')),
		'application/xml' => array('type' => 'Document', 'label' => 'XML File', 'extensions' => array('xml')),
		'text/xml' => array('type' => 'Document', 'label' => 'XML File', 'extensions' => array('xml')),
		'application/x-latex' => array('type' => 'Document', 'label' => 'LaTeX Source File', 'extensions' => array('latex')),
		'application/x-netcdf' => array('type' => 'Document', 'label' => 'Unidata CDF File', 'extensions' => array('nc', 'cdf')),
		'application/x-tcl' => array('type' => 'Document', 'label' => 'TCL Scrip File', 'extensions' => array('tcl')),
		'application/x-tex' => array('type' => 'Document', 'label' => 'TeX File', 'extensions' => array('tex')),
		'application/x-texinfo' => array('type' => 'Document', 'label' => 'TeXinfo File', 'extensions' => array('texinfo', 'texi')),
		'application/x-troff' => array('type' => 'Document', 'label' => 'TROFF File (UNIX)', 'extensions' => array('t', 'tr', 'roff')),
		'application/x-troff-man' => array('type' => 'Document', 'label' => 'TROFF MAN-Makro File (UNIX)', 'extensions' => array('man', 'troff')),
		'application/x-troff-me' => array('type' => 'Document', 'label' => 'TROFF ME-Makro File (UNIX)', 'extensions' => array('me', 'troff')),
		'text/richtext' => array('type' => 'Document', 'label' => 'Richtext File', 'extensions' => array('rtx')),
		'application/x-director' => array('type' => 'Document', 'label' => 'Macromedia Director File', 'extensions' => array('dcr', 'dir', 'dxr')),
		'application/x-mif' => array('type' => 'Document', 'label' => 'FrameMaker Interchange Format File', 'extensions' => array('mif')),
		'application/x-sgml' => array('type' => 'Document', 'label' => 'SGML File', 'extensions' => array('sgm', 'sgml')),

		// Image
		'image/gif' => array('type' => 'Image', 'label' => 'GIF File', 'extensions' => array('gif')),
		'image/ief' => array('type' => 'Image', 'label' => 'IEF File', 'extensions' => array('ief')),
		'image/jpeg' => array('type' => 'Image', 'label' => 'JPEG File', 'extensions' => array('jpg', 'jpeg', 'jpe')),
		'image/png' => array('type' => 'Image', 'label' => 'PNG File', 'extensions' => array('png')),
		'image/tiff' => array('type' => 'Image', 'label' => 'TIFF File', 'extensions' => array('tif', 'tiff')),
		'image/bmp' => array('type' => 'Image', 'label' => 'Bitmap File', 'extensions' => array('bmp')),
		'image/x-ms-bmp' => array('type' => 'Image', 'label' => 'Bitmap File', 'extensions' => array('bmp')),
		'image/vnd.wap.wbmp' => array('type' => 'Image', 'label' => 'Bitmap File (WAP)', 'extensions' => array('wbmp')),
		'image/x-portable-anymap' => array('type' => 'Image', 'label' => 'PBM Anymap File', 'extensions' => array('pnm')),
		'image/x-portable-bitmap' => array('type' => 'Image', 'label' => 'PBM Bitmap File', 'extensions' => array('pbm')),
		'image/x-portable-graymap' => array('type' => 'Image', 'label' => 'PBM Graymap File', 'extensions' => array('pgm')),
		'image/x-portable-pixmap' => array('type' => 'Image', 'label' => 'PBM Pixmap File', 'extensions' => array('ppm')),
		'image/x-rgb' => array('type' => 'Image', 'label' => 'RGB File', 'extensions' => array('rgb')),
		'image/x-cmyk' => array('type' => 'Image', 'label' => 'CMYK File', 'extensions' => array('cmyk')),
		'application/x-ima' => array('type' => 'Image', 'label' => 'IMA File', 'extensions' => array('ima')),
		'image/fif' => array('type' => 'Image', 'label' => 'FIF File', 'extensions' => array('fif')),
		'image/vasa' => array('type' => 'Image', 'label' => 'Vasa File', 'extensions' => array('mcf')),
		'image/x-photoshop' => array('type' => 'Image', 'label' => 'Photoshop File', 'extensions' => array('psd')),
		'image/x-freehand' => array('type' => 'Image', 'label' => 'Freehand File', 'extensions' => array('fhc', 'fh4', 'fh5')),
		'image/x-icon' => array('type' => 'Image', 'label' => 'Icon File', 'extensions' => array('ico')),
		'image/x-xbitmap' => array('type' => 'Image', 'label' => 'XBM File', 'extensions' => array('xbm')),
		'image/x-xpixmap' => array('type' => 'Image', 'label' => 'XPM File', 'extensions' => array('xpm')),
		'drawing/x-dwf' => array('type' => 'Image', 'label' => 'Drawing File', 'extensions' => array('dwf')),
		'image/cis-cod' => array('type' => 'Image', 'label' => 'CIS Cod File', 'extensions' => array('cod')),
		'image/cmu-raster' => array('type' => 'Image', 'label' => 'CMU Raster File', 'extensions' => array('ras')),

		// Data
		'application/octet-stream' => array('type' => 'Data', 'label' => 'Binary File', 'extensions' => array('bin', 'exe', 'com', 'dll', 'class')),
		'image/x-windowdump' => array('type' => 'Data', 'label' => 'X-Windows Dump File', 'extensions' => array('xwd')),
		'application/zip' => array('type' => 'Data', 'label' => 'ZIP File', 'extensions' => array('zip')),
		'application/x-zip' => array('type' => 'Data', 'label' => 'ZIP File', 'extensions' => array('zip')),
		'application/x-zip-compressed' => array('type' => 'Data', 'label' => 'ZIP File', 'extensions' => array('zip')),
		'multipart/x-zip' => array('type' => 'Data', 'label' => 'ZIP File', 'extensions' => array('zip')),
		'application/gzip' => array('type' => 'Data', 'label' => 'GNU ZIP File', 'extensions' => array('gz')),
		'application/x-gzip' => array('type' => 'Data', 'label' => 'GNU ZIP File', 'extensions' => array('gz')),
		'application/x-gtar' => array('type' => 'Data', 'label' => 'GNU TAR File', 'extensions' => array('tar', 'gtar')),
		'application/mac-binhex40' => array('type' => 'Data', 'label' => 'Macintosh Binary File', 'extensions' => array('hqx')),
		'application/x-stuffit' => array('type' => 'Data', 'label' => 'Macintosh Stuffit File', 'extensions' => array('sit')),

		// Software
		'text/javascript' => array('type' => 'Software', 'label' => 'JavaScript File', 'extensions' => array('sit', 'js')),
		'application/applefile' => array('type' => 'Software', 'label' => 'Apple File', 'extensions' => array()),
		'application/futuresplash' => array('type' => 'Software', 'label' => 'Flash Futuresplash File', 'extensions' => array('spl')),
		'application/x-hdf' => array('type' => 'Software', 'label' => 'HDF File', 'extensions' => array('hdf')),
		'application/x-httpd-php' => array('type' => 'Software', 'label' => 'PHP File', 'extensions' => array('php', 'phtml')),
		'application/x-javascript' => array('type' => 'Software', 'label' => 'JavaScript File', 'extensions' => array('js')),
		'application/x-bcpio' => array('type' => 'Software', 'label' => 'BCPIO File', 'extensions' => array('bcpio')),
		'application/x-compress' => array('type' => 'Software', 'label' => 'ZLIB Compressed File', 'extensions' => array('z')),
		'application/x-cpio' => array('type' => 'Software', 'label' => 'CPIO File', 'extensions' => array('cpio')),
		'application/x-csh' => array('type' => 'Software', 'label' => 'C-Shellscript File', 'extensions' => array('csh')),
		'application/x-macbinary' => array('type' => 'Software', 'label' => 'Macintosh Binary File', 'extensions' => array('bin')),
		'application/x-nschat' => array('type' => 'Software', 'label' => 'NS Chat File', 'extensions' => array('nsc')),
		'application/x-sh' => array('type' => 'Software', 'label' => 'FBourne Shellscript File', 'extensions' => array('sh')),
		'application/x-shar' => array('type' => 'Software', 'label' => 'Shell-Archiv File', 'extensions' => array('shar')),
		'application/x-sprite' => array('type' => 'Software', 'label' => 'Sprite File', 'extensions' => array('spr')),
		'application/x-supercard' => array('type' => 'Software', 'label' => 'Supercard File', 'extensions' => array('sca')),
		'application/x-sv4cpio' => array('type' => 'Software', 'label' => 'CPIO File', 'extensions' => array('sv4cpio')),
		'application/x-sv4crc' => array('type' => 'Software', 'label' => 'Sprite File', 'extensions' => array('sv4crc')),
		'application/x-tar' => array('type' => 'Software', 'label' => 'TAR Archiv File', 'extensions' => array('tar')),
		'application/x-ustar' => array('type' => 'Software', 'label' => 'TAR Archiv File (POSIX)', 'extensions' => array('ustar')),
		'application/x-wais-source' => array('type' => 'Software', 'label' => 'WAIS Source File', 'extensions' => array('src')),
		'text/css' => array('type' => 'Software', 'label' => 'CSS Stylesheet File', 'extensions' => array('css')),
		'text/vnd.wap.wml' => array('type' => 'Software', 'label' => 'WML File (WAP)', 'extensions' => array('wml')),
		'application/vnd.wap.wmlc' => array('type' => 'Software', 'label' => 'WMLC File (WAP)', 'extensions' => array('wmlc')),
		'text/vnd.wap.wmlscript' => array('type' => 'Software', 'label' => 'WML Script (WAP)', 'extensions' => array('wmls')),
		'application/vnd.wap.wmlscriptc' => array('type' => 'Software', 'label' => 'WML Script (WAP)', 'extensions' => array('wmlsc')),

		// Flash
		'application/x-shockwave-flash' => array('type' => 'Flash', 'label' => 'Flash Shockwave File', 'extensions' => array('swf', 'cab')),

		// Video
		'video/mpeg' => array('type' => 'Video', 'label' => 'MPEG File', 'extensions' => array('mpeg')),
		'video/mpeg' => array('type' => 'Video', 'label' => 'MPEG File', 'extensions' => array('mpeg', 'mpg', 'mpe')),
		'video/mp2p' => array('type' => 'Video', 'label' => 'MPEG File', 'extensions' => array('mpeg')),
		'video/mp4' => array('type' => 'Video', 'label' => 'MPEG File', 'extensions' => array('mpeg')),
		'video/mpv' => array('type' => 'Video', 'label' => 'MPEG File', 'extensions' => array('mpeg')),
		'video/quicktime' => array('type' => 'Video', 'label' => 'Quicktime File', 'extensions' => array('mov', 'qt')),
		'video/x-msvideo' => array('type' => 'Video', 'label' => 'Microsoft AVI File', 'extensions' => array('avi')),
		'video/x-ms-wmv' => array('type' => 'Video', 'label' => 'Windows Media File', 'extensions' => array('wmv')),
		'video/x-ms-asf' => array('type' => 'Video', 'label' => 'Windows Media File', 'extensions' => array('asf')),
		'video/x-sgi-movie' => array('type' => 'Video', 'label' => 'Movie File', 'extensions' => array('sgi', 'movie')),
		'application/vocaltec-media-desc' => array('type' => 'Video', 'label' => 'Vocaltec Mediadesc File', 'extensions' => array('vmd', 'vmf')),
		'application/x-dvi' => array('type' => 'Video', 'label' => 'DVI File', 'extensions' => array('dvi')),
		'video/vnd.vivo' => array('type' => 'Video', 'label' => 'Vivo File', 'extensions' => array('viv', 'vivo')),
		'workbook/formulaone' => array('type' => 'Video', 'label' => 'FormulaOne File', 'extensions' => array('vts', 'vtts')),
		'video/x-flv' => array('type' => 'Video', 'label' => 'FLV File', 'extensions' => array('flv', 'vts')),
		'video/x-mpeg' => array('type' => 'Audio', 'label' => 'MPEG File', 'extensions' => array('mp2')),
		'video/basic au' => array('type' => 'Audio', 'label' => 'Sound File', 'extensions' => array('au')),
		'video/basic' => array('type' => 'Audio', 'label' => 'Sound File', 'extensions' => array('au', 'snd')),
		'video/x-aiff' => array('type' => 'Audio', 'label' => 'AIFF File', 'extensions' => array('aif', 'aiff', 'aifc')),
		'video/x-wav' => array('type' => 'Audio', 'label' => 'WAV File', 'extensions' => array('wav')),
		'video/x-speech' => array('type' => 'Audio', 'label' => 'Speech File', 'extensions' => array('talk', 'spc')),
		'video/x-3dmf' => array('type' => 'Audio', 'label' => '3DMF File', 'extensions' => array('3dmf', '3dm', 'qd3d', 'qd3')),
		'application/astound' => array('type' => 'Audio', 'label' => 'Astound File', 'extensions' => array('asd', 'asn')),
		'application/listenup' => array('type' => 'Audio', 'label' => 'Listenup File', 'extensions' => array('ptlk')),

		// Audio
		'audio/mpeg' => array('type' => 'Audio', 'label' => 'MP3 file', 'extensions' => array('mp3')),
		'audio/echospeech' => array('type' => 'Audio', 'label' => 'Echospeed File', 'extensions' => array('es')),
		'audio/tsplayer' => array('type' => 'Audio', 'label' => 'TS Player File', 'extensions' => array('tsi')),
		'audio/voxware' => array('type' => 'Audio', 'label' => 'Vox File', 'extensions' => array('vox')),
		'audio/x-dspeeh' => array('type' => 'Audio', 'label' => 'Speech File', 'extensions' => array('dus', 'cht')),
		'audio/x-midi' => array('type' => 'Audio', 'label' => 'MIDI File', 'extensions' => array('mid', 'midi')),
		'audio/x-pn-realaudio' => array('type' => 'Audio', 'label' => 'RealAudio File', 'extensions' => array('ram', 'ra')),
		'audio/x-pn-realaudio-plugin' => array('type' => 'Audio', 'label' => 'RealAudio Plugin File', 'extensions' => array('rpm')),
		'audio/x-qt-stream' => array('type' => 'Audio', 'label' => 'Quicktime Streaming File', 'extensions' => array('stream')),
		'audio/x-wav' => array('type' => 'Audio', 'label' => 'WAV File', 'extensions' => array('es')),
		'audio/echospeech' => array('type' => 'Audio', 'label' => 'Echospeed File', 'extensions' => array('wav')),
	);

	/**
	 * return list of mime types
	 *
	 * @return &array
	 */
	public static function &getMimeTypeList()
	{
		return self::$mimeTypeList;
	}

	/**
	 * check, mime type exists
	 *
	 * @param string $mimeType
	 * @return bool
	 */
	public static function mimeTypeExists($mimeType)
	{
		return array_key_exists($mimeType, self::$mimeTypeList);
	}

	/**
	 * check, mime type exists
	 *
	 * @param string $mimeType
	 * @return bool
	 */
	public static function getExtension($mimeType)
	{
		if (is_string($mimeType)
		    && array_key_exists($mimeType, self::$mimeTypeList)
		    && count(self::$mimeTypeList[$mimeType]['extensions']) > 0) {
			return self::$mimeTypeList[$mimeType]['extensions'][0];
		}
		return '';
	}

	/**
	 * Gibt die Extension anhand des Pathnames zurück.
	 *
	 * @param string $pathname
	 * @param string $extensionPrefix
	 * @return string
	 */
	public static function getExtensionByPathname($pathname, $extensionPrefix = '.')
	{
		$mimeType = self::getByPathname($pathname);
		$extension = self::getExtension($mimeType);
		if (!empty($extension)) {
			return $extensionPrefix . $extension;
		}
		return '';
	}

	/**
	 * gibt den file type zurueck
	 *
	 * @param string $mimeType
	 * @return string
	 */
	public static function getType($mimeType)
	{
		if (is_string($mimeType)
		    && array_key_exists($mimeType, self::$mimeTypeList)) {
			return self::$mimeTypeList[$mimeType]['type'];
		}
		return 'unknown';
	}

	/**
	 * return type by pathname
	 *
	 * @param string $pathname
	 * @return string
	 */
	public static function getTypeByPathname($pathname)
	{
		return self::getType(self::getByPathname($pathname));
	}

	/**
	 * return mime type
	 *
	 * @param string $pathname
	 * @return string
	 */
	public static function getByPathname($pathname)
	{
		$command = self::FILECOMMAND . ' -bi ' . escapeshellarg($pathname);
		$mimeString = trim(`$command 2>&1`);
		$mimeList = explode(' ', $mimeString);
		return (string)str_replace(';', '',$mimeList[0]);
	}
}