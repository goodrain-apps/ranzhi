<?php
if(!isset($lang->leave)) $lang->leave = new stdclass();
$lang->leave->common     = '請假';
$lang->leave->browse     = '請假列表';
$lang->leave->view       = '詳情';
$lang->leave->create     = '請假';
$lang->leave->edit       = '編輯';
$lang->leave->delete     = '刪除';
$lang->leave->review     = '審核';
$lang->leave->cancel     = '撤銷';
$lang->leave->commit     = '提交';
$lang->leave->back       = '銷假';
$lang->leave->export     = '導出請假記錄';
$lang->leave->reviewBack = '審核銷假';

$lang->leave->personal     = '我的請假';
$lang->leave->browseReview = '審核列表';
$lang->leave->company      = '所有請假';
$lang->leave->setReviewer  = '設置審核者';

$lang->leave->id           = '編號';
$lang->leave->year         = '年';
$lang->leave->begin        = '開始';
$lang->leave->end          = '結束';
$lang->leave->start        = '開始時間';
$lang->leave->finish       = '結束時間';
$lang->leave->hours        = '總時長';
$lang->leave->backDate     = '報到時間';
$lang->leave->type         = '類型';
$lang->leave->desc         = '事由';
$lang->leave->status       = '狀態';
$lang->leave->createdBy    = '申請者';
$lang->leave->createdDate  = '申請時間';
$lang->leave->reviewedBy   = '審核者';
$lang->leave->reviewedDate = '審核時間';
$lang->leave->date         = '日期';
$lang->leave->time         = '時間';

$lang->leave->typeList['affairs']   = '事假';
$lang->leave->typeList['sick']      = '病假';
$lang->leave->typeList['annual']    = '年假';
$lang->leave->typeList['lieu']      = '調休';
$lang->leave->typeList['home']      = '探親假';
$lang->leave->typeList['marry']     = '婚假';
$lang->leave->typeList['maternity'] = '產假';

$lang->leave->paid   = '帶薪假';
$lang->leave->unpaid = '非帶薪假';

$lang->leave->statusList['draft']  = '草稿';
$lang->leave->statusList['wait']   = '等待審核';
$lang->leave->statusList['pass']   = '通過';
$lang->leave->statusList['reject'] = '拒絶';
$lang->leave->statusList['back']   = '銷假待審';

$lang->leave->denied        = '信息訪問受限';
$lang->leave->unique        = '%s 已經存在請假記錄';
$lang->leave->sameMonth     = '不支持跨月份請假';
$lang->leave->wrongEnd      = '結束時間應該大於開始時間';
$lang->leave->wrongBackDate = '報到時間應該大於開始時間';

$lang->leave->confirmReview['pass']   = '您確定要執行通過操作嗎？';
$lang->leave->confirmReview['reject'] = '您確定要執行拒絶操作嗎？';

$lang->leave->hoursTip = '小時';
