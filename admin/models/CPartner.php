<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "c_partner".
 *
 * @property string $p_id
 * @property string $p_colorlogo
 * @property string $p_darklogo
 * @property integer $p_is_show
 * @property string $p_url
 * @property integer $is_del
 */
class CPartner extends \yii\db\ActiveRecord
{
    public $color_logo;
    public $dark_logo;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_partner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['p_is_show', 'is_del'], 'integer'],
            [['p_colorlogo','p_darklogo'],'required','message' => '请上传合作伙伴图片'],
            ['color_logo', 'file', 'extensions' => ['png', 'jpg', 'gif', 'jpeg'], 'maxSize' => 1024 * 1024 * 2],
            ['dark_logo','file', 'extensions' => ['png', 'jpg', 'gif', 'jpeg'], 'maxSize' => 1024 * 1024 * 2],
            [['p_colorlogo','p_darklogo'], 'string', 'max' => 200],
            [['p_url'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'p_id' => '序号',
            'p_colorlogo' => '图标',
            'p_is_show' => '是否显示',
            'p_url' => '链接',
            'is_del' => 'Is Del',
        ];
    }
}
