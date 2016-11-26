<?php

namespace pistol88\order\widgets;

use pistol88\order\models\PaymentType;
use yii\helpers\ArrayHelper;
use pistol88\order\models\Order;
use yii;

class ReportPaymentTypes extends \yii\base\Widget
{
    public $dateStart = null;
    public $dateStop = [];
    public $withAssigment = false;
    
    public function init()
    {
        if(!$this->dateStop) {
            $this->dateStop = date('Y-m-d H:i:s');
        }
        
        return parent::init();
    }

    public function run()
    {
        $paymentTypes = ArrayHelper::map(PaymentType::find()->all(), 'id', 'name');
        
        $report = [];
        
        foreach($paymentTypes as $pid => $pname) {
           $query = Order::find()->where('date >= :dateStart AND date <= :dateStop', [':dateStart' => $this->dateStart, ':dateStop' => $this->dateStop]);;
           $sum = $query->andWhere(['payment_type_id' => $pid])
                   ->distinct()
                   ->sum('cost');

           $report[$pname] = $sum;
        }
        
        return $this->render('report-payment-types', [
            'report' => $report,
            'dateStart' => $this->dateStart,
            'dateStop' => $this->dateStop,
        ]);
    }
}
