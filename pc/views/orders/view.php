<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use admin\models\CCity;
use common\helpers\ArrayHelper;
\admin\assets\ImgAsset::register($this);
/**
 * @var yii\web\View $this
 * @var admin\models\COrders $model
 */

$this->title = $model->o_id;
$this->params['breadcrumbs'][] = ['label' => 'Corders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$current_city = $parent_city = '数据异常';
if($model->carreplace){
    $name=CCity::GetCityName($model->carreplace->r_city);
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
<style>
    #w2 table{width:100%;font-family: verdana,arial,sans-serif;font-size:14px;color:#333333;border-width: 1px;border-color: #ccc;border-collapse: collapse;margin-top: 5px;}
    #w2 table th{border-width: 1px;padding: 8px;border-style: solid;border-color: #ccc;}
    #w2 table td{text-align:center;border-width: 1px;padding: 9px 8px;border-style: solid;border-color: #ccc;}

    #w2 ul{list-style: none;float: left;padding: 0;width: 100%;}
    #w2 ul li{list-style: none;float:left;width:100px;height:auto;margin:5px 15px;}
</style>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="form-group">
            <?=Html::a('<i class="fa fa-mail-reply"></i> 返回','javascript:history.go(-1);',['class'=>'btn btn-primary']);?>
        </div>

        <!--用户信息-->
        <div class="col-sm-4">
            <div class="ibox">
                <div class="ibox-title"><h5>用户信息</h5></div>
                <div class="ibox-content">
                    <div class="contact-box">
                        <div class="col-sm-4">
                            <div class="text-center">
                                <img alt="image" class="img-circle m-t-xs img-responsive" src="<?=$model->user?$model->user->u_headImg:\yii\helpers\Url::to('@web/img/icons/user.png')?>">
                                <div class="m-t-xs font-bold"><?=$model->user?$model->user->u_nickname:''?></div>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <h3>
                                <strong>
                                    <abbr title="Phone">Tel:</abbr>
                                    <?=$model->user?$model->user->u_phone:'不详'?>
                                </strong>
                            </h3>
                            <p>
                                <i class="fa fa-map-marker"></i>
                                <?=$model->user?($model->user->u_state==1? '启用':'禁用'):'不详'?>
                            </p>
                            <address>
                                性别：<strong><?=$model->user?($model->user->u_sex==1? '男':'女'):'不详'?></strong><br>
                                年龄：<strong><?=$model->user?$model->user->u_age:'不详'?> 岁</strong><br>
                                申请时间：<strong><?=$model->created_time?></strong><br>
                            </address>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        <!--车辆信息-->
        <div class="col-sm-4">
            <div class="ibox">
                <div class="ibox-title"><h5>车辆信息</h5></div>
                <div class="ibox-content">
                    <?=DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'attribute' => 'o_usercar',
                                'value' => \admin\models\CarSearch::getUserCar($model->o_usercar)
                            ],
                            [
                                'attribute' => 'o_replacecar',
                                'value' => \admin\models\CarSearch::getUserCar($model->o_replacecar)
                            ]
                        ]
                    ]);
                    ?>
                    <?= DetailView::widget([
                        'model' => $model->carreplace,
                        'attributes' => [
                            [
                                'label' => '上牌时间',
                                'attribute' => 'r_cardtime',
                                'value' => $model->carreplace?$model->carreplace->r_cardtime:'数据异常'
                            ],
                            [
                                'label' => '上牌城市',
                                'attribute' => 'r_city',
                                'value' => $current_city
                            ],
                            [
                                'label' => '行驶公里',
                                'attribute' => 'r_miles',
                                'value' => $model->carreplace?$model->carreplace->r_miles:0
                            ],
                            [
                                'attribute' => 'r_price',
                                'value' => $model->carreplace?\common\helpers\StringHelper::change_numbertomillion($model->carreplace->r_price).'元':''
                            ],
                        ]
                    ])
                    ?>
                </div>
            </div>
        </div>
        <!--车辆行驶证照片 -->
        <div class="col-sm-2">
            <div class="ibox">
                <div class="ibox-title"><h5>行驶证</h5></div>
                <div class="ibox-content">
                    <div class="carousel slide" id="carousel1">
                        <div class="carousel-inner">
                            <div class="item active">
                                <img src="<?=$model->carreplace?(empty($model->carreplace->r_driving_pic1)?'无':($model->carreplace->r_driving_pic1)):'' ?>"
                                     class="img-responsive" width="200" height="auto">
                            </div>
                            <div class="item ">
                                <img src="<?=$model->carreplace?(empty($model->carreplace->r_driving_pic2)?'无':($model->carreplace->r_driving_pic2)):'' ?>"
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
        <div class="col-sm-2">
            <div class="ibox">
                <div class="ibox-title"><h5>里程表</h5></div>
                <div class="ibox-content">
                    <div class="carousel slide" id="carousel2">
                        <div class="carousel-inner">
                            <div class="item active">
                                <img src="<?=$model->carreplace?(empty($model->carreplace->r_mileage_pic1)?'无':$model->carreplace->r_mileage_pic1):''?>"
                                     class="img-responsive" width="200" height="auto">
                            </div>
                            <div class="item ">
                                <img src="<?=$model->carreplace?(empty($model->carreplace->r_mileage_pic2)?'无':$model->carreplace->r_mileage_pic2):''?>"
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
        <div class="col-sm-6">
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
        <div class="col-sm-6">
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

    </div>
</div>
