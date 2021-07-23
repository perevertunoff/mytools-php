<?php

namespace Perevertunoff\MyToolsPhp\Functions;

class ArrayFunctions
{
    public static function returnArray($array = null)
    {
        if (is_array($array)) return $array;
        return false;
    }

    public static function returnTrueArray($array = null)
    {
        if (self::returnArray($array) && $array) return $array;
        return false;
    }

    public static function returnTrueArrayKey($key = null)
    {
        if (is_numeric($key) || (is_string($key) && $key)) return $key;
        return false;
    }

    public static function returnArrayValueByKey($array = null, $key = null)
    {
        $array = self::returnTrueArray($array);
        $key = self::returnTrueArrayKey($key);
        if ($array && $key && isset($array[$key])) return $array[$key];
        return false;
    }

    public static function returnTrueArrayValueByKey($array = null, $key = null)
    {
        $value = self::returnArrayValueByKey($array, $key);
        if ($value) return $value;
        return false;
    }

    public static function returnArrayOrValueByKey($array = null, $key = null)
    {
        if ($key === null) return self::returnTrueArray($array);
        return self::returnTrueArrayValueByKey($array, $key);
    }

    public static function isArrayValueByKey($array = null, $keys_and_values = null, bool $all_matches = true)
    {
        $array = self::returnTrueArray($array);
        $keys_and_values = self::returnTrueArray($keys_and_values);

        if ($array && $keys_and_values) {
            foreach ($keys_and_values as $key => $value) {
                $check_point = false;
                if ($value === null && isset($array[$key])) {
                    if ($all_matches === false) return true;
                    $check_point = true;
                } else if ($value === true && isset($array[$key]) && $array[$key]) {
                    if ($all_matches === false) return true;
                    $check_point = true;
                } else if ($value === false && isset($array[$key]) && !$array[$key]) {
                    if ($all_matches === false) return true;
                    $check_point = true;
                } else if ($value && isset($array[$key]) && $array[$key] == $value) {
                    if ($all_matches === false) return true;
                    $check_point = true;
                }
            }
            if (isset($check_point) && $check_point === true) return true;
        }

        return false;
    }

    public static function returnArrayFromArrayByKeyValue($array = null, $keys_and_values = null, bool $all_matches = true, bool $return_all = false, bool $return_key = false)
    {
        $array = self::returnTrueArray($array);
        $keys_and_values = self::returnTrueArray($keys_and_values);

        if ($array && $keys_and_values) {
            foreach ($array as $item_key => $item_value) {
                if ($item_value = self::returnTrueArray($item_value)) {
                    if (self::isArrayValueByKey($item_value, $keys_and_values, $all_matches)) {
                        if ($return_all === true) {
                            ($return_key === true ? $result[$item_key] = $item_value : $result[] = $item_value);
                            continue;
                        } else {
                            ($return_key === true ? $result[$item_key] = $item_value : $result = $item_value);
                            break;
                        }
                    }
                }
            }
            if (isset($result) && $result) return $result;
        }

        return false;
    }

    public static function returnArrayMirrored($array = null)
    {
        if ($array = self::returnTrueArray($array)) {
            foreach ($array as $key => $value) {
                $result[$value] = $key;
            }
            if (isset($result) && $result) return $result;
        }

        return false;
    }

    public static function returnArrayWithChangedKeys($array = null, $new_keys = null, bool $only_new_keys = false)
    {
        $array = self::returnTrueArray($array);
        $new_keys = self::returnTrueArray($new_keys);

        if ($array && $new_keys) {
            foreach ($array as $key => $value) {
                if (isset($new_keys[$key]) && $new_keys[$key]) {
                    if ($only_new_keys) {
                        $new_array[$new_keys[$key]] = $value;
                    } else {
                        $array[$new_keys[$key]] = $value;
                        unset($array[$key]);
                    }
                }
            }
            if ($only_new_keys && isset($new_array) && $new_array) {
                return $new_array;
            } else if (!$only_new_keys && $array) {
                return $array;
            }
        }

        return false;
    }
}
