Yii2-order
==========
Это модуль для реализации функцинала заказа на сайте. Сейчас в заказ попадают элементы корзины, советую в качестве сервиса корзины использовать модуль [pistol88/yii2-cart](https://github.com/pistol88/yii2-cart).

Функционал:

* Добавление заказа, просмотр и управление заказами в админке
* Управление полями заказа в админке
* Управление способами доставки и оплаты в админке

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

Подключение и настройка
---------------------------------
В конфигурационный файл приложения добавить модуль order

```php
    'modules' => [
        'order' => [
            'class' => 'pistol88\order\Module',
            'layoutPath' => 'frontend\views\layouts',
            'successUrl' => '/page/thanks', //Страница, куда попадает пользователь после успешного заказа
            'ordersEmail' => 'test@yandex.ru', //Мыло для отправки заказов
            'cartService' => 'cart', //Название компонента, в котором реализована корзина с методом getElements()
        ],
        //...
    ]
```

Вызвать модуль order для проверки (/?r=order).

За вывод формы заказа отвечает виджет pistol88\order\widgets\OrderForm

```php
<?=OrderForm::widget();?>
```
