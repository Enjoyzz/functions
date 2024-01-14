<?php

declare(strict_types=1);


namespace Enjoys\Functions;


use Enjoys\Functions\Exception\FilesystemException;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final class Filesystem
{

    /**
     * @throws FilesystemException
     */
    public static function createDirectory(string $path, int $permissions = 0x775): bool
    {
        if (preg_match("/(\/\.+|\.+)$/i", $path)) {
            throw new FilesystemException(
                sprintf("Нельзя создать директорию: %s", $path)
            );
        }

        error_clear_last();

        if (!is_dir($path)) {
            if (@mkdir($path, $permissions, true) === false) {
                $error = error_get_last();
                throw new FilesystemException(
                    sprintf("Не удалось создать директорию: %s! Причина: %s", $path, $error['message'])
                );
            }
            return true;
        }
        return false;
    }

    /**
     * @throws FilesystemException
     */
    public static function createFile(string $file, string $data = '', string $mode = 'w')
    {
        $dirname = dirname($file);
        self::createDirectory($dirname);

        $f = fopen($file, $mode);
        if ($f !== false) {
            fwrite($f, $data);
            fclose($f);
        }
    }

    public static function removeDirectoryRecursive(string $path, $removeParent = false)
    {
        if (!file_exists($path)) {
            return;
        }
        $di = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

        /** @var SplFileInfo $file */
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
     * @throws FilesystemException
     */
    public static function copyDirectoryWithFilesRecursive($source, $target)
    {
        self::createDirectory($target);

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            /** @var SplFileInfo $item */
            if ($item->isDir()) {
                self::createDirectory(
                    str_replace($source, $target, $item->getPathname())
                );
                continue;
            }

            copy(
                $item->getPathname(),
                str_replace($source, $target, $item->getPathname())
            );
        }
    }

    /**
     * @throws FilesystemException
     */
    public static function copyFile($source, $target): bool
    {
        $dir = dirname($target);
        self::createDirectory($dir);
        return copy($source, $target);
    }

    /**
     * @throws FilesystemException
     */
    public static function makeSymlink(string $link, string $target): bool
    {
        $directory = pathinfo($link, PATHINFO_DIRNAME);
        self::createDirectory($directory);

        if (!file_exists($target)) {
            throw new \InvalidArgumentException(
                sprintf("Цeлевой директории или файла не существует. Symlink на %s не создан", $target)
            );
        }

        $linkSpl = new SplFileInfo($link);

        if (($linkSpl->isLink() || $linkSpl->isFile() || $linkSpl->isDir()) && $linkSpl->isReadable()) {
            return false;
        }

        if ($linkSpl->isLink() && !$linkSpl->isReadable()) {
            unlink($linkSpl->getPathname());
        }

        return symlink($target, $link);
    }

    /**
     * @template T of bool
     * @psalm-param  T $false_return
     * @return (T is true ? array|false : array)
     * @link https://stackoverflow.com/questions/17160696/php-glob-scan-in-subfolders-for-a-file
     */
    public static function globRecursive(string $pattern, int $flags = 0, bool $false_return = false)
    {
        $files = glob($pattern, $flags);

        if ($false_return && $files === false) {
            return false;
        }

        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge(
                $files,
                self::globRecursive($dir . "/" . basename($pattern), $flags)
            );
        }

        return $files ?: [];
    }
}