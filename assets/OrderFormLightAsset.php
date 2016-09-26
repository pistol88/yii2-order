<?php
namespace pistol88\order\assets;

use yii\web\AssetBundle;

class OrderFormLightAsset extends AssetBundle
{
    public $depends = [
        'pistol88\order\assets\Asset'
    ];

    public $css = [
        'css/orderFormLight.css',
    ];

    public $js = [
        'js/orderFormLight.js',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/../web';
        parent::init();
    }

}
