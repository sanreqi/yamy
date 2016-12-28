<?php
use yii\grid\GridView;
$this->params['extraLoadCss'] = [
    '/resources/auth/css/auth.css',
];
?>

<section class="content-header">
    <h1>
        用户管理
        <small>User Control</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Blank page</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <a href="/auth/user/create" type="button" class="btn btn-primary create-btn">新增用户</a>
    <div style="clear: both;"></div>
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'username',
                'contentOptions' => ['class' => 'username_td']
            ],
            'truename',
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
            var name = $(this).parents("tr").find(".username_td").text();
            var msg = "确定要删除用户" + name + "吗?";
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