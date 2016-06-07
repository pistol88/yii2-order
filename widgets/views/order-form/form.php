<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$widgets = [];
?>
<?php if(Yii::$app->session->hasFlash('orderError')) { ?>
    <script>
    alert('<?= Yii::$app->session->getFlash('orderError') ?>');
    </script>
<?php } ?>
<div class="pistol88_order_form">
    <?php $form = ActiveForm::begin(['action' => Url::toRoute(['/order/order/customer-create'])]); ?>
    
        <div class="row">
            <div class="col-lg-6"><?= $form->field($orderModel, 'client_name')->textInput(['required' => true]) ?></div>
            <div class="col-lg-3"><?= $form->field($orderModel, 'phone')->textInput(['required' => true]) ?></div>
            <div class="col-lg-3"><?= $form->field($orderModel, 'email')->textInput(['required' => true, 'type' => 'email']) ?></div>
        </div>

        <?php if($fields = $fieldFind->all()) { ?>
            <div class="row">
                <?php foreach($fields as $fieldModel) { ?>
                    <div class="col-lg-12 col-xs-12">
                        <?php
                        if($widget = $fieldModel->type->widget) {
                            if(!$widgets[$widget]) {
                                $widgets[$widget] = new $widget;
                            }
                            echo $widgets[$widget]::widget(['form' => $form, 'fieldModel' => $fieldModel]);
                        }
                        else {
                            echo $form->field($fieldValueModel, 'value['.$fieldModel->id.']')->label($fieldModel->name)->textInput(['required' => ($fieldModel->required == 'yes')]);
                        }
                        ?>
                        <?php if($fieldModel->description) { ?>
                            <p><small><?=$fieldModel->description;?></small></p>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col-lg-12"><?= $form->field($orderModel, 'comment')->textArea() ?></div>
        </div>

        <?= $form->field($orderModel, 'shipping_type_id')->dropDownList($shippingTypes) ?>
        
        <?= $form->field($orderModel, 'payment_type_id')->dropDownList($paymentTypes) ?>
    
        <div class="row">
            <div class="col-lg-12">
                <?= Html::submitButton(Yii::t('order', 'Create order'), ['class' => 'btn btn-success']) ?>
                <?php if($referrer = Yii::$app->request->referrer) { ?>
                    <?= Html::a(Yii::t('order', 'Continue shopping'), Html::encode($referrer), ['class' => 'btn btn-default']) ?>
                <?php } ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
