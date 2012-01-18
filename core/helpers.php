<?php

/**
 * @param array $array
 *
 * @return object
 */
function prosc_array_to_object($array)
{
    if (!is_array($array)) {
        return $array;
    }

    $object = new stdClass();

    if (is_array($array) && count($array) > 0) {
        foreach ($array as $name => $value) {
            if (!empty($name)) {
                $object->$name = prosc_array_to_object($value);
            }
        }

        return $object;
    }
    else {
        return FALSE;
    }
}

/**
 * @param string $prefix
 * @param array  $idList
 *
 * @return array
 */
function prosc_array_prefixing(string $prefix, array $idList)
{
    $prefixList = array();
    foreach ($idList as $idItem)
    {
        $prefixList[] = $prefix . $idItem->id;
    }
    return $prefixList;
}

/**
 * @return float
 */
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

/**
 * @param $message
 */
function trace($message)
{
    echo "[" . udate('H:i:s:u') . "]" . $message .PHP_EOL;
}

function udate($format, $utimestamp = null)
{
    if (is_null($utimestamp))
        $utimestamp = microtime(true);

    $timestamp = floor($utimestamp);
    $milliseconds = round(($utimestamp - $timestamp) * 1000000);

    return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
}

/**
 * @param $unixtime
 * @return string
 */
function convertUnixTimeToIso($unixtime)
{
    return date('Y-m-d\TH:i:s', $unixtime);
}