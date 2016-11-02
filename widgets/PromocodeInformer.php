<?php
namespace pistol88\order\widgets;

use pistol88\order\models\Order;

class PromocodeInformer extends \yii\base\Widget
{
    /* props */
    public $view = 'promocodeInformer';


    /* methods */
    public function init()
    {
        parent::init();
        return true;
    }

    public function run()
    {
        $timeFrom01 = mktime(0, 0, 0, date("m")  , 1        ,   date("Y"));
        $timeLast30 = mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"));
        $timeLast90 = mktime(0, 0, 0, date("m")-3, date("d"),   date("Y"));

        $ordersCount = Order::find()->count();
        $promoValues = [];
        $orders = Order::find();
        $promocodes = [];
        foreach ($orders->groupBy('promocode')->all() as $key => $value) {
            $promocodes[] = $value->promocode; 
        }
        $orders = Order::find();

        foreach ($promocodes as $key => $promocode) {

            $name = $promocode ? $promocode : "Без промокода";
            $promocodeOrders = $orders->where(["promocode"=>$promocode]);

            $poClone = clone $promocodeOrders;
            
            $allTime = $poClone->count();

            $t = date('Y-m-d H:i:s',$timeFrom01);
            $poClone = clone $promocodeOrders;
            $thisMonth = $poClone->andWhere("date > '$t'")->count();

            $t = date('Y-m-d H:i:s',$timeLast30);
            $poClone = clone $promocodeOrders;
            $lastMonth = $poClone->andWhere("date > '$t'")->count();

            $poClone = clone $promocodeOrders;
            $avgSum = round($poClone->sum('cost') / ($allTime ? $allTime : 1),2);

            $t = date('Y-m-d H:i:s',$time_last_90);
            $ordersClone = clone $orders;
            $percent = round(
                $ordersClone->where(["promocode"=>$promocode])->andWhere("date > '$t'")->count()
                 / 
                 $ordersCount * 100
            ,2);

            $promoValues[] =
            [
                'name' => $name,
                'thisMonth' => $thisMonth,
                'lastMonth' => $lastMonth,
                'allTime' => $allTime,
                'avgSum' => $avgSum,
                'percent' => $percent
            ];
        }
        
        return $this->render($this->view,['promocodes'=>$promoValues]);
    }
}