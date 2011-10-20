<?php
/**
 * Created by JetBrains PhpStorm.
 * User: VAIO
 * Date: 24.09.11
 * Time: 07:45
 * To change this template use File | Settings | File Templates.
 */
 
class Redisek_Util_Fnmatch
{


    /**
     * @static
     * @param  string|int|float $value
     * @param array $patternList
     * @param  null|int $flags
     * @return bool
     */
    public static function matchOne(
        $value,
        array $patternList,
        $flags
    ) {

        $result = false;
        $isValidType = (
                    (is_string($value))
                    ||(is_int($value))
                    ||(is_float($value))
        );
        if (is_bool($value)) {
            $isValidType = false;
        }

        if (!$isValidType) {
            return $result;
        }

        $value = (string)$value;

        foreach($patternList as $pattern) {
            if ($flags !== null) {
                $isMatched = fnmatch($pattern, $value, $flags);
            } else {
                $isMatched = fnmatch($pattern, $value, $flags);
            }
            if ($isMatched===true) {
                return true;
            }
        }

        return $result;
    }


    /**
     * @static
     * @param  string|int|float $value
     * @param array $whitelist
     * @param array $blacklist
     * @param  int|null $flags
     * @return bool
     */
    public static function isWhitelistedAndNotBlacklisted(
        $value,
        array $whitelist,
        array $blacklist,
        $flags
            )
    {
        $result = false;
        $isWhitelisted = self::matchOne($value, $whitelist, $flags);
        if (!$isWhitelisted) {
            return $result;
        }
        $isBlacklisted = self::matchOne($value, $blacklist, $flags);
        if (!$isBlacklisted) {
            return true;
        }

        return $result;

    }



}
