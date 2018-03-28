<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */
?>
<div class="body-content about" data-page-no="2">
    <section id="story_area" class="section_padding">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="story_image wow slideInLeft" data-wow-duration="2s">
                        <img src="<?=\yii\helpers\Url::to('@web/images/bg.jpg')?>" alt="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="story_text wow slideInRight" data-wow-duration="2s">
                        <h2>Our Short Story</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam volutpat purus in ferment uectetur tortor id, pharetra lorem. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam volutpat purus in ferment uectetur tortor id, pharetra lorem.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam volutpat purus in ferment uectetur tortor id, pharetra lorem. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam volutpat purus in ferment uectetur tortor id, pharetra lorem.</p>
                        <a href="">More about our team</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(function(){
        activeTab(1); //第一个Tab
    })
</script>
