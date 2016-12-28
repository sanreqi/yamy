<?php
/**
 * User: srq
 * Date: 2016/12/22
 * Time: 17:05
 */

namespace app\modules\auth\controllers;

use app\modules\auth\models\AuthItem;
use yii\rbac\Item;
use yii\data\ActiveDataProvider;
use Yii;

class PermissionController extends AController {

    public function actionIndex() {
        $this->checkAccessAndResponse('permission_index');
        $dataProvider = new ActiveDataProvider([
            'query' => AuthItem::find()->where(['type' => Item::TYPE_PERMISSION]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionCreate() {
        $this->checkAccessAndResponse('permission_create');
        $model = new AuthItem();
        $model->type = Item::TYPE_PERMISSION;
        $post = Yii::$app->request->post();

        if ($model->load($post) && $model->validate()) {
            $auth = Yii::$app->authManager;
            //实例化权限类
            $permission = $auth->createPermission($model->name);
            $permission->description = $model->description;
            $permission->ruleName = $model->rule_name;
            if ($auth->add($permission)) {
                $this->redirect(['index']);
            }
        }
        return $this->render('_form', ['model' => $model]);
    }

    public function actionUpdate() {
        $this->checkAccessAndResponse('permission_update');
        $name = Yii::$app->request->get('id');
        $post = Yii::$app->request->post();
        $auth = Yii::$app->authManager;
        $model = AuthItem::getItemByName($name);

        if ($model->load($post) && $model->validate()) {
            $permission = $auth->createPermission($model->name);
            $permission->description = $model->description;
            $permission->ruleName = $model->rule_name;
            if ($auth->update($name, $permission)) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('_form', ['model' => $model]);
    }


    /**
     * 删除权限
     * @return object
     */
    public function actionDelete() {
        $this->checkAccessAndResponse('permission_delete');
        $name = Yii::$app->request->get('id');
        $auth = Yii::$app->authManager;
        $permission = $auth->getPermission($name);
        if ($auth->remove($permission)) {
            return $this->ajaxResponseSuccess();
        }
        return $this->ajaxResponseError('删除失败');
    }
}