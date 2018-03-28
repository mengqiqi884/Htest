<?php

namespace v1;
use Yii;
use yii\base\Module;
class V1 extends Module
{
    public function init()
    {
        parent::init();

        // ...  其他初始化代码 ...
        Yii::configure($this, require(__DIR__ . '/config.php'));
    }
}