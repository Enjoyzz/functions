<?php

declare(strict_types=1);


namespace Enjoys\Functions;


final class Arrays
{

    public static function insertBefore(array $array, $key, $data): array
    {
        return (new ArrayInsert($array))->before($key, $data);
    }

    public static function insertAfter(array $array, $key, $data): array
    {
        return (new ArrayInsert($array))->after($key, $data);
    }

    public static function mergeRecursiveDistinct(array $array_o, array $array_i): array
    {
        foreach ($array_i as $k => $v) {
            if (!isset($array_o[$k])) {
                $array_o[$k] = $v;
            } else {
                if (is_array($array_o[$k])) {
                    if (is_array($v)) {
                        $array_o[$k] = self::mergeRecursiveDistinct($array_o[$k], $v);
                    } else {
                        $array_o[$k] = $v;
                    }
                } else {
                    if (!isset($array_o[$k])) {
                        $array_o[$k] = $v;
                    } else {
                        $array_o[$k] = array($array_o[$k]);
                        $array_o[$k] = $v;
                    }
                }
            }
        }

        return $array_o;
    }

    /**
     *
     * @param string $indexPath
     * @param array $data
     * @return mixed
     */
    public static function getValueByIndexPath(string $indexPath, array $data = [])
    {
        $empty_key = 0;

        preg_match_all("/^([\w\d\-]*)|\[['\"]*(|[a-z0-9_-]+)['\"]*]/i", $indexPath, $matches);
        $last_key = array_key_last($matches[0]);

        if (count($matches[0]) > 0 && !empty($matches[0][0])) {
            foreach ($matches[0] as $identify => $key) {
                if ($key == $indexPath && isset($data[$key])) {
                    return $data[$key];
                }

                if (!is_array($data)) {
                    return false;
                }
                $key = str_replace(['[', ']', '"', '\''], [], $key);
                //если последняя и key пустой [] вернуть все
                if ($identify == $last_key && in_array($key, ['', 0], true)) {
                    if (isset($data[0]) && count($data) > 1) {
                        break;
                    }
                }
                if ($key === '') {
                    $key = $empty_key;
                }
                if (!isset($data[$key])) {
                    return false;
                }
                if ($identify == $last_key && $key !== '') {
                    if (is_array($data[$key])) {
                        return false;
                    }
                }
                $data = $data[$key];
            }
            return $data;
        }
        return false;
    }
}