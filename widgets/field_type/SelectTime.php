<?php

namespace pistol88\order\widgets\field_type;

use yii\helpers\ArrayHelper;

class SelectTime extends \yii\base\Widget  {
    public $fieldValueModel = null;
    public $fieldModel = null;
    public $form = null;
    
    public function run() {
        $variants = $this->fieldModel->getVariants()->all();
        foreach($variants as $key => $var) {
            $time_start = current(explode('-', $var->value));
            $time_start = strtotime(date('Y-m-d').' '.$time_start);
            if($time_start < time()) {
                unset($variants[$key]);
            }
        }
        return $this->form->field($this->fieldValueModel, 'value['.$this->fieldModel->id.']')->label($this->fieldModel->name)
                ->dropDownList(ArrayHelper::map($variants, 'value', 'value'), ['required' => ($this->fieldModel->required == 'yes')]);
    }
}
