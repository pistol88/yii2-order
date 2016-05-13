<?php

namespace pistol88\order\models;

use yii;

class Element extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%order_element}}';
    }

    public function rules()
    {
        return [
            [['order_id', 'model', 'item_id'], 'required'],
            [['description', 'model', 'options'], 'string'],
            [['price'], 'double'],
            [['item_id', 'count'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => yii::t('order', 'ID'),
            'price' => yii::t('order', 'Price'),
            'description' => yii::t('order', 'Description'),
            'options' => yii::t('order', 'Options'),
            'model' => yii::t('order', 'Model name'),
            'order_id' => yii::t('order', 'Order ID'),
            'item_id' => yii::t('order', 'Product'),
            'count' => yii::t('order', 'Count'),
        ];
    }

    public function getProduct()
    {
        $modelStr = $this->model;
        $productModel = new $modelStr();
        return $this->hasOne($productModel::className(), ['id' => 'item_id'])->one();
    }
    
    public static function editField($id, $name, $value)
    {
        $setting = Element::findOne($id);
        $setting->$name = $value;
        $setting->save();
    }
}
