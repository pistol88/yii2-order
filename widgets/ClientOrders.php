<?php
namespace pistol88\order\widgets;

use yii\helpers\Html;
use pistol88\order\models\Order;
use pistol88\order\models\tools\OrderSearch;
use yii;

class ClientOrders extends \yii\base\Widget
{
    public $client_id = null;
    
    public function init()
    {
        return parent::init();
    }

    public function run()
    {
        $searchModel = new OrderSearch();
        
        $params = Yii::$app->request->queryParams;
        
        if($this->client_id && empty($params['OrderSearch'])) {
            $params['OrderSearch']['user_id'] = $this->client_id;
        }
        
        $dataProvider = $searchModel->search($params);

        return $this->render('client_orders', [
            'clientId' => (int)$this->client_id,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
