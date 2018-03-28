<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/31
 * Time: 15:31
 */
namespace v1\controllers;

use Yii;
use yii\base\Exception;
use yii\base\ErrorException;
use v1\models\CForums;
use v1\models\CForumForum;
use v1\models\CForumRule;
use v1\models\CForumReplies;
use v1\models\CUser;
use v1\models\CScoreList;
use v1\models\CSensitive;

class ForumController extends ApiController
{
    const PAGE_SIZE = 10;

    /**
     * 首页获取帖子列表
     * @return array
     */
    public function actionGetHomeForumList()
    {
        $forum_temp_list = CForumForum::find()->select('ff_id,ff_name')->where(['is_del' => 0])->orderBy('ff_id asc')->asArray()->all();
        $data = array();
        foreach ($forum_temp_list as $forum_temp_item) {
            $forum_list = CForums::find()
                ->select('f_id,f_fup,c_forum_forum.ff_name,f_pic,f_title')
                ->leftJoin('c_forum_forum', 'c_forum_forum.ff_id=c_forums.f_fup')
                ->where(['c_forums.is_del' => 0, 'c_forum_forum.is_del' => 0, 'f_state' => -1])
                ->andWhere(['f_fup' => $forum_temp_item['ff_id'], 'f_is_top' => 1])
                ->orderBy('c_forums.created_time desc')
                ->asArray()->all();
            $forum_temp_item['forum_list'] = $forum_list;
            $data[] = $forum_temp_item;
        }

        if (!empty($data)) {
            return $this->showResArr(200, '获取成功', $data);
        } else {
            return $this->showResArr(400, '获取失败');
        }
    }

    /**
     * 获取帖子列表
     * @return array
     */
    public function actionGetForumList()
    {
        $page_index = Yii::$app->request->post('page_index', 1);
        $type = Yii::$app->request->post('type', 1);
        if (empty($type)) {
            $type = 1;
        }

        $forum_temp = CForumForum::find()->where(['is_del' => 0, 'ff_id' => $type])->one();
        if (empty($forum_temp)) {
            return $this->showResult(400, '获取失败');
        }

        $forum_list = CForums::find()
            ->select('f_id,f_fup,c_forum_forum.ff_name,f_pic,f_title,f_replies,f_is_top')
            ->leftJoin('c_forum_forum', 'c_forum_forum.ff_id=c_forums.f_fup')
            ->where(['c_forums.is_del' => 0, 'c_forum_forum.is_del' => 0, 'f_state' => -1])
            ->andWhere(['f_fup' => $forum_temp->ff_id])
            ->orderBy('f_is_top desc,c_forums.created_time desc')
            ->limit(static::PAGE_SIZE)->offset(($page_index - 1) * static::PAGE_SIZE)
            ->asArray()->all();

        return $this->showResArr(200, '获取成功', $forum_list);
    }

    /**
     * 获取我的帖子列表
     * @return array
     */
    public function actionGetMyForumList()
    {
        $page_index = Yii::$app->request->post('page_index', 1);
        $user_id = Yii::$app->user->identity->getId();

        $forum_list = CForums::find()
            ->select('f_id,f_fup,c_forum_forum.ff_name,f_pic,f_title,f_content,f_state,c_forums.created_time,f_is_top')
            ->leftJoin('c_forum_forum', 'c_forum_forum.ff_id=c_forums.f_fup')
            ->where(['c_forums.is_del' => 0, 'c_forum_forum.is_del' => 0])
            ->andWhere(['f_user_id' => $user_id])
            ->orderBy('f_is_top desc,c_forums.created_time desc')
            ->limit(static::PAGE_SIZE)->offset(($page_index - 1) * static::PAGE_SIZE)
            ->asArray()->all();

        $data = array();
        foreach($forum_list as $forum_item) {
            try {
                $forum_item['f_content'] = unserialize($forum_item['f_content']);
            } catch (ErrorException $e) {
                return $this->showResult(400, '异常数据，获取失败');
            }
            $data[] = $forum_item;
        }

        return $this->showResArr(200, '获取成功', $data);
    }

    /**
     * 获取帖子详情
     * @return array
     */
    public function actionGetForumItem()
    {
        $f_id = Yii::$app->request->post('f_id');
        if (empty($f_id)) {
            return $this->showResult(400, '获取失败');
        }

        $f = CForums::find()
            ->select('c_forums.*,c_forum_forum.ff_name')
            ->leftJoin('c_forum_forum', 'c_forum_forum.ff_id=c_forums.f_fup')
            ->where(['c_forums.is_del' => 0, 'c_forum_forum.is_del' => 0])
            ->andWhere(['f_id' => $f_id])
            ->one();
        if (!empty($f)) {
            $f->f_views += 1;
            $f->updated_time = date('Y-m-d H:i:s');
            if (!$f->save()) {
                return $this->showResult(400, '获取失败，请重试');
            }
        } else {
            return $this->showResult(400, '获取失败，请重试');
        }

        $forum = CForums::find()
            ->select('c_forums.*,c_forum_forum.ff_name,c_user.u_phone,c_user.u_headImg')
            ->leftJoin('c_forum_forum', 'c_forum_forum.ff_id=c_forums.f_fup')
            ->leftJOin('c_user', 'c_user.u_id=c_forums.f_user_id')
            ->where(['c_forums.is_del' => 0, 'c_forum_forum.is_del' => 0])
            ->andWhere(['f_id' => $f_id])
            ->asArray()->one();

        if (!empty($forum)) {
            try {
                $forum['f_content'] = unserialize($forum['f_content']);
            } catch (ErrorException $e) {
                return $this->showResult(400, '异常数据，获取失败');
            }

            return $this->showResult(200, '获取成功', $forum);
        } else {
            return $this->showResult(400, '获取失败');
        }
    }

    /**
     * 发布帖子
     * @return array
     */
    public function actionPublishForum()
    {
        $fup = Yii::$app->request->post('fup', 1);//模块id
        $title = Yii::$app->request->post('title');
        $pic = Yii::$app->request->post('pic');
        $content = Yii::$app->request->post('content');
        $car_cycle = Yii::$app->request->post('car_cycle');
        $car_miles = Yii::$app->request->post('car_miles');
        $car_describle = Yii::$app->request->post('car_describle');
        $user_id = Yii::$app->user->identity->getId();
        $nick_name = Yii::$app->user->identity->u_nickname;
        $nick_name = empty($nick_name) ? Yii::$app->user->identity->u_phone : $nick_name;
        if (empty($title) || empty($content)) {
            return $this->showResult(300, '缺少参数');
        }
        if (($fup == 1 || $fup == 2) && (empty($car_cycle) || empty($car_miles) || empty($car_describle))) {
            return $this->showResult(300, '缺少参数');
        }

        $content_arr = json_decode($content);
        if (empty($content_arr) || !is_array($content_arr)) {
            return $this->showResult(300, '参数错误');
        }
        $content_ser = serialize($content_arr);
        //检测敏感词汇
        $sensitive_arr = array();
        $sensitive = CSensitive::find()->where(['is_del' => 0])->asArray()->all();
        foreach ($sensitive as $item) {
            $sensitive_arr[] = $item['s_name'];
        }
        $blacklist = "/" . implode("|", $sensitive_arr) . "/i";
        if (preg_match($blacklist, rawurldecode($title), $matches) || preg_match($blacklist, rawurldecode($content_ser), $matches) || preg_match($blacklist, rawurldecode($car_cycle), $matches) || preg_match($blacklist, rawurldecode($car_miles), $matches) || preg_match($blacklist, rawurldecode($car_describle), $matches)) {
            return $this->showResult(300, '请勿使用敏感词汇');
        }

        $forum = new CForums;
        $forum->f_fup = $fup;
        $forum->f_user_id = $user_id;
        $forum->f_user_nickname = $nick_name;
        $forum->f_pic = empty($pic) ? '' : $pic;
        $forum->f_title = $title;
        $forum->f_content = $content_ser;
        $forum->f_views = 0;
        $forum->f_replies = 0;
        $forum->f_is_top = 0;
        $forum->f_is_first_top = 0;
        $forum->f_state = -1;
        $forum->f_car_cycle = empty($car_cycle) ? '' : $car_cycle;
        $forum->f_car_miles = empty($car_miles) ? '' : $car_miles;
        $forum->f_car_describle = empty($car_describle) ? '' : $car_describle;
        $forum->created_time = date('Y-m-d H:i:s');
        $forum->updated_time = date('Y-m-d H:i:s');
        $forum->is_del = 0;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            //发帖
            if (!$forum->save()) {
                throw new Exception;
            }

            $user = CUser::findOne($user_id);
            if (empty($user)) {
                throw new Exception;
            }
            //修改个人信息里的发帖数
            $user->u_forums += 1;
            if (!$user->save()) {
                throw new Exception;
            }

            //积分
            $rule = CForumRule::find()
                ->select('c_forum_rule.*,c_forum_forum.ff_name')
                ->leftJoin('c_forum_forum', 'c_forum_forum.ff_id=c_forum_rule.fr_fup')
                ->where(['fr_fup' => $fup, 'fr_item' => '发帖', 'c_forum_rule.is_del' => 0])
                ->asArray()->one();
            if (!empty($rule)) {
                //修改个人信息里的积分
                $user->u_score += $rule['fr_score'];
                if (!$user->save()) {
                    throw new Exception;
                }

                //添加积分记录
                $score_list = new CScoreList;
                $score_list->sl_user_id = $user_id;
                $score_list->sl_rule = '【' . $rule['fr_item'] . '】' . $rule['ff_name'];
                $score_list->sl_score = $rule['fr_score'];
                $score_list->sl_act = 'add';
                $score_list->created_time = date('Y-m-d H:i:s');
                if (!$score_list->save()) {
                    throw new Exception;
                }
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            return $this->showResult(400, '失败');
        }

        return $this->showResult(200, '成功');
    }

    /**
     * 修改帖子
     * @return array
     */
    public function actionUpdateForum()
    {
        $f_id = Yii::$app->request->post('id');//帖子id
        //$fup = Yii::$app->request->post('fup', 1);//模块id
        $title = Yii::$app->request->post('title');
        $pic = Yii::$app->request->post('pic');
        $content = Yii::$app->request->post('content');
        $car_cycle = Yii::$app->request->post('car_cycle');
        $car_miles = Yii::$app->request->post('car_miles');
        $car_describle = Yii::$app->request->post('car_describle');
        $user_id = Yii::$app->user->identity->getId();
        $nick_name = Yii::$app->user->identity->u_nickname;
        $nick_name = empty($nick_name) ? Yii::$app->user->identity->u_phone : $nick_name;

        $forum = CForums::find()->where(['is_del' => 0, 'f_user_id' => $user_id, 'f_id' => $f_id])->one();
        if (empty($forum)) {
            return $this->showResult(400, '未找到该帖子，无法修改');
        }

        if (empty($f_id) || empty($title) || empty($content)) {
            return $this->showResult(300, '缺少参数');
        }
        if (($forum->f_fup == 1 || $forum->f_fup == 2) && (empty($car_cycle) || empty($car_miles) || empty($car_describle))) {
            return $this->showResult(300, '缺少参数');
        }

        $content_arr = json_decode($content);
        if (empty($content_arr) || !is_array($content_arr)) {
            return $this->showResult(300, '参数错误');
        }
        $content_ser = serialize($content_arr);
        //检测敏感词汇
        $sensitive_arr = array();
        $sensitive = CSensitive::find()->where(['is_del' => 0])->asArray()->all();
        foreach ($sensitive as $item) {
            $sensitive_arr[] = $item['s_name'];
        }
        $blacklist = "/" . implode("|", $sensitive_arr) . "/i";
        if (preg_match($blacklist, rawurldecode($title), $matches) || preg_match($blacklist, rawurldecode($content_ser), $matches) || preg_match($blacklist, rawurldecode($car_cycle), $matches) || preg_match($blacklist, rawurldecode($car_miles), $matches) || preg_match($blacklist, rawurldecode($car_describle), $matches)) {
            return $this->showResult(300, '请勿使用敏感词汇');
        }

        //$forum->f_fup = $fup;
        //$forum->f_user_id = $user_id;
        $forum->f_user_nickname = $nick_name;
        $forum->f_pic = empty($pic) ? '' : $pic;
        $forum->f_title = $title;
        $forum->f_content = $content_ser;
        //$forum->f_views = 0;
        //$forum->f_replies = 0;
        //$forum->f_is_top = 0;
        //$forum->f_is_first_top = 0;
        //$forum->f_state = -1;
        $forum->f_car_cycle = empty($car_cycle) ? '' : $car_cycle;
        $forum->f_car_miles = empty($car_miles) ? '' : $car_miles;
        $forum->f_car_describle = empty($car_describle) ? '' : $car_describle;
        //$forum->created_time = date('Y-m-d H:i:s');
        $forum->updated_time = date('Y-m-d H:i:s');
        //$forum->is_del = 0;

        if ($forum->save()) {
            return $this->showResult(200, '成功');
        } else {
            return $this->showResult(400, '失败');
        }
    }

    /**
     * 删除帖子
     * @return array
     */
    public function actionDelForum()
    {
        $f_id = Yii::$app->request->post('id');//帖子id
        $user_id = Yii::$app->user->identity->getId();
        if (empty($f_id)) {
            return $this->showResult(300, '缺少参数');
        }

        $forum = CForums::find()->where(['is_del' => 0, 'f_user_id' => $user_id, 'f_id' => $f_id])->one();
        if (empty($forum)) {
            return $this->showResult(400, '未找到该帖子');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $forum->updated_time = date('Y-m-d H:i:s');
            $forum->is_del = 1;
            if (!$forum->save()) {
                throw new Exception;
            }

            $user = CUser::findOne($user_id);
            if (empty($user)) {
                throw new Exception;
            }
            //修改个人信息里的发帖数
            $count =  $forum = CForums::find()->where(['is_del' => 0, 'f_user_id' => $user_id])->count();
            $user->u_forums = $count;
           // $user->u_forums = empty($user->u_forums) || $user->u_forums <= 0 ? 0 : $user->u_forums - 1;
            if (!$user->save()) {
                throw new Exception;
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            return $this->showResult(400, '失败');
        }

        return $this->showResult(200, '成功');
    }

    /**
     * 获取评论列表
     * @return array
     */
    public function actionGetReplyList()
    {
        $page_index = Yii::$app->request->post('page_index', 1);
        $f_id = Yii::$app->request->post('f_id');//帖子id

        $reply_list = CForumReplies::find()
            ->select('c_forum_replies.*')
            ->addSelect('ru.u_phone as fr_phone,ru.u_nickname as fr_user_nickname,ru.u_headImg as fr_headImg')
            ->addSelect('bfr.fr_user_id as re_user_id,bru.u_phone as re_phone,bru.u_nickname as re_user_nickname,bfr.fr_content as re_content,bfr.fr_position as re_position,bfr.created_time as re_created_time')
            ->leftJoin('c_user as ru', 'ru.u_id=c_forum_replies.fr_user_id')
            ->leftJoin('c_forum_replies as bfr', 'bfr.fr_id=c_forum_replies.fr_replay_id')
            ->leftJoin('c_user as bru', 'bru.u_id=bfr.fr_user_id')
            ->where(['c_forum_replies.fr_forum_id' => $f_id, 'c_forum_replies.is_del' => 0])
            ->orderBy('c_forum_replies.fr_position desc')
            ->limit(static::PAGE_SIZE)->offset(($page_index - 1) * static::PAGE_SIZE)
            ->asArray()->all();

        return $this->showResArr(200, '获取成功', $reply_list);
    }

    /**
     * 评论、回复
     * @return array
     */
    public function actionReply()
    {
        $f_id = Yii::$app->request->post('f_id');//帖子id
        $reply_id = Yii::$app->request->post('reply_id', 0);
        $content = Yii::$app->request->post('content');
        $user_id = Yii::$app->user->identity->getId();

        if (empty($f_id) || empty($content)) {
            return $this->showResult(300, '缺少参数');
        }

        $forum = CForums::find()->where(['is_del' => 0, 'f_id' => $f_id])->one();
        if (empty($forum)) {
            return $this->showResult(400, '未找到该帖子');
        }

        if (!empty($reply_id)) {
            $be_reply = CForumReplies::find()->where(['is_del' => 0, 'fr_forum_id' => $f_id, 'fr_id' => $reply_id])->one();
            if (empty($be_reply)) {
                return $this->showResult(400, '回复对象不存在');
            } else {
                if ($be_reply->fr_user_id == $user_id) {
                    return $this->showResult(400, '无法回复自己');
                }
            }
        }

        //检测敏感词汇
        $sensitive_arr = array();
        $sensitive = CSensitive::find()->where(['is_del' => 0])->asArray()->all();
        foreach ($sensitive as $item) {
            $sensitive_arr[] = $item['s_name'];
        }
        $blacklist = "/" . implode("|", $sensitive_arr) . "/i";
        if (preg_match($blacklist, rawurldecode($content), $matches)) {
            return $this->showResult(300, '请勿使用敏感词汇');
        }

        $last_reply = CForumReplies::find()->where(['fr_forum_id' => $f_id])->orderBy('fr_position desc')->limit(1)->one();
        $last_floor_num = empty($last_reply) || empty($last_reply->fr_position) ? 0 : $last_reply->fr_position;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $reply = new CForumReplies;
            $reply->fr_forum_id = $f_id;
            $reply->fr_user_id = $user_id;
            $reply->fr_replay_id = empty($reply_id) ? 0 : $reply_id;
            $reply->fr_content = $content;
            //$reply->fr_userip = ;
            $reply->fr_position = $last_floor_num + 1;
            $reply->created_time = date('Y-m-d H:i:s');
            $reply->is_del = 0;
            if (!$reply->save()) {
                throw new Exception;
            }

            $forum->updated_time = date('Y-m-d H:i:s');
            $forum->f_replies += 1;
            if (!$forum->save()) {
                throw new Exception;
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            return $this->showResult(400, '失败');
        }

        return $this->showResult(200, '成功');
    }
}

