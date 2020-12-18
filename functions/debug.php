<?php

/**
 * @param bool $completeTrace
 * @return array
 */
function getCallingFunctionName(bool $completeTrace = false): array
{
    $trace = \debug_backtrace();
    $result = [];
    if ($completeTrace === true) {
        foreach ($trace as $caller) {
            $result[] = $caller;
        }
    } else {
        $result = $trace[2];
    }
    return $result;
}

