<?php
namespace pistol88\order\widgets;

use pistol88\order\models\Order;
use pistol88\promocode\models\PromoCode;
use pistol88\promocode\models\PromoCodeUse;

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
        /* timestamps */
        $this_month = mktime(0, 0, 0, date("m"), 1,   date("Y"));
        $time_last_30 = mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"));
        $time_last_90 = mktime(0, 0, 0, date("m")-3, date("d"),   date("Y"));
        
        

        /* promocodes */
        $promocodes = PromoCode::find();
        $orders = Order::find();
        $orders_count = $orders->count();
        $promoValues = [];
        foreach ($promocodes->all() as $key => $value) {

            $name = $value->title;
            $all_time = $orders
            ->where(['promocode'=>$value->code])
            ->count();

            $t = date('Y-m-d H:i:s',$this_month);
            $this_month = $orders
            ->where(['promocode'=>$value->code])
            ->andWhere("date > '$t'")
            ->count();

            $t = date('Y-m-d H:i:s',$time_last_30);
            $last_month = $orders
            ->where(['promocode'=>$value->code])
            ->andWhere("date > '$t'")
            ->count();
            
            $avg_sum =round(
                $orders
                ->where(['promocode'=>$value->code])
                ->sum('cost')
                /
                ($all_time ? $all_time : 1)
            );

            $t = date('Y-m-d H:i:s',$time_last_90);
            $percent = $orders
            ->where(['promocode'=>$value->code])
            ->andWhere("date > '$t'")
            ->count();

            $percent = $percent ? round($percent / $orders_count * 100,2) : 0;
            
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
        //var_dump($promoValues); die;
        return $this->render($this->view,['promocodes'=>$promoValues]);
    }
}