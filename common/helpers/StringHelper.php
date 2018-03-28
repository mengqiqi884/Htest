<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\helpers;

use yii\helpers\BaseArrayHelper;
use yii\helpers\BaseStringHelper;

/**
 * ArrayHelper provides additional array functionality that you can use in your
 * application.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class StringHelper extends BaseStringHelper
{
    /**
     * md5加密
     * @param $str
     * @return string
     */
    static public function MyMd5($str)
    {
        return strtolower(md5($str));
    }

    static public function CapitalMd5($str)
    {
        return strtoupper(md5($str));
    }

    static public function tonow(&$v, $k)
    {
        if ($v === null) {
            $v = '';
        }
        if (gettype($v) == 'integer') {
            $v = (string)$v;
        }
        if ($k == 'working_time') {
            $time = date('Y-m-d H:i:s') - strtotime($v);
            $v = self::DiffDate(date, $v);
        }
    }

    function DiffDate($date1, $date2)
    {
        $time1 = strtotime($date1);
        $time2 = strtotime($date2);
        $year = 365 * 24 * 3600;
        if ($time1 > $time2) {
            $plan_times = $time1 - $time2;
            $plan_years = (int)($plan_times / $year);
        } else {
            $plan_years = 0;
        }

        return $plan_years;
    }

    static function checktime($created_time)
    {
        $gotime = time() - $created_time;
        switch ($gotime) {
            case $gotime < 60 :
                return round($gotime) . '秒前';
                break;
            case $gotime < 3600 :
                $result = $gotime / 60;

                return round($result) . '分钟前';
                break;
            case $gotime < 86400 :
                $result = $gotime / 3600;

                return round($result) . '小时前';
                break;
            case $gotime < 2592000 :
                $result = $gotime / 86400;

                return round($result) . '天前';
                break;
            case $gotime < 31104000 :
                $result = $gotime / 2592000;

                return round($result) . '月前';
                break;
            case $gotime > 31104000 :
                $result = $gotime / 31404000;

                return round($result) . '年前';
        }
    }

    static public function explodeLocations($str)
    {
        $arr = explode('|', trim($str, '|'));
        array_walk($arr, function(&$val, $key){
            $val = explode(',', $val);
        });

        return $arr;
    }

    /*过万，用万为单位*/
    static public function change_numbertomillion($num)
    {
        $str = '';
        if ($num >= 10000) {
            $str .= sprintf("%.2f", $num / 10000) . '万';
        } else {
            $str .= $num;
        }

        return $str;
    }

    static public function gmt_iso8601($time)
    {
        $dtStr = date("c", $time);
        $mydatetime = new \DateTime($dtStr);
        $expiration = $mydatetime->format(\DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);

        return $expiration . "Z";
    }

        /**
         * 生成GUID（UUID）
         * @access public
         * @return string
         * @author knight
         */
    static public function createGuid()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));
            // $hyphen = chr(45);// "-"
            $hyphen ='';
            $uuid = substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12);
            return $uuid;
        }
    }
}