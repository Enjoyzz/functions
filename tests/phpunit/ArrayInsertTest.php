<?php

declare(strict_types=1);

namespace Tests\Enjoys\Functions;

use PHPUnit\Framework\TestCase;

class ArrayInsertTest extends TestCase
{

    private $listArray = [
        '1',
        '2',
        '3',
        '4',
        '5'
    ];

    private $assocArray = [
        'one' => 1,
        'two' => 2,
        'three' => 3,
        'four' => 4,
        'five' => 5
    ];

    private $mixArray = [
        1,
        'two' => 2,
        'three' => '3',
        '4',
        'five' => 5
    ];


    public function testAfterAddInMiddlePosition()
    {
        $this->assertSame(
            [
                '1',
                '2',
                2.5,
                '3',
                '4',
                '5'
            ],
            array_insert_after($this->listArray, 1, 2.5)
        );

        $this->assertSame(
            [
                1,
                'two' => 2,
                'three' => '3',
                '4',
                true,
                'five' => 5
            ],
            array_insert_after($this->mixArray, 1, true)
        );

        $this->assertSame(
            [
                'one' => 1,
                'two' => 2,
                'three' => 3,
                'four' => 4,
                null,
                'five' => 5
            ],
            array_insert_after($this->assocArray, 'four', null)
        );

        $this->assertSame(
            [
                'one' => 1,
                'two' => 2,
                'three' => 3,
                3.14,
                'four' => 4,
                'five' => 5
            ],
            array_insert_after($this->assocArray, 'three', 3.14)
        );
    }

    public function testAfterIfKeyNotFound()
    {
        $this->assertSame(
            [
                '1',
                '2',
                '3',
                '4',
                '5',
                6,
            ],
            array_insert_after($this->listArray, 'not_isset_key', 6)
        );
    }

    public function testBeforeIfKeyNotFound()
    {
        $this->assertSame(
            [
                6,
                '1',
                '2',
                '3',
                '4',
                '5',
            ],
            array_insert_before($this->listArray, 'not_isset_key', 6)
        );
    }

    public function testAfterAndBeforeAddArray()
    {
        $this->assertSame(
            [
                '1',
                '2',
                '3',
                '4',
                [5.8, 5.9],
                '5',
            ],
            array_insert_after($this->listArray, 3, [5.8, 5.9])
        );


        $this->assertSame(
            [
                '1',
                '2',
                '3',
                [5.8, 5.9],
                '4',
                '5',
            ],
            array_insert_before($this->listArray, 3, [5.8, 5.9])
        );

        $this->assertSame(
            [
                '1',
                '2',
                '3',
                '4',
                5.8,
                '5',
            ],
            array_insert_after($this->listArray, 3, [5.8])
        );


        $this->assertSame(
            [
                '1',
                '2',
                '3',
                5.8,
                '4',
                '5',
            ],
            array_insert_before($this->listArray, 3, [5.8])
        );


        $this->assertSame(
            [
                'one' => 1,
                'two' => 2,
                ['two_half' => 2.5],
                'three' => 3,
                'four' => 4,
                'five' => 5
            ],
            array_insert_before($this->assocArray, 'three', [['two_half' => 2.5]])
        );
    }


    function testAfterAndBeforeAddArrayAssoc()
    {
        $this->assertSame(
            [
                'one' => 1,
                'two' => 2,
                'three' => 3,
                'three_half' => 3.5,
                'four' => 4,
                'five' => 5
            ],
            array_insert_after($this->assocArray, 'three', ['three_half' => 3.5])
        );
    }


    public function testAfterAndBeforeAddObject()
    {
        $result = [
            0 => '1',
            1 => '2',
            2 => '3',
            3 => $stdClass = new \stdClass(),
            4 => '4',
            5 => '5'
        ];

        $this->assertSame(
            $result,
            array_insert_before($this->listArray, 3, $stdClass)
        );

        $this->assertSame(
            $result,
            array_insert_before($this->listArray, 3, [$stdClass])
        );

        $this->assertSame(
            $result,
            array_insert_before($this->listArray, 3, ['42' => $stdClass])
        );

        $this->assertSame(
            [
                0 => '1',
                1 => '2',
                2 => '3',
                '42_' => $stdClass = new \stdClass(),
                3 => '4',
                4 => '5'
            ],
            array_insert_before($this->listArray, 3, ['42_' => $stdClass])
        );

    }

}
