<?php
namespace pistol88\order;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        if (!isset($app->i18n->translations['order']) && !isset($app->i18n->translations['order*'])) {
            $app->i18n->translations['order'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__.'/messages',
                'forceTranslation' => true
            ];
        }
    }
}
