<?php
if(!isset($lang->overtime)) $lang->overtime = new stdclass();
$lang->overtime->common = '加班';
$lang->overtime->browse = '加班列表';
$lang->overtime->create = '申請';
$lang->overtime->edit   = '編輯';
$lang->overtime->delete = '刪除';
$lang->overtime->review = '審核';
$lang->overtime->cancel = '撤銷';
$lang->overtime->commit = '提交';

$lang->overtime->personal     = '我的加班';
$lang->overtime->browseReview = '審核列表';
$lang->overtime->company      = '所有加班';

$lang->overtime->id           = '編號';
$lang->overtime->begin        = '開始';
$lang->overtime->end          = '結束';
$lang->overtime->start        = '開始時間';
$lang->overtime->finish       = '結束時間';
$lang->overtime->hours        = '總時長';
$lang->overtime->type         = '類型';
$lang->overtime->desc         = '描述';
$lang->overtime->status       = '狀態';
$lang->overtime->createdBy    = '申請者';
$lang->overtime->createdDate  = '申請時間';
$lang->overtime->reviewedBy   = '審核者';
$lang->overtime->reviewedDate = '審核時間';
$lang->overtime->date         = '日期';
$lang->overtime->time         = '時間';

$lang->overtime->typeList['time']    = '超時加班';
$lang->overtime->typeList['rest']    = '休息日加班';
$lang->overtime->typeList['holiday'] = '節假日加班';
$lang->overtime->typeList['lieu']    = '調休';

$lang->overtime->statusList['draft']  = '草稿';
$lang->overtime->statusList['wait']   = '等待審核';
$lang->overtime->statusList['pass']   = '通過';
$lang->overtime->statusList['reject'] = '拒絶';

$lang->overtime->denied = '信息訪問受限';
$lang->overtime->unique = '%s 已經存在加班記錄';

$lang->overtime->confirmReview['pass']   = '您確定要執行通過操作嗎？';
$lang->overtime->confirmReview['reject'] = '您確定要執行拒絶操作嗎？';

$lang->overtime->hoursTip = '小時';
