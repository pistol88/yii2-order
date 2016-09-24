<?php
namespace pistol88\order\models\tools;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pistol88\order\models\Order;
use pistol88\order\models\FieldValue;

class OrderSearch extends Order
{
    public function rules()
    {
        return [
            [['id', 'user_id', 'shipping_type_id', 'payment_type_id', 'seller_user_id'], 'integer'],
            [['client_name', 'phone', 'email', 'status', 'time', 'date', 'promocode'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Order::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'promocode' => $this->promocode,
            'seller_user_id' => $this->seller_user_id,
        ]);

        $query->andFilterWhere(['like', 'client_name', $this->client_name])
                ->andFilterWhere(['like', 'shipping_type_id', $this->shipping_type_id])
                ->andFilterWhere(['like', 'payment_type_id', $this->payment_type_id])
                ->andFilterWhere(['like', 'phone', $this->phone])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'date', $this->date])
                ->andFilterWhere(['like', 'time', $this->time]);

        if(yii::$app->request->get('promocode')) {
            $query->andWhere("promocode != ''");
            $query->andWhere("promocode IS NOT NULL");
        }

        if($customField = yii::$app->request->get('order-custom-field')) {
            $orderIds = [0];
            foreach($customField as $id => $str) {
                if($values = FieldValue::find()->select('order_id')->where(['field_id' => $id])->andWhere(['LIKE', 'value', $str])->all()) {
                    foreach($values as $value) {
                        $orderIds[] = $value->order_id;
                    }
                }
            }
            $query->andWhere(['id' => $orderIds]);            
        }
        
        if($dateStart = yii::$app->request->get('date_start')) {
            $dateStart = date('Y-m-d', strtotime($dateStart));
            if(!yii::$app->request->get('date_stop')) {
                $query->andWhere('DATE_FORMAT(date, "%Y-%m-%d") = :dateStart', [':dateStart' => $dateStart]);
            } else {
                $query->andWhere('date >= :dateStart', [':dateStart' => $dateStart]);
            }
        }
        
        if($dateStop = yii::$app->request->get('date_stop')) {
            $dateStop = date('Y-m-d', strtotime($dateStop));
            if($dateStop == '0000-00-00 00:00:00') {
                $dateStop = date('Y-m-d');
            }
        
            $query->andWhere('date <= :dateStop', [':dateStop' => $dateStop]);
        }
        
        return $dataProvider;
    }
}
