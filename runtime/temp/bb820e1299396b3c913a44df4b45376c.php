<?php /*a:2:{s:63:"F:\project\shop\ThinkAdmin\app\data\view\base\config\wxapp.html";i:1648774486;s:67:"F:\project\shop\ThinkAdmin\app\data\view\..\..\admin\view\main.html";i:1648774486;}*/ ?>
<div class="layui-card"><?php if(!(empty($title) || (($title instanceof \think\Collection || $title instanceof \think\Paginator ) && $title->isEmpty()))): ?><div class="layui-card-header"><span class="layui-icon font-s10 color-desc margin-right-5">&#xe65b;</span><?php echo htmlentities((isset($title) && ($title !== '')?$title:''),ENT_QUOTES); ?><div class="pull-right"></div></div><?php endif; ?><div class="layui-card-line"></div><div class="layui-card-body"><div class="layui-card-html"><div class="think-box-shadow"><form action="<?php echo sysuri(); ?>" autocomplete="off" class="layui-form layui-card" data-auto="true" method="post" style="width:850px"><div class="layui-card-header text-center margin-20 font-w7 color-text layui-bg-gray border-radius-5"><?php echo htmlentities((isset($title) && ($title !== '')?$title:'小程序接口配置'),ENT_QUOTES); ?><span class="color-desc font-s12"> ( 微信公众号平台配置获取 )</span></div><div class="layui-card-body padding-left-40 padding-bottom-0"><label class="layui-form-item margin-bottom-20 block relative"><span class="color-green font-s14 font-w7 margin-right-10">小程序 AppId</span><input class="layui-input" maxlength="18" name="data.wxapp_appid" pattern="^wx[0-9a-z]{16}$" placeholder="请输入18位小程序 AppID" required value="<?php echo sysconf('data.wxapp_appid'); ?>"><span class="help-block"><b>必选</b>，微信小程序 AppID 需要微信公众号平台获取！</span></label><label class="layui-form-item margin-bottom-20 block relative"><span class="color-green font-s14 font-w7 margin-right-10">小程序密钥 AppSecret</span><input class="layui-input" maxlength="32" name="data.wxapp_appkey" pattern="^[0-9a-z]{32}$" placeholder="请输入32位小程序 AppSecret" required value="<?php echo sysconf('data.wxapp_appkey'); ?>"><span class="help-block"><b>必选</b>，微信小程序 AppSecret 需要微信公众号平台获取！</span></label><div class="hr-line-dashed margin-top-30"></div><div class="layui-form-item text-center"><button class="layui-btn" data-submit>保存数据</button></div></div></form></div></div></div></div>