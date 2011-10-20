<?php
/**
 * Misc. functions which don't belong to a class.
 * Keep this file short!
 *
 * @category    default
 * @package     Default
 * @copyright   Copyright (c) 2010 exitb GmbH (http://exitb.de)
 * @license     http://exitb.de/license/default     Default License
 * @version     $Id$
 */

/**
 * @param string $str
 * @return string
 */
function l10n($str)
{
	return $str;
}

/**
 * Returns the first non-(null|false) value of the argument list or null
 * 
 * @return mixed|null
 */
function coalesce()
{
	for ($i = 0;$i < func_num_args();$i++) {

		if (
			(func_get_arg($i) === null)
			|| (func_get_arg($i) === false)
		) {
			continue;
		}
		return func_get_arg($i);
	}
    return null;
}

