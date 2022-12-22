<?php

declare(strict_types=1);

namespace Tests\Enjoys\Functions;

use PHPUnit\Framework\TestCase;

class ConvertsizeTest extends TestCase
{
    public function data()
    {
        return [
            ['0B', 0],
            ['10B', 10],
            ['10K', 10 * 1024],
            ['10M', 10 * pow(1024, 2)],
            ['10G', 10 * pow(1024, 3)],
            ['10T', 10 * pow(1024, 4)],
            ['10P', 10 * pow(1024, 5)]
        ];
    }

    /**
     * @dataProvider  data
     *
     */
    public function test_iniSize2bytes($value, $expect)
    {

        $this->assertSame($expect, \iniSize2bytes($value));
    }

    /**
     * @dataProvider  data
     *
     */
    public function test_bytes2iniSize($expect, $value)
    {

        $this->assertSame($expect, \bytes2iniSize($value));
    }
}