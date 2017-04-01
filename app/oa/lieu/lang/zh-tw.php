<?php
if(!isset($lang->lieu)) $lang->lieu = new stdclass();
$lang->lieu->common = '調休';
$lang->lieu->browse = '調休列表';
$lang->lieu->create = '調休';
$lang->lieu->edit   = '編輯';
$lang->lieu->view   = '詳情';
$lang->lieu->delete = '刪除';
$lang->lieu->review = '審核';
$lang->lieu->cancel = '撤銷';
$lang->lieu->commit = '提交';

$lang->lieu->personal     = '我的調休';
$lang->lieu->browseReview = '審核列表';
$lang->lieu->company      = '所有調休';
$lang->lieu->setReviewer  = '設置審核者';

$lang->lieu->id           = '編號';
$lang->lieu->year         = '年';
$lang->lieu->begin        = '開始';
$lang->lieu->end          = '結束';
$lang->lieu->start        = '開始時間';
$lang->lieu->finish       = '結束時間';
$lang->lieu->hours        = '總時長';
$lang->lieu->overtime     = '加班記錄';
$lang->lieu->status       = '狀態';
$lang->lieu->desc         = '事由';
$lang->lieu->createdBy    = '申請者';
$lang->lieu->createdDate  = '申請時間';
$lang->lieu->reviewedBy   = '審核者';
$lang->lieu->reviewedDate = '審核時間';
$lang->lieu->date         = '日期';
$lang->lieu->time         = '時間';

$lang->lieu->statusList['draft']  = '草稿';
$lang->lieu->statusList['wait']   = '等待審核';
$lang->lieu->statusList['pass']   = '通過';
$lang->lieu->statusList['reject'] = '拒絶';

$lang->lieu->confirmReview['pass']   = '您確定要執行通過操作嗎？';
$lang->lieu->confirmReview['reject'] = '您確定要執行拒絶操作嗎？';

$lang->lieu->denied    = '信息訪問受限';
$lang->lieu->unique    = '%s 已經存在調休記錄';
$lang->lieu->sameMonth = '不支持跨月份調休';
$lang->lieu->wrongEnd  = '結束時間應該大於開始時間';
