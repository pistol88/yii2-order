<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

if($dateStart = yii::$app->request->get('date_start')) {
    $dateStart = date('Y-m-d', strtotime($dateStart));
}

if($dateStop = yii::$app->request->get('date_stop')) {
    $dateStop = date('Y-m-d', strtotime($dateStop));
}

?>
<div class="worker-payments-widget">
    <div class="summary">
        <?=yii::t('order', 'Total');?>:
        <?=number_format($dataProvider->query->sum('cost'), 2, ',', '.');?>
    </div>
    <?php Pjax::begin(); ?>
    <form action="<?=Url::toRoute(['/client/client/update', 'id' => $clientId]);?>" class="row search">
        <input type="hidden" name="id" value="<?=$clientId;?>" />
        <div class="col-md-4">
            <input style="width: 180px; float: left;" class="form-control" type="date" name="date_start" value="<?=$dateStart;?>" />
            <input style="width: 180px;" class="form-control" type="date" name="date_stop" value="<?=$dateStop;?>" />
        </div>

        <div class="col-md-2">
            <select class="form-control" name="OrderSearch[status]">
                <option value=""><?=yii::t('order', 'Status');?></option>
                <?php foreach(yii::$app->getModule('order')->orderStatuses as $status => $statusName) { ?>
                    <option <?php if($status == yii::$app->request->get('OrderSearch')['status']) echo ' selected="selected"';?> value="<?=$status;?>"><?=$statusName;?></option>
                <?php } ?>
            </select>
        </div>

        <?php if($sellers = yii::$app->getModule('order')->getSellerList()) { ?>
            <div class="col-md-2">
                <select class="form-control" name="OrderSearch[seller_user_id]">
                    <option value=""><?=yii::t('order', 'Seller');?></option>
                    <?php foreach($sellers as $seller) { ?>
                        <option <?php if($seller->id == yii::$app->request->get('OrderSearch')['seller_user_id']) echo ' selected="selected"';?> value="<?=$seller->id;?>"><?=$seller->username;?></option>
                    <?php } ?>
                </select>
            </div>
        <?php } ?>

        <div class="col-md-2">
            <input type="checkbox" <?php if(yii::$app->request->get('promocode')) echo ' checked="checked"'; ?> name="promocode" value="1" id="order-promocode" />
            <label for="order-promocode"><?=yii::t('order', 'Promocode');?></label>
        </div>

        <div class="col-md-2">
            <input class="form-control" type="submit" value="<?=Yii::t('order', 'Search');?>" class="btn btn-success" />
        </div>
    </form>
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['attribute' => 'id', 'filter' => false, 'options' => ['style' => 'width: 49px;']],
            [
                'attribute' => 'cost',
                'label' => yii::$app->getModule('order')->currency,
                'content' => function($model) {
                    $total = $model->cost;
                    if($model->promocode) {
                        $total .= Html::tag('div', $model->promocode, ['style' => 'color: orange; font-size: 80%;', yii::t('order', 'Promocode')]);
                    }

                    return $total;
                },
            ],
            ['attribute' => 'count', 'filter' => false],
            'date',
            ['content' => function($model) {
                    return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['/order/order/view', 'id' => $model->id], ['class' => 'btn btn-default']);
            }, 'options' => ['style' => 'width: 50px;']]
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
