<?php

namespace giovdk21\yii2SyntaxHighlighter;

use yii\web\AssetBundle as AssetBundle;
use yii;

class SyntaxHighlighterAsset extends AssetBundle
{
    public $sourcePath = '@yii2SyntaxHighlighter/assets';

    public static $extraCss = [];
    public static $extraJs = [];

    public $css = [];

    public $js = [
        'scripts/shCore.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];

    public function init()
    {

        Yii::setAlias('@yii2SyntaxHighlighter', __DIR__);

        foreach (static::$extraCss as $css) {
            $this->css[] = $css;
        }

        foreach (static::$extraJs as $js) {
            $this->js[] = $js;
        }

        return parent::init();
    }

}