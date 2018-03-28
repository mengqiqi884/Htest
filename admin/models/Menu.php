<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "wine_admin_menu".
 *
 * @property integer $mam_id
 * @property string $mam_name
 * @property integer $mam_parentid
 * @property string $mam_route
 * @property integer $mam_order
 * @property string $mam_data
 * @property Menu $mamParent
 * @property Menu[] $menus
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mam_parentid', 'mam_order'], 'integer'],
            [['mam_name'], 'string', 'max' => 128],
            [['mam_route', 'mam_data'], 'string', 'max' => 255],
            [['mam_parentid'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 'targetAttribute' => ['mam_parentid' => 'mam_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mam_id' => '菜单id',
            'mam_name' => '菜单名称',
            'mam_parentid' => '父类id',
            'mam_route' => '路由地址',
            'mam_order' => '排序',
            'mam_data' => '菜单描述',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMamParent()
    {
        return $this->hasOne(Menu::className(), ['mam_id' => 'mam_parentid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(Menu::className(), ['mam_parentid' => 'mam_id']);
    }

    //获取顶级菜单列表
    public function  getAllMenu(){
        $menu = Yii::$app->db->createCommand("SELECT * FROM `menu` WHERE mam_parentid='0' ")->queryAll();
        return $menu;
    }
    //获取所有菜单列表
    public function  getMenuList(){
        $menu = Yii::$app->db->createCommand("SELECT * FROM `menu` ORDER BY mam_order ASC ")->queryAll();
        $menu = self::list_to_tree($menu,'mam_id','mam_parentid');
        return $menu;
    }
    //获取左侧菜单列表(item)
    public function  getLeftMenuList(){
        $uid = Yii::$app->user->identity->getId();
        $auth = Yii::$app->authManager;
        $Roles = $auth->getRolesByUser($uid);

        foreach($Roles as $vo){
            $name = $vo->name;
        }

        $Permission = $auth->getPermissionsByRole($name);
        $RolesList = '';
        foreach($Permission as $vo){
            $RolesList .= "'".$vo->name."',";
        }
        $RolesList = substr($RolesList,0,-1);

        //var_dump($RolesList);exit;
        $menu = Yii::$app->db->createCommand("SELECT * FROM `menu` WHERE mam_route IN ($RolesList)  ORDER BY `mam_order` ASC")->queryAll();
        $menu = self::list_to_tree2($menu,'mam_id','mam_parentid');
        return $menu;
    }

    //通过id找到router
    public function getRouteById($id){
        $router = Yii::$app->db->createCommand("SELECT * FROM `menu` WHERE mam_id='$id'")->queryOne();
        return $router['route'];
    }

    /**
     * 把返回的数据集转换成Tree
     * @param array $list 要转换的数据集
     * @param string $pid parent标记字段
     * @param string $level level标记字段
     * @return array
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $list[$key]['name'] ='&nbsp;&nbsp;&nbsp;&nbsp;|--'.$list[$key]['name'];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }

    function list_to_tree2($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $list[$key]['mam_name'] =$list[$key]['mam_name'];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }
}
