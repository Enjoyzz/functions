<?php

declare(strict_types=1);

namespace Enjoys\Functions\Text;

use Enjoys\Functions\Hyphenize;

/**
 * Обрезает текст вне зависимости от слов и так далее, может обрезать на полуслове
 */
function truncateSimple(string $text, int $length = 0, string $continue = "\xe2\x80\xa6"): string
{
    if (\mb_strlen($text) <= $length) {
        return $text;
    }
    return trim(\mb_substr($text, 0, $length)) . $continue;
}

/**
 * Обрезает текст в кодировке UTF-8 до заданной длины, причём последнее слово показывается целиком,
 * а не обрывается на середине. Html сущности корректно обрабатываются.
 * @param string $s Текст в кодировке UTF-8
 * @param int $maxlength Ограничение длины текста
 * @param string $continue Завершающая строка, которая будет вставлена после текста, если он обрежется
 * @param int $tailMinLength Если длина "хвоста", оставшегося после обрезки текста, меньше $tailMinLength,
 * то текст возвращается без изменений
 * @param bool|null $isCut Текст был обрезан?
 * @return string|null
 * @author   Nasibullin Rinat
 */
function truncate(
    string $s,
    int $maxlength,
    string $continue = "\xe2\x80\xa6", #"\xe2\x80\xa6" = "&hellip;"
    int $tailMinLength = 20,
    ?bool &$isCut = null
): ?string {
    $isCut = false;

    if ($maxlength <= 0) {
        throw new \InvalidArgumentException('MaxLength must be positive integer');
    }

    # speed improve block
    if (\mb_strlen($s) <= $maxlength) {
        return $s;
    }

    $s2 = str_replace("\r\n", '?', $s);
    $s2 = preg_replace(
        '/&(?> [a-zA-Z][a-zA-Z\d]+
                                | \#(?> \d{1,4}
                                      | x[\da-fA-F]{2,4}
                                    )
                              );  # html сущности (&lt; &gt; &amp; &quot;)
                            /sxSX',
        '?',
        $s2
    );

    if (\mb_strlen($s2) <= $maxlength) {
        return $s;
    }


    $r = preg_match_all(
        '/(?> \r\n   # переносы строк
								   | &(?> [a-zA-Z][a-zA-Z\d]+
										| \#(?> \d{1,4}
											  | x[\da-fA-F]{2,4}
											)
									  );  # html сущности (&lt; &gt; &amp; &quot;)
								   | .
								 )
								/sxuSX',
        $s,
        $m
    );

    if ($r === false) {
        return null;
    }

    # d($m);
    if (count($m[0]) <= $maxlength) {
        return $s;
    }

    $left = implode('', array_slice($m[0], 0, $maxlength));
    /*
    * из диапазона ASCII исключаем буквы, цифры, открывающие парные символы [a-zA-Z\d\(\{\[] и некоторые др. символы
    * нельзя вырезать в конце строки символ ";", т.к. он используются в сущностях &xxx;
    */
    $left2 = rtrim($left, "\x00..\x28\x2A..\x2F\x3A\x3C..\x3E\x40\x5B\x5C\x5E..\x60\x7B\x7C\x7E\x7F");
    if (strlen($left) !== strlen($left2)) {
        $return = $left2 . $continue;
    } else {
        # добавляем остаток к обрезанному слову
        $right = implode('', array_slice($m[0], $maxlength));
        preg_match(
            '/^(?> [\d\)\]\}\-\.:]+  #цифры, закрывающие парные символы, дефис для составных слов, дата, время, IP-адреса, URL типа www.ya.ru:80!
                            | \p{L}+        #буквы
                            | \xe2\x80\x9d  #закрывающие кавычки
                            | \xe2\x80\x99  #закрывающие кавычки
							| \xe2\x80\x9c  #закрывающие кавычки
							| \xc2\xbb      #закрывающие кавычки
                          )+
                        /suxSX',
            $right,
            $m
        );
        # d($m);
        $right = isset($m[0]) ? rtrim($m[0], '.-') : '';
        $return = $left . $right;
        if (strlen($return) !== strlen($s)) {
            $return .= $continue;
        }
    }


    if (\mb_strlen($s) - \mb_strlen($return) < $tailMinLength) {
        return $s;
    }

    $isCut = true;
    return $return;
}


/**
 * Расстановка "мягких" переносов в словах.
 */
function hyphenize(string $s, bool $isHtml = false, $algo = Hyphenize::KOTEROFF_ALGORITHM): ?string
{
    $hyphenize = new Hyphenize($algo);
    return $hyphenize->handle($s, $isHtml);
}