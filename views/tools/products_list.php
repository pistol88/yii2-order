<?php
use yii\helpers\Html;
use yii\grid\GridView;
use pistol88\cart\widgets\BuyButton;
use pistol88\cart\widgets\ChangeCount;
$this->registerJs("$(document).on('renderCart', function() {
        window.parent.pistol88.createorder.updateCart();
    });");
?>

<style>
.to-order {
    padding: 2px;
    margin-top: 2px;
}    
</style>
<div class="products-list">

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['attribute' => 'id', 'options' => ['style' => 'width: 45px;']],
            'name',
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
                'content' => function($product) {
                    $price = $product->price;

                    $count = ChangeCount::widget(['model' => $product->sellModel]);
                    $btn = BuyButton::widget([
                        'model' => $product->sellModel,
                        'text' => yii::t('order', 'To order'),
                        'htmlTag' => 'a',
                        'cssClass' => 'btn btn-success to-order'
                    ]);
                    
                    return $product->sellModel->getCartPrice().$count.$btn;
                }
			],
        ],
    ]); ?>
    
</div>
