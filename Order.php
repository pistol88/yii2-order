<?php
namespace pistol88\order;

use pistol88\order\models\Order as OrderModel;

use yii\base\Component;
use yii;

class Order extends Component
{
    public function init()
    {
        parent::init();
    }

    public function get($id)
    {
        return OrderModel::findOne($id);
    }
}
