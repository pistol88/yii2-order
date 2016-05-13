<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('order', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="order-index">

    <div class="row">
        <div class="col-lg-6">
            <?= Html::a(Yii::t('order', 'Create order'), ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="col-lg-6">
            <?= $this->render('/parts/menu.php'); ?>
        </div>
    </div>

    <hr />

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['attribute' => 'id', 'filter' => false, 'options' => ['style' => 'width: 49px;']],
        			[
        				'attribute' => 'count',
                        'label' => yii::t('order', 'Cnt'),
        				'content' => function($model) {
        					return $model->count;
        				}
        			],
        			[
        				'attribute' => 'price',
                        'label' => yii::t('order', 'Price'),
        				'content' => function($model) {
        					return $model->total;
        				}
        			],
                    'client_name',
                    'phone',
                    'email:email',
                    [
                        'attribute' => 'payment_type_id',
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'payment_type_id',
                            $paymentTypes,
                            ['class' => 'form-control', 'prompt' => Yii::t('order', 'Payment type')]
                        ),
                        'value' => function($model) use ($paymentTypes) {
                            return $paymentTypes[$model->payment_type_id];
                        }
                    ],
                    [
                        'attribute' => 'shipping_type_id',
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'shipping_type_id',
                            $shippingTypes,
                            ['class' => 'form-control', 'prompt' => Yii::t('order', 'Shipping type')]
                        ),
                        'value' => function($model) use ($shippingTypes) {
                            return $shippingTypes[$model->shipping_type_id];
                        }
                    ],
                    [
                        'attribute' => 'date',
                        'filter' => false,
                        'value' => function($model) {
                            return date(yii::$app->getModule('order')->dateFormat, $model->timestamp);
                        }
                    ],
        			[
        				'attribute' => 'status',
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'status',
                            Yii::$app->getModule('order')->orderStatuses,
                            ['class' => 'form-control', 'prompt' => Yii::t('order', 'Status')]
        				),
        				'value'	=> function($model) {
        					return  Yii::$app->getModule('order')->orderStatuses[$model->status];
        				}
        			],
                    ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {update} {delete}',  'buttonOptions' => ['class' => 'btn btn-default'], 'options' => ['style' => 'width: 145px;']],
                ],
            ]); ?>
        </div>
    </div>


</div>
