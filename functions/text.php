<?php

declare(strict_types=1);

namespace Enjoys\Functions\Text;

function truncateSimple(string $text, int $chars = 0, ?string $continue = null): string
{
    if ($continue === null) {
        $continue = "\xe2\x80\xa6";
    }
    if (\mb_strlen($text) <= $chars) {
        return $text;
    }
    return trim(\mb_substr($text, 0, $chars)) . $continue;
}
