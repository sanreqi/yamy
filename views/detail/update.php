<div id="dcMain">
    <div id="urHere">p2p平台<b>></b><strong>修改明细</strong> </div>   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3>修改明细</h3>
        <?php 
        echo $this->context->renderPartial('_form', [
            'platOptions' => $platOptions, 
            'accountOptions' => $accountOptions,
            'model' => $model
        ]); 
        ?>
    </div>
</div>