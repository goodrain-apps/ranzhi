<?php
if(!isset($lang->makeup)) $lang->makeup = new stdclass();
$lang->makeup->common = '補班';
$lang->makeup->browse = '補班列表';
$lang->makeup->create = '申請';
$lang->makeup->edit   = '編輯';
$lang->makeup->view   = '詳情';
$lang->makeup->delete = '刪除';
$lang->makeup->review = '審核';
$lang->makeup->cancel = '撤銷';
$lang->makeup->commit = '提交';
$lang->makeup->export = '導出補班記錄';

$lang->makeup->personal     = '我的補班';
$lang->makeup->browseReview = '審核列表';
$lang->makeup->company      = '所有補班';
$lang->makeup->setReviewer  = '設置審核者';

$lang->makeup->id           = '編號';
$lang->makeup->year         = '年';
$lang->makeup->begin        = '開始';
$lang->makeup->end          = '結束';
$lang->makeup->start        = '開始時間';
$lang->makeup->finish       = '結束時間';
$lang->makeup->hours        = '總時長';
$lang->makeup->leave        = '請假記錄';
$lang->makeup->type         = '類型';
$lang->makeup->desc         = '事由';
$lang->makeup->status       = '狀態';
$lang->makeup->createdBy    = '申請者';
$lang->makeup->createdDate  = '申請時間';
$lang->makeup->reviewedBy   = '審核者';
$lang->makeup->reviewedDate = '審核時間';
$lang->makeup->date         = '日期';
$lang->makeup->time         = '時間';
$lang->makeup->rejectReason = '拒絶理由';

$lang->makeup->typeList['compensate'] = '補班';

$lang->makeup->statusList['draft']  = '草稿';
$lang->makeup->statusList['wait']   = '等待審核';
$lang->makeup->statusList['pass']   = '通過';
$lang->makeup->statusList['reject'] = '拒絶';

$lang->makeup->denied    = '信息訪問受限';
$lang->makeup->unique    = '%s 已經存在補班記錄';
$lang->makeup->sameMonth = '不支持跨月份補班';
$lang->makeup->wrongEnd  = '結束時間應該大於開始時間';

$lang->makeup->confirmReview['pass']   = '您確定要執行通過操作嗎？';
$lang->makeup->confirmReview['reject'] = '您確定要執行拒絶操作嗎？';

$lang->makeup->hoursTip = '小時';
$lang->makeup->baseInfo = '基本信息';
