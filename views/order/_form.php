<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use pistol88\order\models\PaymentType;
use pistol88\order\models\ShippingType;

$paymentTypes = ArrayHelper::map(PaymentType::find()->all(), 'id', 'name');
$shippingTypes = ArrayHelper::map(ShippingType::find()->all(), 'id', 'name');
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'status')->dropDownList(Yii::$app->getModule('order')->orderStatuses) ?>

        <?= $form->field($model, 'client_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'shipping_type_id')->dropDownList($shippingTypes) ?>

        <?= $form->field($model, 'payment_type_id')->dropDownList($paymentTypes) ?>

        <?= $form->field($model, 'comment')->textArea(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('order', 'Create') : Yii::t('order', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
