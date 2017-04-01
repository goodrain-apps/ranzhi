<?php
if(!isset($lang->makeup)) $lang->makeup = new stdclass();
$lang->makeup->common = '补班';
$lang->makeup->browse = '补班列表';
$lang->makeup->create = '申请';
$lang->makeup->edit   = '编辑';
$lang->makeup->view   = '详情';
$lang->makeup->delete = '删除';
$lang->makeup->review = '审核';
$lang->makeup->cancel = '撤销';
$lang->makeup->commit = '提交';
$lang->makeup->export = '导出补班记录';

$lang->makeup->personal     = '我的补班';
$lang->makeup->browseReview = '审核列表';
$lang->makeup->company      = '所有补班';
$lang->makeup->setReviewer  = '设置审核者';

$lang->makeup->id           = '编号';
$lang->makeup->year         = '年';
$lang->makeup->begin        = '开始';
$lang->makeup->end          = '结束';
$lang->makeup->start        = '开始时间';
$lang->makeup->finish       = '结束时间';
$lang->makeup->hours        = '总时长';
$lang->makeup->leave        = '请假记录';
$lang->makeup->type         = '类型';
$lang->makeup->desc         = '事由';
$lang->makeup->status       = '状态';
$lang->makeup->createdBy    = '申请者';
$lang->makeup->createdDate  = '申请时间';
$lang->makeup->reviewedBy   = '审核者';
$lang->makeup->reviewedDate = '审核时间';
$lang->makeup->date         = '日期';
$lang->makeup->time         = '时间';
$lang->makeup->rejectReason = '拒绝理由';

$lang->makeup->typeList['compensate'] = '补班';

$lang->makeup->statusList['draft']  = '草稿';
$lang->makeup->statusList['wait']   = '等待审核';
$lang->makeup->statusList['pass']   = '通过';
$lang->makeup->statusList['reject'] = '拒绝';

$lang->makeup->denied    = '信息访问受限';
$lang->makeup->unique    = '%s 已经存在补班记录';
$lang->makeup->sameMonth = '不支持跨月份补班';
$lang->makeup->wrongEnd  = '结束时间应该大于开始时间';

$lang->makeup->confirmReview['pass']   = '您确定要执行通过操作吗？';
$lang->makeup->confirmReview['reject'] = '您确定要执行拒绝操作吗？';

$lang->makeup->hoursTip = '小时';
$lang->makeup->baseInfo = '基本信息';
