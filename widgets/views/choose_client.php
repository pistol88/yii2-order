<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>
<div class="choose-client row">
    <div class="col-md-12">
        <label class="control-label" for="order-user_id"><?=Yii::t('order', 'Buyer');?></label>
        <?= Html::input('text', 'Order[user_id]', '', ['id' => 'order-user_id', 'class' => 'form-control', 'data-info-service' => Url::toRoute(['/order/tools/user-info'])]) ?>

        <p><?=Html::a('<i class="glyphicon glyphicon-search"></i> Найти покупателя', '#usersModal', ['id' => 'choose-user-id', 'data-toggle' => "modal", 'data-target' => "#usersModal"]);?></a></p>
        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-12">
        <?= $form->field($model, 'client_name')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<div class="modal fade" id="usersModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?=yii::t('order', 'Clients');?></h4>
            </div>
            <div class="modal-body">
                <iframe src="<?=Url::toRoute(['/order/tools/find-users-window']);?>" id="users-list-window"></iframe>
            </div>
        </div>
    </div>
</div>