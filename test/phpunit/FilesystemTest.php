<?php

declare(strict_types=1);


namespace Test\Enjoys\Functions;


use PHPUnit\Framework\TestCase;

use function Enjoys\FileSystem\copyDirectoryWithFilesRecursive;
use function Enjoys\FileSystem\createDirectory;
use function Enjoys\FileSystem\CreateSymlink;
use function Enjoys\FileSystem\removeDirectoryRecursive;

final class FilesystemTest extends TestCase
{
    protected function tearDown(): void
    {
        removeDirectoryRecursive(__DIR__ . '/_temp');
    }

    public function dataErrorPath()
    {
        return [
            [__DIR__ . '/_temp', true],
            ['.', false],
            ['..', false],
            ['...', false],
            [__DIR__ . '/...', false],
            [__DIR__ . '/..', false],
            [__DIR__ . '/.', false],
            [__DIR__ . '/_temp/.s', true],
//            ['/_te<>mp', false]
        ];
    }

    /**
     * @dataProvider dataErrorPath
     */
    public function testCreateDirectoryError($path, $create)
    {
        if ($create === false) {
            $this->expectException(\Exception::class);
        }
        createDirectory($path);
        $this->assertDirectoryExists($path);
    }

    /**
     * @throws \Exception
     */
    public function testCreateSymlinkWhenUpFolderSymlynkAlreadyExist()
    {
        //$this->expectWarning();
        CreateSymlink(__DIR__ . '/_temp/fixtures/test.css', __DIR__ . '/fixtures/test.css');
        $linkSpl = new \SplFileInfo(__DIR__ . '/_temp/fixtures/test.css');
        $this->assertTrue($linkSpl->isLink());

        CreateSymlink(__DIR__ . '/_temp/fixtures', __DIR__ . '/fixtures');
        $linkSpl = new \SplFileInfo(__DIR__ . '/_temp/fixtures');
        $this->assertFalse($linkSpl->isLink());
    }

    /**
     * @throws \Exception
     */
    public function testCreateSymlinkWhenTargetNotExist()
    {
        $this->expectException(\InvalidArgumentException::class);
        CreateSymlink(__DIR__ . '/_temp/fixtures/test.css', __DIR__ . '/fixtures/invalid_target');
    }

    public function testCopyDirectoryWithFilesRecursive()
    {
        $source_dir = __DIR__ .'/fixtures';
        $target_dir = __DIR__ .'/_temp/dd';
        $pattern = '/.*/';
        $globOrig = $this->getListFilesInCatalogRecursive($source_dir,$pattern );
        copyDirectoryWithFilesRecursive($source_dir, $target_dir);
        $this->assertSame($globOrig, $this->getListFilesInCatalogRecursive($target_dir, $pattern));
    }

    private function getListFilesInCatalogRecursive($folder, $regPattern)
    {
        $dir = new \RecursiveDirectoryIterator($folder, \RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($dir);
        $files = new \RegexIterator($iterator, $regPattern, \RegexIterator::GET_MATCH);
        $fileList = [];

        foreach ($files as $file) {
            $fileList = array_merge($fileList, str_replace($folder, '', $file));
        }
        return $fileList;
    }
}