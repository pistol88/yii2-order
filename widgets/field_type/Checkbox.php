<?php

namespace pistol88\order\widgets\field_type;

class Checkbox extends \yii\base\Widget  {
    public $fieldValueModel = null;
    public $fieldModel = null;
    public $form = null;
    
    public function run() {
        return $this->form->field($this->fieldValueModel, 'value['.$this->fieldModel->id.']')->label($this->fieldModel->name)->checkbox(['required' => ($this->fieldModel->required == 'yes'),'label' => $this->fieldModel->name]);
    }
}
