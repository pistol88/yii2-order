Yii2-order
==========
Это модуль для реализации функцинала заказа на сайте. Сейчас в заказ попадают элементы корзины, советую в качестве сервиса корзины использовать модуль [pistol88/yii2-cart](https://github.com/pistol88/yii2-cart).

Установка
---------------------------------
Выполнить команду

```
php composer require pistol88/yii2-order "*"
```

Или добавить в composer.json

```
"pistol88/yii2-order": "*",
```

И выполнить

```
php composer update
```

Далее, мигрируем базу:

```
php yii migrate --migrationPath=vendor/pistol88/yii2-order/migrations
```

