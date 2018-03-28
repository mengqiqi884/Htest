<?php

use yii\db\Migration;

/**
 * Handles the creation for table `appliance111`.
 */
class m160511_080413_create_appliance extends Migration
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
        $this->createTable('appliance', [
            'appliance_id' => $this->primaryKey()->notNull()->unsigned()->comment('电器id'),
            'pid'=>$this->string(30)->notNull()->defaultValue('')->comment('电器父id'),
            'name'=>$this->string(30)->notNull()->defaultValue('')->comment('电器名'),
            'logo'=>$this->string(500)->notNull()->defaultValue('')->comment('电器图片地址'),
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
        $this->dropTable('appliance');
    }
}
