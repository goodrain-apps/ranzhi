<?php
if(!isset($lang->refund)) $lang->refund = new stdclass();
$lang->refund->common       = '报销';
$lang->refund->create       = '申请报销';
$lang->refund->browse       = '报销列表';
$lang->refund->personal     = '我的报销';
$lang->refund->company      = '所有报销';
$lang->refund->todo         = '待报销';
$lang->refund->browseReview = '报销审批列表';
$lang->refund->edit         = '编辑报销';
$lang->refund->view         = '详情';
$lang->refund->delete       = '删除';
$lang->refund->review       = '审批';
$lang->refund->detail       = '明细';
$lang->refund->reimburse    = '报销记账';
$lang->refund->cancel       = '撤销';
$lang->refund->commit       = '提交';
$lang->refund->settings     = '设置';
$lang->refund->setReviewer  = '审批人设置';
$lang->refund->setCategory  = '报销科目设置';
$lang->refund->setDepositor = '报销账户设置';
$lang->refund->setRefundBy  = '报销者设置';
$lang->refund->export       = '导出报销记录';

$lang->refund->id               = '编号';
$lang->refund->name             = '名称';
$lang->refund->category         = '科目';
$lang->refund->date             = '日期';
$lang->refund->money            = '金额';
$lang->refund->reviewMoney      = '报销额度';
$lang->refund->currency         = '货币';
$lang->refund->desc             = '描述';
$lang->refund->files            = '附件';
$lang->refund->status           = '状态';
$lang->refund->createdBy        = '申请人';
$lang->refund->createdDate      = '申请日期';
$lang->refund->editedBy         = '编辑者';
$lang->refund->editedDate       = '编辑日期';
$lang->refund->firstReviewer    = '第一审批人';
$lang->refund->firstReviewDate  = '第一审批日期';
$lang->refund->secondReviewer   = '第二审批人';
$lang->refund->secondReviewDate = '第二审批日期';
$lang->refund->refundBy         = '由谁报销';
$lang->refund->refundDate       = '报销日期';
$lang->refund->baseInfo         = '基本信息';
$lang->refund->reason           = '理由';
$lang->refund->reviewer         = '审批人';
$lang->refund->related          = '参与人';
$lang->refund->depositor        = '报销账户';

$lang->refund->statusList['draft']  = '草稿';
$lang->refund->statusList['wait']   = '等待审批';
$lang->refund->statusList['doing']  = '审批中';
$lang->refund->statusList['pass']   = '审批通过';
$lang->refund->statusList['reject'] = '审批拒绝';
$lang->refund->statusList['finish'] = '已报销';

$lang->refund->reviewStatusList['pass']   = '通过';
$lang->refund->reviewStatusList['reject'] = '拒绝';

$lang->refund->reviewAllStatusList['allpass']   = '全部通过';
$lang->refund->reviewAllStatusList['allreject'] = '全部拒绝';

$lang->refund->descTip = "%s 申请报销 %s。";

$lang->refund->uniqueReviewer    = '第一审批人和第二审批人不能是同一个人';
$lang->refund->createTradeTip    = '是否关联记账？';
$lang->refund->secondReviewerTip = '二级审批需要设置二级审批人。';
$lang->refund->correctMoney      = '报销额度不能多于申请金额';
$lang->refund->categoryTips      = '尚未设置支出科目。';
$lang->refund->setExpense        = '设置科目';
$lang->refund->moneyTip          = '低于金额只需要一级审批，高于金额需要二级审批';
$lang->refund->total             = '报销合计：';
$lang->refund->totalMoney        = '%s%s；';

$lang->refund->settings = new stdclass();
$lang->refund->settings->setReviewer  = "审批人|refund|setreviewer";
$lang->refund->settings->setCategory  = "报销科目|refund|setcategory";
$lang->refund->settings->setDepositor = "报销账户|refund|setdepositor";
$lang->refund->settings->setRefundBy  = "由谁报销|refund|setrefundby";
