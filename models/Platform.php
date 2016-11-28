<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "p2p_platform".
 *
 * @property integer $id
 * @property string $name
 */
class Platform extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'p2p_platform';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'unique'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }
    
    public static function getOptions() {
        $options = [];
        $models = Platform::find()->where(['is_deleted' => 0])->asArray()->all();
        if (!empty($models)) {
            foreach ($models as $model) {
                $options[$model['id']] = $model['name'];
            }
        }
        return $options;
    }
    
    public static function getNameById($id) {
        $model = Platform::find()->where(['id' => $id])->one();
        return isset($model['name']) ? $model['name'] : '';
    }
}
