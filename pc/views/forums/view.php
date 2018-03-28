<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var admin\models\CForums $model
 */
\admin\assets\FootTableAsset::register($this); //评论区表格分页，参考WebSite/bootstrap/H+/user/view/后台模板/table_foo_table.html

$this->title = rawurldecode($model->f_title);
$this->params['breadcrumbs'][] = ['label' => 'Cforums', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$url = Yii::$app->params['base_url'] . Yii::$app->params['base_file'] . '/photo/logo/';
?>

<style>
    .m{margin: 15px auto!important;}
    .bg-replay {
        padding: 5px 10px;border:1px dashed #bbb;
    }
</style>

<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-coffee"></i> 帖子详情
                </div>
                <div class="panel-body">
                    <!--------------------------------------------------帖子右上角标记 start------------------------------------------------------->
                    <div class="pull-right">
                        <!--帖子板块名称-->
                        <?=\admin\models\CForumForum::GetFupName($model->f_fup)?>
                        <!--浏览量/回复数-->
                        <button class="btn btn-white btn-xs" type="button">
                            <i class="fa fa-eye"></i> <?=$model->f_views ?>  /
                            <i class="fa fa-comment-o"></i> <?=$model->f_replies?>
                        </button>
                    </div>
                    <!--------------------------------------------------帖子右上角标记 end--------------------------------------------------------->

                    <!------------------------------------------------------帖子内容 start-------------------------------------------------------->
                    <div class="text-center article-title">
                        <h1>
                            <!--置顶标志-->
                            <?=$model->f_is_top==1 ? '<button class="btn btn-outline btn-danger  dim " type="button">置顶</button>':''?>
                            <!--帖子标题-->
                            <?=rawurldecode($model->f_title)?>
                        </h1>
                    </div>

                    <?php
                    $str = "";
                    $arr = unserialize(stripslashes($model->f_content));  //stripslashes用于清理从数据库(或者从 HTML 表单)取回的数据中添加的反斜杠\。
                    foreach ($arr as $value) {
                        if ($value->type == '1') {
                            $str .= "<br/><div>" . rawurldecode($value->content) . "</div><br/>";
                        } elseif ($value->type == '2') {
                            $str .= "<div class='show-img' style='text-align: center;position: relative;'>";
                            $str .= "<img src='" . $value->content . "' width='400px' height='auto' >";

                            if($value->content || $value->mark) {
                                $str .="<div class='pic-t' style='position: absolute;z-index: 2;left: 0;top: 0;'>".rawurldecode($value->mark)."</div>";
                            }
                            $str.="</div>";
                        }
                    }
                    echo $str;
                    ?>
                    <!-----------------------------------------------------帖子内容 end----------------------------------------------------------->
                    <!--分割线-->
                    <hr>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-leaf"></i> 评论列表
                </div>
                <div class="panel-body">
                    <!-----------------------------------------------------评论区(每页显示5条) start----------------------------------------------------------->
                    <div class="row">
                        <div class="col-lg-12">
                            <h2>评论：</h2>
                            <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="5">
                                <thead></thead>
                                <tbody>
                                <?php
                                if(count($allreplies)==0) {
                                    echo '<tr class="social-feed-box">暂无评论...</tr>';
                                }else {
                                    foreach ($allreplies as $key => $val) {
                                        $user = \v1\models\CUser::find()->where(['u_id' => $val->fr_user_id])->one();
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="social-feed-box">
                                                    <!--评论者信息-->
                                                    <div class="social-avatar">
                                                        <!--评论者头像-->
                                                        <a href="" class="pull-left">
                                                            <img alt="头像" class="img-circle"
                                                                 src="<?= empty($user) || empty($user->u_headImg) ? ($url . 'user_default.jpg') : $user->u_headImg ?>">
                                                        </a>
                                                        <!--评论者昵称，评论时间，评论所占楼层数-->
                                                        <div class="media-body">
                                                            <a href="#">
                                                                <?= empty($user) ? '' : (empty($user->u_nickname) ? substr_replace($user->u_phone, '****', 3, 4) : $user->u_nickname) ?>
                                                            </a>
                                                            <span class="pull-right"><label class="text-success"><?= $val->fr_position ?></label>#</span>
                                                            <small class="text-muted"><?= $val->created_time ?></small>
                                                        </div>
                                                    </div>
                                                    <!--评论内容-->
                                                    <div class="social-body">
                                                        <p>
                                                            <?= rawurldecode($val->fr_content); ?>
                                                        </p>
                                                        <?php
                                                        if ($val->fr_replay_id) {  //判断是否有二级回复
                                                            //获取上级回复者的信息
                                                            $lastreplay = \admin\models\CForumReplies::GetReplayContent($val->fr_replay_id);
                                                            $str = "";
                                                            $str .= "<div class='bg-replay'>";
                                                            $str .= "<p class='replay-p'>";
                                                            $str .= "<label><a href='javascript:;'>" . $lastreplay->user->u_nickname . "</a></label>";
                                                            $str .= "<label class='pull-right'><label class='text-success'>" . $lastreplay->fr_position . "</label>#</label>";
                                                            $str .= "</p>";
                                                            $str .= "<p class='replay-p'>" . rawurldecode($lastreplay->fr_content) . "</p>";
                                                            $str .= "</div>";

                                                            echo $str;
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                </tbody>
                                <!--分页-->
                                <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <ul class="pagination pull-right"></ul>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!-----------------------------------------------------评论区 end----------------------------------------------------------->

                </div>
            </div>
        </div>
    </div>
</div>