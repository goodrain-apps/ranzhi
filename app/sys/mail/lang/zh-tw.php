<?php
/**
 * The zh-tw file of mail module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     mail 
 * @version     $Id: zh-tw.php 4029 2016-08-26 06:50:41Z liugang $
 * @link        http://www.ranzhico.com
 */
$lang->mail->common = '發信設置';
$lang->mail->index  = '首頁';
$lang->mail->detect = '檢測';
$lang->mail->edit   = '編輯配置';
$lang->mail->save   = '成功保存';
$lang->mail->test   = '測試發信';
$lang->mail->reset  = '重置';

$lang->mail->turnon      = '是否打開';
$lang->mail->fromAddress = '發信郵箱';
$lang->mail->fromName    = '發信人';
$lang->mail->mta         = '發信方式';
$lang->mail->host        = 'smtp伺服器';
$lang->mail->port        = 'smtp連接埠號';
$lang->mail->auth        = '是否需要驗證';
$lang->mail->username    = 'smtp帳號';
$lang->mail->password    = 'smtp密碼';
$lang->mail->secure      = '是否加密';
$lang->mail->debug       = '調試級別';

$lang->mail->turnonList[1]  = '打開';
$lang->mail->turnonList[0] = '關閉';

$lang->mail->debugList[0] = '關閉';
$lang->mail->debugList[1] = '一般';
$lang->mail->debugList[2] = '較高';

$lang->mail->authList[1]  = '需要';
$lang->mail->authList[0] = '不需要';

$lang->mail->secureList['']    = '不加密';
$lang->mail->secureList['ssl'] = 'ssl';
$lang->mail->secureList['tls'] = 'tls';

$lang->mail->inputFromEmail = '請輸入發信郵箱：';
$lang->mail->nextStep       = '下一步';
$lang->mail->successSaved   = '配置信息已經成功保存。';
$lang->mail->subject        = '測試郵件';
$lang->mail->content        = '郵箱設置成功';
$lang->mail->successSended  = '成功發送！';
$lang->mail->needConfigure  = '無法找到郵件配置信息，請先配置郵件發送參數。';

$lang->mail->mailContentTip = <<<EOT
<strong>%s</strong>(%s)由<a href='https://www.ranzhico.com' target='blank'>然之協同管理系統</a>搭建。<br />
<a href='http://www.cnezsoft.com' target='blank'>易軟天創</a>為天下企業提供專業的管理工具。
EOT;
$lang->mail->openTip = '訂單、客戶、任務指派，請假、報銷審批時會進行郵件提醒';
