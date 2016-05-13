<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use pistol88\order\models\OrderFieldType;
use yii\helpers\ArrayHelper;
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'name')->textInput() ?>
    
        <?= $form->field($model, 'order')->textInput() ?>

        <?= $form->field($model, 'cost')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('order', 'Create') : Yii::t('order', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
