<?php

declare(strict_types=1);

namespace Enjoys\Functions\Text;

use Enjoys\Functions\Hyphenize;
use Enjoys\Functions\Truncate;


function truncateSimple(string $text, int $length = 0, string $continue = "\xe2\x80\xa6"): string
{
    $truncate = new Truncate($text);
    return $truncate->simpleTruncate($length, $continue);
}


function truncate(
    string $s,
    int $maxlength,
    string $continue = "\xe2\x80\xa6",
    int $tailMinLength = 20,
    ?bool &$isCut = null
): ?string {
    $truncate = new Truncate($s);
    return $truncate->smartTruncate($maxlength, $continue, $tailMinLength, $isCut);
}


/**
 * Расстановка "мягких" переносов в словах.
 */
function hyphenize(string $s, bool $isHtml = false, $algo = Hyphenize::KOTEROFF_ALGORITHM): ?string
{
    $hyphenize = new Hyphenize($algo);
    return $hyphenize->handle($s, $isHtml);
}