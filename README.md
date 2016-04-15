#Config tool

Утилита для работы работы с настройками в виде массива.

##Установка

через composer

`composer require new-inventor/config-tool`

##Инициализация

Инициализация может проходить нестолькими путями:
1. `Config::getInstance()`;
2. `Config::getInstance(['file1.php', 'file2.php'])`;

Впервом случае создастся объект с пустыми настройками. Во втором - загрузятся настройки из всех файлов указанных в массиве.

**Если вызвать метод ``getInstance(['file1.php', 'file2.php'])`` еще раз, произойдет переинициализация объекта настроек.**

##Методы

###get
Метод get получает значение из объекта настроек.

```
$res = Config::get(['test', 'test', 0], 123);
$res = Config::get('test', 123);
$res = Config::get(['test', 'test', 0]);
$res = Config::get('test');
```

Первым параметром может быть любой валидный ключ массива. также можно передавать **одномерный** массив ключей массива который будет воспринят как путь от корня настроек.
Второй параметр - значение по умолчанию для *ненайденного* элемента. Если не задан, возвращается `null`

###set
Метод set устанавливает значение в объекте настроек.

```
$res = Config::set(['test', 'test', 0], 123);
$res = Config::set('test', 123);
```

Первым параметром может быть любой валидный ключ массива. также можно передавать **одномерный** массив ключей массива который будет воспринят как путь от корня настроек.
Второй параметр - устанавливаемое значение.

**Если в пути до устанавливаемого значения встречается ключ, значение которого не является массивом или неопределенным значением то бросается исключение.**

###mergeFile
Объединяет массив по пути с полученным из файла настроек массивом.

###merge
Объединяет массив по пути с переданным массивом.

###find
ищет значение по определенному базовому и доченнему пути.