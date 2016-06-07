<?php
namespace pistol88\order\models;

use yii;
use pistol88\order\models\PaymentType;
use pistol88\order\models\ShippingType;
use pistol88\order\models\Element;
use pistol88\order\models\FieldValue;
use pistol88\order\models\tools\OrderQuery;

class Order extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%order}}';
    }

    public static function find() {
        return new OrderQuery(get_called_class());
    }

    public function rules()
    {
        return [
            [['status'], 'required'],
            [['status', 'date', 'payment', 'comment'], 'string'],
            [['email'], 'email'],
            [['status', 'date', 'payment', 'client_name', 'phone', 'email', 'comment'], 'safe'],
			[['user_id', 'shipping_type_id', 'payment_type_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => yii::t('order', 'ID'),
            'client_name' => yii::t('order', 'Client name'),
            'shipping_type_id' => yii::t('order', 'Delivery'),
            'payment_type_id' => yii::t('order', 'Payment type'),
            'comment' => yii::t('order', 'Comment'),
            'phone' => yii::t('order', 'Phone'),
			'date' => yii::t('order', 'Date'),
            'email' => yii::t('order', 'Email'),
			'payment' => yii::t('order', 'Paid'),
            'status' => yii::t('order', 'Status'),
			'time' => yii::t('order', 'Time'),
			'user_id' => yii::t('order', 'User ID'),
        ];
    }

    public function scenarios()
    {
        return [
            'customer' => ['comment', 'client_name', 'shipping_type_id', 'payment_type_id', 'phone', 'email'],
            'admin' => array_keys($this->attributeLabels()),
			'default' => array_keys($this->attributeLabels()),
        ];
    }

    public function getTotal()
    {
        return floatVal($this->hasMany(Element::className(), ['order_id' => 'id'])->sum('price*count'));
    }

    public function getTotalFormatted()
    {
        $priceFormat = yii::$app->getModule('order')->priceFormat;
        $price = number_format($this->getPrice(), $priceFormat[0], $priceFormat[1], $priceFormat[2]);
        $currency = yii::$app->getModule('order')->currency;
        if (yii::$app->getModule('order')->currencyPosition == 'after') {
            return "$price $currency";
        } else {
            return "$currency $price";
        }
    }

    public function getPayment()
    {
        return $this->hasOne(PaymentType::className(), ['id' => 'payment_type_id']);
    }
    
    public function getShipping()
    {
        return $this->hasOne(ShippingType::className(), ['id' => 'shipping_type_id']);
    }
    
    public function getCount()
    {
        return intval($this->hasMany(Element::className(), ['order_id' => 'id'])->sum('count'));
    }

    public function getFields()
    {
        return $this->hasMany(FieldValue::className(), ['order_id' => 'id']);
    }
    
    public function getAllFields()
    {
        return Field::find()->all();
    }
    
    public function getElements($withModel = true)
    {
        $returnModels = [];
        $elements = $this->hasMany(Element::className(), ['order_id' => 'id'])->all();
        foreach ($elements as $element) {
            if ($withModel && class_exists($element->model)) {
                $model = '\\'.$element->model;
                $productModel = new $model();
                if ($productModel = $productModel::findOne($element->item_id)) {
                    $element->model = $productModel;
                }
            }
            $returnModels[$element->id] = $element;
        }
        return $returnModels;
    }

    public function getElementByModel(\pistol88\cart\models\tools\CartElementInterface $model)
    {
        return $this->hasMany(Element::className(), ['order_id' => 'id'])->andWhere(['model' => get_class($model), 'item_id' => $model->id])->one();
    }

    public function getElementById($id)
    {
        return $this->hasMany(Element::className(), ['order_id' => 'id'])->andWhere(['id' => $id])->one();
    }

    public function haveModelElements($modelName)
    {
        if ($this->hasMany(Element::className(), ['order_id' => 'id'])->andWhere(['model' => $modelName])->one()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        if(true) {
            if($fieldValues = yii::$app->request->post('FieldValue')['value']) {
                foreach($fieldValues as $field_id => $fieldValue) {
                    $fieldValueModel = new FieldValue;
                    $fieldValueModel->value = $fieldValue;
                    $fieldValueModel->order_id = $this->id;
                    $fieldValueModel->field_id = $field_id;
                    $fieldValueModel->save();
                }
            }

            $cartService = yii::$app->cart;

            if($elements = $cartService->elements) {
                foreach($elements as $element) {
                    $count = $element->getCount();

                    $element->getModel()->minusAmount($count);

                    $orderElementModel = new Element;
                    $orderElementModel->order_id = $this->id;
                    $orderElementModel->model = $element->getModel(false);
                    $orderElementModel->item_id = $element->getItemId();
                    $orderElementModel->count = $count;
                    $orderElementModel->price = $element->getPrice();
                    $orderElementModel->options = json_encode($element->getOptions());
                    $orderElementModel->description = '';
                    $orderElementModel->save();
                }
            }

            $cartService->truncate();
        }
        return true;
    }
    
    public function beforeDelete()
    {
        foreach ($this->hasMany(Element::className(), ['order_id' => 'id'])->all() as $elem) {
            $elem->delete();
        }
        foreach ($this->hasMany(FieldValue::className(), ['order_id' => 'id'])->all() as $val) {
            $val->delete();
        }
		
		return true;
    }
}
