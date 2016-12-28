<?php
namespace app\modules\auth\models\rule;
use yii\rbac\Rule;

/**
 * User: srq
 * Date: 2016/12/28
 * Time: 10:49
 */
class AuthorRule extends Rule {

    public $name = '123';

    public function execute($user, $item, $params) {
        // TODO: Implement execute() method.
    }
}