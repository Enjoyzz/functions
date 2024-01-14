<?php

declare(strict_types=1);

namespace Tests\Enjoys\Functions;


use PHPUnit\Framework\TestCase;

/**
 * Class arrayTest
 * @package Test\Enjoys\Functions
 */
class ArrayTest extends TestCase
{
    /**
     * @dataProvider data_getValueByIndexPath
     */
    public function test_getValueByIndexPath($indexPath, $expect)
    {
        $arrays = [
            'foo' => [
                'bar' => 'bar1',
                'name' => 'myname',
                4,
                'test' => [
                    '3' => 55
                ]
            ],
            'notarray' => 'yahoo',
            'arrays' => [
            ],
            'fff' => [
                1,
                2
            ],
            'test' => [
                [
                    [
                        25,
                        11
                    ]
                ]
            ],
            'bar' => 'test',
            'bars' => ['test'],
            'baz' => [
                [
                    [
                        'ddd'
                    ]
                ]
            ],
            'my' => 'my',
            'my-var' => 'my-var',
        ];
        $this->assertEquals($expect, \getValueByIndexPath($indexPath, $arrays));
    }

    public function data_getValueByIndexPath()
    {
        return [
            ['/invalide_string/', false],
            ['//', false],
            ['', false],
            ['invalide', false],
            ['invalide[]', false],
            ['invalide[][]', false],
            ['invalide[invalide][]', false],
            ['foo[bar]', 'bar1'],
            ['foo[name]', 'myname'],
            ['foo[0]', 4],
            ['notarray', 'yahoo'],
            ['notarray[]', false],
            ['fff[]', [1, 2]], //11
            ['fff[0]', 1], //11
            ['foo[test][3]', 55],
            ['foo[test][3]', '55'],
            ['arrays', []],
            ['bar', 'test'],
            ['bar[]', false],
            ['bar[][]', false],
            ['bars', ['test']],
            ['bars[]', 'test'],
            ['bars[][]', false],
            ['baz', [[['ddd']]]],
            ['baz[]', false],
            ['baz[][]', false],
            ['baz[][][]', 'ddd'],
            ['test[][][0]', 25],
            ['fff', [1, 2]],
            [
                'foo[]',
                [
                    'bar' => 'bar1',
                    'name' => 'myname',
                    4,
                    'test' => [
                        '3' => 55
                    ]
                ]
            ], //15
            [
                'test[][][]',
                [
                    25,
                    11
                ]
            ], //16
            ['my', 'my'],
            ['my-var', 'my-var'],
        ];
    }

    public function testArrayMergeRecursiveDistinct()
    {
        $a1 = array(
            88 => 1,
            'foo' => 2,
            'bar' => array(4),
            'x' => 5,
            'z' => array(
                6,
                'm' => 'hi',
            ),
            'w' => [
                'z' => [
                    1
                ]
            ]
        );
        $a2 = array(
            99 => 7,
            'foo' => array(8),
            'bar' => 9,
            'y' => 10,
            'z' => array(
                'm' => 'bye',
                11,
            ),
            'w' => [
                'z' => [
                    1
                ]
            ]
        );


        //system function
        $this->assertSame(
            [
                1,
                'foo' => [
                    2,
                    8
                ],
                'bar' => [
                    4,
                    9
                ],
                'x' => 5,
                'z' => [
                    6,
                    'm' => [
                        'hi',
                        'bye'
                    ],
                    11
                ],
                'w' => [
                    'z' => [
                        1,
                        1
                    ]
                ],
                7,
                'y' => 10,
            ],
            \array_merge_recursive($a1, $a2)
        );

        //distinct function
        $this->assertSame(
            [
                88 => 1,
                'foo' => [
                    8
                ],
                'bar' => 9,
                'x' => 5,
                'z' => [
                    11,
                    'm' => 'bye',

                ],
                'w' => [
                    'z' => [
                        1
                    ]
                ],
                99 => 7,
                'y' => 10,
            ],
            \array_merge_recursive_distinct($a1, $a2)
        );
    }
}
