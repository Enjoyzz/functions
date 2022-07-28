<?php

declare(strict_types=1);

namespace Benchmark\Enjoys;

use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\Revs;

/**
 * @BeforeMethods("setUp")
 * @Revs(100)
 * @Iterations(50)
 */
final class ArrayInsertBench
{
    /**
     * @var array
     */
    private $inputArray;

    private $count = 20;

    public function setUp()
    {
        $this->inputArray = array_fill(0, $this->count, str_repeat('x', 10));
    }


    public function benchAfterSlice()
    {
        array_insert_after($this->inputArray, $this->count-1, 50);
    }


    public function benchBeforeSlice()
    {
        array_insert_before($this->inputArray, $this->count-1, 50);
    }
}