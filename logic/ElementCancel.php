<?php
namespace pistol88\order\logic;

use pistol88\order\interfaces\Element;

class ElementCancel extends \yii\base\Component
{
    public $element;

    public function execute()
    {
        $this->element->is_deleted = 1;
        $this->element->save();
        
        yii::createObject(['class' => CountCalculate::class, 'order' => $this->order])->execute();
        yii::createObject(['class' => CostCalculate::class, 'order' => $this->order])->execute();
        
        return true;
    }
}