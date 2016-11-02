<?php
use pistol88\order\widgets\PromocodeInformer;
use yii\helpers\Html;
use pistol88\order\assets\Asset;

Asset::register($this);
$this->title = yii::t('order', 'Промокоды');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-menu row">
    <div class="col-lg-10 col-lg-offset-2">
        <?= $this->render('/parts/menu.php', ['active' => 'pr']); ?>
    </div>
</div>
<div class="informer-widget">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?=yii::t('order', 'Statistics');?>: промокоды</h3>
        </div>
        <div class="panel-body">
            <?=PromocodeInformer::widget();?>
        </div>
    </div>
</div>