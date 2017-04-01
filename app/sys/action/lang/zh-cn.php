<?php
/**
 * The lang file of zh-cn module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     action
 * @version     $Id: zh-cn.php 4955 2013-07-02 01:47:21Z chencongzhi520@gmail.com $
 * @link        http://www.ranzhico.com
 */
if(!isset($lang->action)) $lang->action = new stdclass();

$lang->action->common   = '系统日志';
$lang->action->product  = '产品';
$lang->action->actor    = '操作者';
$lang->action->contact  = '联系人';
$lang->action->comment  = '内容';
$lang->action->action   = '动作';
$lang->action->actionID = '记录ID';
$lang->action->date     = '日期';
$lang->action->nextDate = '下次联系';

$lang->action->trash      = '回收站';
$lang->action->objectType = '对象类型';
$lang->action->objectID   = '对象ID';
$lang->action->objectName = '对象名称';

$lang->action->createContact = '新建';
$lang->action->editComment   = '修改备注';
$lang->action->hide          = '隐藏';       
$lang->action->hideOne       = '隐藏';
$lang->action->hideAll       = '隐藏全部';
$lang->action->hidden        = '已隐藏';
$lang->action->undelete      = '还原';
$lang->action->trashTips     = '提示：为了保证系统的完整性，然之系统的删除都是标记删除。';

$lang->action->textDiff = '文本格式';
$lang->action->original = '原始格式';

/* 用来描述操作历史记录。*/
$lang->action->desc = new stdclass();
$lang->action->desc->common                = '$date, <strong>$action</strong> by <strong>$actor</strong>。' . "\n";
$lang->action->desc->extra                 = '$date, <strong>$action</strong> as <strong>$extra</strong> by <strong>$actor</strong>。' . "\n";
$lang->action->desc->opened                = '$date, 由 <strong>$actor</strong> 创建。' . "\n";
$lang->action->desc->created               = '$date, 由 <strong>$actor</strong> 创建。' . "\n";
$lang->action->desc->edited                = '$date, 由 <strong>$actor</strong> 编辑。' . "\n";
$lang->action->desc->assigned              = '$date, 由 <strong>$actor</strong> 指派给 <strong>$extra</strong>。' . "\n";
$lang->action->desc->merged                = '$date, 由 <strong>$actor</strong> 合并客户 <strong>$extra</strong>。' . "\n";
$lang->action->desc->transmit              = '$date, 由 <strong>$actor</strong> 转交给 <strong>$extra</strong>。' . "\n";
$lang->action->desc->closed                = '$date, 由 <strong>$actor</strong> 关闭，关闭原因:<strong>$extra</strong>。' . "\n";
$lang->action->desc->deleted               = '$date, 由 <strong>$actor</strong> 删除。' . "\n";
$lang->action->desc->deletedfile           = '$date, 由 <strong>$actor</strong> 删除了附件：<strong><i>$extra</i></strong>。' . "\n";
$lang->action->desc->editfile              = '$date, 由 <strong>$actor</strong> 编辑了附件：<strong><i>$extra</i></strong>。' . "\n";
$lang->action->desc->erased                = '$date, 由 <strong>$actor</strong> 删除。' . "\n";
$lang->action->desc->commented             = '$date, 由 <strong>$actor</strong> 添加备注。' . "\n";
$lang->action->desc->activated             = '$date, 由 <strong>$actor</strong> 激活。' . "\n";
$lang->action->desc->moved                 = '$date, 由 <strong>$actor</strong> 移动，之前为 "$extra"。' . "\n";
$lang->action->desc->started               = '$date, 由 <strong>$actor</strong> 启动。' . "\n";
$lang->action->desc->delayed               = '$date, 由 <strong>$actor</strong> 延期。' . "\n";
$lang->action->desc->suspended             = '$date, 由 <strong>$actor</strong> 挂起。' . "\n";
$lang->action->desc->canceled              = '$date, 由 <strong>$actor</strong> 取消。' . "\n";
$lang->action->desc->finished              = '$date, 由 <strong>$actor</strong> 完成。' . "\n";
$lang->action->desc->replied               = '$date, 由 <strong>$actor</strong> 回复。' . "\n";
$lang->action->desc->doubted               = '$date, 由 <strong>$actor</strong> 追问。' . "\n";
$lang->action->desc->transfered            = '$date, 由 <strong>$actor</strong> 转交。' . "\n";
$lang->action->desc->reviewed              = '$date, 由 <strong>$actor</strong> 审核 $extra。' . "\n";
$lang->action->desc->reimburse             = '$date, 由 <strong>$actor</strong> 报销 $extra。' . "\n";
$lang->action->desc->revoked               = '$date, 由 <strong>$actor</strong> 撤销。' . "\n";
$lang->action->desc->commited              = '$date, 由 <strong>$actor</strong> 提交' . "\n";
$lang->action->desc->returned              = '$date, 由 <strong>$actor</strong> 回款$extra。' . "\n";
$lang->action->desc->editreturned          = '$date, 由 <strong>$actor</strong> 编辑回款。' . "\n";
$lang->action->desc->deletereturned        = '$date, 由 <strong>$actor</strong> 删除$extra。' . "\n";
$lang->action->desc->finishreturned        = '$date, 由 <strong>$actor</strong> 回款$extra，回款完成。' . "\n";
$lang->action->desc->delivered             = '$date, 由 <strong>$actor</strong> 交付。' . "\n";
$lang->action->desc->editdelivered         = '$date, 由 <strong>$actor</strong> 编辑交付。' . "\n";
$lang->action->desc->deletedelivered       = '$date, 由 <strong>$actor</strong> 删除$extra。' . "\n";
$lang->action->desc->finishdelivered       = '$date, 由 <strong>$actor</strong> 完成交付。' . "\n";
$lang->action->desc->createdresume         = '$date, 由 <strong>$actor</strong> 添加履历：<strong>$extra</strong>。' . "\n";
$lang->action->desc->editedresume          = '$date, 由 <strong>$actor</strong> 修改履历。' . "\n";
$lang->action->desc->deleteresume          = '$date, 由 <strong>$actor</strong> 删除履历：<strong>$extra</strong>。' . "\n";
$lang->action->desc->createaddress         = '$date, 由 <strong>$actor</strong> 添加地址：<strong>$extra</strong>。' . "\n";
$lang->action->desc->editaddress           = '$date, 由 <strong>$actor</strong> 修改地址。' . "\n";
$lang->action->desc->deleteaddress         = '$date, 由 <strong>$actor</strong> 删除地址：<strong>$extra</strong>。' . "\n";
$lang->action->desc->diff1                 = '修改了 <strong><i>%s</i></strong>，旧值为 "%s"，新值为 "%s"。<br />' . "\n";
$lang->action->desc->diff2                 = '修改了 <strong><i>%s</i></strong>，区别为：' . "\n" . "<blockquote>%s</blockquote>" . "\n<div class='hidden'>%s</div>";
$lang->action->desc->diff3                 = "将文件名 %s 改为 %s 。\n";
$lang->action->desc->record                = '$date, <strong>$actor</strong> 添加了沟通日志，联系人：<strong>$contact</strong>，联系时间：$extra。' . "\n";
$lang->action->desc->signed                = '$date, 由 <strong>$actor</strong> 签约，成交金额：<strong>$extra</strong>。' . "\n";
$lang->action->desc->linkcontact           = '$date, 由 <strong>$actor</strong> 添加联系人：<strong>$extra</strong>。' . "\n";
$lang->action->desc->createorder           = '$date, 由 <strong>$actor</strong> 创建订单：<strong>$extra</strong>。' . "\n";
$lang->action->desc->editorder             = '$date, 由 <strong>$actor</strong> 编辑订单：<strong>$extra</strong>。' . "\n";
$lang->action->desc->assignorder           = '$date, 由 <strong>$actor</strong> 指派订单：<strong>$extra</strong>。' . "\n";
$lang->action->desc->closeorder            = '$date, 由 <strong>$actor</strong> 关闭订单 <strong>$extra</strong>。' . "\n";
$lang->action->desc->activateorder         = '$date, 由 <strong>$actor</strong> 激活订单：<strong>$extra</strong>。' . "\n";
$lang->action->desc->createcontract        = '$date, 由 <strong>$actor</strong> 创建合同：<strong>$extra</strong>。' . "\n";
$lang->action->desc->editcontract          = '$date, 由 <strong>$actor</strong> 编辑合同：<strong>$extra</strong>。' . "\n";
$lang->action->desc->delivercontract       = '$date, 由 <strong>$actor</strong> 对合同<strong>$extra</strong>进行交付。' . "\n";
$lang->action->desc->receivecontract       = '$date, 由 <strong>$actor</strong> 对合同<strong>$extra</strong>。' . "\n";
$lang->action->desc->finishdelivercontract = '$date, 由 <strong>$actor</strong> 完成合同<strong>$extra</strong>的交付。' . "\n";
$lang->action->desc->finishreceivecontract = '$date, 由 <strong>$actor</strong> 对合同<strong>$extra</strong>，完成回款。' . "\n";
$lang->action->desc->finishcontract        = '$date, 由 <strong>$actor</strong> 完成合同：<strong>$extra</strong>。' . "\n";
$lang->action->desc->cancelcontract        = '$date, 由 <strong>$actor</strong> 取消合同：<strong>$extra</strong>。' . "\n";
$lang->action->desc->hidden                = '$date, 由 <strong>$actor</strong> 隐藏。' . "\n";
$lang->action->desc->undeleted             = '$date, 由 <strong>$actor</strong> 还原。' . "\n";
$lang->action->desc->transform             = '$date, 由 <strong>$actor</strong> 转换为联系人。' . "\n";
$lang->action->desc->ignored               = '$date, 由 <strong>$actor</strong> 忽略。' . "\n";
$lang->action->desc->createtrip            = '$date, 由 <strong>$actor</strong> 创建出差：<strong>$extra</strong>。' . "\n";
$lang->action->desc->createegress          = '$date, 由 <strong>$actor</strong> 创建外出：<strong>$extra</strong>。' . "\n";

/* 用来显示动态信息。*/
$lang->action->label = new stdclass();
$lang->action->label->created     = '创建了';
$lang->action->label->edited      = '编辑了';
$lang->action->label->assigned    = '指派了';
$lang->action->label->transmit    = '转交了';
$lang->action->label->closed      = '关闭了';
$lang->action->label->deleted     = '删除了';
$lang->action->label->erased      = '删除了';
$lang->action->label->undeleted   = '还原了';
$lang->action->label->deletedfile = '删除附件';
$lang->action->label->editfile    = '编辑附件';
$lang->action->label->commented   = '备注了';
$lang->action->label->activated   = '激活了';
$lang->action->label->resolved    = '解决了';
$lang->action->label->reviewed    = '评审了';
$lang->action->label->moved       = '移动了';
$lang->action->label->marked      = '编辑了';
$lang->action->label->started     = '开始了';
$lang->action->label->canceled    = '取消了';
$lang->action->label->finished    = '完成了';
$lang->action->label->reimbursed  = '报销了';
$lang->action->label->record      = '沟通了';
$lang->action->label->signed      = '签约了';
$lang->action->label->commited    = '提交了';
$lang->action->label->revoked     = '撤销了';
$lang->action->label->forbidden   = '禁用了';
$lang->action->label->transform   = '转换了';
$lang->action->label->ignore      = '忽略了';
$lang->action->label->login       = '登录系统';
$lang->action->label->logout      = "退出登录";

$lang->action->label->createdbalance        = '登记余额';
$lang->action->label->createorder           = '创建订单';
$lang->action->label->editorder             = '编辑了订单';
$lang->action->label->activateorder         = '激活订单';
$lang->action->label->closeorder            = '关闭订单';
$lang->action->label->linkcontact           = '关联联系人';
$lang->action->label->createcontract        = '创建合同';
$lang->action->label->editcontract          = '编辑合同';
$lang->action->label->cancelcontract        = '取消合同';
$lang->action->label->finishcontract        = '完成合同';
$lang->action->label->createdresume         = '创建履历';
$lang->action->label->editedresume          = '编辑履历';
$lang->action->label->deleteresume          = '删除履历';
$lang->action->label->createaddress         = '创建地址';
$lang->action->label->editaddress           = '编辑地址';
$lang->action->label->deleteaddress         = '删除地址';
$lang->action->label->finishdelivered       = '完成交付';
$lang->action->label->finishdelivercontract = '完成交付';
$lang->action->label->delivered             = '交付';
$lang->action->label->delivercontract       = '交付';
$lang->action->label->returned              = '回款';
$lang->action->label->receivecontract       = '回款';
$lang->action->label->finishreceivecontract = '完成回款';
$lang->action->label->finishreturned        = '完成回款';
$lang->action->label->deletereturned        = '删除回款';

/* 用来做动态搜索中显示动作 */
$lang->action->search = new stdclass();
$lang->action->search->label = (array)$lang->action->label;

/* 用来生成相应对象的链接。*/
$lang->action->label->announce  = '公告|announce|view|announceID=%s';
$lang->action->label->balance   = '余额|balance|browse|depositorID=%s';
$lang->action->label->doc       = '文档|doc|view|docID=%s';
$lang->action->label->doclib    = '文档库|doc|browse|doclibID=%s';
$lang->action->label->contact   = '联系人|contact|view|contactID=%s';
$lang->action->label->contract  = '合同|contract|view|contractID=%s';
$lang->action->label->customer  = '客户|customer|view|customerID=%s';
$lang->action->label->depositor = '账户|depositor|browse|';
$lang->action->label->holiday   = '放假安排|holiday|browse|';
$lang->action->label->order     = '订单|order|view|orderID=%s';
$lang->action->label->product   = '产品|product|view|productID=%s';
$lang->action->label->project   = '项目|task|browse|projectID=%s';
$lang->action->label->provider  = '供应商|provider|view|providerID=%s';
$lang->action->label->schema    = '记账模板|schema|browse|';
$lang->action->label->space     = '　';
$lang->action->label->task      = '任务|task|view|taskID=%s';
$lang->action->label->todo      = '待办|todo|calendar|';
$lang->action->label->trade     = '账目|trade|browse|';

$lang->action->label->attend = array();
$lang->action->label->attend['commited'] = '考勤审核|attend|browsereview|';
$lang->action->label->attend['reviewed'] = '考勤审核|attend|personal|';
$lang->action->label->leave = array();
$lang->action->label->leave['created']  = '请假审核|leave|browsereview|';
$lang->action->label->leave['commited'] = '请假审核|leave|browsereview|';
$lang->action->label->leave['revoked']  = '请假审核|leave|browsereview|';
$lang->action->label->leave['reviewed'] = '请假审核|leave|personal|';
$lang->action->label->lieu = array();
$lang->action->label->lieu['created']  = '调休审核|lieu|browsereview|';
$lang->action->label->lieu['commited'] = '调休审核|lieu|browsereview|';
$lang->action->label->lieu['revoked']  = '调休审核|lieu|browsereview|';
$lang->action->label->lieu['reviewed'] = '调休审核|lieu|personal|';
$lang->action->label->makeup = array();
$lang->action->label->makeup['created']  = '加班审核|makeup|browsereview|';
$lang->action->label->makeup['commited'] = '加班审核|makeup|browsereview|';
$lang->action->label->makeup['revoked']  = '加班审核|makeup|browsereview|';
$lang->action->label->makeup['reviewed'] = '加班审核|makeup|personal|';
$lang->action->label->overtime = array();
$lang->action->label->overtime['created']  = '加班审核|overtime|browsereview|';
$lang->action->label->overtime['commited'] = '加班审核|overtime|browsereview|';
$lang->action->label->overtime['revoked']  = '加班审核|overtime|browsereview|';
$lang->action->label->overtime['reviewed'] = '加班审核|overtime|personal|';
$lang->action->label->refund = array();
$lang->action->label->refund['commited']    = '报销审批|refund|browsereview|';
$lang->action->label->refund['revoked']     = '报销审批|refund|browsereview|';
$lang->action->label->refund['created']     = '报销审批|refund|view|refundID=%s';
$lang->action->label->refund['edited']      = '报销审批|refund|view|refundID=%s';
$lang->action->label->refund['reviewed']    = '报销审批|refund|view|refundID=%s';
$lang->action->label->refund['reimburse']   = '报销审批|refund|view|refundID=%s';
$lang->action->label->refund['deletedfile'] = '报销审批|refund|view|refundID=%s';
$lang->action->label->user = array();
$lang->action->label->user['login']  = '登录|user|login|';
$lang->action->label->user['logout'] = '退出|user|logout|';

$lang->action->nextContactList[1]      = '明天';
$lang->action->nextContactList[2]      = '后天';
$lang->action->nextContactList[3]      = '三天后';
$lang->action->nextContactList[7]      = '一周后';
$lang->action->nextContactList[14]     = '两周后';
$lang->action->nextContactList[365000] = '无需联系';

$lang->action->record = new stdclass();
$lang->action->record->common     = '沟通';
$lang->action->record->create     = '添加记录';
$lang->action->record->edit       = '编辑记录';
$lang->action->record->history    = '沟通记录';
$lang->action->record->customer   = '客户';
$lang->action->record->provider   = '供应商';
$lang->action->record->contract   = '合同';
$lang->action->record->order      = '订单';
$lang->action->record->contact    = '联系人';
$lang->action->record->actor      = '操作人';
$lang->action->record->comment    = '沟通内容';
$lang->action->record->date       = '时间';
$lang->action->record->file       = '附件';
$lang->action->record->nextDate   = '下次联系';
$lang->action->record->uploadFile = '上传附件:';

$lang->action->objectTypes['order']     = '订单';
$lang->action->objectTypes['customer']  = '客户';
$lang->action->objectTypes['provider']  = '供应商';
$lang->action->objectTypes['doc']       = '文档';
$lang->action->objectTypes['task']      = '任务';
$lang->action->objectTypes['product']   = '产品';
$lang->action->objectTypes['contact']   = '联系人';
$lang->action->objectTypes['contract']  = '合同';
$lang->action->objectTypes['project']   = '项目';
$lang->action->objectTypes['user']      = '用户';
$lang->action->objectTypes['resume']    = '履历';
$lang->action->objectTypes['leave']     = '请假';
$lang->action->objectTypes['lieu']      = '调休';
$lang->action->objectTypes['makeup']    = '补班';
$lang->action->objectTypes['overtime']  = '加班';
$lang->action->objectTypes['refund']    = '报销';
$lang->action->objectTypes['depositor'] = '账户';
$lang->action->objectTypes['balance']   = '余额';
$lang->action->objectTypes['todo']      = '待办';
$lang->action->objectTypes['announce']  = '公告';
$lang->action->objectTypes['holiday']   = '放假安排';
$lang->action->objectTypes['trade']     = '账目';
$lang->action->objectTypes['schema']    = '记账模板';
$lang->action->objectTypes['doclib']    = '文档库';
$lang->action->objectTypes['action']    = '沟通记录';

$lang->action->noticeTitle = "%s <a href='%s' data-appid='%s'>%s</a>";
