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
            [['заштрихуй', Hyphenize::HRISTOFF_ALGORITHM], 'заш­три­хуй'],
            [['заштрихуй', Hyphenize::KOTEROFF_ALGORITHM], 'зашт­ри­хуй'],
            [['заштрихуй', Hyphenize::NASIBULLIN_ALGORITHM], 'зашт­ри­хуй'],
            [['оскорблять', Hyphenize::HRISTOFF_ALGORITHM], 'ос­кор­блять'], //о­скор­блять - верный перенос
            [['оскорблять', Hyphenize::KOTEROFF_ALGORITHM], 'ос­корб­лять'],
            [['оскорблять', Hyphenize::NASIBULLIN_ALGORITHM], 'ос­корб­лять'],
            [['водоворот',  Hyphenize::HRISTOFF_ALGORITHM], 'во­дово­рот'], // во­до­во­рот - верный перенос
            [['водоворот',  Hyphenize::KOTEROFF_ALGORITHM], 'во­дово­рот'],
            [['водоворот',  Hyphenize::NASIBULLIN_ALGORITHM], 'во­дово­рот'],
            [['борода',  Hyphenize::HRISTOFF_ALGORITHM], 'бо­рода'], // бо­ро­да - верный перенос
            [['борода',  Hyphenize::KOTEROFF_ALGORITHM], 'бо­рода'],
            [['борода',  Hyphenize::NASIBULLIN_ALGORITHM], 'бо­рода'],
            [['лебеда',  Hyphenize::HRISTOFF_ALGORITHM], 'ле­беда'], // ле­бе­да - верный перенос
            [['лебеда',  Hyphenize::KOTEROFF_ALGORITHM], 'ле­беда'],
            [['лебеда',  Hyphenize::NASIBULLIN_ALGORITHM], 'ле­беда'],
            [['бригады',  Hyphenize::HRISTOFF_ALGORITHM], 'бри­гады'],
            [['бригады',  Hyphenize::KOTEROFF_ALGORITHM], 'бри­гады'],
            [['бригады',  Hyphenize::NASIBULLIN_ALGORITHM], 'бри­гады'],
            [['своевременный',  Hyphenize::HRISTOFF_ALGORITHM], 'сво­ев­ре­мен­ный'],
            [['своевременный',  Hyphenize::KOTEROFF_ALGORITHM], 'сво­ев­ре­мен­ный'],
            [['своевременный',  Hyphenize::NASIBULLIN_ALGORITHM], 'сво­ев­ре­мен­ный'],
            [['парикмахер',  Hyphenize::HRISTOFF_ALGORITHM], 'па­рик­ма­хер'],
            [['парикмахер',  Hyphenize::KOTEROFF_ALGORITHM], 'па­рик­ма­хер'],
            [['парикмахер',  Hyphenize::NASIBULLIN_ALGORITHM], 'па­рик­ма­хер'],
            [['процедура',  Hyphenize::HRISTOFF_ALGORITHM], 'про­цеду­ра'],
            [['процедура',  Hyphenize::KOTEROFF_ALGORITHM], 'про­цеду­ра'],
            [['процедура',  Hyphenize::NASIBULLIN_ALGORITHM], 'про­цеду­ра'],
            [['йод'], 'йод'],
            [['йод', Hyphenize::KOTEROFF_ALGORITHM, 3], 'й­од'],
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
