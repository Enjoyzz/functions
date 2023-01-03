<?php

namespace Enjoys\FileSystem;

use FilesystemIterator;

/**
 * @param string $file
 * @param string $data
 * @param string $mode
 * @return void
 * @throws \Exception
 */
function writeFile(string $file, string $data = '', string $mode = 'w')
{
    $dirname = dirname($file);
    createDirectory($dirname);

    $f = fopen($file, $mode);
    if ($f !== false) {
        fwrite($f, $data);
        fclose($f);
    }
}

/**
 * Alias for writeFile
 * @param string $file
 * @param string $data
 * @param string $mode
 * @throws \Exception
 */
function createFile(string $file, string $data = '', string $mode = 'w')
{
    writeFile($file, $data, $mode);
}

/**
 * @param string $path
 * @param int $permissions
 * @return bool
 * @throws \Exception
 */
function createDirectory(string $path, int $permissions = 0775): bool
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
        return true;
    }
    return false;
}


function removeDirectoryRecursive(string $path, $removeParent = false)
{
    if (!file_exists($path)){
        return;
    }
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
    createDirectory($target);

    $iterator = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS),
        \RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $item) {
        if ($item->isDir()) {
            createDirectory(
                $target . DIRECTORY_SEPARATOR
                . str_replace($source . DIRECTORY_SEPARATOR, '', $item->getPathname())
            );
        } else {
            copy(
                $item,
                $target . DIRECTORY_SEPARATOR
                . str_replace($source . DIRECTORY_SEPARATOR, '', $item->getPathname())
            );
        }
    }
}


/**
 * @throws \Exception
 */
function CreateSymlink(string $link, string $target): bool
{
    $directory = pathinfo($link, PATHINFO_DIRNAME);
    createDirectory($directory);

    if (!file_exists($target)) {
        throw new \InvalidArgumentException(
            sprintf("Цeлевой директории или файла не существует. Symlink на %s не создан", $target)
        );
    }

    $linkSpl = new \SplFileInfo($link);

    if (($linkSpl->isLink() || $linkSpl->isFile() || $linkSpl->isDir()) && $linkSpl->isReadable()) {
        return false;
    }

    if ($linkSpl->isLink() && !$linkSpl->isReadable()) {
        unlink($linkSpl->getPathname());
    }

    return symlink($target, $link);
}

/**
 * @throws \Exception
 */
function makeSymlink(string $link, string $target): bool
{
    return CreateSymlink($link, $target);
}