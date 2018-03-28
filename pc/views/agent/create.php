<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CAgent $model
 */

$this->title = 'Create Cagent';
$this->params['breadcrumbs'][] = ['label' => 'Cagents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cagent-create">

    <?php
      switch($flag) {
          case 'create-agent': //创建4S店
              echo  $this->render('_formagent', [
                  'model' => $model,
              ]);
              break;
      }
    ?>

</div>
