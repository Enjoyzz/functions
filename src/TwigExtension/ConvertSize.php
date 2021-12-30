<?php

declare(strict_types=1);


namespace Enjoys\Functions\TwigExtension;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class ConvertSize extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('iniSize2bytes', [$this, 'iniSize2bytes'], ['is_safe' => ['all']]),
            new TwigFilter('bytes2iniSize', [$this, 'bytes2iniSize'], ['is_safe' => ['all']]),
        ];
    }

    public function iniSize2bytes($body): int
    {
        return iniSize2bytes($body);
    }

    public function bytes2iniSize($body): string
    {
        return bytes2iniSize($body);
    }
}