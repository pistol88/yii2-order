<?php

namespace pistol88\order\widgets;

use yii;

class AssigmentToOrder extends \yii\base\Widget
{
    public $model = null;
    public $staffers = [];

    public function init()
    {
        \pistol88\order\assets\AssigmentToOrder::register($this->getView());
        
        return parent::init();
    }

    public function run()
    {
        return $this->render('assigment-to-order', [
            'staffers' => $this->staffers,
            'model' => $this->model,
        ]);
    }
}
