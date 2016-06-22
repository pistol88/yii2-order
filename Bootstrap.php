<?php
namespace pistol88\order;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        if(!$app->has('orderModel')) {
            $app->set('orderModel', ['class' => 'pistol88\order\models\Order']);
        }

        if(empty($app->modules['gridview'])) {
            $app->setModule('gridview', [
                'class' => '\kartik\grid\Module',
            ]);
        }
        
        if (!isset($app->i18n->translations['order']) && !isset($app->i18n->translations['order*'])) {
            $app->i18n->translations['order'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__.'/messages',
                'forceTranslation' => true
            ];
        }
    }
}
