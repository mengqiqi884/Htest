<?php

use yii\db\Migration;

/**
 * Handles the creation for table `mer_login`.
 */
class m160510_063527_create_mer_login extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }
        $this->createTable('mer_login', [
            'login_id' => $this->primaryKey()->notNull()->unsigned()->comment('商户端登录表主键id'),
            'account'=>$this->string(15)->notNull()->defaultValue('')->comment('账号'),
            'password'=>$this->char(32)->notNull()->defaultValue('')->comment('密码'),
            'type'=>$this->boolean()->notNull()->defaultValue(0)->comment('商户类型 0 游客 1 商户 2 员工'),
            'logo'=>$this->string(500)->notNull()->defaultValue('')->comment('头像'),
            'email'=>$this->string(30)->notNull()->defaultValue('')->comment('邮件'),
            'token'=>$this->string(32)->notNull()->defaultValue('')->comment('用户令牌'),
            'registerid'=>$this->string(32)->notNull()->defaultValue('')->comment('推送registerid'),
            'created_at'=>$this->integer(11)->notNull()->defaultValue(0)->comment('创建时间'),
            'updated_at'=>$this->integer(11)->notNull()->defaultValue(0)->comment('更新时间'),
            'is_del'=>$this->boolean()->notNull()->defaultValue(0)->comment('是否删除 1 删除 0 未删除')
        ],$tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('mer_login');
    }
}
