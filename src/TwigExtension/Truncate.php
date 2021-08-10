<?php

declare(strict_types=1);


namespace Enjoys\Functions\TwigExtension;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;


final class Truncate extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('truncateSimple', [$this, 'truncateSimple'], ['is_safe' => ['all']]),
            new TwigFilter('truncate', [$this, 'truncate'], ['is_safe' => ['all']]),
        ];
    }

    public function truncateSimple(?string $body, $chars = 0, $continue = null): string
    {
        return (new \Enjoys\Functions\Truncate($body))->simpleTruncate($chars, $continue);
    }

    public function truncate(?string $body, int $chars = 0, string $continue = null, int $tailMinLength = 20): string
    {
        if ($continue === null) {
            $continue = "\xe2\x80\xa6";
        }

        return (new \Enjoys\Functions\Truncate($body))->smartTruncate($chars, $continue, $tailMinLength);
    }
}
