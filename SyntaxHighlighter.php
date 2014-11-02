<?php

/**
 * SyntaxhigHlighter class file.
 *
 * @author  Giovanni Derks
 * @license MIT License
 * http://derks.me.uk
 */

namespace giovdk21\yii2SyntaxHighlighter;

use Yii;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\base\Widget as Widget;

class SyntaxHighlighter extends Widget
{
    public $theme = 'default';

    public $brushes = [];
    public $brushAliases = [
        'js' => 'JScript',
    ];

    // config:
    public $bloggerMode = false;
    public $strings = [];
    public $stripBrs = false;
    public static $tagName = 'pre';

    // defaults:
    public $showToolbar = false;
    public $tabSize = 4;
    public $blockClassName = '';
    public $autoLinks = true;
    public $collapse = false;
    public $showGutter = true;
    public $smartTabs = true;


    /**
     * Publishes the assets
     */
    public function publishAssets()
    {
        SyntaxHighlighterAsset::register($this->getView());
    }

    /**
     * Run the widget
     */
    public function run()
    {

        SyntaxHighlighterAsset::$extraCss[] = 'styles/shCore'.ucfirst($this->theme).'.css';

        foreach ($this->brushes as $brushName) {
            $brushFile = (!empty($this->brushAliases[$brushName])
                ? $this->brushAliases[$brushName]
                : ucfirst(
                    $brushName
                ));
            SyntaxHighlighterAsset::$extraJs[] = 'scripts/shBrush'.$brushFile.'.js';
        }

        $this->publishAssets();

        $initJs = '';
        $initJs .= "SyntaxHighlighter.config.bloggerMode = ".($this->bloggerMode ? 'true' : 'false').";\n";
        if (!empty($this->strings)) {
            $initJs .= "SyntaxHighlighter.config.strings = ".Json::encode($this->strings).";\n";
        }
        $initJs .= "SyntaxHighlighter.config.stripBrs = ".($this->stripBrs ? 'true' : 'false').";\n";
        $initJs .= "SyntaxHighlighter.config.tagName = '".static::$tagName."';\n";
        $initJs .= "SyntaxHighlighter.defaults = {
            'tab-size': {$this->tabSize},
            'class-name': '{$this->blockClassName}',
            'auto-links': ".($this->autoLinks ? 'true' : 'false').",
            'collapse': ".($this->collapse ? 'true' : 'false').",
            'gutter': ".($this->showGutter ? 'true' : 'false').",
            'smart-tabs': ".($this->smartTabs ? 'true' : 'false').",
            'toolbar': ".($this->showToolbar ? 'true' : 'false')."
        };\n";
        $initJs .= 'SyntaxHighlighter.all();'."\n";
        $this->getView()->registerJs($initJs, View::POS_READY, 'InitSyntaxHighlighter');


        parent::run();
    }

    public static function getBlock($source, $type, $firstLine = 1)
    {
        $res = Html::tag(
            static::$tagName,
            htmlentities($source),
            [
                'class' => 'brush: '.$type.'; first-line: '.($firstLine).';',
            ]
        );

        return $res;
    }

}
