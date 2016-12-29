<?php
use yii\grid\GridView;
$this->params['extraLoadCss'] = [
    '/resources/auth/css/auth.css',
];
?>

<section class="content-header">
    <h1>
        角色管理
        <small>Role Control</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Blank page</li>
    </ol>
</section>

<section class="content">
    <ul class="nav nav-tabs pr-tab" role="tablist">
        <li role="presentation" class="active"><a href="/auth/role">角色</a></li>
        <li role="presentation"><a href="/auth/permission">权限</a></li>
        <li role="presentation"><a href="/auth/rule">规则</a></li>
    </ul>
    <a href="/auth/role/create" type="button" class="btn btn-primary create-btn">新增角色</a>
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'name',
            [
                'class' => 'yii\grid\DataColumn',
                'header' => '基于',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width:800px;'],
                'value' => function($data) {
                    return $data->getBasedDisplay();
                }
            ],
            'description',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{update} {delete}',
            ],
        ],
    ]);
    ?>
</section>

<script type="text/javascript">
    $("document").ready(function () {
        $("a[title='Delete']").click(function () {
            var $this = $(this);
            var url = $this.attr("href");
            var name = $(this).parents("tr").attr("data-key");
            var msg = "确定要删除角色" + name + "吗?";
            if (confirm(msg)) {
                $.ajax({
                    url: url,
                    type: "get",
                    dataType: "json",
                    data: {},
                    success: function(data) {
                        if (data.status == 1) {
                            $this.parents("tr").remove();
                        }
                    }
                });
            }
            return false;
        });
    });
</script>