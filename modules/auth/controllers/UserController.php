<?php
/**
 * User: srq
 * Date: 2016/12/20
 * Time: 16:54
 */

namespace app\modules\auth\controllers;

use app\modules\auth\models\AuthAssignment;
use app\modules\auth\models\AuthItem;
use app\modules\auth\models\LoginForm;
use app\modules\auth\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use Yii;

class UserController extends AController {

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

    /**
     * 用户列表
     * @return string
     */
    public function actionIndex() {
        $this->checkAccessAndResponse('user_index');
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where(['is_deleted' => 0]),
            'pagination' => [
                'pageSize' => 1000,
            ],
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    /**
     * 创建用户
     * @return string
     */
    public function actionCreate() {
        $this->checkAccessAndResponse('user_create');
        $model = new User();
        $model->scenario = 'create';
        $post = Yii::$app->request->post();
        $auth = Yii::$app->authManager;

        if (!empty($post)) {
            if ($model->load($post) && $model->validate()) {
                $model->password = User::getEncryptPwd($model->pwd);
                $model->createtime = time();
                $model->is_deleted = 0;
                if ($model->save(false)) {
                    $roles = $model->roles;
                    if (!empty($roles) && is_array($roles)) {
                        foreach ($roles as $roleName) {
                            $role = $auth->getRole($roleName);
                            $auth->assign($role, $model->id);
                        }
                    }
                    $this->redirect(['index']);
                }
            }
        }
        $roleList = AuthItem::getAllRoleOptions();
        return $this->render('create', [
            'model' => $model,
            'roleList' => $roleList
        ]);
    }

    /**
     * 登录
     * @return string
     */
    public function actionLogin() {
        if (Yii::$app->user->isGuest) {
            //根据不同角色跳转到不同页面
        }
        $this->layout = false;
        $model = new LoginForm();
        $post = Yii::$app->request->post('LoginForm');
        if (!empty($post)) {
            $model->username = $post['username'];
            $model->password = $post['password'];
            $model->rememberMe = isset($post['rememberMe']) && !empty($post['rememberMe']) ? 1 : 0;
            if ($model->login()) {

                $auth = Yii::$app->authManager;
                //角色名称数组
                $roles = array_keys($auth->getRolesByUser(Yii::$app->user->id));
                if (in_array('admin', $roles)) {
                    return $this->redirect(['/auth/user/index']);
                } elseif (in_array('normal', $roles)) {
                    return $this->redirect(['/']);
                }
            }
        }
        return $this->render('login', ['model' => $model]);
    }

    /**
     * 注销
     */
    public function actionLogout() {
        Yii::$app->user->logout();
        $this->redirect(['/auth/user/login']);
    }

    public function actionDelete() {
        $this->checkAccessAndResponse('user_delete');
        $id = Yii::$app->request->get('id');
        $user = User::findOne(['id' => $id]);
        if (!empty($user)) {
            $user->is_deleted = 1;
            if ($user->save()) {
                return $this->ajaxResponseSuccess();
            }
        }
        return $this->ajaxResponseError('删除失败');
    }

    /**
     * 编辑user
     * @return string
     */
    public function actionUpdate() {
        $this->checkAccessAndResponse('user_update');
        $id = Yii::$app->request->get('id');
        $model = User::findIdentity($id);
        $post = Yii::$app->request->post();
        $auth = Yii::$app->authManager;
        $model->roles = array_keys($auth->getAssignments($id));
        $model->scenario = 'update';
        if ($model->load($post) && $model->validate()) {
            //全部删了重新添加
            AuthAssignment::deleteAll(['user_id' => $id]);
            if (!empty($model->pwd)) {
                $model->password = User::getEncryptPwd($model->pwd);
            }
            $model->updatetime = time();
            if ($model->save(false)) {
                if (isset($model->roles) && !empty($model->roles) && is_array($model->roles)) {
                    $roles = $model->roles;
                    foreach ($roles as $roleName) {
                        $role = $auth->getRole($roleName);
                        $auth->assign($role, $model->id);
                    }
                }
                $this->redirect(['index']);
            }
        }
        $roleList = AuthItem::getAllRoleOptions();
        return $this->render('create', ['model' => $model, 'roleList' => $roleList]);
    }

}
