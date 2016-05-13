<?php

namespace pistol88\order\widgets\field_type;

use yii\helpers\ArrayHelper;

class Select extends \yii\base\Widget  {
    public $fieldValueModel = null;
    public $fieldModel = null;
    public $form = null;
    
    public function run() {
        $variants = $this->fieldModel->getVariants()->all();
        return $this->form->field($this->fieldValueModel, 'value['.$this->fieldModel->id.']')->label($this->fieldModel->name)
                ->dropDownList(ArrayHelper::map($variants, 'value', 'value'), ['required' => ($this->fieldModel->required == 'yes')]);
    }
}
