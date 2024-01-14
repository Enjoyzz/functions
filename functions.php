<?php

use Enjoys\Functions\Arrays;
use Enjoys\Functions\Convert;
use Enjoys\Functions\Filesystem;
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
        return Truncate::smartTruncate($text, $maxlength, $continue, $tailMinLength, $isCut);
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
        return Truncate::simpleTruncate($text, $length, $continue);
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
        return Truncate::simpleTruncate($text, $length, $continue);
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
        return Hyphenize::handle($s, $isHtml, $algo);
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
        return Convert::iniSize2bytes($phpIniSize);
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
        return Convert::iniSize2bytes($phpIniSize);
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
        return Convert::bytes2iniSize($size);
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
        return Convert::bytes2iniSize($size);
    }
}

if (!function_exists('getValueByIndexPath')) {
    function getValueByIndexPath(string $indexPath, array $data = [])
    {
        return Arrays::getValueByIndexPath($indexPath, $data);
    }
}

if (!function_exists('array_merge_recursive_distinct')) {
    function array_merge_recursive_distinct(array $array_o, array $array_i): array
    {
        return Arrays::mergeRecursiveDistinct($array_o, $array_i);
    }
}


if (!function_exists('array_insert_before')) {
    function array_insert_before(array $array, $key, $data): array
    {
        return Arrays::insertBefore($array, $key, $data);
    }
}


if (!function_exists('array_insert_after')) {
    function array_insert_after(array $array, $key, $data): array
    {
        return Arrays::insertAfter($array, $key, $data);
    }
}

if (!function_exists('glob_recursive')) {
    function glob_recursive(string $pattern, int $flags = 0, bool $false_return = false)
    {
        return Filesystem::globRecursive($pattern, $flags, $false_return);
    }
}