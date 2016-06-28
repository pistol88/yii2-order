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
use yii;

class OrderForm extends \yii\base\Widget
{
    
    public $view = 'order-form/form';
    public $elements = [];
    
    public function init()
    {
        \pistol88\order\assets\OrderFormAsset::register($this->getView());
        
        return parent::init();
    }
    
    public function run()
    {
        $shippingTypesList = ShippingType::find()->orderBy('order DESC')->all();
        
        $paymentTypes = ArrayHelper::map(PaymentType::find()->orderBy('order DESC')->all(), 'id', 'name');
        $shippingTypes = ['' => yii::t('order', 'Choose shipping type')];
        foreach($shippingTypesList as $sht) {
            if($sht->cost > 0) {
                $currency = yii::$app->getModule('order')->currency;
                $name = "{$sht->name} ({$sht->cost}{$currency})";
            } else {
                $name = $sht->name;
            }
            $shippingTypes[$sht->id] = $name;
        }
        
        $fieldFind = Field::find();
        
        $fieldValueModel = new FieldValue;
    
        $orderModel = new Order;
        
        if(empty($orderModel->shipping_type_id) && $orderShippingType = yii::$app->session->get('orderShippingType')) {
            if($orderShippingType > 0) {
                $orderModel->shipping_type_id = (int)$orderShippingType;
            }
        }
        
        $this->getView()->registerJs("pistol88.order.updateShippingType = '".Url::toRoute(['/order/tools/update-shipping-type'])."';");
        
        return $this->render($this->view, [
            'orderModel' => $orderModel,
            'fieldFind' => $fieldFind,
            'paymentTypes' => $paymentTypes,
            'elements' => $this->elements,
            'shippingTypes' => $shippingTypes,
            'shippingTypesList' => $shippingTypesList,
            'fieldValueModel' => $fieldValueModel,
        ]);
    }

}
