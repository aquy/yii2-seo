Yii2 Seo
========
СЕО модуль для установки title, description, keywords и других тегов

Установка
---------

Предпочтительный способ установить это расширение через [composer](http://getcomposer.org/download/).

Запустить

```
php composer.phar require --prefer-dist aquy/yii2-seo "*"
```

или добавить

```
"aquy/yii2-seo": "*"
```

в раздел require ващего `composer.json`.

После установки выполнить миграцию

```php
./yii migrate/up --migrationPath=@vendor/aquy/yii2-seo/migrations
```

для того, чтобы создать таблицу в базе данных, если вы удалили данное расширение, то стоит удалять и миграцию, для этого выполните

```php
./yii migrate/down --migrationPath=@vendor/aquy/yii2-seo/migrations
```

а уже после этого удалите строку

```
"aquy/yii2-seo": "*"
```

из раздела require ващего `composer.json` и выполните

```
php composer.phar update
```

Интеграция
----------

В секцию modules зоны администрирования прописать:

```
'seo' => [
    'class' => 'aquy\seo\module\Meta'
],
```

В секцию components вашей внешней части сайта прописать:

```
'seo' => [
    'class' => 'aquy\seo\components\Seo'
],
```

Добавить в секцию bootstrap вызов seo, получится примерно следующее:

```
'bootstrap' => ['seo','log'],
```

В шаблон представления в раздел head добавить

```
<?php
    if (is_null(Yii::$app->seo->block('title'))) {
        echo '<title>' . Html::encode($this->title) . '</title>';
    } else {
        echo '<title>' . Html::encode(Yii::$app->seo->block('title')) . '</title>';
    }
    ?>
```

Администрирование
-----------------

Если вы сделали все правильно, то после первого входа по вашему внешнему сайту будут появяться страницы для которых можно будет заполнить СЕО поля