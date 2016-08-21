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
            ['mobile' => '13817917452', 'username' => 'sanreqi2', 'bankcard' => '***5949', 'banktype' => 'icbc'],
            ['mobile' => '13601864732', 'username' => 'sanreqiqwq', 'bankcard' => '***2621', 'banktype' => 'spdb'],
            ['mobile' => '15202122176', 'username' => 'sanreqisfh', 'bankcard' => '***8331', 'banktype' => 'icbc'],
            ['mobile' => '17717866074', 'username' => 'sanreqizyl', 'bankcard' => '***7036', 'banktype' => 'spdb'],
            ['mobile' => '17721497452', 'username' => 'sanreqiqww', 'bankcard' => '***1685', 'banktype' => 'ccb'],
            ['mobile' => '13122049757', 'username' => 'sanreqiqlz', 'bankcard' => '***6652', 'banktype' => 'ccb'],
        ];
    }

    public static function getInfoByMobile($mobile) {
        $list = self::getPersonalInfoList();
        foreach ($list as $v) {
            if ($v['mobile'] == $mobile) {
                return $v;
            }
        }
        return false;
    }
}