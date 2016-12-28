<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InstructionController
 *
 * @author Administrator
 */

namespace app\controllers;

use yii\db\Query;
use app\models\Detail;
use app\controllers\MController;
use Yii;
use yii\filters\AccessControl;

class InstructionController extends MController {

    /**
     * 登录才能访问
     * @return array
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $this->checkAccessAndResponse('instruction_index');
        $uid = Yii::$app->user->id;
        $query = new Query();
        $row1 = $query->select(['sum(balance) as sum'])->from('p2p_account')->where(['is_deleted' => 0, 'uid' => $uid])->one();
        $balance = isset($row1['sum']) ? $row1['sum'] : 0;

        $row2 = $query->select(['sum(amount) as sum'])->from('p2p_detail')->where(['is_deleted' => 0, 'type' => Detail::TYPE_RECHARGE, 'uid' => $uid])->one();
        $row3 = $query->select(['sum(amount) as sum'])->from('p2p_detail')->where(['is_deleted' => 0, 'type' => Detail::TYPE_WITHDRAW, 'uid' => $uid])->one();
        $recharge = isset($row2['sum']) ? $row2['sum'] : 0;
        $withdraw = isset($row3['sum']) ? $row3['sum'] : 0;

        $row4 = $query->select(['sum(amount) as sum'])->from('p2p_cashback')->where(['is_deleted' => 0, 'uid' => $uid])->one();
        $cashback = isset($row4['sum']) ? $row4['sum'] : 0;

        $profit = $balance - ($recharge - $withdraw) + $cashback;
        $profit = round($profit, 2);
        return $this->render('index', ['profit' => $profit]);
    }
}
