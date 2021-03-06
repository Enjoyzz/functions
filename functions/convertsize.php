<?php

/**
 * converts a number with byte unit (B / K / M / G / T) into an integer
 * @param string|int $phpIniSize
 * @return int
 */
function iniSize2bytes($phpIniSize): int
{
    $unit = preg_replace('/[^bkmgtp]/i', '', $phpIniSize); // Remove the non-unit characters from the size.
    $size = preg_replace('/[^0-9.]/', '', $phpIniSize); // Remove the non-numeric characters from the size.
    if ($unit) {
        // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
        return (int) round($size * pow(1024, stripos('bkmgtp', $unit[0])));
    } else {
        return (int) round($size);
    }
}

/**
 * convert Bytes to ini_set rules B / K / M / G / T (1024 = 1K)
 * @param int $size
 * @return string
 */
function bytes2iniSize($size = 0): string
{
    $size = (int) $size;
    $filesizename = ["B", "K", "M", "G", "T", "P"];
    return $size ? round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . $filesizename[$i] : '0B';
}
