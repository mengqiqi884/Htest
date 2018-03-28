<?php

namespace admin\models;

use common\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "c_forum_forum".
 *
 * @property string $ff_id
 * @property string $ff_name
 * @property string $ff_logo
 * @property integer $is_del
 */
class CForumForum extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_forum_forum';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ff_name', 'ff_logo'], 'required'],
            [['is_del'], 'integer'],
            [['ff_name'], 'string', 'max' => 100],
            [['ff_logo'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ff_id' => 'Ff ID',
            'ff_name' => 'Ff Name',
            'ff_logo' => 'Ff Logo',
            'is_del' => 'Is Del',
        ];
    }

    /*获取所有板块*/
    public static function GetForumsName(){
        $arr=array();
        $model=CForumForum::find()->where(['is_del'=>0])->asArray()->all();
        if($model){
            $arr = ArrayHelper::map($model,'ff_id','ff_name');
//            foreach($model as $k =>$v){
//                $arr[$v->ff_id]=$v->ff_name;
//            }
        }
        return $arr;
    }

    /*根据板块id获取板块名称*/
    public static function GetFupName($fup){
        $html = '';
        switch($fup%3){
            case 0:
                $html = "btn btn-xs btn-success";
                break;
            case 1:
                $html = "btn btn-xs btn-warning";
                break;
            case 2:
                $html = "btn btn-xs btn-info";
                break;
            default:
                $html = "btn btn-xs btn-danger";
                break;
        }
        $model=CForumForum::find()->where(['ff_id'=>$fup,'is_del'=>0])->one();
        if($model){
            return '<span class="' . $html . '">' . $model->ff_name . '</span>';
        }else{
            return '<span class="' . $html . '">板块异常</span>';
        }

    }
}
