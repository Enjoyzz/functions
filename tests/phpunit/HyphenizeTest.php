<?php

declare(strict_types=1);

namespace Tests\Enjoys\Functions;

use Enjoys\Functions\Hyphenize;
use PHPUnit\Framework\TestCase;


class HyphenizeTest extends TestCase
{
    public function data()
    {
        return [
            [['заштрихуй', false, Hyphenize::HRISTOFF_ALGORITHM], 'заш­три­хуй'],
            [['заштрихуй', false, Hyphenize::KOTEROFF_ALGORITHM], 'зашт­ри­хуй'],
            [['заштрихуй', false, Hyphenize::NASIBULLIN_ALGORITHM], 'зашт­ри­хуй'],
            [['оскорблять', false, Hyphenize::HRISTOFF_ALGORITHM], 'ос­кор­блять'], //о­скор­блять - верный перенос
            [['оскорблять', false, Hyphenize::KOTEROFF_ALGORITHM], 'ос­корб­лять'],
            [['оскорблять', false, Hyphenize::NASIBULLIN_ALGORITHM], 'ос­корб­лять'],
            [['водоворот', false, Hyphenize::HRISTOFF_ALGORITHM], 'во­дово­рот'], // во­до­во­рот - верный перенос
            [['водоворот', false, Hyphenize::KOTEROFF_ALGORITHM], 'во­дово­рот'],
            [['водоворот', false, Hyphenize::NASIBULLIN_ALGORITHM], 'во­дово­рот'],
            [['борода', false, Hyphenize::HRISTOFF_ALGORITHM], 'бо­рода'], // бо­ро­да - верный перенос
            [['борода', false, Hyphenize::KOTEROFF_ALGORITHM], 'бо­рода'],
            [['борода', false, Hyphenize::NASIBULLIN_ALGORITHM], 'бо­рода'],
            [['лебеда', false, Hyphenize::HRISTOFF_ALGORITHM], 'ле­беда'], // ле­бе­да - верный перенос
            [['лебеда', false, Hyphenize::KOTEROFF_ALGORITHM], 'ле­беда'],
            [['лебеда', false, Hyphenize::NASIBULLIN_ALGORITHM], 'ле­беда'],
            [['бригады', false, Hyphenize::HRISTOFF_ALGORITHM], 'бри­гады'],
            [['бригады', false, Hyphenize::KOTEROFF_ALGORITHM], 'бри­гады'],
            [['бригады', false, Hyphenize::NASIBULLIN_ALGORITHM], 'бри­гады'],
            [['своевременный', false, Hyphenize::HRISTOFF_ALGORITHM], 'сво­ев­ре­мен­ный'],
            [['своевременный', false, Hyphenize::KOTEROFF_ALGORITHM], 'сво­ев­ре­мен­ный'],
            [['своевременный', false, Hyphenize::NASIBULLIN_ALGORITHM], 'сво­ев­ре­мен­ный'],
            [['парикмахер', false, Hyphenize::HRISTOFF_ALGORITHM], 'па­рик­ма­хер'],
            [['парикмахер', false, Hyphenize::KOTEROFF_ALGORITHM], 'па­рик­ма­хер'],
            [['парикмахер', false, Hyphenize::NASIBULLIN_ALGORITHM], 'па­рик­ма­хер'],
            [['процедура', false, Hyphenize::HRISTOFF_ALGORITHM], 'про­цеду­ра'],
            [['процедура', false, Hyphenize::KOTEROFF_ALGORITHM], 'про­цеду­ра'],
            [['процедура', false, Hyphenize::NASIBULLIN_ALGORITHM], 'про­цеду­ра'],
        ];
    }

    /**
     * @dataProvider data
     */
    public function testHyphenizeFunction($data, $expect)
    {
        $this->assertSame($expect, hyphenize(...$data));
    }

}
