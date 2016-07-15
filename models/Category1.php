<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Category
 *
 * @author Administrator
 */

namespace app\models;

use yii\db\ActiveRecord;

class Category1 extends ActiveRecord {

    //是否叶子节点
    CONST IS_LEAF = 1;
    CONST IS_NOT_LEAF = 0;

    public static function tableName() {
        return 'Category';
    }

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
    public static function buildTree($items, $pid = 0, $html = '----') {     
        $tree = [];
        foreach ($items as $v) {
            if ($v['pid'] == $pid) {
                $temp = $v;
                $temp['html'] = str_repeat($html, $temp['level']);
                $tree[] = $temp;
                $tree = array_merge($tree, self::buildTree($items, $temp['id'], $html));
            }
        }
        return $tree;
    }

}
