<?php /*a:2:{s:67:"F:\project\shop\ThinkAdmin\app\data\view\base\config\page_home.html";i:1648774486;s:67:"F:\project\shop\ThinkAdmin\app\data\view\..\..\admin\view\main.html";i:1648774486;}*/ ?>
<div class="layui-card"><?php if(!(empty($title) || (($title instanceof \think\Collection || $title instanceof \think\Paginator ) && $title->isEmpty()))): ?><div class="layui-card-header"><span class="layui-icon font-s10 color-desc margin-right-5">&#xe65b;</span><?php echo htmlentities((isset($title) && ($title !== '')?$title:''),ENT_QUOTES); ?><div class="pull-right"></div></div><?php endif; ?><div class="layui-card-line"></div><div class="layui-card-body"><div class="layui-card-html"><div class="layui-row layui-col-space20 portal-block-container notselect"><?php foreach($pageTypes as $key=>$type): ?><div class="layui-col-sm4 layui-col-md4 layui-col-lg3"><!--<?php if(auth('pageEdit')): ?>--><div class="pointer" data-open="<?php echo url('pageEdit'); ?>?type=<?php echo htmlentities($key,ENT_QUOTES); ?>"><div class="portal-block-item nowrap think-bg-violet"><div>编辑页面内容</div><div style="font-size:26px"><?php echo htmlentities((isset($type) && ($type !== '')?$type:''),ENT_QUOTES); ?></div></div><i class="portal-block-icon layui-icon layui-icon-read"></i></div><!--<?php else: ?>--><div class="portal-block-item nowrap think-bg-violet"><div>编辑页面内容</div><div style="font-size:26px"><?php echo htmlentities((isset($type) && ($type !== '')?$type:''),ENT_QUOTES); ?></div></div><i class="portal-block-icon layui-icon layui-icon-read"></i><!--<?php endif; ?>--></div><?php endforeach; ?></div></div></div></div>