<?php

namespace v1\models;

use Yii;

/**
 * This is the model class for table "c_carreplace".
 *
 * @property string $r_id
 * @property integer $r_accept_id
 * @property integer $r_role
 * @property string $r_brand
 * @property string $r_car_id
 * @property string $r_volume_id
 * @property string $r_cardtime
 * @property string $r_city
 * @property string $r_driving_pic1
 * @property string $r_driving_pic2
 * @property string $r_mileage_pic1
 * @property string $r_mileage_pic2
 * @property string $r_miles
 * @property integer $r_price
 * @property integer $r_state
 * @property integer $r_views
 * @property integer $r_persons
 * @property string $created_time
 * @property integer $is_del
 */
class CCarreplace1 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_carreplace';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['r_accept_id', 'r_brand', 'r_car_id', 'r_volume_id', 'r_cardtime', 'r_city', 'r_driving_pic1', 'r_driving_pic2', 'r_mileage_pic1', 'r_mileage_pic2'], 'required'],
            [['r_accept_id', 'r_role', 'r_state', 'r_views', 'r_persons', 'is_del'], 'integer'],
            [['r_cardtime', 'created_time'], 'safe'],
            [['r_brand', 'r_miles'], 'string', 'max' => 100],
            [['r_car_id', 'r_volume_id', 'r_city', 'r_driving_pic1', 'r_driving_pic2', 'r_mileage_pic1', 'r_mileage_pic2'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'r_id' => 'R ID',
            'r_accept_id' => 'R Accept ID',
            'r_role' => 'R Role',
            'r_brand' => 'R Brand',
            'r_car_id' => 'R Car ID',
            'r_volume_id' => 'R Volume ID',
            'r_cardtime' => 'R Cardtime',
            'r_city' => 'R City',
            'r_driving_pic1' => 'R Driving Pic1',
            'r_driving_pic2' => 'R Driving Pic2',
            'r_mileage_pic1' => 'R Mileage Pic1',
            'r_mileage_pic2' => 'R Mileage Pic2',
            'r_miles' => 'R Miles',
            'r_price' => 'R Price',
            'r_state' => 'R State',
            'r_views' => 'R Views',
            'r_persons' => 'R Persons',
            'created_time' => 'Created Time',
            'is_del' => 'Is Del',
        ];
    }
}
