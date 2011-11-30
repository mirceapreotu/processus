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