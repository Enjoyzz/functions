<?php
/**
 * 
 * @param string $indexPath
 * @param array $data
 * @return mixed
 */
function getValueByIndexPath(string $indexPath, $data = [])
    {
        $empty_key = 0;

        preg_match_all("/^([\w\d]*)|\[['\"]*(|[a-z0-9_-]+)['\"]*]/i", $indexPath, $matches);
        $last_key = array_key_last($matches[0]);

        if (count($matches[0]) > 0 && !empty($matches[0][0])) {
            foreach ($matches[0] as $identify => $key) {
                if ($key == $indexPath && isset($data[$key])) {
                    return $data[$key];
                }
                
                if (!is_array($data)) {
                    return false;
                }
                $key = str_replace(['[', ']', '"', '\''], [], $key);
                //если последняя и key пустой [] вернуть все
                if ($identify == $last_key && in_array($key, ['', 0], true)) {
                    if (isset($data[0]) && \count($data) > 1) {
                        break;
                    }
                }
                if ($key === '') {
                    $key = $empty_key;
                }
                if (!isset($data[$key])) {
                    return false;
                }
                if ($identify == $last_key && $key !== '') {
                    if (is_array($data[$key])) {
                        return false;
                    }
                }
                $data = $data[$key];
            }
            return $data;
        }
        return false;
    }