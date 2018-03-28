<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
\admin\assets\TreetableAsset::register($this);
\admin\assets\ImgAsset::register($this);
\admin\assets\WarningAsset::register($this);
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var admin\models\CarSearch $searchModel
 */
$this->title = '车型列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .datagrid-wrap{width: 1800px!important;}

    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
        border-top: 1px solid #e7eaec;line-height: 1.42857;padding: 8px;vertical-align: middle;}
    .table-bordered > thead > tr > td, .table-bordered > thead > tr > th { background-color: #f5f5f6; }

</style>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="icon pull-left"><i class="fa fa-automobile text-navy"></i>&nbsp;&nbsp;</span>
                    <h5><?=Html::encode($this->title);?></h5>
                </div>
                <div class="ibox-content car">
                    <div class="row">
                        <a id="create" class="btn btn-outline btn-primary dim" href="javascript:void(0);" onclick="import_data()" data-toggle="modal" data-target="#create-modal" data-flag="create">
                            <i class="glyphicon glyphicon-log-in"></i>
                            Excel导入
                        </a>
                    </div>
                    <div class="row">
                        <table id="tt" class="table"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
