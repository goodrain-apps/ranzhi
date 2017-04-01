<?php
/**
 * The todo module zh-cn file of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: zh-cn.php 5022 2013-07-05 06:50:39Z chencongzhi520@gmail.com $
 * @link        http://www.ranzhico.com
 */
if(!isset($lang->todo)) $lang->todo = new stdclass();
$lang->todo->common       = '待办';
$lang->todo->index        = "待办一览";
$lang->todo->browse       = "待办列表";
$lang->todo->create       = "新增待办";
$lang->todo->batchCreate  = "批量添加";
$lang->todo->edit         = "更新待办";
$lang->todo->batchEdit    = "批量编辑";
$lang->todo->view         = "待办详情";
$lang->todo->viewAB       = "详情";
$lang->todo->finish       = "完成";
$lang->todo->batchFinish  = "批量完成";
$lang->todo->export       = "导出";
$lang->todo->delete       = "删除待办";
$lang->todo->browse       = "浏览待办";
$lang->todo->import       = "移动";
$lang->todo->changeStatus = "更改";
$lang->todo->legendBasic  = "基本信息";
$lang->todo->calendar     = "日历";
$lang->todo->assignTo     = "指派";

$lang->todo->id           = '编号';
$lang->todo->account      = '所有者';
$lang->todo->date         = '日期';
$lang->todo->begin        = '开始时间';
$lang->todo->beginAB      = '开始';
$lang->todo->end          = '结束时间';
$lang->todo->endAB        = '结束';
$lang->todo->beginAndEnd  = '起止时间';
$lang->todo->type         = '类型';
$lang->todo->pri          = '优先级';
$lang->todo->name         = '名称';
$lang->todo->status       = '状态';
$lang->todo->desc         = '描述';
$lang->todo->private      = '私人事务';
$lang->todo->idvalue      = '任务或订单';
$lang->todo->assignedTo   = '指派给';
$lang->todo->assignedBy   = '由谁指派';
$lang->todo->assignedDate = '指派时间';
$lang->todo->finishedBy   = '完成者';
$lang->todo->finishedDate = '完成时间';
$lang->todo->closedBy     = '关闭者';
$lang->todo->closedDate   = '关闭时间';
$lang->todo->ranzhi       = '然之';
$lang->todo->task         = '任务';
$lang->todo->bug          = 'Bug';

$lang->todo->confirmTip  = '该Todo关联的是%s #%s，需要修改它吗？';
$lang->todo->assignedTip = '%s 于 %s';
$lang->todo->finishedTip = '%s 于 %s';
$lang->todo->closedTip   = '%s 于 %s';

$lang->todo->statusList['wait']     = '未开始';
$lang->todo->statusList['doing']    = '进行中';
$lang->todo->statusList['done']     = '已完成';
$lang->todo->statusList['closed']   = '已关闭';
//$lang->todo->statusList['cancel']   = '已取消';
//$lang->todo->statusList['postpone'] = '已延期';

$lang->todo->priList[3] = '一般';
$lang->todo->priList[1] = '最高';
$lang->todo->priList[2] = '较高';
$lang->todo->priList[4] = '最低';

$lang->todo->typeList['custom']   = '自定义';
$lang->todo->typeList['task']     = '项目任务';
$lang->todo->typeList['order']    = '订单沟通';
$lang->todo->typeList['customer'] = '客户沟通';

$lang->todo->confirmDelete  = "您确定要删除这条待办吗？";
$lang->todo->successMarked  = "成功切换状态！";
$lang->todo->thisIsPrivate  = '这是一条私人事务。:)';
$lang->todo->lblDisableDate = '暂时不设定时间';
$lang->todo->emptyTodo      = '您今天还没有添加待办。';

$lang->todo->periods['today']      = '今日';
$lang->todo->periods['yesterday']  = '昨日';
$lang->todo->periods['thisWeek']   = '本周';
$lang->todo->periods['lastWeek']   = '上周';
$lang->todo->periods['thisMonth']  = '本月';
$lang->todo->periods['lastmonth']  = '上月';
$lang->todo->periods['thisSeason'] = '本季';
$lang->todo->periods['thisYear']   = '本年';
$lang->todo->periods['future']     = '待定';
$lang->todo->periods['before']     = '未完';
$lang->todo->periods['all']        = '所有';

$lang->todo->action = new stdclass();
$lang->todo->action->finished  = array('main' => '$date, 由 <strong>$actor</strong>完成');
$lang->todo->action->marked    = array('main' => '$date, 由 <strong>$actor</strong> 标记为<strong>$extra</strong>。', 'extra' => 'statusList');
