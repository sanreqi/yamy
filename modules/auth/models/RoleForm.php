<?php
/**
 * User: srq
 * Date: 2016/12/23
 * Time: 11:32
 */

namespace app\modules\auth\models;

use yii\base\Model;
use Yii;

class RoleForm extends Model {

    public $name;
    public $description;
    public $basedRoles;
    public $basedPermission;

    public function rules() {
        return [
          ['name']
        ];
    }
}