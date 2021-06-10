<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use function Enjoys\Functions\Text\hyphenize;

class HyphenizeTest extends TestCase
{
    public function data()
    {
        return [
            [['заштрихуй'], 'зашт­ри­хуй'],
//            [['водоворот'], 'во­до­во­рот'],
//            [['борода'], 'бо­ро­да'],
//            [['лебеда'], 'ле­бе­да'],
//            [['оскорблять'], 'ос­корб­лять'],
//            [['бригады'], 'бри­га­ды'],
            [['своевременный'], 'сво­ев­ре­мен­ный'],
            [['парикмахер'], 'па­рик­ма­хер'],
//            [['процедура'], 'про­це­ду­ра'],
        ];
    }

    /**
     * @dataProvider data
     */
    public function testHyphenyzeFunction($data, $expect)
    {
        $result = hyphenize(...$data);
        $this->assertSame($expect, $result);
    }
}
