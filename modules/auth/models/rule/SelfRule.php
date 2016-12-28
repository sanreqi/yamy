<?php

namespace app\modules\auth\models\rule;
/**
 * User: srq
 * Date: 2016/12/28
 * Time: 10:55
 */
class SelfRule extends \yii\rbac\Rule {

    public $name = 'isSelf';

    public function execute($user, $item, $params) {
        return isset($params['uid']) ? $params['uid'] == $user : false;
    }
}