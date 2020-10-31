<?php

namespace Enjoys;

/**
 * 
 * Подсвечивает представление переменной, функцией highlight_string
 * http://php.net/manual/ru/function.highlight-string.php
 * 
 * Цвета для режима подсвечивания (Syntax Highlighting). Всё, что 
 * приемлемо в <font color="??????">, будет работать.
 * 
 * ini_set("highlight.comment", "#008000");
 * ini_set("highlight.default", "#000000");
 * ini_set("highlight.html", "#808080");
 * ini_set("highlight.keyword", "#0000BB; font-weight: bold");
 * ini_set("highlight.string", "#DD0000");
 * 
 * _var_dump($var, ...$vars)
 * _var_export($var)
 * dump($var)
 */
function _var_dump($var, ...$vars)
{
    ob_start();
    var_dump($var, ...$vars);
    echo '<pre>' . highlight_string("<?php\n" . str_replace('\\\\', '\\', ob_get_clean()) . "\n?>", true) . '</pre><br />';
}

function _var_export($var)
{
    echo '<pre>' . highlight_string("<?php\n" . str_replace('\\\\', '\\', var_export($var, true)) . "\n?>", true) . '</pre><br />';
}

function dump($var)
{
    echo _var_export($var);
}
