<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use admin\models\CarSearch;
use admin\models\CCity;
use common\helpers\ArrayHelper;

\admin\assets\ImgAsset::register($this);
/**
 * @var yii\web\View $this
 * @var admin\models\CCarreplace $model
 */

$this->title = $model->r_id;
$this->params['breadcrumbs'][] = ['label' => 'Ccarreplaces', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$img_url=Yii::$app->params['base_url'].Yii::$app->params['base_file'].'/';

$current_city='';
if($model->r_city){
    $name=CCity::GetCityName($model->r_city);
    if($name){
        $f_code=explode('^',$name)[1];
        $son_city=explode('^',$name)[0];
        $parent=CCity::GetCityName($f_code);
        $parent_city=explode('^',$parent)[0];
        if($parent){
            $current_city=CCity::insertToStr($son_city,0,$parent_city.'、');
        }
    }
}
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <!--车辆信息-->
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-title"><h5>车辆信息</h5></div>
                <div class="ibox-content">
                    <?=DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'label' => '车主车系',
                                'attribute' => 'r_brand',
                                'value' => CarSearch::getCarTitle($model->r_brand) . ' ' . CarSearch::getCarTitle($model->r_car_id)
                            ],
                            [
                                'label' => '排量',
                                'attribute' => 'r_volume_id',
                                'value' => CarSearch::getCarTitle($model->r_volume_id)
                            ],
                            'r_cardtime',
                            [
                                'label' => '上牌城市',
                                'attribute' => 'r_city',
                                'value' => $current_city
                            ],
                            [
                                'label' => '行驶公里',
                                'attribute' => 'r_miles',
                                'value' => $model->r_miles .'公里'
                            ],
                            [
                                'attribute' => 'r_price',
                                'value' => \common\helpers\StringHelper::change_numbertomillion($model->r_price) .'元'
                            ]
                        ]
                    ]);
                    ?>
                </div>
            </div>
        </div>
        <!--车辆行驶证照片 -->
        <div class="col-sm-6">
            <div class="ibox">
                <div class="ibox-title"><h5>行驶证</h5></div>
                <div class="ibox-content">
                    <div class="carousel slide" id="carousel1">
                        <div class="carousel-inner">
                            <div class="item active">
                                <img src="<?= empty($model->r_driving_pic1)?'无':$model->r_driving_pic1 ?>"
                                     class="img-responsive" width="200" height="auto">
                            </div>
                            <div class="item ">
                                <img src="<?= empty($model->r_driving_pic2)?'无':$model->r_driving_pic2 ?>"
                                     class="img-responsive" width="200" height="auto">
                            </div>
                        </div>
                        <a data-slide="prev" href="#carousel1" class="left carousel-control">
                            <span class="icon-prev"></span>  <!--左-->
                        </a>
                        <a data-slide="next" href="#carousel1" class="right carousel-control">
                            <span class="icon-next"></span>  <!--右-->
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!--车辆里程表照片-->
        <div class="col-sm-6">
            <div class="ibox">
                <div class="ibox-title"><h5>里程表</h5></div>
                <div class="ibox-content">
                    <div class="carousel slide" id="carousel2">
                        <div class="carousel-inner">
                            <div class="item active">
                                <img src="<?= empty($model->r_mileage_pic1)?'无':$model->r_mileage_pic1 ?>"
                                     class="img-responsive" width="200" height="auto">
                            </div>
                            <div class="item ">
                                <img src="<?= empty($model->r_mileage_pic2)?'无':$model->r_mileage_pic2?>"
                                     class="img-responsive" width="200" height="auto">
                            </div>
                        </div>
                        <a data-slide="prev" href="#carousel2" class="left carousel-control">
                            <span class="icon-prev"></span>  <!--左-->
                        </a>
                        <a data-slide="next" href="#carousel2" class="right carousel-control">
                            <span class="icon-next"></span>  <!--右-->
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!--车辆外观照片-->
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-title"><h5>汽车外观图片</h5></div>
                <div class="ibox-content">
                    <?php
                    if(!empty($outpics)){
                        foreach($outpics as $key=>$val){
                            echo '<a class="fancybox" href="' . $val['rp_pics'] . '" title="外观图片'.$key.'">' .
                                '<img alt="图片" src="' . $val['rp_pics'] . '" width="100px"/>' .
                                '</a> | ';
                        }
                    }else{
                        echo "暂无外观照片";
                    }
                    ?>

                </div>
            </div>
        </div>
        <!--车辆内饰照片-->
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-title"><h5>汽车内饰图片</h5></div>
                <div class="ibox-content">
                    <?php
                    if(!empty($inpics)){
                        foreach($inpics as $key=>$val){
                            echo '<a class="fancybox" href="' . $val['rp_pics'] . '" title="内饰图片'.$key.'">' .
                                '<img alt="图片" src="' . $val['rp_pics'] . '" width="100px"/>' .
                                '</a> | ';
                        }
                    }
                    ?>

                </div>
            </div>
        </div>
        <!--发布信息-->
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-title"><h5>发布信息</h5></div>
                <div class="ibox-content">
                    <table class="table">
                        <tr>
                            <th>类型</th><td><?= $model->r_role==1 ? '用户':'4s店'?></td>
                            <th>申请时间</th><td><?= $model->created_time?></td>
                        </tr>
                        <tr>
                            <th>昵称</th><td><?= $model->r_role==1?$model['user']->u_nickname : $model['agent']->a_name ?></td>
                            <th>联系方式</th><td><?= $model->r_role==1?$model['user']->u_phone : $model['agent']->a_phone ?></td>
                        </tr>
                        <tr>
                            <th>性别</th><td><?= $model->r_role==1?($model['user']->u_sex==1?'男':'女'):'' ?></td>
                            <th>年龄</th><td><?= $model->r_role==1?$model['user']->u_age.'岁':'' ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
