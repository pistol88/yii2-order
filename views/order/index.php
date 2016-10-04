<?php
use yii\helpers\Html;
use yii\grid\GridView;
use pistol88\order\widgets\Informer;
use kartik\export\ExportMenu;
use nex\datepicker\DatePicker;
use pistol88\order\assets\Asset;
use pistol88\order\assets\OrdersListAsset;

$this->title = yii::t('order', 'Orders');
$this->params['breadcrumbs'][] = $this->title;


Asset::register($this);
OrdersListAsset::register($this);

if($dateStart = yii::$app->request->get('date_start')) {
    $dateStart = date('Y-m-d', strtotime($dateStart));
}

if($dateStop = yii::$app->request->get('date_stop')) {
    $dateStop = date('Y-m-d', strtotime($dateStop));
}

$columns = [];

$columns[] = ['attribute' => 'id', 'options' => ['style' => 'width: 49px;']];

$columns[] = [
    'attribute' => 'count',
    'label' => yii::t('order', 'Cnt'),
    'content' => function($model) {
        return $model->count;
    }
];

$columns[] = [
	'attribute' => 'base_cost',
	'label' => yii::$app->getModule('order')->currency,
];

$columns[] = [
    'attribute' => 'cost',
    'label' => '%',
    'content' => function($model) {
        $total = $model->cost;
        if($model->promocode) {
            $total .= Html::tag('div', $model->promocode, ['style' => 'color: orange; font-size: 80%;', yii::t('order', 'Promocode')]);
        }

        return $total.$detail;
    },
];

foreach(Yii::$app->getModule('order')->orderColumns as $column) {
    if($column == 'payment_type_id') {
        $column = [
            'attribute' => 'payment_type_id',
            'filter' => Html::activeDropDownList(
                $searchModel,
                'payment_type_id',
                $paymentTypes,
                ['class' => 'form-control', 'prompt' => Yii::t('order', 'Payment type')]
            ),
            'value' => function($model) use ($paymentTypes) {
                if(isset($paymentTypes[$model->payment_type_id])) {
                    return $paymentTypes[$model->payment_type_id];
                }
            }
        ];
    } elseif($column == 'shipping_type_id') {
        $column = [
            'attribute' => 'shipping_type_id',
            'filter' => Html::activeDropDownList(
                $searchModel,
                'shipping_type_id',
                $shippingTypes,
                ['class' => 'form-control', 'prompt' => Yii::t('order', 'Shipping type')]
            ),
            'value' => function($model) use ($shippingTypes) {
                if(isset($shippingTypes[$model->shipping_type_id])) {
                    return $shippingTypes[$model->shipping_type_id];
                }
            }
        ];
    } elseif(is_array($column) && isset($column['field'])) {
        $column = [
            'attribute' => 'field',
            'label' => $column['label'],
            'value' => function($model) use ($column) {
                return $model->getField($column['field']);
            }
        ];
    }
    
    $columns[] = $column;
}

$columns[] = [
	'attribute' => 'date',
	'filter' => false,
	'value' => function($model) {
		return date(yii::$app->getModule('order')->dateFormat, $model->timestamp);
	}
];
        
$columns[] = [
	'attribute' => 'status',
	'filter' => Html::activeDropDownList(
		$searchModel,
		'status',
		yii::$app->getModule('order')->orderStatuses,
		['class' => 'form-control', 'prompt' => Yii::t('order', 'Status')]
	),
	'value'	=> function($model) {
		if(!$model->status) {
			return null;
		}
		
		return  Yii::$app->getModule('order')->orderStatuses[$model->status];
	}
];

$columns[] = ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {update} {delete}',  'buttonOptions' => ['class' => 'btn btn-default'], 'options' => ['style' => 'width: 100px;']];

$order = yii::$app->order;
?>


<div class="main-menu row">
    <div class="col-lg-2">
        <?= Html::a(yii::t('order', 'Create order'), ['create'], ['class' => 'btn btn-success']) ?>
    </div>
    <div class="col-lg-10">
        <?= $this->render('/parts/menu.php', ['active' => 'orders']); ?>
    </div>
</div>

<div class="informer-widget">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?=yii::t('order', 'Statistics');?></h3>
        </div>
        <div class="panel-body">
            <?=Informer::widget();?>
        </div>
    </div>
</div>

<div class="order-index">
    <div class="box">
        <div class="box-body">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?=yii::t('order', 'Search');?></h3>
                </div>
                <div class="panel-body">
                    <?php if(yii::$app->user->can(current(yii::$app->getModule('order')->adminRoles))) { ?>
                        <form action="" class="row search">
                            <?php
                            foreach(Yii::$app->getModule('order')->orderColumns as $column) {
                                if(is_array($column) && isset($column['field'])) {
                                    ?>
                                    <div class="col-md-2">
                                        <label for="custom-field-<?=$column['field'];?>"><?=$column['label'];?></label>
                                        <input class="form-control" type="text" name="order-custom-field[<?=$column['field'];?>]" value="<?=Html::encode(yii::$app->request->get('order-custom-field')[$column['field']]);?>" id="custom-field-<?=$column['field'];?>" />
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            <div class="col-md-4">
                                <label><?=yii::t('order', 'Date');?></label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= DatePicker::widget([
                                            'name' => 'date_start',
                                            'addon' => false,
                                            'value' => $dateStart,
                                            'size' => 'sm',
                                            'language' => 'ru',
                                            'placeholder' => yii::t('order', 'Date from'),
                                            'clientOptions' => [
                                                'format' => 'L',
                                                'minDate' => '2015-01-01',
                                                'maxDate' => date('Y-m-d'),
                                            ],
                                            'dropdownItems' => [
                                                ['label' => 'Yesterday', 'url' => '#', 'value' => \Yii::$app->formatter->asDate('-1 day')],
                                                ['label' => 'Tomorrow', 'url' => '#', 'value' => \Yii::$app->formatter->asDate('+1 day')],
                                                ['label' => 'Some value', 'url' => '#', 'value' => 'Special value'],
                                            ],
                                        ]);?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= DatePicker::widget([
                                            'name' => 'date_stop',
                                            'addon' => false,
                                            'value' => $dateStop,
                                            'size' => 'sm',
                                            'placeholder' => yii::t('order', 'Date to'),
                                            'language' => 'ru',
                                            'clientOptions' => [
                                                'format' => 'L',
                                                'minDate' => '2015-01-01',
                                                'maxDate' => date('Y-m-d'),
                                            ],
                                            'dropdownItems' => [
                                                ['label' => yii::t('order', 'Yesterday'), 'url' => '#', 'value' => \Yii::$app->formatter->asDate('-1 day')],
                                                ['label' => yii::t('order', 'Tomorrow'), 'url' => '#', 'value' => \Yii::$app->formatter->asDate('+1 day')],
                                                ['label' => yii::t('order', 'Some value'), 'url' => '#', 'value' => 'Special value'],
                                            ],
                                        ]);?>
                                    </div>
                                </div>
                            </div>

							<?php if($module->orderStatuses) { ?>
								<div class="col-md-2">
									<label><?=yii::t('order', 'Status');?></label>
									<select class="form-control" name="OrderSearch[status]">
										<option value="">Все</option>
										<?php foreach($module->orderStatuses as $status => $statusName) { ?>
											<option <?php if($status == yii::$app->request->get('OrderSearch')['status']) echo ' selected="selected"';?> value="<?=$status;?>"><?=$statusName;?></option>
										<?php } ?>
									</select>
								</div>
							<?php } ?>

							<?php if($module->elementModels) { ?>
								<div class="col-md-2">
									<label><?=yii::t('order', 'With elements');?></label>
									<select class="form-control" name="element_types[]" multiple>
										<?php foreach($module->elementModels as $elementModel => $elementName) { ?>
											<option <?php if(yii::$app->request->get('element_types') && in_array($elementModel, yii::$app->request->get('element_types'))) echo ' selected="selected"';?> value="<?=$elementModel;?>"><?=$elementName;?></option>
										<?php } ?>
									</select>
								</div>
							<?php } ?>
							
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
                                <input class="btn btn-success form-control" type="submit" value="<?=Yii::t('order', 'Search');?>"  />
                            </div>
                        </form>
                    <?php } ?>
                </div>
            </div>
            
            <div class="summary row">
                <div class="col-md-4">
					<?php if($elementModels = $module->elementModels) { ?>
						<h3><?=yii::t('order', 'Total');?>:</h3>
						<?php foreach($elementModels as $elementModel => $elementName) { ?>
							<?php $query = clone $dataProvider->query; ?>
							<?php if($total = number_format($query->andWhere(['order_element.model' => $elementModel])->sum('order_element.price'))) { ?>
								<p>«<?=$elementName;?>»: <?=$total; ?> <?=$module->currency;?></p>
							<?php } ?>
						<?php } ?>
					<?php } else { ?>
						<h3>
							<?=number_format($dataProvider->query->sum('cost'), 2, ',', '.');?>
							<?=$module->currency;?>
						</h3>
					<?php } ?>
                </div>
                <div class="col-md-4">
                    <ul>
                        <?php
                        foreach($paymentTypes as $pid => $pname) {
                           $query = clone $dataProvider->query;
                           $sum = $query
                                   ->andWhere(['payment_type_id' => $pid])
                                   ->sum('cost');
                           
                           echo '<li>'.$pname.': '.(int)$sum.' '.yii::$app->getModule('order')->currency.'</li>';
                        }
                        ?>
                    </ul>
                </div>
                <div class="col-md-4 export">
                    <?php
                    $gridColumns = $columns;
                    echo ExportMenu::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => $gridColumns
                    ]);
                    ?>
                </div>
            </div>
            
            <div class="order-list">
                <?=  \kartik\grid\GridView::widget([
                    'export' => false,
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => $columns,
                ]); ?>
            </div>
        </div>
    </div>
</div>