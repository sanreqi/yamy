<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $name
 * @property string $shortname
 * @property string $type
 * @property integer $pid
 * @property integer $level
 * @property integer $is_leaf
 * @property string $description
 * @property integer $createtime
 * @property integer $updatetime
 */
class Category extends \yii\db\ActiveRecord {

    //是否叶子节点
    CONST IS_LEAF = 1;
    CONST IS_NOT_LEAF = 0;
    
    //是否删除 0-未删除，1-已删除 
    CONST IS_DELETED_NO = 0;
    CONST IS_DELETED_YES = 1;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'pid', 'level', 'is_leaf'], 'required'],
            [['pid', 'level', 'is_leaf', 'createtime', 'updatetime'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['shortname', 'type'], 'string', 'max' => 20],
            [['description'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'shortname' => 'Shortname',
            'type' => 'Type',
            'pid' => 'Pid',
            'level' => 'Level',
            'is_leaf' => 'Is Leaf',
            'description' => 'Description',
            'createtime' => 'Createtime',
            'updatetime' => 'Updatetime',
        ];
    }

    /**
     * 根据id查找分类
     * @param type $id
     * @return type
     */
    public static function findCateogryById($id) {
        return self::findOne($id);
    }

    /**
     * 分类树
     * @param type $items 所有分类
     * @param type $pid
     * @param type $html
     * @return type
     */
    public static function buildTree($items, $pid = 0, $html = '--') {
        $tree = [];
        foreach ($items as $k=>$v) {
            if ($v['pid'] == $pid) {
//                $temp = [];
                $temp = $v;
                $temp['prev'] = str_repeat($html, $temp['level']);
                $temp['display'] = $temp['prev'] . $temp['name'];
                $tree[] = $temp;
                unset($items[$k]);
                $tree = array_merge($tree, self::buildTree($items, $temp['id'], $html));
            }
        }
        return $tree;
    }

}
