<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('order', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">
    <?php if(Yii::$app->session->hasFlash('reSendDone')) { ?>
        <script>
        alert('<?= Yii::$app->session->getFlash('reSendDone') ?>');
        </script>
    <?php } ?>


    <h1><?=Yii::t('order', 'Order');?> #<?= $model->id ?></h1>

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

    <?php
    $detailElements = [
        'model' => $model,
        'attributes' => [
            'id',
            [
				'attribute' => 'status',
				'value'		=> Yii::$app->getModule('order')->orderStatuses[$model->status],
			],
            'client_name',
			[
				'attribute' => 'shipping_type_id',
				'value'		=> $shippingTypes[$model->shipping_type_id],
			],
			[
				'attribute' => 'payment_type_id',
				'value'		=> $paymentTypes[$model->payment_type_id],
			],
            'phone',
            'email:email',
            'comment',
			[
				'attribute' => 'date',
				'value'		=> date(yii::$app->getModule('order')->dateFormat, $model->timestamp),
			],
        ],
    ];

    if($fields = $fieldFind->all()) {
        foreach($fields as $fieldModel) {
            $detailElements['attributes'][] = [
				'label' => $fieldModel->name,
				'value'		=> $fieldModel->getValue($model->id),
			];
        }
    }

    echo DetailView::widget($detailElements);
    ?>


	<h2><?=Yii::t('order', 'Order list'); ?></h2>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			[
				'attribute' => 'item_id',
                'content' => function($model) {
                    if($productModel = $model->product) {
                        return $productModel->getCartName();
                    }
                    else {
                        return Yii::t('order', 'Unknow product');
                    }
                }
			],
            'description',
			[
				'class' => \dosamigos\grid\EditableColumn::className(),
				'attribute' => 'count',
				'content' => function($model) {
                    if($productModel = $model->product->getProduct()) {
                        return $model->count;
                    }
                    else {
                        return Yii::t('order', 'Unknow product');
                    }
                },
                'filter' => false,
				'url' => ['/order/element/editable'],
				'editableOptions' => [
					'mode' => 'inline',
				]
			],
			[
				'class' => \dosamigos\grid\EditableColumn::className(),
				'attribute' => 'price',
				'url' => ['/order/element/editable'],
                'filter' => false,
				'editableOptions' => [
					'mode' => 'inline',
				]
			],
            ['class' => 'yii\grid\ActionColumn', 'controller' => '/order/element', 'template' => '{delete}',  'buttonOptions' => ['class' => 'btn btn-default'], 'options' => ['style' => 'width: 75px;']],
        ],
    ]); ?>
    <h3 align="right"><?=Yii::t('order', 'In total'); ?>: <?=$model->getCount();?>, <?=$model->getTotal();?> <?=Yii::$app->getModule('order')->currency;?> </h3>
</div>
