<?php
use app\models\Platform;
use yii\helpers\Url;
$this->params['extraLoadJS'] = [
    '/resources/js/echarts.min.js',
    '/resources/jcalendar/js/jalendar.js',
    '/resources/js/main.js',
];
$this->params['extraLoadCss'] = [
    '/resources/jcalendar/style/documentation.css',
    '/resources/jcalendar/style/jalendar.css',
    '/resources/css/yamy.css'
];
?>

<div id="main-left">
    <div id="main-calendar" class="jalendar mid">
        <?php if (!empty($accounts)): ?>
            <?php foreach ($accounts as $k => $v): ?>
                <?php $t = $v['returned_time']; ?>
                <?php $pName = Platform::getNameById($v['platform_id']).','.$v['balance'].'元,'.$v['mobile']; ?>
                <div class="added-event" data-id="0" data-date="<?php echo date('j',$t).'/'.date('n',$t).'/'.date('Y',$t); ?>" data-time=""
                     data-title="<a target=blank href='<?php echo Url::toRoute(['/account/view','id'=>$v['id']]); ?>'><?php echo $pName; ?></a>"></div>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if (!empty($remarks)): ?>
            <?php foreach ($remarks as $k => $v): ?>
                <?php $t = $v['time']; ?>
                <div class="added-event" data-id="<?php echo $v['id']; ?>" data-date="<?php echo date('j',$t).'/'.date('n',$t).'/'.date('Y',$t); ?>" data-time=""
                     data-title="<?php echo '备注:'.$v['content'];  ?>"></div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<div id="main-right">
    <div id="platform-amount-chart" style="width: 600px;height:400px;"></div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        yamy.main.init();
    });
</script>

