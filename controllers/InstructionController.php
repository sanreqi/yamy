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

class InstructionController extends MController {
    
    public function actionIndex() {
        $query = new Query();
        $row1 = $query->select(['sum(balance) as sum'])->from('p2p_account')->where(['is_deleted' => 0])->one();
        $balance = isset($row1['sum']) ? $row1['sum'] : 0;
      
        $row2 = $query->select(['sum(amount) as sum'])->from('p2p_detail')->where(['is_deleted' => 0, 'type' => Detail::TYPE_RECHARGE])->one();
        $row3 = $query->select(['sum(amount) as sum'])->from('p2p_detail')->where(['is_deleted' => 0, 'type' => Detail::TYPE_WITHDRAW])->one();
        $recharge = isset($row2['sum']) ? $row2['sum'] : 0;
        $withdraw = isset($row3['sum']) ? $row3['sum'] : 0;
        
        $row4 = $query->select(['sum(amount) as sum'])->from('p2p_cashback')->where(['is_deleted' => 0])->one();
        $cashback = isset($row4['sum']) ? $row4['sum'] : 0;
        
        $profit = $balance - ($recharge - $withdraw) + $cashback;
        $profit = round($profit, 2);
        return $this->render('index', ['profit' => $profit]);
    }
}
