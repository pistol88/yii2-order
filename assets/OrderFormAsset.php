<?php
namespace pistol88\order\assets;

use yii\web\AssetBundle;

class OrderFormAsset extends AssetBundle
{
    public $depends = [
        'pistol88\order\assets\Asset'
    ];

    public $js = [
        'js/scripts.js',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/../web';
        parent::init();
    }

}
