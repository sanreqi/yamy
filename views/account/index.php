<?php
use yii\helpers\Url;
use app\models\Platform;
use yii\widgets\LinkPager;

$this->params['extraLoadJS'] = [
];
?>

<div id="dcMain">
    <!-- 当前位置 -->
    <div id="urHere">p2p平台<b>></b><strong>p2p账号</strong> </div>   <div class="mainBox" style="height:auto!important;height:550px;min-height:550px;">
        <h3><a href="/account/create" class="actionBtn add">新增账号</a>p2p账号</h3>
        <h3>总资产：<?php echo $sum; ?>元</h3>
        <div class="filter">
            <form action="" method="get">
                <select name="Search[platform_id]" style="width: 170px;" class="select-platform">
                    <option value="0">请选择平台</option>
                    <?php if (!empty($platOptions)): ?>    
                        <?php foreach ($platOptions as $k => $v): ?>
                            <?php if ($k == $search['platform_id']): ?>
                                <option selected="selected" value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php else: ?>
                                <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>        
                </select>
  
                <select name="Search[mobile]" style="width: 170px;" class="select-account">    
                    <option value="0">请选择手机</option>
                    <?php if (!empty($mobileOptions)): ?>   
                        <?php foreach ($mobileOptions as $k => $v): ?>
                            <?php if ($k == $search['mobile']): ?>
                                <option selected="selected" value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php else: ?>
                                <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <input name="Search[balance]" type="text" class="inpMain" value="<?php echo $search['balance']; ?>" size="20" placeholder="余额大于等于" />
                <input name="keyword" type="text" class="inpMain" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>" size="20" placeholder="关键字" />
                <input name="submit" class="btnGray" type="submit" value="搜索" />
            </form>
        </div>
        <?php $orderReturned = (!isset($_GET['order_returned']) || $_GET['order_returned']=='asc') ? 'desc' : 'asc';  ?>
        <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
            <tr>
                <th width="120" align="left">账户id</th>
                <th align="left">平台</th>
                <th align="left">手机</th>
                <th align="left">用户名</th>
                <th align="left">余额</th>
                <th align="left"><a href="<?php echo Url::toRoute(['/account/index','order_returned'=>$orderReturned]);  ?>">最近回款日期</a></th>
                <th align="left">操作</th>
            </tr>
            <?php if (!empty($models)): ?>
                <?php foreach ($models as $model): ?>
                    <tr>
                        <td align="left">
                            <a href="<?php echo Url::toRoute(['/account/view', 'id' => $model['id']]);?>"><?php echo $model['id']; ?></a>
                        </td>
                        <td align="left"><?php echo Platform::getNameById($model['platform_id']); ?></td>
                        <td align="left"><?php echo $model['mobile']; ?></td>
                        <td align="left"><?php echo $model['username']; ?></td>
                        <td align="left"><?php echo $model['balance']; ?></td>
                        <td align="left"><?php echo !empty($model['returned_time']) ? date('Y-m-d', $model['returned_time']) : ''; ?></td>
                        <td align="center"><a href="<?php echo Url::toRoute(['/account/update','id' => $model['id']]) ?>">编辑</a> | <a href="<?php echo Url::toRoute(['/account/delete','id' => $model['id']]) ?>">删除</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
        <div class="spage">
            第<?php echo $pages->page+1; ?>页
            <?php echo LinkPager::widget([
                'pagination' => $pages,
                'firstPageLabel' => '首页',
                'lastPageLabel' => '末页',
                'nextPageLabel' => '下一页',
                'prevPageLabel' => '上一页'
            ]); ?>
        </div>
    </div>
</div>   