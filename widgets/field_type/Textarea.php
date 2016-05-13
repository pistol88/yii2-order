<?php

namespace pistol88\order\widgets\field_type;

class Textarea extends \yii\base\Widget  {
    public $fieldValueModel = null;
    public $fieldModel = null;
    public $form = null;
    
    public function run() {
        return $this->form->field($this->fieldValueModel, 'value['.$this->fieldModel->id.']')->label($this->fieldModel->name)->textArea(['required' => ($this->fieldModel->required == 'yes')]);
    }
}
