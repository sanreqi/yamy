<?php

namespace app\modules\auth\models;

use ReflectionClass;
use Yii;

/**
 * This is the model class for table "auth_rule".
 *
 * @property string $name
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthItem[] $authItems
 */
class AuthRule extends \yii\db\ActiveRecord {

    public $ruleClassName;

    CONST RULE_NAMESPACE = 'app\modules\auth\models\rule\\';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'auth_rule';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['name', 'safe'],
            ['name', 'unique', 'message' => '名称已存在'],
            ['ruleClassName', 'required', 'message' => '名称不能为空'],
            ['ruleClassName', 'validateClass', 'on' => 'form'],
            [['data'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 64]
        ];
    }

    /**
     * 验证
     */
    public function validateClass() {
        $className = self::RULE_NAMESPACE . $this->ruleClassName;
        if (class_exists($className)) {
            $reflectionClass = new ReflectionClass($className);
            //判断是否可以实例化
            if($reflectionClass->isInstantiable()) {
                $class = new $className;
                if ($class instanceof \yii\Rbac\Rule) {
                    if (AuthRule::find()->where(['name' => $class->name])->exists()) {
                        $this->addError('ruleClassName', '规则已存在');
                        return false;
                    }
                    return true;
                }
            }
        }
        $this->addError('ruleClassName', '规则名称输入错误');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'name' => '规则名称',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItems() {
        return $this->hasMany(AuthItem::className(), ['rule_name' => 'name']);
    }

    public static function getAllRules() {
        return AuthRule::find()->all();
    }

    public static function getAllRuleOptions() {
        $result = [];
        $rules = self::getAllRules();
        if (!empty($rules)) {
            foreach ($rules as $rule) {
                $result[$rule->name] = $rule->name;
            }
        }
        return $result;
    }
}
