<?php

use yii\db\Migration;

/**
 * Handles the creation for table `mer_merchant`.
 */
class m160510_072657_create_mer_merchant extends Migration
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
        $this->createTable('mer_merchant', [
            'mer_id' => $this->primaryKey()->notNull()->unsigned()->comment('商户资料id'),
            'login_id'=>$this->integer()->notNull()->defaultValue(0)->comment('登录表id'),
            'header_name'=>$this->string(20)->notNull()->defaultValue('')->comment('负责人名字'),
            'header_phone'=>$this->string(20)->notNull()->defaultValue('')->comment('负责人电话'),
            'header_identification'=>$this->string(500)->notNull()->defaultValue('')->comment('负责人身份证'),
            'business_pic'=>$this->string(500)->notNull()->defaultValue('')->comment('营业执照'),
            'address'=>$this->string(100)->notNull()->defaultValue('')->comment('地址'),
            'lat'=>$this->char(15)->notNull()->defaultValue('')->comment('维度'),
            'lng'=>$this->char(15)->notNull()->defaultValue('')->comment('经度'),
            'city'=>$this->integer()->notNull()->defaultValue(0)->comment('城市id'),
            'mer_name'=>$this->string(50)->notNull()->defaultValue('')->comment('商户名字'),
            'descript'=>$this->string(500)->notNull()->defaultValue('')->comment('商户描述'),
            'pics'=>$this->string(500)->notNull()->defaultValue('')->comment('商户图片'),
            'identification_code'=>$this->string(50)->notNull()->defaultValue('')->comment('唯一标识码'),
            'reason'=>$this->string(100)->notNull()->defaultValue('')->comment('拒绝理由'),
            'state'=>$this->boolean()->notNull()->defaultValue(0)->comment(' 0 未认证 1已认证 2 认证失败'),
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
        $this->dropTable('mer_merchant');
    }
}
