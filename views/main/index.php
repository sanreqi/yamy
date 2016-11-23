<?php
use app\models\Platform;
$this->params['extraLoadJS'] = [
];
$this->params['extraLoadCss'] = [
    '/resources/jcalendar/style/documentation.css',
    '/resources/jcalendar/style/jalendar.css',
    '/resources/css/yamy.css'
];
?>
<script type="text/javascript" src="/resources/jcalendar/js/jalendar.js"></script>

<div id="main-left">
    <div id="main-calendar" class="jalendar mid">
        <?php if (!empty($accounts)): ?>
            <?php foreach ($accounts as $k => $v): ?>
                <?php $t = $v['returned_time']; ?>
                <div class="added-event" data-id="0" data-date="<?php echo date('j',$t).'/'.date('n',$t).'/'.date('Y',$t); ?>" data-time=""
                     data-title="<?php echo '回款:'.Platform::getNameById($v['platform_id']).','.$v['balance'].'元,'.$v['mobile']; ?>"></div>
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
<div id="main-right" style="font-size: 20px">呵呵</div>

<script type="text/javascript">
    $(function () {
        $('#main-calendar').jalendar();
    });
</script>