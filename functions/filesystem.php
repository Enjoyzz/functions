<?php
namespace Enjoys\FileSystem;

/**
 * @param string $file
 * @param string $data
 * @param string $mode
 * @return void
 */
function writeFile(string $file, string $data, string  $mode = 'w')
{
    $f = fopen($file, $mode);
    if ($f) {
        fwrite($f, $data);
        fclose($f);
    }
}

/**
 * @param string $path
 * @param int $permissions
 * @return void
 * @throws \Exception
 */
function createDirectory(string $path, int $permissions = 0777)
{
    if (!is_dir($path)) {
        if (@mkdir($path, $permissions, true) === false) {
            $error = error_get_last();
            throw new \Exception(
                sprintf("Не удалось создать директорию: %s! Причина: %s", $path, $error['message'])
            );
        }
    }
}