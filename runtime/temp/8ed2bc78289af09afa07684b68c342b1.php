<?php /*a:1:{s:56:"F:\project\shop\ThinkAdmin\app\admin\view\user\form.html";i:1648774486;}*/ ?>
<form class="layui-form layui-card" action="<?php echo sysuri(); ?>" data-table-id="UserTable" data-auto="true" method="post"><div class="layui-card-body padding-left-40"><fieldset><legend><b class="layui-badge think-bg-violet">用户账号</b></legend><div class="layui-row layui-col-space15"><div class="layui-row layui-col-space15"><div class="layui-col-xs2 text-center"><input type="hidden" name="headimg" value="<?php echo htmlentities((isset($vo['headimg']) && ($vo['headimg'] !== '')?$vo['headimg']:''),ENT_QUOTES); ?>"><script>$('[name=headimg]').uploadOneImage();</script></div><div class="layui-col-xs5"><label class="block relative"><span class="help-label"><b>登录账号</b>Login Username</span><?php if(isset($vo) and isset($vo['username'])): ?><input disabled value='<?php echo htmlentities((isset($vo['username']) && ($vo['username'] !== '')?$vo['username']:""),ENT_QUOTES); ?>' required class="layui-input think-bg-gray"><?php else: ?><input name="username" value='<?php echo htmlentities((isset($vo['username']) && ($vo['username'] !== '')?$vo['username']:""),ENT_QUOTES); ?>' required pattern="^.{4,}$" placeholder="请输入登录账号" class="layui-input"><?php endif; ?><span class="help-block">登录账号不能重复并且创建后不能再次修改哦。</span></label></div><div class="layui-col-xs5"><label class="block relative"><span class="help-label"><b>用户名称</b>User Nickname</span><input name="nickname" value='<?php echo htmlentities((isset($vo['nickname']) && ($vo['nickname'] !== '')?$vo['nickname']:""),ENT_QUOTES); ?>' required placeholder="请输入用户名称" class="layui-input"><span class="help-block">用于区分用户数据的用户名称，请尽量不要重复。</span></label></div></div></div></fieldset><?php if(!empty($bases) || !empty($authorizes)): ?><fieldset><legend><b class="layui-badge think-bg-violet">用户权限</b></legend><?php if(!empty($bases)): ?><div class="layui-form-item"><div class="help-label"><b>角色身份</b>Role Identity</div><div class="layui-textarea help-checks"><?php foreach($bases as $base): ?><label class="think-checkbox"><?php if(isset($vo['usertype']) and $vo['usertype'] == $base['code']): ?><input type="radio" checked name="usertype" value="<?php echo htmlentities($base['code'],ENT_QUOTES); ?>" lay-ignore><?php echo htmlentities($base['name'],ENT_QUOTES); else: ?><input type="radio" name="usertype" value="<?php echo htmlentities($base['code'],ENT_QUOTES); ?>" lay-ignore><?php echo htmlentities($base['name'],ENT_QUOTES); ?><?php endif; ?></label><?php endforeach; ?></div></div><?php endif; if(!empty($authorizes)): ?><div class="layui-form-item"><div class="help-label"><b>访问权限</b>Role Permission</div><div class="layui-textarea help-checks"><?php if(isset($vo['username']) and $vo['username'] == $superName): ?><span class="color-desc padding-left-5">超级用户拥所有访问权限，不需要配置权限。</span><?php else: foreach($authorizes as $authorize): ?><label class="think-checkbox"><?php if(in_array($authorize['id'], $vo['authorize'])): ?><input type="checkbox" checked name="authorize[]" value="<?php echo htmlentities($authorize['id'],ENT_QUOTES); ?>" lay-ignore><?php echo htmlentities($authorize['title'],ENT_QUOTES); else: ?><input type="checkbox" name="authorize[]" value="<?php echo htmlentities($authorize['id'],ENT_QUOTES); ?>" lay-ignore><?php echo htmlentities($authorize['title'],ENT_QUOTES); ?><?php endif; ?></label><?php endforeach; ?><?php endif; ?></div></div><?php endif; ?></fieldset><?php endif; ?><fieldset><legend><b class="layui-badge think-bg-violet">用户资料</b></legend><div class="layui-row layui-col-space15"><div class="layui-col-xs4"><label class="relative block"><span class="help-label"><b>联系邮箱</b>Contact Email</span><input name="contact_mail" pattern="^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$" value='<?php echo htmlentities((isset($vo['contact_mail']) && ($vo['contact_mail'] !== '')?$vo['contact_mail']:""),ENT_QUOTES); ?>' placeholder="请输入联系电子邮箱" class="layui-input"><span class="color-desc">可选，请填写用户常用的电子邮箱</span></label></div><div class="layui-col-xs4"><label class="relative block"><span class="help-label"><b>联系手机</b>Contact Mobile</span><input type="tel" maxlength="11" name="contact_phone" value='<?php echo htmlentities((isset($vo['contact_phone']) && ($vo['contact_phone'] !== '')?$vo['contact_phone']:""),ENT_QUOTES); ?>' pattern="^1[3-9][0-9]{9}$" placeholder="请输入用户联系手机" class="layui-input"><span class="color-desc">可选，请填写用户常用的联系手机号</span></label></div><div class="layui-col-xs4"><label class="relative block"><span class="help-label"><b>联系ＱＱ</b>Contact QQ</span><input name="contact_qq" pattern="^\d{6,}$" value='<?php echo htmlentities((isset($vo['contact_qq']) && ($vo['contact_qq'] !== '')?$vo['contact_qq']:""),ENT_QUOTES); ?>' placeholder="请输入常用的联系ＱＱ" class="layui-input"><span class="color-desc">可选，请填写用户常用的联系ＱＱ号</span></label></div></div><label class="layui-form-item block relative margin-top-10"><span></span><b class="color-green">用户描述</b><span class="color-desc margin-left-5">User Remark</span><textarea placeholder="请输入用户描述" class="layui-textarea" name="describe"><?php echo htmlentities((isset($vo['describe']) && ($vo['describe'] !== '')?$vo['describe']:""),ENT_QUOTES); ?></textarea></label></fieldset></div><div class="hr-line-dashed"></div><?php if(!(empty($vo['id']) || (($vo['id'] instanceof \think\Collection || $vo['id'] instanceof \think\Paginator ) && $vo['id']->isEmpty()))): ?><input type='hidden' value='<?php echo htmlentities($vo['id'],ENT_QUOTES); ?>' name='id'><?php endif; ?><div class="layui-form-item text-center"><button class="layui-btn" type='submit'>保存数据</button><button class="layui-btn layui-btn-danger" type='button' data-confirm="确定要取消编辑吗？" data-close>取消编辑</button></div></form>