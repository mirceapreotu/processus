<?php

/**
 * @param array $array
 * @return object
 */
function array_to_object($array)
{
    if (! is_array($array)) {
        return $array;
    }
    
    $object = new stdClass();
    
    if (is_array($array) && count($array) > 0) {
        foreach ($array as $name => $value) {
            if (! empty($name)) {
                $object->$name = array_to_object($value);
            }
        }
        
        return $object;
    }
    else {
        return FALSE;
    }
}
