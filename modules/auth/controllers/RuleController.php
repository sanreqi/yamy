<?php
/**
 * User: srq
 * Date: 2016/12/22
 * Time: 17:05
 */

namespace app\modules\auth\controllers;

use app\modules\auth\models\AuthItem;
use app\modules\auth\models\AuthRule;
use yii\data\ActiveDataProvider;
use Yii;

class RuleController extends AController {

    public function actionIndex() {
        $this->checkAccessAndResponse('rule_index');
        $dataProvider = new ActiveDataProvider([
            'query' => AuthRule::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionCreate() {
        $this->checkAccessAndResponse('rule_create');
        $model = new AuthRule();
        $model->scenario = 'form';
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->validate()) {
            $className = AuthRule::RULE_NAMESPACE . $model->ruleClassName;

            $rule = new $className;
            $auth = Yii::$app->authManager;
            if ($auth->add($rule)) {
                return $this->redirect(['/auth/rule']);
            }
        }
        return $this->render('form', ['model' => $model]);
    }


    /**
     * 删除权限
     * @return object
     */
    public function actionDelete() {
        $this->checkAccessAndResponse('rule_delete');
        $name = Yii::$app->request->get('id');
        $count = AuthRule::deleteAll(['name' => $name]);
        if ($count) {
            return $this->ajaxResponseSuccess();
        }
        return $this->ajaxResponseError('删除失败');
    }
}