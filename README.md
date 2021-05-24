# array helpers

```php
\getValueByIndexPath($indexPath, $data = []); //$var['foo[name]'] выбирает из массива нужный результат
```

**array_merge_recursive_distinct** работает не так как array_merge_recursive, 
*например в array_merge_recursive_distinct если входные массивы имеют одинаковые
строковые ключи, то значения с правого массива заменяются, и это делается рекурсивно.
Совпадающие значения ключей во втором массиве перезаписывают значения в первом массиве, как в случае с array_merge*

```php
\array_merge_recursive_distinct(array $array1, array $array2); 
```

# convertsize

```php
\iniSize2bytes($phpIniSize); //10М -> 10485760
\bytes2iniSize($size); // 10485760 -> 10М
```