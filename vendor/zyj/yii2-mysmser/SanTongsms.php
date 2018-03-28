<?php
/**
 * Created by PhpStorm.
 * User: BF
 * Date: 2016/12/13
 * Time: 15:48
 */

namespace zyj\smser;

use \Yii;
class SanTongsms
{
    public 	function sendSms($mobile,$content)
    {
        $url = 'http://wt.3tong.net/json/sms/Submit';
        $data = [
            "account"=>"dh21551",
            "password"=>md5('pcmtong.2014'),
            "msgid"=>Yii::$app->security->generateRandomString(),
            "phones"=>$mobile,
            "content"=>$content,
            "sign"=>"【PnPark车位分享】",  //最多35个汉字
            "subcode"=>"13462",
            "sendtime"=>date('YmdHi')

        ];
        $res=json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $res);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result,true);
    }

}