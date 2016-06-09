<?php
$currency = yii::$app->getModule('order')->currency;
?>
<div class=" order-informer">
    <div class="container">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-hover">
                    <tr>
                        <th>&nbsp;</th>
                        <th><?=yii::t('order', 'Today');?></th>
                        <th><?=yii::t('order', 'In month');?></th>
                        <th><?=yii::t('order', 'By month');?></th>
                    </tr>
                    <tr>
                        <td><?=yii::t('order', 'Turnover');?></td>
                        <td><?=round($today['total']);?><?=$currency; ?></td>
                        <td><?=round($inMonth['total'], 2);?><?=$currency; ?></td>
                        <td>
                            <?=round($byMonth['total'], 2);?><?=$currency; ?>
                            <?php
                            if($byOldMonth['total']) {
                                $cssClass = '';
                                $sum = '+0';
                                if($byOldMonth['total'] < $inMonth['total']) {
                                    $cssClass = 'good-result';
                                    $sum = '+'.($inMonth['total']-$byOldMonth['total']);
                                } elseif($byOldMonth['total'] < $inMonth['total']) {
                                    $cssClass = 'bad-result';
                                    $sum = '-'.($byOldMonth['total']-$inMonth['total']);
                                }
                                ?>
                                    <span class="result <?=$cssClass;?>"><?=$sum;?></span>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?=yii::t('order', 'Orders count');?></td>
                        <td><?=round($today['count_order'], 2);?></td>
                        <td><?=round($inMonth['count_order'], 2);?></td>
                        <td><?=round($byMonth['count_order'], 2);?></td>
                    </tr>
                    <tr>
                        <td><?=yii::t('order', 'Average check');?></td>
                        <td><?php if($today['count_order']) { ?><?=round($today['total']/$today['count_order'], 2);?><?=$currency; ?><?php } ?></td>
                        <td><?php if($inMonth['count_order']) { ?><?=round($inMonth['total']/$inMonth['count_order'], 2);?><?=$currency; ?><?php } ?></td>
                        <td><?php if($byMonth['count_order']) { ?><?=round($byMonth['total']/$byMonth['count_order'], 2);?><?=$currency; ?><?php } ?></td>
                    </tr>
                </table>
            </div>
    </div>
</div>