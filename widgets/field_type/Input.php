<?php

namespace pistol88\order\widgets\field_type;

class Input extends \yii\base\Widget  {
    public $fieldValueModel = null;
    public $fieldModel = null;
    public $form = null;
    
    public function run() {
        return $this->form->field($this->fieldValueModel, 'value['.$this->fieldModel->id.']')->label($this->fieldModel->name)->textInput(['required' => ($this->fieldModel->required == 'yes')]);
    }
}
