<?php

namespace pistol88\order\widgets;

use yii\helpers\Html;
use yii\helpers\Url;
use pistol88\order\models\Order;
use pistol88\order\models\PaymentType;
use pistol88\order\models\ShippingType;
use pistol88\order\models\Field;
use pistol88\order\models\FieldValue;
use yii\helpers\ArrayHelper;

class OrderForm extends \yii\base\Widget  {
    
    public $view = 'order-form/form';
    public $elements = [];
    
    public function run() {
        $paymentTypes = ArrayHelper::map(PaymentType::find()->all(), 'id', 'name');
        $shippingTypes = ArrayHelper::map(ShippingType::find()->all(), 'id', 'name');
        
        $fieldFind = Field::find();
        
        $fieldValueModel = new FieldValue;
    
        $orderModel = new Order;
        
        return $this->render($this->view, [
            'orderModel' => $orderModel,
            'fieldFind' => $fieldFind,
            'paymentTypes' => $paymentTypes,
            'elements' => $this->elements,
            'shippingTypes' => $shippingTypes,
            'fieldValueModel' => $fieldValueModel,
        ]);
    }

}
