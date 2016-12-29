<?php
/**
 * User: srq
 * Date: 2016/12/22
 * Time: 17:29
 */

namespace app\modules\auth\controllers;

use app\modules\auth\models\AuthItem;
use app\modules\auth\models\AuthItemChild;
use Yii;
use yii\rbac\Item;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

class RoleController extends AController {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'login', 'logout'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'logout'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $this->checkAccessAndResponse('role_index');
        $dataProvider = new ActiveDataProvider([
            'query' => AuthItem::find()->where(['type' => Item::TYPE_ROLE]),
            'pagination' => [
                'pageSize' => 1000,
            ],
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionCreate() {
        $this->checkAccessAndResponse('role_create');
        $post = Yii::$app->request->post();
        $auth = Yii::$app->authManager;
        $model = new AuthItem();
        //这步好像没用,骗骗验证吧
        $model->type = Item::TYPE_ROLE;
        if ($model->load($post) && $model->validate()) {
            //实例化role类
            $role = $auth->createRole($model->name);
            $role->description = $model->description;
            //存入数据库
            $auth->add($role);
            //基于权限
            if (!empty($model->childPermissions)) {
                foreach ($model->childPermissions as $p) {
                    $childPermission = $auth->getPermission($p);
                    $auth->addChild($role, $childPermission);
                }
            }
            //基于角色
            if (!empty($model->childRoles)) {
                foreach ($model->childRoles as $r) {
                    $childRole = $auth->getRole($r);
                    $auth->addChild($role, $childRole);
                }
            }
            $this->redirect(['/auth/role/index']);
        }
        $roleList = AuthItem::getAllRoleOptions();
        $permissionList = AuthItem::getAllPermissionOptions();
        return $this->render('_form', [
            'model' => $model,
            'roleList' => $roleList,
            'permissionList' => $permissionList
        ]);
    }

    public function actionUpdate() {
        $this->checkAccessAndResponse('role_update');
        //gridview自带参数名id，懒得改
        $name = Yii::$app->request->get('id');
        $post = Yii::$app->request->post();
        $auth = Yii::$app->authManager;
        $model = AuthItem::find()->where(['name' => $name])->one();
        $itemChildren = [];
        if (!empty($model->authItemChildren)) {
            foreach ($model->authItemChildren as $v) {
                $itemChildren[] = $v->child;
            }
        }
        $model->childPermissions = $itemChildren;
        $model->childRoles = $itemChildren;

        if ($model->load($post) && $model->validate()) {
            AuthItemChild::deleteAll(['parent' => $name]);
            //实例化role类
            $role = $auth->getRole($name);
            $role->name = $model->name;
            $role->description = $model->description;
            //存入数据库
            $auth->update($name, $role);
            //基于权限
            if (!empty($model->childPermissions)) {
                foreach ($model->childPermissions as $p) {
                    $childPermission = $auth->getPermission($p);
                    $auth->addChild($role, $childPermission);
                }
            }
            //基于角色
            if (!empty($model->childRoles)) {
                foreach ($model->childRoles as $r) {
                    $childRole = $auth->getRole($r);
                    $auth->addChild($role, $childRole);
                }
            }
            $this->redirect(['/auth/role/index']);
        }

        $roleList = AuthItem::getRoleFormOptions($name);
        $permissionList = AuthItem::getAllPermissionOptions();
        return $this->render('_form', [
            'model' => $model,
            'roleList' => $roleList,
            'permissionList' => $permissionList
        ]);
    }

    /**
     * 删除角色
     * @return object
     */
    public function actionDelete() {
        $this->checkAccessAndResponse('role_delete');
        $name = Yii::$app->request->get('id');
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($name);
        if ($auth->remove($role)) {
            return $this->ajaxResponseSuccess();
        }
        return $this->ajaxResponseError('删除失败');
    }


}