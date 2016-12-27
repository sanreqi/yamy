<?php

namespace app\modules\auth\models;

use Yii;
use yii\rbac\Item;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 */
class AuthItem extends \yii\db\ActiveRecord {

    public $childRoles = [];
    public $childPermissions = [];

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'auth_item';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'type'], 'required'],
            ['name', 'unique', 'message' => '名称"{value}"已存在'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['childRoles', 'childPermissions'], 'safe'],
            [['name', 'rule_name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'name' => '名称',
            'type' => 'Type',
            'description' => '描述',
            'rule_name' => 'Rule Name',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments() {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName() {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren() {
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren0() {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }

    //    public function getAuthItemChildren1() {
    //        return $this->hasMany(AuthItemChild::className(), ['child' => 'name'])->onCondition(['type' => Item::TYPE_ROLE]);
    //    }
    //
    //    public function getAuthItemChildren2() {
    //        return $this->hasMany(AuthItemChild::className(), ['child' => 'name'])->where(['type' => Item::TYPE_PERMISSION]);
    //    }

    /**
     * 所有角色
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getAllRoles() {
        return AuthItem::find()->where(['type' => Item::TYPE_ROLE])->asArray()->all();
    }

    /**
     * 所有权限
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getAllPermissions() {
        return AuthItem::find()->where(['type' => Item::TYPE_PERMISSION])->asArray()->all();
    }

    /**
     * checkbox使用
     * @return array
     */
    public static function getAllRoleOptions() {
        $result = [];
        $roles = self::getAllRoles();
        foreach ($roles as $role) {
            $result[$role['name']] = $role['name'];
        }
        return $result;
    }

    public static function getRoleFormOptions($name) {
        $result = [];
        $allRoles = self::getAllRoles();
        $model = self::getItemByName($name);
        if ($allRoles && $model) {
            $removed = [];
            if (!empty($model->authItemChildren0)) {
                foreach ($model->authItemChildren0 as $v) {
                    $removed[] = $v->parent;
                }
            }
            foreach ($allRoles as $role) {
                if (!in_array($role['name'], $removed)) {
                    $result[$role['name']] = $role['name'];
                }
            }
        }

        return $result;
    }

    /**
     * checkbox使用
     * @return array
     */
    public static function getAllPermissionOptions() {
        $result = [];
        $permissions = self::getAllPermissions();
        foreach ($permissions as $permission) {
            $result[$permission['name']] = $permission['name'];
        }
        return $result;
    }

    /**
     * gridview使用
     * @return string
     */
    public function getBasedDisplay() {
        $auth = Yii::$app->authManager;
        $children = $auth->getChildren($this->name);
        $cRoles = [];
        $cPermissions = [];
        foreach ($children as $child) {
            if ($child->type == Item::TYPE_ROLE) {
                $cRoles[] = $child->name;
            } elseif ($child->type == Item::TYPE_PERMISSION) {
                $cPermissions[] = $child->name;
            }
        }
        $result = '';
        $result .= "基于角色(" . implode(',', $cRoles) . ")";
        $result .= " | 基于权限(" . implode(',', $cPermissions) . ")";
        return $result;
    }

    public function getRoleChildren() {
        return AuthItemChild::find()
            ->innerJoinWith('child0')
            ->where(['parent' => $this->name, 'type' => Item::TYPE_ROLE])
            ->asArray()
            ->all();
    }

    public function getPermissionChildren() {
        return AuthItemChild::find()
            ->innerJoinWith('child0')
            ->where(['parent' => $this->name, 'type' => Item::TYPE_PERMISSION])
            ->asArray()
            ->all();
    }

    public static function getItemByName($name) {
        return AuthItem::find()->where(['name' => $name])->one();
    }
}
