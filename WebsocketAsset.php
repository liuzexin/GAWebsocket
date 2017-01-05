<?php
/**
 * Created by PhpStorm.
 * User: xin
 * Date: 2017/1/5
 * Time: 上午9:22
 */
namespace app\assets;
use yii\web\AssetBundle;

class WebsocketAsset extends AssetBundle{

    public $sourcePath = '@bower/jquery-ui';
    public $js = [
        'jquery-ui.min.js',
    ];
    public $css = [
        'themes/black-tie/jquery-ui.min.css',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}

