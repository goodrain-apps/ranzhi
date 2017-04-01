<?php
/**
 * The lang file of zh-tw module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     action
 * @version     $Id: zh-tw.php 4955 2013-07-02 01:47:21Z chencongzhi520@gmail.com $
 * @link        http://www.ranzhico.com
 */
if(!isset($lang->action)) $lang->action = new stdclass();

$lang->action->common   = '系統日誌';
$lang->action->product  = '產品';
$lang->action->actor    = '操作者';
$lang->action->contact  = '聯繫人';
$lang->action->comment  = '內容';
$lang->action->action   = '動作';
$lang->action->actionID = '記錄ID';
$lang->action->date     = '日期';
$lang->action->nextDate = '下次聯繫';

$lang->action->trash      = '資源回收筒';
$lang->action->objectType = '對象類型';
$lang->action->objectID   = '對象ID';
$lang->action->objectName = '對象名稱';

$lang->action->createContact = '新建';
$lang->action->editComment   = '修改備註';
$lang->action->hide          = '隱藏';       
$lang->action->hideOne       = '隱藏';
$lang->action->hideAll       = '隱藏全部';
$lang->action->hidden        = '已隱藏';
$lang->action->undelete      = '還原';
$lang->action->trashTips     = '提示：為了保證系統的完整性，然之系統的刪除都是標記刪除。';

$lang->action->textDiff = '文本格式';
$lang->action->original = '原始格式';

/* 用來描述操作歷史記錄。*/
$lang->action->desc = new stdclass();
$lang->action->desc->common                = '$date, <strong>$action</strong> by <strong>$actor</strong>。' . "\n";
$lang->action->desc->extra                 = '$date, <strong>$action</strong> as <strong>$extra</strong> by <strong>$actor</strong>。' . "\n";
$lang->action->desc->opened                = '$date, 由 <strong>$actor</strong> 創建。' . "\n";
$lang->action->desc->created               = '$date, 由 <strong>$actor</strong> 創建。' . "\n";
$lang->action->desc->edited                = '$date, 由 <strong>$actor</strong> 編輯。' . "\n";
$lang->action->desc->assigned              = '$date, 由 <strong>$actor</strong> 指派給 <strong>$extra</strong>。' . "\n";
$lang->action->desc->merged                = '$date, 由 <strong>$actor</strong> 合併客戶 <strong>$extra</strong>。' . "\n";
$lang->action->desc->transmit              = '$date, 由 <strong>$actor</strong> 轉交給 <strong>$extra</strong>。' . "\n";
$lang->action->desc->closed                = '$date, 由 <strong>$actor</strong> 關閉，關閉原因:<strong>$extra</strong>。' . "\n";
$lang->action->desc->deleted               = '$date, 由 <strong>$actor</strong> 刪除。' . "\n";
$lang->action->desc->deletedfile           = '$date, 由 <strong>$actor</strong> 刪除了附件：<strong><i>$extra</i></strong>。' . "\n";
$lang->action->desc->editfile              = '$date, 由 <strong>$actor</strong> 編輯了附件：<strong><i>$extra</i></strong>。' . "\n";
$lang->action->desc->erased                = '$date, 由 <strong>$actor</strong> 刪除。' . "\n";
$lang->action->desc->commented             = '$date, 由 <strong>$actor</strong> 添加備註。' . "\n";
$lang->action->desc->activated             = '$date, 由 <strong>$actor</strong> 激活。' . "\n";
$lang->action->desc->moved                 = '$date, 由 <strong>$actor</strong> 移動，之前為 "$extra"。' . "\n";
$lang->action->desc->started               = '$date, 由 <strong>$actor</strong> 啟動。' . "\n";
$lang->action->desc->delayed               = '$date, 由 <strong>$actor</strong> 延期。' . "\n";
$lang->action->desc->suspended             = '$date, 由 <strong>$actor</strong> 掛起。' . "\n";
$lang->action->desc->canceled              = '$date, 由 <strong>$actor</strong> 取消。' . "\n";
$lang->action->desc->finished              = '$date, 由 <strong>$actor</strong> 完成。' . "\n";
$lang->action->desc->replied               = '$date, 由 <strong>$actor</strong> 回覆。' . "\n";
$lang->action->desc->doubted               = '$date, 由 <strong>$actor</strong> 追問。' . "\n";
$lang->action->desc->transfered            = '$date, 由 <strong>$actor</strong> 轉交。' . "\n";
$lang->action->desc->reviewed              = '$date, 由 <strong>$actor</strong> 審核 $extra。' . "\n";
$lang->action->desc->reimburse             = '$date, 由 <strong>$actor</strong> 報銷 $extra。' . "\n";
$lang->action->desc->revoked               = '$date, 由 <strong>$actor</strong> 撤銷。' . "\n";
$lang->action->desc->commited              = '$date, 由 <strong>$actor</strong> 提交' . "\n";
$lang->action->desc->returned              = '$date, 由 <strong>$actor</strong> 回款$extra。' . "\n";
$lang->action->desc->editreturned          = '$date, 由 <strong>$actor</strong> 編輯回款。' . "\n";
$lang->action->desc->deletereturned        = '$date, 由 <strong>$actor</strong> 刪除$extra。' . "\n";
$lang->action->desc->finishreturned        = '$date, 由 <strong>$actor</strong> 回款$extra，回款完成。' . "\n";
$lang->action->desc->delivered             = '$date, 由 <strong>$actor</strong> 交付。' . "\n";
$lang->action->desc->editdelivered         = '$date, 由 <strong>$actor</strong> 編輯交付。' . "\n";
$lang->action->desc->deletedelivered       = '$date, 由 <strong>$actor</strong> 刪除$extra。' . "\n";
$lang->action->desc->finishdelivered       = '$date, 由 <strong>$actor</strong> 完成交付。' . "\n";
$lang->action->desc->createdresume         = '$date, 由 <strong>$actor</strong> 添加履歷：<strong>$extra</strong>。' . "\n";
$lang->action->desc->editedresume          = '$date, 由 <strong>$actor</strong> 修改履歷。' . "\n";
$lang->action->desc->deleteresume          = '$date, 由 <strong>$actor</strong> 刪除履歷：<strong>$extra</strong>。' . "\n";
$lang->action->desc->createaddress         = '$date, 由 <strong>$actor</strong> 添加地址：<strong>$extra</strong>。' . "\n";
$lang->action->desc->editaddress           = '$date, 由 <strong>$actor</strong> 修改地址。' . "\n";
$lang->action->desc->deleteaddress         = '$date, 由 <strong>$actor</strong> 刪除地址：<strong>$extra</strong>。' . "\n";
$lang->action->desc->diff1                 = '修改了 <strong><i>%s</i></strong>，舊值為 "%s"，新值為 "%s"。<br />' . "\n";
$lang->action->desc->diff2                 = '修改了 <strong><i>%s</i></strong>，區別為：' . "\n" . "<blockquote>%s</blockquote>" . "\n<div class='hidden'>%s</div>";
$lang->action->desc->diff3                 = "將檔案名 %s 改為 %s 。\n";
$lang->action->desc->record                = '$date, <strong>$actor</strong> 添加了溝通日誌，聯繫人：<strong>$contact</strong>，聯繫時間：$extra。' . "\n";
$lang->action->desc->signed                = '$date, 由 <strong>$actor</strong> 簽約，成交金額：<strong>$extra</strong>。' . "\n";
$lang->action->desc->linkcontact           = '$date, 由 <strong>$actor</strong> 添加聯繫人：<strong>$extra</strong>。' . "\n";
$lang->action->desc->createorder           = '$date, 由 <strong>$actor</strong> 創建訂單：<strong>$extra</strong>。' . "\n";
$lang->action->desc->editorder             = '$date, 由 <strong>$actor</strong> 編輯訂單：<strong>$extra</strong>。' . "\n";
$lang->action->desc->assignorder           = '$date, 由 <strong>$actor</strong> 指派訂單：<strong>$extra</strong>。' . "\n";
$lang->action->desc->closeorder            = '$date, 由 <strong>$actor</strong> 關閉訂單 <strong>$extra</strong>。' . "\n";
$lang->action->desc->activateorder         = '$date, 由 <strong>$actor</strong> 激活訂單：<strong>$extra</strong>。' . "\n";
$lang->action->desc->createcontract        = '$date, 由 <strong>$actor</strong> 創建合同：<strong>$extra</strong>。' . "\n";
$lang->action->desc->editcontract          = '$date, 由 <strong>$actor</strong> 編輯合同：<strong>$extra</strong>。' . "\n";
$lang->action->desc->delivercontract       = '$date, 由 <strong>$actor</strong> 對合同<strong>$extra</strong>進行交付。' . "\n";
$lang->action->desc->receivecontract       = '$date, 由 <strong>$actor</strong> 對合同<strong>$extra</strong>。' . "\n";
$lang->action->desc->finishdelivercontract = '$date, 由 <strong>$actor</strong> 完成合同<strong>$extra</strong>的交付。' . "\n";
$lang->action->desc->finishreceivecontract = '$date, 由 <strong>$actor</strong> 對合同<strong>$extra</strong>，完成回款。' . "\n";
$lang->action->desc->finishcontract        = '$date, 由 <strong>$actor</strong> 完成合同：<strong>$extra</strong>。' . "\n";
$lang->action->desc->cancelcontract        = '$date, 由 <strong>$actor</strong> 取消合同：<strong>$extra</strong>。' . "\n";
$lang->action->desc->hidden                = '$date, 由 <strong>$actor</strong> 隱藏。' . "\n";
$lang->action->desc->undeleted             = '$date, 由 <strong>$actor</strong> 還原。' . "\n";
$lang->action->desc->transform             = '$date, 由 <strong>$actor</strong> 轉換為聯繫人。' . "\n";
$lang->action->desc->ignored               = '$date, 由 <strong>$actor</strong> 忽略。' . "\n";
$lang->action->desc->createtrip            = '$date, 由 <strong>$actor</strong> 創建出差：<strong>$extra</strong>。' . "\n";
$lang->action->desc->createegress          = '$date, 由 <strong>$actor</strong> 創建外出：<strong>$extra</strong>。' . "\n";

/* 用來顯示動態信息。*/
$lang->action->label = new stdclass();
$lang->action->label->created     = '創建了';
$lang->action->label->edited      = '編輯了';
$lang->action->label->assigned    = '指派了';
$lang->action->label->transmit    = '轉交了';
$lang->action->label->closed      = '關閉了';
$lang->action->label->deleted     = '刪除了';
$lang->action->label->erased      = '刪除了';
$lang->action->label->undeleted   = '還原了';
$lang->action->label->deletedfile = '刪除附件';
$lang->action->label->editfile    = '編輯附件';
$lang->action->label->commented   = '備註了';
$lang->action->label->activated   = '激活了';
$lang->action->label->resolved    = '解決了';
$lang->action->label->reviewed    = '評審了';
$lang->action->label->moved       = '移動了';
$lang->action->label->marked      = '編輯了';
$lang->action->label->started     = '開始了';
$lang->action->label->canceled    = '取消了';
$lang->action->label->finished    = '完成了';
$lang->action->label->reimbursed  = '報銷了';
$lang->action->label->record      = '溝通了';
$lang->action->label->signed      = '簽約了';
$lang->action->label->commited    = '提交了';
$lang->action->label->revoked     = '撤銷了';
$lang->action->label->forbidden   = '禁用了';
$lang->action->label->transform   = '轉換了';
$lang->action->label->ignore      = '忽略了';
$lang->action->label->login       = '登錄系統';
$lang->action->label->logout      = "退出登錄";

$lang->action->label->createdbalance        = '登記餘額';
$lang->action->label->createorder           = '創建訂單';
$lang->action->label->editorder             = '編輯了訂單';
$lang->action->label->activateorder         = '激活訂單';
$lang->action->label->closeorder            = '關閉訂單';
$lang->action->label->linkcontact           = '關聯聯繫人';
$lang->action->label->createcontract        = '創建合同';
$lang->action->label->editcontract          = '編輯合同';
$lang->action->label->cancelcontract        = '取消合同';
$lang->action->label->finishcontract        = '完成合同';
$lang->action->label->createdresume         = '創建履歷';
$lang->action->label->editedresume          = '編輯履歷';
$lang->action->label->deleteresume          = '刪除履歷';
$lang->action->label->createaddress         = '創建地址';
$lang->action->label->editaddress           = '編輯地址';
$lang->action->label->deleteaddress         = '刪除地址';
$lang->action->label->finishdelivered       = '完成交付';
$lang->action->label->finishdelivercontract = '完成交付';
$lang->action->label->delivered             = '交付';
$lang->action->label->delivercontract       = '交付';
$lang->action->label->returned              = '回款';
$lang->action->label->receivecontract       = '回款';
$lang->action->label->finishreceivecontract = '完成回款';
$lang->action->label->finishreturned        = '完成回款';
$lang->action->label->deletereturned        = '刪除回款';

/* 用來做動態搜索中顯示動作 */
$lang->action->search = new stdclass();
$lang->action->search->label = (array)$lang->action->label;

/* 用來生成相應對象的連結。*/
$lang->action->label->announce  = '公告|announce|view|announceID=%s';
$lang->action->label->balance   = '餘額|balance|browse|depositorID=%s';
$lang->action->label->doc       = '文檔|doc|view|docID=%s';
$lang->action->label->doclib    = '文檔庫|doc|browse|doclibID=%s';
$lang->action->label->contact   = '聯繫人|contact|view|contactID=%s';
$lang->action->label->contract  = '合同|contract|view|contractID=%s';
$lang->action->label->customer  = '客戶|customer|view|customerID=%s';
$lang->action->label->depositor = '賬戶|depositor|browse|';
$lang->action->label->holiday   = '放假安排|holiday|browse|';
$lang->action->label->order     = '訂單|order|view|orderID=%s';
$lang->action->label->product   = '產品|product|view|productID=%s';
$lang->action->label->project   = '項目|task|browse|projectID=%s';
$lang->action->label->provider  = '供應商|provider|view|providerID=%s';
$lang->action->label->schema    = '記賬模板|schema|browse|';
$lang->action->label->space     = '　';
$lang->action->label->task      = '任務|task|view|taskID=%s';
$lang->action->label->todo      = '待辦|todo|calendar|';
$lang->action->label->trade     = '賬目|trade|browse|';

$lang->action->label->attend = array();
$lang->action->label->attend['commited'] = '考勤審核|attend|browsereview|';
$lang->action->label->attend['reviewed'] = '考勤審核|attend|personal|';
$lang->action->label->leave = array();
$lang->action->label->leave['created']  = '請假審核|leave|browsereview|';
$lang->action->label->leave['commited'] = '請假審核|leave|browsereview|';
$lang->action->label->leave['revoked']  = '請假審核|leave|browsereview|';
$lang->action->label->leave['reviewed'] = '請假審核|leave|personal|';
$lang->action->label->lieu = array();
$lang->action->label->lieu['created']  = '調休審核|lieu|browsereview|';
$lang->action->label->lieu['commited'] = '調休審核|lieu|browsereview|';
$lang->action->label->lieu['revoked']  = '調休審核|lieu|browsereview|';
$lang->action->label->lieu['reviewed'] = '調休審核|lieu|personal|';
$lang->action->label->makeup = array();
$lang->action->label->makeup['created']  = '加班審核|makeup|browsereview|';
$lang->action->label->makeup['commited'] = '加班審核|makeup|browsereview|';
$lang->action->label->makeup['revoked']  = '加班審核|makeup|browsereview|';
$lang->action->label->makeup['reviewed'] = '加班審核|makeup|personal|';
$lang->action->label->overtime = array();
$lang->action->label->overtime['created']  = '加班審核|overtime|browsereview|';
$lang->action->label->overtime['commited'] = '加班審核|overtime|browsereview|';
$lang->action->label->overtime['revoked']  = '加班審核|overtime|browsereview|';
$lang->action->label->overtime['reviewed'] = '加班審核|overtime|personal|';
$lang->action->label->refund = array();
$lang->action->label->refund['commited']    = '報銷審批|refund|browsereview|';
$lang->action->label->refund['revoked']     = '報銷審批|refund|browsereview|';
$lang->action->label->refund['created']     = '報銷審批|refund|view|refundID=%s';
$lang->action->label->refund['edited']      = '報銷審批|refund|view|refundID=%s';
$lang->action->label->refund['reviewed']    = '報銷審批|refund|view|refundID=%s';
$lang->action->label->refund['reimburse']   = '報銷審批|refund|view|refundID=%s';
$lang->action->label->refund['deletedfile'] = '報銷審批|refund|view|refundID=%s';
$lang->action->label->user = array();
$lang->action->label->user['login']  = '登錄|user|login|';
$lang->action->label->user['logout'] = '退出|user|logout|';

$lang->action->nextContactList[1]      = '明天';
$lang->action->nextContactList[2]      = '後天';
$lang->action->nextContactList[3]      = '三天後';
$lang->action->nextContactList[7]      = '一周後';
$lang->action->nextContactList[14]     = '兩周後';
$lang->action->nextContactList[365000] = '無需聯繫';

$lang->action->record = new stdclass();
$lang->action->record->common     = '溝通';
$lang->action->record->create     = '添加記錄';
$lang->action->record->edit       = '編輯記錄';
$lang->action->record->history    = '溝通記錄';
$lang->action->record->customer   = '客戶';
$lang->action->record->provider   = '供應商';
$lang->action->record->contract   = '合同';
$lang->action->record->order      = '訂單';
$lang->action->record->contact    = '聯繫人';
$lang->action->record->actor      = '操作人';
$lang->action->record->comment    = '溝通內容';
$lang->action->record->date       = '時間';
$lang->action->record->file       = '附件';
$lang->action->record->nextDate   = '下次聯繫';
$lang->action->record->uploadFile = '上傳附件:';

$lang->action->objectTypes['order']     = '訂單';
$lang->action->objectTypes['customer']  = '客戶';
$lang->action->objectTypes['provider']  = '供應商';
$lang->action->objectTypes['doc']       = '文檔';
$lang->action->objectTypes['task']      = '任務';
$lang->action->objectTypes['product']   = '產品';
$lang->action->objectTypes['contact']   = '聯繫人';
$lang->action->objectTypes['contract']  = '合同';
$lang->action->objectTypes['project']   = '項目';
$lang->action->objectTypes['user']      = '用戶';
$lang->action->objectTypes['resume']    = '履歷';
$lang->action->objectTypes['leave']     = '請假';
$lang->action->objectTypes['lieu']      = '調休';
$lang->action->objectTypes['makeup']    = '補班';
$lang->action->objectTypes['overtime']  = '加班';
$lang->action->objectTypes['refund']    = '報銷';
$lang->action->objectTypes['depositor'] = '賬戶';
$lang->action->objectTypes['balance']   = '餘額';
$lang->action->objectTypes['todo']      = '待辦';
$lang->action->objectTypes['announce']  = '公告';
$lang->action->objectTypes['holiday']   = '放假安排';
$lang->action->objectTypes['trade']     = '賬目';
$lang->action->objectTypes['schema']    = '記賬模板';
$lang->action->objectTypes['doclib']    = '文檔庫';
$lang->action->objectTypes['action']    = '溝通記錄';

$lang->action->noticeTitle = "%s <a href='%s' data-appid='%s'>%s</a>";
