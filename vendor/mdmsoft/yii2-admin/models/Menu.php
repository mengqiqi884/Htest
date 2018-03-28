<?php

namespace mdm\admin\models;

use Yii;
use mdm\admin\components\Configs;
use yii\db\Query;

/**
 * This is the model class for table "menu".
 *
 * @property integer $mam_id Menu id(autoincrement)
 * @property string $mam_name Menu name
 * @property integer $mam_parentid Menu parent
 * @property string $mam_route Route for this menu
 * @property integer $mam_order Menu order
 * @property string $mam_data Extra information for this menu
 *
 * @property Menu $menuParent Menu parent
 * @property Menu[] $menus Menu children
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Menu extends \yii\db\ActiveRecord
{
    public $parent_name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Configs::instance()->menuTable;
    }

    /**
     * @inheritdoc
     */
    public static function getDb()
    {
        if (Configs::instance()->db !== null) {
            return Configs::instance()->db;
        } else {
            return parent::getDb();
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mam_name'], 'required'],
            [['parent_name'], 'in',
                'range' => static::find()->select(['mam_name'])->column(),
                'message' => 'Menu "{value}" not found.'],
            [['mam_parentid', 'mam_route', 'mam_data', 'mam_order'], 'default'],
            [['mam_parentid'], 'filterParent', 'when' => function() {
                return !$this->isNewRecord;
            }],
            [['mam_order'], 'integer'],
            [['mam_route'], 'in',
                'range' => static::getSavedRoutes(),
                'message' => 'Route "{value}" not found.']
        ];
    }

    /**
     * Use to loop detected.
     */
    public function filterParent()
    {
        $parent = $this->mam_parentid;
        $db = static::getDb();
        $query = (new Query)->select(['mam_parentid'])
            ->from(static::tableName())
            ->where('[[mam_id]]=:id');
        while ($parent) {
            if ($this->mam_id == $parent) {
                $this->addError('parent_name', 'Loop detected.');
                return;
            }
            $parent = $query->params([':id' => $parent])->scalar($db);
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mam_id' => Yii::t('rbac-admin', 'ID'),
            'mam_name' => Yii::t('rbac-admin', 'Name'),
            'mam_parentid' => Yii::t('rbac-admin', 'Parent'),
            'parent_name' => Yii::t('rbac-admin', 'Parent Name'),
            'mam_route' => Yii::t('rbac-admin', 'Route'),
            'mam_order' => Yii::t('rbac-admin', 'Order'),
            'mam_data' => Yii::t('rbac-admin', 'Data'),
        ];
    }

    /**
     * Get menu parent
     * @return \yii\db\ActiveQuery
     */
    public function getMenuParent()
    {
        return $this->hasOne(Menu::className(), ['mam_id' => 'mam_parentid']);
    }

    /**
     * Get menu children
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(Menu::className(), ['mam_parentid' => 'mam_id']);
    }
    private static $_routes;

    /**
     * Get saved routes.
     * @return array
     */
    public static function getSavedRoutes()
    {
        if (self::$_routes === null) {
            self::$_routes = [];
            foreach (Yii::$app->getAuthManager()->getPermissions() as $name => $value) {
                if ($name[0] === '/' && substr($name, -1) != '*') {
                    self::$_routes[] = $name;
                }
            }
        }
        return self::$_routes;
    }

    public static function getMenuSource()
    {
        $tableName = static::tableName();
        return (new \yii\db\Query())
                ->select(['m.mam_id', 'm.mam_name', 'm.mam_route', 'parent_name' => 'p.mam_name'])
                ->from(['m' => $tableName])
                ->leftJoin(['p' => $tableName], '[[m.mam_parentid]]=[[p.mam_id]]')
                ->all(static::getDb());
    }
}
