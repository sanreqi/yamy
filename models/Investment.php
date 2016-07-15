<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yamy_investment".
 *
 * @property integer $id
 * @property string $name
 * @property integer $catid
 * @property string $company
 * @property integer $area_id
 * @property integer $withdraw
 * @property string $web_address
 * @property integer $createtime
 * @property integer $updatetime
 */
class Investment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yamy_investment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['catid', 'area_id', 'withdraw', 'createtime', 'updatetime'], 'integer'],
            [['name', 'company'], 'string', 'max' => 32],
            [['web_address'], 'string', 'max' => 255]
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
            'catid' => 'Catid',
            'company' => 'Company',
            'area_id' => 'Area ID',
            'withdraw' => 'Withdraw',
            'web_address' => 'Web Address',
            'createtime' => 'Createtime',
            'updatetime' => 'Updatetime',
        ];
    }
}
