<?php

namespace pistol88\order\models;

use yii;

class ShippingType extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%order_shipping_type}}';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['order'], 'integer'],
            [['cost'], 'double'],
            [['description'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => yii::t('order', 'Name'),
            'order' => yii::t('order', 'Order'),
            'cost' => yii::t('order', 'Cost'),
        ];
    }
}
