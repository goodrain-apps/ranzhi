<?php
global $app;
$app->loadLang('trip', 'oa');
$menu = isset($lang->egress->menu) ? $lang->egress->menu : '';
$lang->egress = clone $lang->trip;
$lang->egress->menu = $menu;

$lang->egress->common   = '外出';
$lang->egress->browse   = '外出列表';
$lang->egress->personal = '我的外出';
$lang->egress->view     = '外出详情';

$lang->egress->from = '出发地';
$lang->egress->to   = '目的地';

$lang->egress->unique    = '%s 已经存在外出记录';
$lang->egress->sameMonth = '不支持跨月份外出';
