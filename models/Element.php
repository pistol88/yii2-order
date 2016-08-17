<?php

namespace pistol88\order\models;

use yii;
use yii\db\Query;

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
    
	public function getOrder()
    {
		return $this->hasOne(Order::className(), ['id' => 'order_id']);
	}

    
    public function getModel($withCartElementModel = true)
    {
        if(!$withCartElementModel) {
            return $this->model;
        }

        if(is_string($this->model)) {
            if(class_exists($this->model)) {
                $model = '\\'.$this->model;
                $productModel = new $model();
                if ($productModel = $productModel::findOne($this->item_id)) {
                    $model = $productModel;
                } else {
                    throw new \yii\base\Exception('Element model not found');
                }
            } else {
                //throw new \yii\base\Exception('Unknow element model');
            }
        } else {
            $model = $this->model;
        }
        
        return $model;
    }
    
    public static function getStatInMoth()
    {
        $query = new Query();
        $query->addSelect(['sum(e.count*e.price) as total, sum(e.count) as count_elements, COUNT(DISTINCT order_id) as count_order'])
                ->from ([Element::tableName().' e'])
                ->leftJoin(Order::tableName().' o','o.id = e.order_id')
                ->where('DATE_FORMAT(o.date, "%Y-%m") = :date', [':date' => date('Y-m')]);

        $result = $query->one();
        
        return array_map('intval', $result);
    }

    public static function getStatByDate($date)
    {
        $query = new Query();
        $query->addSelect(['sum(e.count*e.price) as total, sum(e.count) as count_elements, COUNT(DISTINCT order_id) as count_order'])
                ->from ([Element::tableName().' e'])
                ->leftJoin(Order::tableName().' o','o.id = e.order_id')
                ->where('DATE_FORMAT(o.date, "%Y-%m-%d") = :date', [':date' => $date]);

        $result = $query->one();
        
        return array_map('intval', $result);
    }
    
    public static function getStatByModelAndDatePeriod($model, $dateStart, $dateStop)
    {
        if($dateStop == '0000-00-00 00:00:00' | empty($dateStop)) {
            $dateStop = date('Y-m-d H:i:s');
        }
        
        $query = new Query();
        $query->addSelect(['sum(e.count*e.price) as total, sum(e.count) as count_elements, COUNT(DISTINCT order_id) as count_order'])
                ->from ([Element::tableName().' e'])
                ->leftJoin(Order::tableName().' o','o.id = e.order_id')
                ->where('o.date >= :dateStart', [':dateStart' => $dateStart])
                ->andWhere('o.date <= :dateStop', [':dateStop' => $dateStop])
                ->andWhere(['e.model' => $model]);

        $result = $query->one();
        
        return array_map('intval', $result);
    }
    
    public static function getStatByDatePeriod($dateStart, $dateStop)
    {
        if($dateStop == '0000-00-00 00:00:00' | empty($dateStop)) {
            $dateStop = date('Y-m-d H:i:s');
        }
        
        $query = new Query();
        $query->addSelect(['sum(e.count*e.price) as total, sum(e.count) as count_elements, COUNT(DISTINCT order_id) as count_order'])
                ->from ([Element::tableName().' e'])
                ->leftJoin(Order::tableName().' o','o.id = e.order_id')
                ->where('o.date >= :dateStart', [':dateStart' => $dateStart])
                ->andWhere('o.date <= :dateStop', [':dateStop' => $dateStop]);

        $result = $query->one();
        
        return array_map('intval', $result);
    }
    
    public static function editField($id, $name, $value)
    {
        $setting = Element::findOne($id);
        $setting->$name = $value;
        $setting->save();
    }

    public function afterDelete()
    {
        parent::afterDelete();
        
        $this->order->reCount();

        return true;
    }
    
    public function beforeDelete()
    {
        parent::beforeDelete();
        
        $this->getModel()->plusAmount($this->count);
        
        return true;
    }
}
