<?php
if(!isset($lang->leads)) $lang->leads = new stdclass();

$lang->leads->common    = '名单';
$lang->leads->browse    = '浏览名单';
$lang->leads->create    = '添加名单';
$lang->leads->edit      = '编辑名单';
$lang->leads->delete    = '删除名单';
$lang->leads->view      = '名单详情';
$lang->leads->apply     = '申请';
$lang->leads->assign    = '指派';
$lang->leads->transform = '确认';
$lang->leads->ignore    = '忽略';
$lang->leads->settings  = '设置';
$lang->leads->applyRule = '派发规则';

$lang->leads->list = '名单列表';

$lang->leads->applyLimit   = '每次申请记录数';
$lang->leads->applyRemain  = '最多未处理记录数';
$lang->leads->ignoreReason = '原因';

$lang->leads->tips = new stdclass();
$lang->leads->tips->apply       = '请先处理现有的名单联系人。';
$lang->leads->tips->applyRemain = '未处理的名单数低于此值才可以再次申请';

$lang->leads->applyLimitList['10']  = 10;
$lang->leads->applyLimitList['20']  = 20;
$lang->leads->applyLimitList['30']  = 30;
$lang->leads->applyLimitList['40']  = 40;
$lang->leads->applyLimitList['50']  = 50;
$lang->leads->applyLimitList['60']  = 60;
$lang->leads->applyLimitList['70']  = 70;
$lang->leads->applyLimitList['80']  = 80;
$lang->leads->applyLimitList['90']  = 90;
$lang->leads->applyLimitList['100'] = 100;

$lang->leads->applyRemainList['5']   = 5;
$lang->leads->applyRemainList['10']  = 10;
$lang->leads->applyRemainList['15']  = 15;
$lang->leads->applyRemainList['20']  = 20;
$lang->leads->applyRemainList['25']  = 25;
$lang->leads->applyRemainList['30']  = 30;
