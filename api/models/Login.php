<?php

namespace api\models;

use Yii;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "login".
 *
 * @property string $login_id
 * @property integer $user_id
 * @property string $account
 * @property string $token
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class Login extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'login';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['account'], 'string', 'max' => 11],
            [['token'], 'string', 'max' => 50]
        ];
    }

    public function behaviors(){
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'login_id' => 'Login ID',
            'user_id' => 'User ID',
            'account' => 'Account',
            'token' => 'Token',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_del' => 'Is Del',
        ];
    }

    public function fields()
    {
        $fields = parent::fields();
        // 去掉一些包含敏感信息的字段
        unset( $fields['cau_pwd'],$fields['cau_id'], $fields['created_at'],$fields['updated_at'],$fields['is_del'],$fields['cau_openid']);

        return $fields;
    }

    /**
     * 根据用户名密码验证用户
     */
    public static function findByUser($username){
//        $password = md5($password);
        return static::findOne(['account'=>$username,'is_del'=>0]);
    }

    public function  getUserInfo(){
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }

    /**
     * 根据给到的ID查询身份。
     *
     * @param string|integer $id 被查询的ID
     * @return IdentityInterface|null 通过ID匹配到的身份对象
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * 根据 token 查询身份。
     *
     * @param string $token 被查询的 token
     * @return IdentityInterface|null 通过 token 得到的身份对象
     */
    public static function findIdentityByAccessToken($token,$type = null)
    {
        return static::findOne(['token' => $token,'is_del'=>0]);
    }

    /**
     * @return int|string 当前用户ID
     */
    public function getId()
    {
        return $this->user_id;
    }

    /**
     * @return string 当前用户的（cookie）认证密钥
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

//    public static function GetMessage($token,$caption,$act_id){
//        if(empty($token)){
//            $message = '读取用户信息出错';
//            return $message;
//        }
//        $user = UserLogin::findIdentityByAccessToken($token);
////        var_dump($user);
////        exit;
//        if(empty($user)){
//            $message = '读取用户信息出错';
//            return $message;
//        }
//        else{
//            $user_id = $user->ci_id;
//            $proinfo = Promotion::find()->where(['and','pi_id='.$act_id,'pi_type=27','pi_sdate<'.time(),'pi_edate>'.time()])->one();
//            if(empty($proinfo)){
//                $message = '活动已过期';
//                return $message;
//            }else{
//                $transaction = Yii::$app->db->beginTransaction();
//                try{
//                    $regs = RegInfo::find()->where(['and','ci_id='.$user_id,'pi_id='.$act_id,'reg_title='."'".$caption."'"])->count();
//                    $tickets = Ticket::find()->joinWith('ticketList')->where(['and','ticket_list.gi_id=0'])->asArray()->all();
//                    if(!empty($tickets)){
//                        $str = [];
//                        foreach($tickets as $ticket){
//                            $str[]=$ticket['ticketList']['tk_id'];
//                        }
//                        $ticketLists = TicketList::find()->where(['and','tk_id not in ('.implode(',',$str).')','gi_id=0'])->asArray()->all();
//                    }else{
//                        $ticketLists = TicketList::find()->where(['and','gi_id=0'])->asArray()->all();
//                    }
//                    $key = array_rand($ticketLists);
//                    $tk_id = $ticketLists[$key]['tk_id'];
//                    if($regs >= $proinfo->pi_time){
//                        $message = '已超过领取限制次数';
//                        return $message;
//                    }
//                    $eticket = new Ticket();
//                    $regInfo = new RegInfo();
//                    $eticket->attributes = [
//                        'ci_id' => $user_id,
//                        'tk_id' => $tk_id,
//                        'pi_id' => $act_id,
//                        'cel_edate' => 0,
//                        'cel_status' => 1,
//                    ];
//                    if(!$eticket->save()){
//                        throw new \Exception;
//                    }
//                    $regInfo->attributes = [
//                        'ci_id' => $user_id,
//                        'pi_id' => $act_id,
//                        'reg_title' => $caption,
//                        'reg_date' => time(),
//                        'reg_status' => 1,
//                    ];
//                    if(!$regInfo->save()){
//                        throw new \Exception;
//                    }
//                    $couponInout = new TicketInout();
//                    $couponInout->attributes = [
//                        'tir_date' => time(),
//                        'ci_id' => $user_id,
//                        'tir_sno' => $tk_id,
//                        'tir_eno' => $tk_id,
//                        'tir_amount' => 1,
//                        'tir_status' => 1,
//                        'ot_typecode' => 'H10',
//                        'operation_id' => 176,
//                    ];
//                    if(!$couponInout->save()){
//                        throw new Exception;
//                    }
//                    $transaction->commit();
//                    $message = '领取成功';
//                    return $message;
//                }catch (Exception $e){
//                    var_dump($e);
//                    $transaction->rollBack();
//                    $message = '领取失败,检查网络';
//                    return $message;
//                }
//            }
//        }
//    }

}
