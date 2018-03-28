<?php
/**
 * Created by PhpStorm.
 * User: BF
 * Date: 2016/7/18
 * Time: 11:37
 */

namespace api\helpers;
/**
 * 将重要信息写入log文件中的类
 */
class Log{
    // 打印log
    static function log_result($word)
    {
        $fp = fopen("dblog.log","a");
        flock($fp, LOCK_EX) ;
       // fwrite($fp,"********************************执行日期：".strftime("%Y-%m-%d-%H：%M：%S",time())."*************************\r\n");
        fwrite($fp,"添加订单查找推荐商户的mysql执行时间：".$word."\r\n");
      //  fwrite($fp,"*************************************************************************************************************\r\r\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    static function log_result2($word)
    {
        $fp = fopen("dblog.log","a");
        flock($fp, LOCK_EX) ;
        // fwrite($fp,"********************************执行日期：".strftime("%Y-%m-%d-%H：%M：%S",time())."*************************\r\n");
        fwrite($fp,"添加胞外需求单查找推荐师傅的mysql执行时间：".$word."\r\n");
        //  fwrite($fp,"*************************************************************************************************************\r\r\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}