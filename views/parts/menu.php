<?php
use yii\helpers\Html;

$menu = [
    'orders' => ['url' => '/order/order/index', 'name' => '<i class="glyphicon glyphicon-list"></i> '.Yii::t('order', 'Orders')],
    'operator' => ['url' => '/order/operator/index', 'name' => '<i class="glyphicon glyphicon-list"></i> '.Yii::t('order', 'Operator area')],
    'field' => ['url' => '/order/field/index', 'name' => '<i class="glyphicon glyphicon-list"></i> '.Yii::t('order', 'Fields')],
    'shipping-type' => ['url' => '/order/shipping-type/index', 'name' => '<i class="glyphicon glyphicon-list"></i> '.Yii::t('order', 'Shipping types')],
    'payment-type' => ['url' => '/order/payment-type/index', 'name' => '<i class="glyphicon glyphicon-list"></i> '.Yii::t('order', 'Payment types')],
    'payment' => ['url' => '/order/payment/index', 'name' => '<i class="glyphicon glyphicon-list"></i> '.Yii::t('order', 'Payments')],
];

if(yii::$app->user->can(current(yii::$app->getModule('order')->adminRoles))) {
    $menu['statistics'] = ['url' => '/order/stat/index', 'name' => '<i class="glyphicon glyphicon-list"></i> '.Yii::t('order', 'Order statistics')];
}

if(!isset($active)) {
    $active = false;
}

foreach($menu as $key => $params) {
    if($active == $key) {
        $class = 'btn btn-success';
    } else {
        $class = 'btn btn-default';
    }
    echo Html::a($params['name'], [$params['url']], ['class' => $class]);
}