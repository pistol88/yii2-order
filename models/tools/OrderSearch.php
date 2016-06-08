<?php
namespace pistol88\order\models\tools;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pistol88\order\models\Order;

class OrderSearch extends Order
{
    public function rules()
    {
        return [
            [['id', 'user_id', 'shipping_type_id', 'payment_type_id', 'seller_user_id'], 'integer'],
            [['client_name', 'phone', 'email', 'status', 'time', 'date'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Order::find()->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'seller_user_id' => $this->seller_user_id,
        ]);

        $query->andFilterWhere(['like', 'client_name', $this->client_name])
                ->andFilterWhere(['like', 'shipping_type_id', $this->shipping_type_id])
                ->andFilterWhere(['like', 'payment_type_id', $this->payment_type_id])
                ->andFilterWhere(['like', 'phone', $this->phone])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'date', $this->date])
                ->andFilterWhere(['like', 'time', $this->time]);

        if($dateStart = yii::$app->request->get('date_start')) {
            $query->andWhere('date > :dateStart', [':dateStart' => $dateStart]);
        }
        
        if($dateStop = yii::$app->request->get('date_stop')) {
            $query->andWhere('date < :dateStop', [':dateStop' => $dateStop]);
        }
        
        return $dataProvider;
    }
}
