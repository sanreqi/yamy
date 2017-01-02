<?php
use yii\grid\GridView;

$this->params['extraLoadJS'] = [
    '/resources/js/yamy.js'
];
$this->params['extraLoadCss'] = [
    '/resources/bootstrap/css/bootstrap.min.css',
    '/resources/css/yamy.css',
];
?>

<div id="dcMain">
    <div id="urHere">利息</div>
    <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3>借款途径</h3>
        <a href="/borrow/way-create" class="btn btn-primary"
           style="float: right; margin-bottom: 10px; margin-top: -10px;">新增借款途径</a>
        <?php
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                'platform',
//                'rule_name',
//                [
//                    'class' => 'yii\grid\ActionColumn',
//                    'header' => '操作',
//                    'template' => '{update} {delete}',
//                ],
            ],
        ]);
        ?>
    </div>
</div>
