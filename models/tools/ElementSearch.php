<?php
namespace pistol88\order\models\tools;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pistol88\order\models\Element;

class ElementSearch extends Element
{
    public function rules()
    {
        return [
            [['id', 'order_id', 'count'], 'integer'],
            [['price', 'description'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Element::find()->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'order_id' => $this->order_id,
            'count' => $this->count,
        ]);

        $query->andFilterWhere(['like', 'price', $this->price]);
        return $dataProvider;
    }
}
