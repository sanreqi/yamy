<?php
/**
 * User: srq
 * Date: 2016/8/13
 * Time: 20:00
 */

namespace app\models;


use yii\base\Model;

class ConstData extends Model {

    public static function getMobileList() {
        return [
            '13817917452' => '13817917452-小散'
        ];
    }
}