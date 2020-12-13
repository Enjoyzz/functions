#array helpers
```php
\getValueByIndexPath($indexPath, $data = []); //$var['foo[name]'] выбирает из массива нужный результат
```

#convertsize
```php
\iniSize2bytes($phpIniSize); //10М -> 10485760
\bytes2iniSize($size); // 10485760 -> 10М
```