<?php
if(!isset($lang->lieu)) $lang->lieu = new stdclass();
$lang->lieu->common = '调休';
$lang->lieu->browse = '调休列表';
$lang->lieu->create = '调休';
$lang->lieu->edit   = '编辑';
$lang->lieu->view   = '详情';
$lang->lieu->delete = '删除';
$lang->lieu->review = '审核';
$lang->lieu->cancel = '撤销';
$lang->lieu->commit = '提交';

$lang->lieu->personal     = '我的调休';
$lang->lieu->browseReview = '审核列表';
$lang->lieu->company      = '所有调休';
$lang->lieu->setReviewer  = '设置审核者';

$lang->lieu->id           = '编号';
$lang->lieu->year         = '年';
$lang->lieu->begin        = '开始';
$lang->lieu->end          = '结束';
$lang->lieu->start        = '开始时间';
$lang->lieu->finish       = '结束时间';
$lang->lieu->hours        = '总时长';
$lang->lieu->overtime     = '加班记录';
$lang->lieu->status       = '状态';
$lang->lieu->desc         = '事由';
$lang->lieu->createdBy    = '申请者';
$lang->lieu->createdDate  = '申请时间';
$lang->lieu->reviewedBy   = '审核者';
$lang->lieu->reviewedDate = '审核时间';
$lang->lieu->date         = '日期';
$lang->lieu->time         = '时间';

$lang->lieu->statusList['draft']  = '草稿';
$lang->lieu->statusList['wait']   = '等待审核';
$lang->lieu->statusList['pass']   = '通过';
$lang->lieu->statusList['reject'] = '拒绝';

$lang->lieu->confirmReview['pass']   = '您确定要执行通过操作吗？';
$lang->lieu->confirmReview['reject'] = '您确定要执行拒绝操作吗？';

$lang->lieu->denied    = '信息访问受限';
$lang->lieu->unique    = '%s 已经存在调休记录';
$lang->lieu->sameMonth = '不支持跨月份调休';
$lang->lieu->wrongEnd  = '结束时间应该大于开始时间';
