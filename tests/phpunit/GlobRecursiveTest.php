<?php

declare(strict_types=1);


namespace Tests\Enjoys\Functions;


use PHPUnit\Framework\TestCase;


final class GlobRecursiveTest extends TestCase
{
    public function testGlobRecursive()
    {
        $result = glob_recursive(__DIR__.'/fixtures/*.css');
        $this->assertSame([
            __DIR__.'/fixtures/test.css',
            __DIR__.'/fixtures/test2.css',
            __DIR__.'/fixtures/test3.css',
            __DIR__.'/fixtures/subdirectory/test4.css',
        ], $result);
    }

}