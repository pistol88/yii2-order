<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;

$this->title = Yii::t('order', 'Order').' #'.$model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('order', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">
    <?php if(Yii::$app->session->hasFlash('reSendDone')) { ?>
        <script>
        alert('<?= Yii::$app->session->getFlash('reSendDone') ?>');
        </script>
    <?php } ?>

    <p>
        <?= Html::a(Yii::t('order', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('order', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('order', 'Realy?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?=pistol88\order\widgets\ChangeStatus::widget(['model' => $model]);?>
    
    <?php
    $detailElements = [
        'model' => $model,
        'attributes' => [
            'id',
            'client_name',
			[
				'attribute' => 'shipping_type_id',
				'value'		=> @$shippingTypes[$model->shipping_type_id],
			],
			[
				'attribute' => 'payment_type_id',
				'value'		=> @$paymentTypes[$model->payment_type_id],
			],
            'phone',
            'email:email',
            'promocode',
            'comment',
			[
				'attribute' => 'date',
				'value'		=> date(yii::$app->getModule('order')->dateFormat, $model->timestamp),
			],
        ],
    ];
    
    if($model->delivery_type == 'totime') {
        $detailElements['attributes'][] = 'delivery_time_date';
        $detailElements['attributes'][] = 'delivery_time_hour';
        $detailElements['attributes'][] = 'delivery_time_min';
    }

    if($fields = $fieldFind->all()) {
        foreach($fields as $fieldModel) {
            $detailElements['attributes'][] = [
				'label' => $fieldModel->name,
				'value'		=> Html::encode($fieldModel->getValue($model->id)),
			];
        }
    }

    echo DetailView::widget($detailElements);
    ?>


	<h2><?=Yii::t('order', 'Order list'); ?></h2>

    <?= \kartik\grid\GridView::widget([
        'export' => false,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			[
				'attribute' => 'item_id',
                'content' => function($model) {
                    if($productModel = $model->product) {
                        return $productModel->getCartId().'. '.$productModel->getCartName();
                    }
                    else {
                        return Yii::t('order', 'Unknow product');
                    }
                }
			],
			[
				'attribute' => 'description',
                'content' => function($model) {
                    $return = $model->description;

                    if($options = json_decode($model->options)) {
                        foreach($options as $name => $value) {
                            $return .= Html::tag('p', Html::encode($name).': '.Html::encode($value));
                        }
                    }
                    
                    return $return;
                }
			],
			'count',
			'price',
            ['class' => 'yii\grid\ActionColumn', 'controller' => '/order/element', 'template' => '{delete}',  'buttonOptions' => ['class' => 'btn btn-default'], 'options' => ['style' => 'width: 75px;']],
        ],
    ]); ?>
    <h3 align="right"><?=Yii::t('order', 'In total'); ?>: <?=$model->count;?> <?=Yii::t('order', 'on'); ?> <?=$model->cost;?> <?=Yii::$app->getModule('order')->currency;?> </h3>
</div>
