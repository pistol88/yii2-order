<?php
namespace pistol88\order\widgets\field_type;

use yii\helpers\ArrayHelper;
use pistol88\order\models\FieldValue;

class Select extends \yii\base\Widget
{
    public $fieldModel = null;
    public $form = null;
    
    public function run()
    {
        $variants = $this->fieldModel->getVariants()->all();
        $fieldValueModel = new FieldValue;
        
        $variantsList = ['' => '-'];

        $variants = ArrayHelper::map($variants, 'value', 'value');
        
        foreach($variants as $var) {
            $variantsList[$var] = $var;
        }
        
        return $this->form->field($fieldValueModel, 'value['.$this->fieldModel->id.']')->label($this->fieldModel->name)
                ->dropDownList($variantsList, ['required' => ($this->fieldModel->required == 'yes')]);
    }
}
