<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sim".
 *
 * @property integer $id
 * @property string $mobile
 * @property integer $type
 * @property string $balance
 * @property string $owner
 * @property integer $is_deleted
 * @property string $createtime
 * @property string $updatetime
 */
class Sim extends \yii\db\ActiveRecord {

    /*移动*/
    CONST TYPE_CMCC = 1;
    /*联通*/
    CONST TYPE_CUCC = 2;
    /*电信*/
    CONST TYPE_CTCC = 3;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'sim';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['type', 'is_deleted'], 'integer'],
            [['createtime', 'updatetime'], 'safe'],
            [['mobile', 'balance', 'owner'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'mobile' => 'Mobile',
            'type' => 'Type',
            'balance' => 'Balance',
            'owner' => 'Owner',
            'is_deleted' => 'Is Deleted',
            'createtime' => 'Createtime',
            'updatetime' => 'Updatetime',
        ];
    }

    /**
     * 获取手机号码数组
     * @return array
     */
    public static function getMobileOptions() {
        $result = [];
        $models = Sim::find()->where(['is_deleted' => 0])->asArray()->all();
        if (!empty($models)) {
            foreach ($models as $model) {
                $result[$model['id']] = $model['mobile'];
            }
        }
        return $result;
    }

    /**
     * 通过id获取model
     * @param $id
     */
    public function getModelById($id) {
        return Sim::find()->where(['id' => $id])->asArray()->one();
    }
}
