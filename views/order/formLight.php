<div class="custom-order-form-light-container" style="max-width: 320px;">
    <?php if (Yii::$app->getModule('service')->splitOrderPerfome) {
        $staffers = isset(yii::$app->worksess->soon()->users) ? yii::$app->worksess->soon()->users : null;
    } else {
        $staffers = null;
    } ?>

    <?= \pistol88\order\widgets\OrderFormLight::widget([
        'useAjax' => $useAjax,
        'staffer' => $staffers
    ]); ?>
</div>
