<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "remark".
 *
 * @property integer $id
 * @property string $content
 * @property string $time
 * @property string $createtime
 * @property integer $is_deleted
 */
class Remark extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'remark';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'is_deleted'], 'integer'],
            [['time', 'createtime'], 'safe'],
            [['content'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'time' => 'Time',
            'createtime' => 'Createtime',
            'is_deleted' => 'Is Deleted',
        ];
    }

    public static function getModelById($id) {
        return Remark::find()->where(['id' => $id])->one();
    }
}
