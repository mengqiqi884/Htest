<?php

use yii\db\Migration;

/**
 * Handles the creation for table `mer_staff`.
 */
class m160511_022520_create_mer_staff extends Migration
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
        $this->createTable('mer_staff', [
            'staff_id' => $this->primaryKey()->notNull()->unsigned()->comment('员工资料id'),
            'login_id'=>$this->integer()->notNull()->defaultValue(0)->comment('登录表id'),
            'mer_id'=>$this->integer()->notNull()->defaultValue(0)->comment('商户id'),
            'lat'=>$this->char(15)->notNull()->defaultValue('')->comment('维度'),
            'lng'=>$this->char(15)->notNull()->defaultValue('')->comment('经度'),
            'city'=>$this->integer()->notNull()->defaultValue(0)->comment('城市id'),
            'identification'=>$this->string(100)->defaultValue('')->comment('身份证'),
            'staff_name'=>$this->string(30)->defaultValue('')->comment('员工姓名'),
            'phone'=>$this->string(15)->defaultValue('')->comment('联系电话'),
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
        $this->dropTable('mer_staff');
    }
}
