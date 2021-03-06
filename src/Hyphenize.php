<?php

declare(strict_types=1);


namespace Enjoys\Functions;

/**
 * Расстановка "мягких" переносов в словах.
 * Браузеры, которые показывают их: IE 6.0.x, Opera 7.54u2, Safari 3.1.1, Firefox 3.0.0
 * Поддерживается текст для русского (UTF-8) и английского языков (ANSI).
 *
 * TODO?  ftp://scon155.phys.msu.su/pub/russian/hyphen/
 *
 * @link    http://www.chebykin.ru/tutorials/hyphenation/
 * @link    http://shy.dklab.ru/newest/
 * @link    http://gramota.ru/
 *
 * @param string $s текст
 * @param bool $is_html если TRUE, то html таги, комментарии и сущности не обрабатываются
 * @return  string
 *
 * @license  http://creativecommons.org/licenses/by-sa/3.0/
 * @author   Nasibullin Rinat: http://orangetie.ru/, http://rin-nas.moikrug.ru/
 */
final class Hyphenize
{
    const HRISTOFF_ALGORITHM = 0;
    const KOTEROFF_ALGORITHM = 1;
    const NASIBULLIN_ALGORITHM = 2;

    /**
     * @var int
     */
    private $algo;

    /**
     * Hyphenize constructor.
     * @param int $algo
     */
    public function __construct($algo = self::KOTEROFF_ALGORITHM)
    {
        $this->algo = $algo;
    }

    public function handle(string $s, bool $isHtml = false): ?string
    {
        /*
          TODO
          Запрет переносов в ситуациях, когда составные части слова получаются плохими/неблагозвучными словами:
          заштри-ХУЙ, ли-ХУЯ, оскор-БЛЯТЬ, са-бля, бля-ха, ХЕР-сонская, парикма-ХЕР, ЛОХ-матый
          по-беда, бри-гады, про-раб, проце-дура, ссы-лок, попа-дает,
         */

        $s = str_replace("\xc2\xad", '', $s);  #remove all hyphens (repair text)
        if (strlen($s) < 4) {
            return $s;
        }#speed improve
        if (!$isHtml) {
            $m = [$s];
            $m[3] = &$m[0];
            return $this->hyphenize($m);
        }

        $regexp = '/(?> #встроенный PHP, Perl, ASP код
                    <([\?\%]) .*? \\1>  #1

                    #блоки CDATA
                  | <\!\[CDATA\[ .*? \]\]>

                    #MS Word таги типа "<![if! vml]>...<![endif]>",
                    #условное выполнение кода для IE типа "<!--[if lt IE 7]>...<![endif]-->"
                  | <\! (?>--)?
                        \[
                        (?> [^\]"\']+ | "[^"]*" | \'[^\']*\' )*
                        \]
                        (?>--)?
                    >

                    #комментарии
                  | <\!-- .*? -->

                    #парные таги вместе с содержимым
                  | <((?i:noindex|script|style|comment|button|map|iframe|frameset|object|applet))' . HTML::$re_attrs . '(?<!\/)>
                      .*?
                    <\/(?i:\\2)>  #2

                    #парные и непарные таги
                  | <[\/\!]?+ [a-zA-Z][a-zA-Z\d]*+ ' . HTML::$re_attrs . '>

                    #html сущности (&lt; &gt; &amp;) (+ корректно обрабатываем код типа &amp;amp;nbsp;)
                  | &(?>
                        (?> [a-zA-Z][a-zA-Z\d]++
                          | \#(?> \d{1,4}+
                                | x[\da-fA-F]{2,4}+
                              )
                        );
                     )+
                )+
                #\K  #PCRE 7.0+ / PHP >= 5.2.6

                #не html таги и не сущности
                | ([^<&]++)  #3
               /sxSX';
        return preg_replace_callback($regexp, ['self', 'hyphenize'], $s);
    }

    private function hyphenize(array $m)
    {
        if (strlen($m[0]) < 4 || !@$m[3]) {
            return $m[0];
        }

        static $rules = null;

        if ($rules === null) {
            #буква (letter)
            $l = '(?: \xd0[\x90-\xbf\x81]|\xd1[\x80-\x8f\x91]  #А-я (все)
                | [a-zA-Z]
              )';

            #гласная (vowel)
            $v = '(?: \xd0[\xb0\xb5\xb8\xbe]|\xd1[\x83\x8b\x8d\x8e\x8f\x91]  #аеиоуыэюяё (гласные)
                | \xd0[\x90\x95\x98\x9e\xa3\xab\xad\xae\xaf\x81]         #АЕИОУЫЭЮЯЁ (гласные)
                | (?i:[aeiouy])
              )';

            #согласная (consonant)
            $c = '(?: \xd0[\xb1-\xb4\xb6\xb7\xba-\xbd\xbf]|\xd1[\x80\x81\x82\x84-\x89]  #бвгджзклмнпрстфхцчшщ (согласные)
                | \xd0[\x91-\x94\x96\x97\x9a-\x9d\x9f-\xa2\xa4-\xa9]                #БВГДЖЗКЛМНПРСТФХЦЧШЩ (согласные)
                | (?i:sh|ch|qu|[bcdfghjklmnpqrstvwxz])
              )';

            #специальные
            $x = '(?:\xd0[\x99\xaa\xac\xb9]|\xd1[\x8a\x8c])';   #ЙЪЬйъь (специальные)

            switch ($this->algo) {
                case 0:
                    # алгоритм П.Христова в модификации Дымченко и Варсанофьева
                    $rules = [
                        # $1       $2
                        "/($x)     ($l$l)/sxSX",
                        "/($v)     ($v$l)/sxSX",
                        "/($v$c)   ($c$v)/sxSX",
                        "/($c$v)   ($c$v)/sxSX",
                        "/($v$c)   ($c$c$v)/sxSX",
                        "/($v$c$c) ($c$c$v)/sxSX",
                    ];
                    break;
                case 1:
                    # improved rules by Dmitry Koteroff
                    $rules = [
                        # $1       $2
                        "/($x)     ($l$l)/sxSX",
                        "/($v$c$c) ($c$c$v)/sxSX",
                        "/($v$c$c) ($c$v)/sxSX",
                        "/($v$c)   ($c$c$v)/sxSX",
                        "/($c$v)   ($c$v)/sxSX",
                        "/($v$c)   ($c$v)/sxSX",
                        "/($c$v)   ($v$l)/sxSX",
                    ];
                    break;
                case 2:
                default:
                    # improved rules by Dmitry Koteroff and Rinat Nasibullin
                    $rules = [
                        # $1                       $2
                        "/($x)                     ($c (?:\xcc\x81)?+ $l)/sxSX",
                        "/($v (?:\xcc\x81)?+ $c$c) ($c$c$v)/sxSX",
                        "/($v (?:\xcc\x81)?+ $c$c) ($c$v)/sxSX",
                        "/($v (?:\xcc\x81)?+ $c)   ($c$c$v)/sxSX",
                        "/($c$v (?:\xcc\x81)?+ )   ($c$v)/sxSX",
                        "/($v (?:\xcc\x81)?+ $c)   ($c$v)/sxSX",
                        "/($c$v (?:\xcc\x81)?+ )   ($v (?:\xcc\x81)?+ $l)/sxSX",
                    ];
                    break;
            }
        }
        # \xc2\xad = &shy;  U+00AD SOFT HYPHEN
        return preg_replace($rules, "$1\xc2\xad$2", $m[0]);
    }
}