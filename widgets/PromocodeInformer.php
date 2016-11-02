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
        $time_from_01 = mktime(0, 0, 0, date("m")  , 1        ,   date("Y"));
        $time_last_30 = mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"));
        $time_last_90 = mktime(0, 0, 0, date("m")-3, date("d"),   date("Y"));

        $orders_count = Order::find()->count();
        $promoValues = [];
        $orders = Order::find();
        $promocodes = [];
        foreach ($orders->groupBy('promocode')->all() as $key => $value) {
            $promocodes[] = $value->promocode; 
        }
        $orders = Order::find();

        foreach ($promocodes as $key => $promocode) {

            $name = $promocode ? $promocode : "Без промокода";
            $promocode_orders = $orders->where(["promocode"=>$promocode]);

            $po_clone = clone $promocode_orders;
            
            $all_time = $po_clone->count();

            $t = date('Y-m-d H:i:s',$time_from_01);
            $po_clone = clone $promocode_orders;
            $this_month = $po_clone->andWhere("date > '$t'")->count();

            $t = date('Y-m-d H:i:s',$time_last_30);
            $po_clone = clone $promocode_orders;
            $last_month = $po_clone->andWhere("date > '$t'")->count();

            $po_clone = clone $promocode_orders;
            $avg_sum = round($po_clone->sum('cost') / ($all_time ? $all_time : 1),2);

            $t = date('Y-m-d H:i:s',$time_last_90);
            $orders_clone = clone $orders;
            $percent = round(
                $orders_clone->where(["promocode"=>$promocode])->andWhere("date > '$t'")->count()
                 / 
                 $orders_count * 100
            ,2);

            $promoValues[] =
            [
                'name' => $name,
                'this_month' => $this_month,
                'last_month' => $last_month,
                'all_time' => $all_time,
                'avg_sum' => $avg_sum,
                'percent' => $percent
            ];
        }
        
        return $this->render($this->view,['promocodes'=>$promoValues]);
    }
}