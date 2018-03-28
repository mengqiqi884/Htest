<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('@photo', dirname(dirname(__DIR__)) . '/photo');
Yii::setAlias('@admin', dirname(dirname(__DIR__)) . '/admin');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('tests', dirname(dirname(__DIR__)) . '/tests');
Yii::setAlias('v1',dirname(dirname(__DIR__)).'/api/modules/v1');
Yii::setAlias('v2',dirname(dirname(__DIR__)).'/api/modules/v2');
Yii::setAlias('pc',dirname(dirname(__DIR__)).'/pc');