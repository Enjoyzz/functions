<?php

declare(strict_types=1);


namespace Enjoys\Functions\Arrays;


final class ArrayInsert
{
    /**
     * @var array
     */
    private $input;


    public function __construct(array $input)
    {
        $this->input = $input;
    }

    public function after($key, $data): array
    {
        $position = $this->getPosition($key);

        if ($position === false) {
            $position = count($this->input) - 1;
        }

        ++$position;

        return $this->merged($position, $data);
    }

    public function before($key, $data): array
    {
        $position = $this->getPosition($key);

        if ($position === false) {
            $position = 0;
        }

        return $this->merged($position, $data);
    }

    /**
     * @param mixed $data
     */
    private function normalizeData($data): array
    {
        if (is_array($data) && count($data) > 1) {
            return [$data];
        }

        if (!is_array($data)) {
            return [$data];
        }
        return $data;
    }

    /**
     * @param $key
     * @return false|int
     */
    private function getPosition($key)
    {
        return array_search($key, array_keys($this->input), true);
    }

    /**
     * @param $position
     * @param mixed $data
     * @return array
     */
    private function merged($position, $data): array
    {
        return array_merge(
            array_slice($this->input, 0, $position),
            $this->normalizeData($data),
            array_slice($this->input, $position)
        );
    }


}