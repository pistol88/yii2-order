<?php
use yii\helpers\Html;
use yii\grid\GridView;

$columns = [
            ['attribute' => 'id', 'options' => ['style' => 'width: 49px;']],
            ['attribute' => 'username', 'content' => function($model) {
                if($profile = $model->userProfile) {
                    if($fullName = $profile->getFullName()) {
                        return $model->username.' ('.$fullName.')';
                    } else {
                        return $model->username;
                    }
                } else {
                    return $model->username;
                }
            }],
            'email',
            'phone',
        ];

if($fields = yii::$app->getModule('order')->userModelCustomFields) {
    $columns = array_merge($columns, $fields);
}

$columns[] = [
                'label' => yii::t('order', 'Choose'),
                'content' => function($user) {
                    return Html::a(yii::t('order', 'Choose'), "#", ['class' => 'btn btn-success', 'onclick' => 'window.parent.pistol88.createorder.chooseUser('.$user->id.')']);
                }
            ];

?>
<div class="user-list">

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>

</div>
