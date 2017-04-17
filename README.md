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

Далее, связываем модуль с корзиной. Устанавливаем [pistol88/yii2-cart](https://github.com/pistol88/yii2-cart) и добавляем в начало конфига вашего приложения:

```
yii::$container->set('pistol88\order\interfaces\Cart', 'pistol88\order\drivers\Pistol88Cart');
```

Чтобы связать модуль с другой корзиной:
```
yii::$container->set('pistol88\order\interfaces\Cart', 'app\objects\Cart');
```

app\objects\Cart должен содержать класс, имплементирующий \pistol88\order\interfaces\Cart.

Установка как модуля
---------------------------------
Если Вы планируете вносить множество измененией в код, лучшей практикой будет установить расширение как модуль.

Клонируете с Github расширение в нужную папку:
```
git clone https://github.com/pistol88/yii2-order.git
```

В composer.json добавляете путь до модуля:
```
"autoload": {
    "psr-4": {
      "pistol88\\order\\": "common/modules/order"
    }
  }
```
Запускаете update:
```
php composer update
```

Делаете миграции:
```
php yii migrate --migrationPath=common/modules/order/migrations
```

Добавляете в конфиг:
```
'bootstrap' => ['pistol88\order\Bootstrap'],
```
Связываете с корзиной, как описано выше.

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
        ],
        //...
    ]
```

Все настройки модуля:

* orderStatuses - статусы (по умолчанию: 'new' => 'Новый', 'approve' => 'Подтвержден', 'cancel' => 'Отменен', 'process' => 'В обработке', 'done' => 'Выполнен')
* defaultStatus - статус нового заказа (по умолчанию 'new')
* successUrl - урл, куда будет перенаправлен покупатель в случае успешной покупки (по умолчанию /order/info/thanks/)
* ordersEmail - почта администратора, туда уходят письма с заказами
* robotEmail - e-mail робота (по умолчанию no-reply@localhost)
* robotName - имя почтового робота (по умолчанию Robot)
* orderColumns - массмв полей для вывода. Кастомные поля добавляются как массив, содержащий ID и наименование поля: ['field' => 2, 'label' => 'Автомобиль']
* dateFormat - формат даты (по умолчанию d.m.Y H:i:s)
* cartService - имя компонента, в которой реализована корзина (по умолчанию cart). Интерфейс смотреть в pistol88/yii2-cart.

* currency - валюта, по умолчанию рубли
* currencyPosition - позиция значка валюты относительно цены (before или after)
* priceFormat - формат цены (по умолчанию [2, '.', ''])
* adminRoles - список ролей, которые имеют доступ в CRUD заказа (по умолчанию ['admin', 'superadmin'])

Сервисы
---------------------------------

Модуль автоматически инжектит в yii2 (в Service locator) компонент order (сервис), который доступен глобально через yii::$app->order и предоставляет следующие сервисы:

* get($id) - получить заказ по ID
* getStatInMoth($month) - получить статистику заказов за месяц
* getStatByDate($date) - получить статичтику заказов за день
* getStatByDatePeriod($dateStart, $dateStop) - получить статистику заказов за период
* getStatByModelAndDatePeriod($model, $dateStart, $dateStop) - получить статистику заказов определенной модели за период

Виджеты
---------------------------------
За вывод формы заказа отвечает виджет pistol88\order\widgets\OrderForm

```php
<?=OrderForm::widget();?>
```

Кнопка "заказ в один клик" - pistol88\order\widgets\OneClick:

```php
<?=OneClick::widget(['model' => $model]);?>
```

Триггеры
---------------------------------

В Module:

 * create - создание заказа
 * delete_order - удаление заказа
 * delete_element - удаление элемента заказа

Пример использования через конфиг:

```php
    'order' => [
        'class' => 'pistol88\order\Module',
        'on create' => function($event) {
            send_sms(...); //Отправляем СМС оповещение
        },
    ],
```

Онлайн оплата
---------------------------------
Чтобы добавить способ оплаты, перейдите в ?r=/order/payment-type/index, добавьте новый способ, где в поле "Виджет" укажите класс виджета, который будет отдавать форму оплаты. Виджеты оплаты устанавливаются отдельно.

* Paymaster.ru: [pistol88/yii2-paymaster](https://github.com/pistol88/yii2-paymaster)
* Liqpay: [pistol88/yii2-liqpay](https://github.com/pistol88/yii2-liqpay)
* Сбербанк: [pistol88/yii2-sberbank-payment](https://github.com/pistol88/yii2-sberbank-payment)

Скриншоты
---------------------------------
Форма заказа
![1](https://cloud.githubusercontent.com/assets/27691515/25094364/7dbb15a6-239f-11e7-992d-1325560546f3.png)
Главная панель
![2](https://cloud.githubusercontent.com/assets/27691515/25094385/91f5be9a-239f-11e7-8803-1c0f491f2f13.png)
Поиск по заказам
![3](https://cloud.githubusercontent.com/assets/27691515/25094387/91f8d224-239f-11e7-8ec4-88c93598121e.png)
Статистика
![4](https://cloud.githubusercontent.com/assets/27691515/25094384/91f52e44-239f-11e7-803b-f3454a74eed1.png)
Добавление заказа
![5](https://cloud.githubusercontent.com/assets/27691515/25094386/91f61e80-239f-11e7-8ce1-5e8e2e2471d3.png)
