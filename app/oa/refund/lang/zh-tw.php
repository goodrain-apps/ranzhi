<?php
if(!isset($lang->refund)) $lang->refund = new stdclass();
$lang->refund->common       = '報銷';
$lang->refund->create       = '申請報銷';
$lang->refund->browse       = '報銷列表';
$lang->refund->personal     = '我的報銷';
$lang->refund->company      = '所有報銷';
$lang->refund->todo         = '待報銷';
$lang->refund->browseReview = '報銷審批列表';
$lang->refund->edit         = '編輯報銷';
$lang->refund->view         = '詳情';
$lang->refund->delete       = '刪除';
$lang->refund->review       = '審批';
$lang->refund->detail       = '明細';
$lang->refund->reimburse    = '報銷記賬';
$lang->refund->cancel       = '撤銷';
$lang->refund->commit       = '提交';
$lang->refund->settings     = '設置';
$lang->refund->setReviewer  = '審批人設置';
$lang->refund->setCategory  = '報銷科目設置';
$lang->refund->setDepositor = '報銷賬戶設置';
$lang->refund->setRefundBy  = '報銷者設置';
$lang->refund->export       = '導出報銷記錄';

$lang->refund->id               = '編號';
$lang->refund->name             = '名稱';
$lang->refund->category         = '科目';
$lang->refund->date             = '日期';
$lang->refund->money            = '金額';
$lang->refund->reviewMoney      = '報銷額度';
$lang->refund->currency         = '貨幣';
$lang->refund->desc             = '描述';
$lang->refund->files            = '附件';
$lang->refund->status           = '狀態';
$lang->refund->createdBy        = '申請人';
$lang->refund->createdDate      = '申請日期';
$lang->refund->editedBy         = '編輯者';
$lang->refund->editedDate       = '編輯日期';
$lang->refund->firstReviewer    = '第一審批人';
$lang->refund->firstReviewDate  = '第一審批日期';
$lang->refund->secondReviewer   = '第二審批人';
$lang->refund->secondReviewDate = '第二審批日期';
$lang->refund->refundBy         = '由誰報銷';
$lang->refund->refundDate       = '報銷日期';
$lang->refund->baseInfo         = '基本信息';
$lang->refund->reason           = '理由';
$lang->refund->reviewer         = '審批人';
$lang->refund->related          = '參與人';
$lang->refund->depositor        = '報銷賬戶';

$lang->refund->statusList['draft']  = '草稿';
$lang->refund->statusList['wait']   = '等待審批';
$lang->refund->statusList['doing']  = '審批中';
$lang->refund->statusList['pass']   = '審批通過';
$lang->refund->statusList['reject'] = '審批拒絶';
$lang->refund->statusList['finish'] = '已報銷';

$lang->refund->reviewStatusList['pass']   = '通過';
$lang->refund->reviewStatusList['reject'] = '拒絶';

$lang->refund->reviewAllStatusList['allpass']   = '全部通過';
$lang->refund->reviewAllStatusList['allreject'] = '全部拒絶';

$lang->refund->descTip = "%s 申請報銷 %s。";

$lang->refund->uniqueReviewer    = '第一審批人和第二審批人不能是同一個人';
$lang->refund->createTradeTip    = '是否關聯記賬？';
$lang->refund->secondReviewerTip = '二級審批需要設置二級審批人。';
$lang->refund->correctMoney      = '報銷額度不能多於申請金額';
$lang->refund->categoryTips      = '尚未設置支出科目。';
$lang->refund->setExpense        = '設置科目';
$lang->refund->moneyTip          = '低於金額只需要一級審批，高於金額需要二級審批';
$lang->refund->total             = '報銷合計：';
$lang->refund->totalMoney        = '%s%s；';

$lang->refund->settings = new stdclass();
$lang->refund->settings->setReviewer  = "審批人|refund|setreviewer";
$lang->refund->settings->setCategory  = "報銷科目|refund|setcategory";
$lang->refund->settings->setDepositor = "報銷賬戶|refund|setdepositor";
$lang->refund->settings->setRefundBy  = "由誰報銷|refund|setrefundby";
