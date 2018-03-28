<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var admin\models\CProducts $model
 */
\admin\assets\ImgAsset::register($this);
\admin\assets\PrintAsset::register($this);   //打印

$this->title = $model->p_name;
$this->params['breadcrumbs'][] = ['label' => 'Cproducts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$url = Yii::$app->params['base_url'].Yii::$app->params['base_file'];
$img_arr = explode(',',str_replace(']','',str_replace('[','',rtrim($model->p_content, ","))));
$imgs = '';
foreach($img_arr as $item){
    $imgs .= '<a href="' . $url . $item . '" class="fancybox" title="申请流程图">';
    $imgs .=  '<img src="' . $url . $item .'" width="50" height="50">';
    $imgs .= '</a>';
}
?>
<?=Html::cssFile('@web/css/car/style.css')?>


<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-4">
            <div class="ibox float-e-margins">
                <?=DetailView::widget([
                    'model' => $model,
                    'condensed' => false,
                    'hover' => true,
                    'panel' => [
                        'heading'=>$this->title,
                        'type' => DetailView::TYPE_INFO
                    ],
                    'attributes' => [
                        'p_name',
                        [
                            'attribute' => 'p_month12',
                            'label' => '12期',
                            'format' => 'raw',
                            'value' => $model->p_month12 . '%'
                        ],
                        [
                            'attribute' => 'p_month24',
                            'label' => '24期',
                            'format' => 'raw',
                            'value' => $model->p_month24 . '%'
                        ],
                        [
                            'attribute' => 'p_month36',
                            'label' => '36期',
                            'format' => 'raw',
                            'value' => $model->p_month36 . '%'
                        ],
                        [
                            'attribute' => 'p_content',
                            'format' => 'raw',
                            'value' =>$imgs
                        ],
                        'p_sortorder'
                    ],
                    'enableEditMode' => false,
                ]);?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="ibox float-e-margins">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            基本报表测试
                        </h3>
                    </div>
                    <div class="panel-body">
                        <button class="btn btn-outline btn-warning pull-left" onclick="PrintPage()"><i class="fa fa-print"></i> 打印</button>
                        <div class="col-sm-12" id="printThis">
                            <div class="ibox-content p-xl">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <address>
                                            <strong>北京百度在线网络技术有限公司</strong><br>
                                            北京市海淀区上地十街10号<br>
                                            <abbr title="Phone">总机：</abbr> (+86 10) 5992 8888
                                        </address>
                                    </div>

                                    <div class="col-sm-6 text-right">
                                        <h4>单据编号：</h4>
                                        <h4 class="text-navy">H+-000567F7-00</h4>
                                        <address>
                                            <strong>阿里巴巴集团</strong><br>
                                            中国杭州市华星路99号东部软件园创业大厦6层(310099)<br>
                                            <abbr title="Phone">总机：</abbr> (86) 571-8502-2088
                                        </address>
                                        <p>
                                            <span><strong>日期：</strong> 2014-11-11</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="table-responsive m-t">
                                    <table class="table invoice-table">
                                        <thead>
                                        <tr>
                                            <th>清单</th>
                                            <th>数量</th>
                                            <th>单价</th>
                                            <th>税率</th>
                                            <th>总价</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <div><strong>尚都比拉2013冬装新款女装 韩版修身呢子大衣 秋冬气质羊毛呢外套</strong>
                                                </div>
                                            </td>
                                            <td>1</td>
                                            <td>&yen;26.00</td>
                                            <td>&yen;1.20</td>
                                            <td>&yen;31,98</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div><strong>11*11夏娜 新款斗篷毛呢外套 女秋冬呢子大衣 韩版大码宽松呢大衣</strong>
                                                </div>
                                                <small>双十一特价
                                                </small>
                                            </td>
                                            <td>2</td>
                                            <td>&yen;80.00</td>
                                            <td>&yen;1.20</td>
                                            <td>&yen;196.80</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div><strong>2013秋装 新款女装韩版学生秋冬加厚加绒保暖开衫卫衣 百搭女外套</strong>
                                                </div>
                                            </td>
                                            <td>3</td>
                                            <td>&yen;420.00</td>
                                            <td>&yen;1.20</td>
                                            <td>&yen;1033.20</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <!-- /table-responsive -->

                                <table class="table invoice-total">
                                    <tbody>
                                    <tr>
                                        <td><strong>总价：</strong>
                                        </td>
                                        <td>&yen;1026.00</td>
                                    </tr>
                                    <tr>
                                        <td><strong>税：</strong>
                                        </td>
                                        <td>&yen;235.98</td>
                                    </tr>
                                    <tr>
                                        <td><strong>总计</strong>
                                        </td>
                                        <td>&yen;1261.98</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="text-right">
                                    <button class="btn btn-primary"><i class="fa fa-dollar"></i> 去付款</button>
                                </div>

                                <div class="well m-t"><strong>注意：</strong> 请在30日内完成付款，否则订单会自动取消。
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
