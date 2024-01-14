<?php

use Enjoys\Functions\Hyphenize;
use Enjoys\Functions\Truncate;

if (!function_exists('truncate')) {
    /**
     * @param string $text
     * @param int $maxlength
     * @param string $continue
     * @param int $tailMinLength
     * @param bool|null $isCut
     * @return string|null
     */
    function truncate(
        string $text,
        int $maxlength,
        string $continue = "\xe2\x80\xa6",
        int $tailMinLength = 20,
        ?bool &$isCut = null
    ): ?string {
        $truncate = new Truncate($text);
        return $truncate->smartTruncate($maxlength, $continue, $tailMinLength, $isCut);
    }
}

if (!function_exists('truncate_simple')) {
    /**
     * @param string $text
     * @param int $length
     * @param string $continue
     * @return string
     */
    function truncate_simple(string $text, int $length = 0, string $continue = "\xe2\x80\xa6"): string
    {
        $truncate = new Truncate($text);
        return $truncate->simpleTruncate($length, $continue);
    }
}

if (!function_exists('truncateSimple')) {
    /**
     * @param string $text
     * @param int $length
     * @param string $continue
     * @return string
     * @see truncate_simple()
     */
    function truncateSimple(string $text, int $length = 0, string $continue = "\xe2\x80\xa6"): string
    {
        return truncate_simple($text, $length, $continue);
    }
}

if (!function_exists('hyphenize')) {
    /**
     * Расстановка "мягких" переносов в словах.
     * @param string $s
     * @param bool $isHtml
     * @param int $algo \Enjoys\Functions\Hyphenize::KOTEROFF_ALGORITHM, HRISTOFF_ALGORITHM, NASIBULLIN_ALGORITHM
     * @return string|null
     */
    function hyphenize(string $s, bool $isHtml = false, int $algo = Hyphenize::KOTEROFF_ALGORITHM): ?string
    {
        $hyphenize = new Hyphenize($algo);
        return $hyphenize->handle($s, $isHtml);
    }
}

if (!function_exists('ini_size_to_bytes')) {
    /**
     * converts a number with byte unit (B / K / M / G / T) into an integer
     * @param string|int $phpIniSize
     * @return int
     */
    function ini_size_to_bytes($phpIniSize): int
    {
        $unit = preg_replace('/[^bkmgtp]/i', '', $phpIniSize); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9.]/', '', $phpIniSize); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return (int)round($size * pow(1024, stripos('bkmgtp', $unit[0])));
        } else {
            return (int)round($size);
        }
    }
}

if (!function_exists('iniSize2bytes')) {
    /**
     * converts a number with byte unit (B / K / M / G / T) into an integer
     * @param string|int $phpIniSize
     * @return int
     * @see ini_size_to_bytes()
     */
    function iniSize2bytes($phpIniSize): int
    {
        return ini_size_to_bytes($phpIniSize);
    }
}

if (!function_exists('bytes_to_ini_size')) {
    /**
     * convert Bytes to ini_set rules B / K / M / G / T (1024 = 1K)
     * @param int|string $size
     * @return string
     */
    function bytes_to_ini_size($size = 0): string
    {
        $size = (int)$size;
        $filesizename = ["B", "K", "M", "G", "T", "P"];
        return $size ? round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . $filesizename[$i] : '0B';
    }
}

if (!function_exists('bytes2iniSize')) {
    /**
     * convert Bytes to ini_set rules B / K / M / G / T (1024 = 1K)
     * @param int|string $size
     * @return string
     * @see bytes_to_ini_size()
     */
    function bytes2iniSize($size = 0): string
    {
       return  bytes_to_ini_size($size);
    }
}

if (!function_exists('getValueByIndexPath')) {
    /**
     *
     * @param string $indexPath
     * @param array $data
     * @return mixed
     */
    function getValueByIndexPath(string $indexPath, $data = [])
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
                    if (isset($data[0]) && \count($data) > 1) {
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

if (!function_exists('array_merge_recursive_distinct')) {
    function array_merge_recursive_distinct(array $array_o, array $array_i): array
    {
        foreach ($array_i as $k => $v) {
            if (!isset($array_o[$k])) {
                $array_o[$k] = $v;
            } else {
                if (is_array($array_o[$k])) {
                    if (is_array($v)) {
                        $array_o[$k] = array_merge_recursive_distinct($array_o[$k], $v);
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
}


if (!function_exists('array_insert_before')) {
    function array_insert_before(array $array, $key, $data): array
    {
        $insert = new \Enjoys\Functions\Arrays\ArrayInsert($array);
        return $insert->before($key, $data);
    }
}


if (!function_exists('array_insert_after')) {
    function array_insert_after(array $array, $key, $data): array
    {
        $insert = new \Enjoys\Functions\Arrays\ArrayInsert($array);
        return $insert->after($key, $data);
    }
}