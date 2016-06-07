<?php
namespace pistol88\order\widgets\field_type;

use pistol88\order\models\FieldValue;

class Input extends \yii\base\Widget
{
    public $fieldValueModel = null;
    public $fieldModel = null;
    public $form = null;
    
    public function run()
    {
        $fieldValueModel = new FieldValue;
        return $this->form->field($fieldValueModel, 'value['.$this->fieldModel->id.']')->label($this->fieldModel->name)->textInput(['required' => ($this->fieldModel->required == 'yes')]);
    }
}
