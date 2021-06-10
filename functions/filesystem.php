<?php

namespace Enjoys\FileSystem;

/**
 * @param string $file
 * @param string $data
 * @param string $mode
 * @return void
 */
function writeFile(string $file, string $data, string $mode = 'w')
{
    $f = fopen($file, $mode);
    if ($f !== false) {
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
    if (preg_match("/(\/\.+|\.+)$/i", $path)) {
        throw new \Exception(
            sprintf("Нельзя создать директорию: %s", $path)
        );
    }

    error_clear_last();

    if (!is_dir($path)) {
        if (@mkdir($path, $permissions, true) === false) {
            $error = error_get_last();
            throw new \Exception(
                sprintf("Не удалось создать директорию: %s! Причина: %s", $path, $error['message'])
            );
        }
    }
}


function removeDirectoryRecursive(string $path, $removeParent = false)
{
    $di = new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS);
    $ri = new \RecursiveIteratorIterator($di, \RecursiveIteratorIterator::CHILD_FIRST);

    /** @var \SplFileInfo $file */
    foreach ($ri as $file) {
        if ($file->isLink()) {
            $symlink = realpath($file->getPath()) . DIRECTORY_SEPARATOR . $file->getFilename();
            if (PHP_OS_FAMILY == 'Windows') {
                (is_dir($symlink)) ? rmdir($symlink) : unlink($symlink);
            } else {
                unlink($symlink);
            }
            continue;
        }
        $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
    }
    if ($removeParent) {
        rmdir($path);
    }
}

/**
 * @throws \Exception
 */
function copyDirectoryWithFilesRecursive($source, $target)
{
    createDirectory($target, 0755);

    foreach (
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        ) as $item
    ) {
        if ($item->isDir()) {
            mkdir($target . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
        } else {
            copy($item, $target . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
        }
    }
}

/**
 * @throws \Exception
 */
function CreateSymlink(string $link, string $target)
{
    $directory = pathinfo($link, PATHINFO_DIRNAME);
    createDirectory($directory, 0755);

    if (!file_exists($target)) {
        throw new \InvalidArgumentException(
            sprintf("Цeлевой директории или файла не существует. Symlink на %s не создан", $target)
        );
    }

    $linkSpl = new \SplFileInfo($link);

    if (($linkSpl->isLink() || $linkSpl->isFile() || $linkSpl->isDir()) && $linkSpl->isReadable()) {
        return;
    }

    if ($linkSpl->isLink() && !$linkSpl->isReadable()) {
        unlink($linkSpl->getPathname());
    }

    symlink($target, $link);
}