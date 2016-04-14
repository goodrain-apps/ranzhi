<?php
if(!isset($lang->overtime)) $lang->overtime = new stdclass();
$lang->overtime->common = '加班';
$lang->overtime->browse = '加班列表';
$lang->overtime->create = '申请';
$lang->overtime->edit   = '编辑';
$lang->overtime->delete = '删除';
$lang->overtime->review = '审核';
$lang->overtime->cancel = '撤销';
$lang->overtime->commit = '提交';

$lang->overtime->personal     = '我的加班';
$lang->overtime->browseReview = '审核列表';
$lang->overtime->company      = '所有加班';

$lang->overtime->id           = '编号';
$lang->overtime->begin        = '开始';
$lang->overtime->end          = '结束';
$lang->overtime->start        = '开始时间';
$lang->overtime->finish       = '结束时间';
$lang->overtime->hours        = '总时长';
$lang->overtime->type         = '类型';
$lang->overtime->desc         = '描述';
$lang->overtime->status       = '状态';
$lang->overtime->createdBy    = '申请者';
$lang->overtime->createdDate  = '申请时间';
$lang->overtime->reviewedBy   = '审核者';
$lang->overtime->reviewedDate = '审核时间';
$lang->overtime->date         = '日期';
$lang->overtime->time         = '时间';

$lang->overtime->typeList['time']    = '超时加班';
$lang->overtime->typeList['rest']    = '休息日加班';
$lang->overtime->typeList['holiday'] = '节假日加班';
$lang->overtime->typeList['lieu']    = '调休';

$lang->overtime->statusList['draft']  = '草稿';
$lang->overtime->statusList['wait']   = '等待审核';
$lang->overtime->statusList['pass']   = '通过';
$lang->overtime->statusList['reject'] = '拒绝';

$lang->overtime->denied = '信息访问受限';
$lang->overtime->unique = '%s 已经存在加班记录';

$lang->overtime->confirmReview['pass']   = '您确定要执行通过操作吗？';
$lang->overtime->confirmReview['reject'] = '您确定要执行拒绝操作吗？';

$lang->overtime->hoursTip = '小时';
