<?php

namespace Perevertunoff\MyToolsPhp\Functions;

class OtherFunctions
{
    public static function returnArrayIncludedFiles($path = null, $file_extension = null)
    {
        if (is_string($path) && $path && ($path = preg_replace('#/$#', '', $path)) && is_dir($path) && $scandir = scandir($path)) {
            $result = [];
            foreach ($scandir as $file) {
                if (is_file("$path/$file")) {
                    if ($file_extension === null) {
                        $key = $file;
                        $result[$key] = include "$path/$file";
                    } else if (is_string($file_extension) && $file_extension && stristr($file, $file_extension)) {
                        $key = preg_replace('#'.$file_extension.'$#', '', $file);
                        $result[$key] = include "$path/$file";
                    }
                }
            }
            return ArrayFunctions::returnTrueArray($result);
        }

        return false;
    }

    public static function returnFormattedRusPhoneNumber($phone = null, $prefix = null)
    {
        if ($phone && (is_string($phone) || is_numeric($phone))) {
            $phone = preg_replace("/[^0-9]/", '', $phone);
            $first_number = substr($phone, 0, 1);
            if (iconv_strlen($phone) == 11 && ($first_number == 7 || $first_number == 8)) {
                $phone = mb_substr($phone, 1);
            }
            if (iconv_strlen($phone) == 10) {
                if ($prefix === null) {
                    return $phone;
                } else if ($prefix && (is_string($phone) || is_numeric($phone))) {
                    return $prefix.$phone;
                }
            }
        }

        return false;
    }
}
