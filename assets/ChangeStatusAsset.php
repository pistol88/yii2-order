<?php
namespace pistol88\order\assets;

use yii\web\AssetBundle;

class ChangeStatusAsset extends AssetBundle
{
    public $depends = [
        'pistol88\order\assets\Asset'
    ];

    public $js = [
        'js/changestatus.js',
    ];

    public $css = [
        
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/../web';
        parent::init();
    }

}
