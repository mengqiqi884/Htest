<?php
/**
 * User: Administrator
 * Date: 2016-7-5
 * Time: 16:10
 * Description:
 */

namespace admin\components;


use Yii;
use yii\rbac\Rule;


class ArticleRule extends Rule
{
    public $name = 'SHANTE';
    public function execute($user, $item, $params)
    {
        // 这里先设置为false,逻辑上后面再完善
        return true;
    }
}