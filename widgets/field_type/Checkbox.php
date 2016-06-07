<?php
namespace pistol88\order\widgets\field_type;

use pistol88\order\models\FieldValue;

class Checkbox extends \yii\base\Widget
{
    public $fieldModel = null;
    public $form = null;
    
    public function run()
    {
        $fieldValueModel = new FieldValue;
        return $this->form->field($fieldValueModel, 'value['.$this->fieldModel->id.']')->label($this->fieldModel->name)->checkbox(['required' => ($this->fieldModel->required == 'yes'),'label' => $this->fieldModel->name]);
    }
}
