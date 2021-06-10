<?php

declare(strict_types=1);


namespace Test\Enjoys\Functions;


use PHPUnit\Framework\TestCase;

use function Enjoys\Functions\Text\truncateSimple;

final class textTest extends TestCase
{
    public function dataTruncateSimple(): array
    {
        return [
            ['Lorem ipsum', 4, null, 'Lore…'],
            ['Lorem ipsum', 6, '', 'Lorem'],
            ['Lorem ipsum', 7, '...', 'Lorem i...'],
            ['Lorem <i>ipsum</i>', 7, '...', 'Lorem <...'],
        ];
    }

    /**
     * @dataProvider dataTruncateSimple
     */
    public function testTruncateSimple($text, $chars, $continue, $expect)
    {
        $truncated = truncateSimple($text, $chars, $continue);
        $this->assertSame($expect, $truncated);
    }
}