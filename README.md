# array helpers

```php
\getValueByIndexPath($indexPath, $data = []); //$var['foo[name]'] выбирает из массива нужный результат
```

**array_merge_recursive_distinct** работает не так как array_merge_recursive,
*например в array_merge_recursive_distinct если входные массивы имеют одинаковые строковые ключи, то значения с правого
массива заменяются, и это делается рекурсивно. Совпадающие значения ключей во втором массиве перезаписывают значения в
первом массиве, как в случае с array_merge*

```php
\array_merge_recursive_distinct(array $array1, array $array2); 
```

# convertsize

```php
\iniSize2bytes($phpIniSize); //10М -> 10485760
\bytes2iniSize($size); // 10485760 -> 10М
```

# text

- **truncateSimple(string $text, int $length = 0, ?string $continue = "\xe2\x80\xa6")**

  Обрезает текст вне зависимости от слов и так далее, может обрезать на полуслове

- **truncate(string $s, int $maxlength, string $continue = "\xe2\x80\xa6", int $tailMinLength = 20, ?bool &$isCut)**

  *@author   Nasibullin Rinat*

  Обрезает текст в кодировке UTF-8 до заданной длины, причём последнее слово показывается целиком, а не обрывается на
  середине. Html сущности корректно обрабатываются.
  
    * **$s** - Текст в кодировке UTF-8
    * **$maxlength** - Ограничение длины текста
    * **$continue** - Завершающая строка, которая будет вставлена после текста, если он обрежется
    * **$tailMinLength** - Если длина "хвоста", оставшегося после обрезки текста, меньше $tailMinLength, то текст
      возвращается без изменений
    * **&$isCut** - Текст был обрезан?