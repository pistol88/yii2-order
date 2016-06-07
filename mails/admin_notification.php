<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\DetailView;
?>
<h1><?=Yii::t('order', 'New order'); ?> #<?=$model->id;?></h1>

<p><?=Html::a(yii::t('order', 'View'), Url::to(['/order/order/view', 'id' => $model->id], true));?></p>

<ul>
    <li><?=$model->client_name;?></li>
	<li><?=$model->phone;?></li>
	<li><?=$model->comment;?></li>
    <li><?=$model->date;?> <?=$model->time;?></li>
    <?php if($model->payment) { ?>
        <li><?=$model->payment->name;?></li>
    <?php } ?>
    <?php if($model->shipping) { ?>
        <li><?=$model->shipping->name;?></li>
    <?php } ?>
        
    <?php
    if($fields = $model->fields) {
        foreach($fields as $fieldModel) {
            echo "<li>{$fieldModel->field->name}: {$fieldModel->value}</li>";
        }
    }
    ?>
</ul>

<h2><?=Yii::t('order', 'Order list'); ?></h2>

<table width="100%">
    <?php foreach($model->elements as $element) { ?>
        <tr>
            <td>
                <?=$element->product->getCartName(); ?>
                <?php if($element->description) { echo "({$element->description})"; } ?>
            </td>
            <td>
                <?=$element->count;?>
            </td>
            <td>
                <?=$element->price;?>
            </td>
        </tr>

    <?php } ?>
</table>
]); ?>
    
<h3><?=Yii::t('order', 'In total'); ?>: <?=$model->count;?>, <?=$model->total;?> <?=Yii::$app->getModule('order')->currency;?> </h3>
