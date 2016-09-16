<?php
use yii\helpers\Html;
use yii\grid\GridView;
use pistol88\cart\widgets\BuyButton;
use pistol88\cart\widgets\ChangeCount;
use pistol88\cart\widgets\ChangeOptions;
$this->registerJs("$(document).on('renderCart', function() {
        window.parent.pistol88.createorder.updateCart();
    });");
?>

<style>
.to-order {
    padding: 2px;
    margin-top: 2px;
}
.pistol88-order-prodlist-widgets {
    font-size: 12px;
}

.pistol88-change-count, .pistol88-cart-buy-button {
    display: inline;
    width: 45%;
}

.products-list select {
    padding: 1px;
    margin: 1px;
    font-size: 12px;
    height: auto;
}
</style>
<div class="products-list">

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['attribute' => 'id', 'options' => ['style' => 'width: 45px;']],
            [
                'attribute' => 'name',
                'content' => function($product) {
                    $name = $product->name;

                    $count = ChangeCount::widget(['model' => $product->sellModel]);
                    
                    if($product->getCartOptions()) {
                        $options = ChangeOptions::widget(['model' => $product]);
                    } else {
                        $options = null;
                    }
                    
                    $btn = BuyButton::widget([
                        'model' => $product->sellModel,
                        'text' => yii::t('order', 'To order'),
                        'htmlTag' => 'a',
                        'cssClass' => 'btn btn-success to-order'
                    ]);
                    
                    return Html::tag('div', $name.$options.$count.$btn, ['class' => 'pistol88-order-prodlist-widgets']);
                }
            ],
            'code',
			[
				'attribute' => 'category_id',
				'filter' => Html::activeDropDownList(
					$searchModel,
					'category_id',
					$categories,
					['class' => 'form-control', 'prompt' => 'Категория']
				),
				'value' => 'category.name'
			],
			[
				'label' => yii::t('order', 'Amount'),
				'value' => 'amount'
			],
			[
				'label' => yii::t('order', 'Price'),
				'value' => 'price',
			],
        ],
    ]); ?>
    
</div>
