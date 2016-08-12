<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use pistol88\order\models\PaymentType;
use pistol88\order\models\ShippingType;
use pistol88\cart\widgets\ElementsList;
use pistol88\cart\widgets\CartInformer;
use pistol88\order\widgets\BuyByCode;
use pistol88\order\widgets\ChooseClient;
use pistol88\order\models\FieldValue;

$paymentTypes = ArrayHelper::map(PaymentType::find()->orderBy('order DESC')->all(), 'id', 'name');
$shippingTypes = ArrayHelper::map(ShippingType::find()->orderBy('order DESC')->all(), 'id', 'name');

pistol88\order\assets\CreateOrderAsset::register($this);
$this->registerJs("pistol88.createorder.updateCartUrl = '".Url::toRoute(['tools/cart-info'])."';");

$this->title = Yii::t('order', 'Create order');
$this->params['breadcrumbs'][] = ['label' => Yii::t('order', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create">

<div class="order-form">
    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'orderForm']); ?>
        <div class="col-lg-6">
            <div class=" panel panel-default">
                <div class="panel-heading"><h3>Заказ</h3></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'status')->dropDownList(Yii::$app->getModule('order')->orderStatuses) ?>
                        </div>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'date')->textInput(['value' => date('Y-m-d H:i:s')]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'shipping_type_id')->dropDownList($shippingTypes) ?>
                        </div>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'payment_type_id')->dropDownList($paymentTypes) ?>
                        </div>
                    </div>

                    <?php if($fields = $model->allfields) { ?>
                        <div class="row">
                            <?php foreach($fields as $fieldModel) { ?>
                                <div class="col-lg-12 col-xs-12">
                                    <?php
                                    if($widget = $fieldModel->type->widget) {
                                        echo $widget::widget(['form' => $form, 'fieldModel' => $fieldModel]);
                                    }
                                    else {
                                        echo $form->field(new FieldValue, 'value['.$fieldModel->id.']')->label($fieldModel->name)->textInput(['required' => ($fieldModel->required == 'yes')]);
                                    }
                                    ?>
                                    <?php if($fieldModel->description) { ?>
                                        <p><small><?=$fieldModel->description;?></small></p>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>

                    <?= $form->field($model, 'comment')->textArea(['maxlength' => true]) ?>
                </div>
            </div>
            <div class=" panel panel-default">
               <div class="panel-heading"><h3>Клиент</h3></div>
               <div class="panel-body">
                   <?=ChooseClient::widget(['form' => $form, 'model' => $model]);?>
               </div>

           </div>
        </div>
        <?php ActiveForm::end(); ?>
        <div class="col-lg-6">
            <div class=" panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-lg-9">
                            <h3>Состав</h3>
                        </div>
                        <div class="col-lg-3">
                            <a href="#productsModal" data-toggle="modal" data-target="#productsModal" class="choice-product btn "><?=yii::t('order', 'Choose');?>... <span class="glyphicon glyphicon-plus add-option"></span></a>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="cart">
                        <?=ElementsList::widget(['type' => ElementsList::TYPE_FULL, 'showOffer' => false, 'otherFields' => yii::$app->getModule('order')->cartCustomFields]);?>
                    </div>
                    <?=BuyByCode::widget();?>
                </div>
            </div>

            <?php if(yii::$app->has('promocode')) { ?>
                <div class=" panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-lg-9">
                                <h3>Промокод</h3>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="form-group promocode">
                            <?=\pistol88\promocode\widgets\Enter::widget();?>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class=" panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-lg-9">
                            <h3>Итого</h3>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="total">
                        <?= CartInformer::widget(['htmlTag' => 'div', 'text' => '{c} на {p}']); ?>
                    </div>
                    <div class="form-group offer">
                        <?= Html::submitButton($model->isNewRecord ? Yii::t('order', 'Create order') : Yii::t('order', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'onclick' => '$("#orderForm").submit();']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="productsModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?=yii::t('order', 'Products');?></h4>
      </div>
      <div class="modal-body">
        <iframe src="<?=Url::toRoute(['tools/find-products-window']);?>" id="products-list-window"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=yii::t('order', 'Close');?></button>
      </div>
    </div>
  </div>
</div>


</div>
