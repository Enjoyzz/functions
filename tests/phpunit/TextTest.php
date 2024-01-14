<?php

declare(strict_types=1);

namespace Tests\Enjoys\Functions;

use PHPUnit\Framework\TestCase;


final class TextTest extends TestCase
{
    public function dataTruncateSimple(): array
    {
        return [
            [['Lorem ipsum', 4], 'Lore…'],
            [['Lorem ipsum', 6, ''], 'Lorem'],
            [['Lorem ipsum', 7, '...'], 'Lorem i...'],
            [['Lorem <i>ipsum</i>', 7, '...'], 'Lorem <...'],
            [['Тестовая строка', 7, '...'], 'Тестова...'],
        ];
    }

    /**
     * @dataProvider dataTruncateSimple
     */
    public function testTruncateSimple($data, $expect)
    {
        $truncated = truncateSimple(...$data);
        $this->assertSame($expect, $truncated);
    }


    public function dataTruncate(): array
    {
        return [
            [['PHPUnit 9.5.5 by Sebastian Bergmann and contributors.', 4], 'PHPUnit…'],
            [['PHPUnit 9.5.5 by Sebastian Bergmann and contributors.', 0], \InvalidArgumentException::class],
            [['PHPUnit&nbsp;9.5.5&nbsp;by Sebastian Bergmann and contributors.', 8, '...'], 'PHPUnit&nbsp;9.5.5...'],
            [['PHPUnit 9.5.5 by Sebastian Bergmann and contributors.', 28], 'PHPUnit 9.5.5 by Sebastian Bergmann and contributors.'],
            [['PHPUnit 9.5.5 by Sebastian Bergmann and contributors.', 27], 'PHPUnit 9.5.5 by Sebastian…'],
            [['(PHPUnit 9.5.5 by Sebastian) Bergmann and contributors.', 22], '(PHPUnit 9.5.5 by Sebastian)…'],
        ];
    }

    /**
     * @dataProvider dataTruncate
     */
    public function testTruncate($data, $expect)
    {
        if(class_exists($expect)){
            $this->expectException($expect);
        }
        $truncated = truncate(...$data);
        $this->assertSame($expect, $truncated);
    }
}