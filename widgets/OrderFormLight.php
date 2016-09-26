<?php

namespace pistol88\order\widgets;

use yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use pistol88\order\models\Order;
use pistol88\order\models\PaymentType;
use pistol88\order\models\ShippingType;
use pistol88\order\models\Field;
use pistol88\order\models\FieldValue;

class OrderFormLight extends \yii\base\Widget
{

    public $view = 'order-form/light';
    public $useAjax = false;
    public $nextStep = false;

    public function init()
    {
        \pistol88\order\assets\OrderFormLightAsset::register($this->getView());
        \pistol88\order\assets\CreateOrderAsset::register($this->getView());

        return parent::init();
    }

    public function run()
    {
        $paymentTypes = ArrayHelper::map(PaymentType::find()->orderBy('order DESC')->all(), 'id', 'name');

        $orderModel = yii::$app->orderModel;
        $model = new $orderModel;

        $this->getView()->registerJs("pistol88.createorder.updateCartUrl = '".Url::toRoute(['tools/cart-info'])."';");

        return $this->render($this->view, [
            'model' => $orderModel,
            'paymentTypes' => $paymentTypes,
            'useAjax' => $this->useAjax,
            'nextStep' => $this->nextStep
        ]);
    }

}
