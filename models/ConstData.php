<?php
/**
 * User: srq
 * Date: 2016/8/13
 * Time: 20:00
 */

namespace app\models;


use yii\base\Model;

class ConstData extends Model {

    /**
     * 个人信息
     * @return array
     */
    public static function getPersonalInfoList() {
        return [
            '13817917452' => ['mobile' => '13817917452', 'username' => 'sanreqi2', 'bankcard' => '***5949', 'banktype' => 'icbc'],
            '13601864732' => ['mobile' => '13601864732', 'username' => 'sanreqiqwq', 'bankcard' => '***2621', 'banktype' => 'spdb'],
            '15202122176' => ['mobile' => '15202122176', 'username' => 'sanreqisfh', 'bankcard' => '***8331', 'banktype' => 'icbc'],
            '17717866074' => ['mobile' => '17717866074', 'username' => 'sanreqizyl', 'bankcard' => '***7036', 'banktype' => 'spdb'],
            '17721497452' => ['mobile' => '17721497452', 'username' => 'sanreqiqww', 'bankcard' => '***1685', 'banktype' => 'ccb'],
            '13122049757' => ['mobile' => '13122049757', 'username' => 'sanreqiqlz', 'bankcard' => '***6652', 'banktype' => 'ccb'],
        ];
    }
}