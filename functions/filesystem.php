<?php

namespace Enjoys\FileSystem;

use Enjoys\Functions\Exception\FilesystemException;
use Enjoys\Functions\Filesystem;

/**
 * @param string $file
 * @param string $data
 * @param string $mode
 * @return void
 * @throws FilesystemException
 */
function writeFile(string $file, string $data = '', string $mode = 'w')
{
    Filesystem::createFile($file, $data, $mode);
}

/**
 * Alias for writeFile
 * @param string $file
 * @param string $data
 * @param string $mode
 * @throws FilesystemException
 */
function createFile(string $file, string $data = '', string $mode = 'w')
{
    Filesystem::createFile($file, $data, $mode);
}

/**
 * @param string $path
 * @param int $permissions
 * @return bool
 * @throws FilesystemException
 */
function createDirectory(string $path, int $permissions = 0x775): bool
{
    return Filesystem::createDirectory($path, $permissions);
}


function removeDirectoryRecursive(string $path, $removeParent = false)
{
    Filesystem::removeDirectoryRecursive($path, $removeParent);
}

/**
 * @throws FilesystemException
 */
function copyDirectoryWithFilesRecursive($source, $target)
{
    Filesystem::copyDirectoryWithFilesRecursive($source, $target);
}


/**
 * @throws FilesystemException
 */
function copyFile($source, $target): bool
{
    return Filesystem::copyFile($source, $target);
}


/**
 * @throws FilesystemException
 */
function makeSymlink(string $link, string $target): bool
{
    return Filesystem::makeSymlink($link, $target);
}
