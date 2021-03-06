<?php

namespace admin\models;

use Yii;
use v1\models\CUser;
/**
 * This is the model class for table "c_forum_replies".
 *
 * @property string $fr_id
 * @property integer $fr_forum_id
 * @property integer $fr_user_id
 * @property integer $fr_replay_id
 * @property string $fr_content
 * @property string $fr_userip
 * @property string $fr_position
 * @property string $created_time
 * @property integer $is_del
 */
class CForumReplies extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_forum_replies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fr_forum_id', 'fr_user_id'], 'required'],
            [['fr_forum_id', 'fr_user_id', 'fr_replay_id', 'fr_position', 'is_del'], 'integer'],
            [['fr_content'], 'string'],
            [['created_time'], 'safe'],
            [['fr_author'], 'string', 'max' => 200],
            [['fr_userip'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fr_id' => 'Fr ID',
            'fr_forum_id' => 'Fr Forum ID',
            'fr_user_id' => 'Fr User ID',
            'fr_replay_id' => 'Fr Replay ID',
            'fr_content' => 'Fr Content',
            'fr_userip' => 'Fr Userip',
            'fr_position' => 'Fr Position',
            'created_time' => 'Created Time',
            'is_del' => 'Is Del',
        ];
    }

    public function getuser()
    {
        return $this->hasOne(CUser::className(), ['u_id'=>'fr_user_id'])->onCondition(['c_user.is_del'=>0]);
    }

    /*根据fr_replay_id查找回复的楼层的作者、楼层数、内容*/
    public static function GetReplayContent($replay_id){
        $model=CForumReplies::find()->where(['fr_id'=>$replay_id])->one();
        return $model;
    }
}
