<?php
/**
 * The upgrade module zh-cn file of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $$
 * @link        http://www.ranzhico.com
 */
$lang->upgrade = new stdclass();
$lang->upgrade->common  = '升级';

$lang->upgrade->result  = '升级结果';
$lang->upgrade->fail    = '升级失败';
$lang->upgrade->success = '升级成功';
$lang->upgrade->tohome  = '返回首页';

$lang->upgrade->index         = '检查是否可以执行升级程序';
$lang->upgrade->backup        = '备份数据';
$lang->upgrade->selectVersion = '确认升级之前的版本';
$lang->upgrade->confirm       = '确认要执行的SQL语句';
$lang->upgrade->execute       = '确认执行';
$lang->upgrade->next          = '下一步';
$lang->upgrade->redeploy      = '请重新部署app文件夹后继续';
$lang->upgrade->redeployDesc  = "<h5>因为代码结构调整,需要重新部署app目录。</h5><div class='text-important'>操作方法:删除旧的app目录，再从新的安装包里面复制app文件夹。</div>";
$lang->upgrade->removeTodo    = '请删除 %s 文件夹后继续';
$lang->upgrade->removeTodoTip = "<h5>因为代码结构调整,需要删除%s目录。</h5><div class='text-important'>操作方法:删除旧的%s文件夹。</div>";
$lang->upgrade->updateLicense = '然之协同 2. 0 已更换授权协议至 Z PUBLIC LICENSE(ZPL) 1.1。';

$lang->upgrade->majorList['3_5'] = array();
$lang->upgrade->majorList['3_5']['1'] = '主营业务收入';
$lang->upgrade->majorList['3_5']['2'] = '非主营业务收入';
$lang->upgrade->majorList['3_5']['3'] = '主营业务成本';
$lang->upgrade->majorList['3_5']['4'] = '非主营业务成本';

$lang->upgrade->majorList['3_6'] = array();
$lang->upgrade->majorList['3_6']['5'] = '理财盈利';
$lang->upgrade->majorList['3_6']['6'] = '理财亏损';
$lang->upgrade->majorList['3_6']['7'] = '手续费';
$lang->upgrade->majorList['3_6']['8'] = '借贷利息';

$lang->upgrade->backupData = <<<EOT
<pre>
<strong>使用phpMyAdmin或者mysqldump命令备份数据库。</strong>
<code class='red'>$ mysqldump -u %s</span> -p%s %s > ranzhi.sql</code>
</pre>
EOT;

$lang->upgrade->versionNote = "务必选择正确的版本，否则会造成数据丢失。";

$lang->upgrade->fromVersions['1_0_beta'] = '1.0.beta';
$lang->upgrade->fromVersions['1_1_beta'] = '1.1.beta';
$lang->upgrade->fromVersions['1_2_beta'] = '1.2.beta';
$lang->upgrade->fromVersions['1_3_beta'] = '1.3.beta';
$lang->upgrade->fromVersions['1_4_beta'] = '1.4.beta';
$lang->upgrade->fromVersions['1_5_beta'] = '1.5.beta';
$lang->upgrade->fromVersions['1_6']      = '1.6';
$lang->upgrade->fromVersions['1_7']      = '1.7';
$lang->upgrade->fromVersions['2_0']      = '2.0';
$lang->upgrade->fromVersions['2_1']      = '2.1';
$lang->upgrade->fromVersions['2_2']      = '2.2';
$lang->upgrade->fromVersions['2_3']      = '2.3';
$lang->upgrade->fromVersions['2_4']      = '2.4';
$lang->upgrade->fromVersions['2_5']      = '2.5';
$lang->upgrade->fromVersions['2_6']      = '2.6';
$lang->upgrade->fromVersions['2_7']      = '2.7';
$lang->upgrade->fromVersions['3_0']      = '3.0';
$lang->upgrade->fromVersions['3_1']      = '3.1';
$lang->upgrade->fromVersions['3_2']      = '3.2';
$lang->upgrade->fromVersions['3_2_1']    = '3.2.1';
$lang->upgrade->fromVersions['3_3']      = '3.3';
$lang->upgrade->fromVersions['3_4']      = '3.4';
$lang->upgrade->fromVersions['3_5']      = '3.5';
$lang->upgrade->fromVersions['3_6']      = '3.6';
$lang->upgrade->fromVersions['3_7']      = '3.7';
$lang->upgrade->fromVersions['4_0']      = '4.0';
$lang->upgrade->fromVersions['4_1']      = '4.1';

$lang->upgrade->deleteTips   = '需要删除部分文件。linux下面命令为：<br />';
$lang->upgrade->deleteDir    = '<code>rm -fr %s</code>';
$lang->upgrade->deleteFile   = '<code>rm %s</code>';
$lang->upgrade->afterDeleted = '<br />删除以上文件后刷新！';
