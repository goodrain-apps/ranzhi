<?php
if(!isset($lang->refund)) $lang->refund = new stdclass();
$lang->refund->common       = 'Reimbursement';
$lang->refund->create       = 'Create';
$lang->refund->browse       = 'List';
$lang->refund->personal     = 'My Reimbursement';
$lang->refund->company      = 'All Reimbursement';
$lang->refund->todo         = 'Reimbursement Waiting';
$lang->refund->browseReview = 'Review';
$lang->refund->edit         = 'Edit';
$lang->refund->view         = 'View';
$lang->refund->delete       = 'Delete';
$lang->refund->review       = 'Review';
$lang->refund->detail       = 'Detail';
$lang->refund->reimburse    = 'Reimburse';
$lang->refund->cancel       = 'Cancel';
$lang->refund->commit       = 'Commit';
$lang->refund->settings     = 'Settings';
$lang->refund->setReviewer  = 'Set Reviewer';
$lang->refund->setCategory  = 'Set Category';
$lang->refund->setDepositor = 'Set Account';
$lang->refund->setRefundBy  = 'Set RefundBy';
$lang->refund->export       = 'Export';

$lang->refund->id               = 'ID';
$lang->refund->name             = 'Name';
$lang->refund->category         = 'Category';
$lang->refund->date             = 'Date';
$lang->refund->money            = 'Amount';
$lang->refund->reviewMoney      = 'Reimbursement';
$lang->refund->currency         = 'Currency';
$lang->refund->desc             = 'Description';
$lang->refund->files            = 'Files';
$lang->refund->status           = 'Status';
$lang->refund->createdBy        = 'Created by';
$lang->refund->createdDate      = 'Created on';
$lang->refund->editedBy         = 'Edited By';
$lang->refund->editedDate       = 'Edited on';
$lang->refund->firstReviewer    = '1st Reviewer';
$lang->refund->firstReviewDate  = '1st Review on';
$lang->refund->secondReviewer   = '2nd Reviewer';
$lang->refund->secondReviewDate = '2nd Review on';
$lang->refund->refundBy         = 'Reimbursed by';
$lang->refund->refundDate       = 'Reimbursed on';
$lang->refund->baseInfo         = 'Basic Info';
$lang->refund->reason           = 'Reason';
$lang->refund->reviewer         = 'Reviewer';
$lang->refund->related          = 'Involved';
$lang->refund->depositor        = 'Account';

$lang->refund->statusList['draft']  = 'Draft';
$lang->refund->statusList['wait']   = 'Wait';
$lang->refund->statusList['doing']  = 'Doing';
$lang->refund->statusList['pass']   = 'Passed';
$lang->refund->statusList['reject'] = 'Rejected';
$lang->refund->statusList['finish'] = 'Finished';

$lang->refund->reviewStatusList['pass']   = 'Pass';
$lang->refund->reviewStatusList['reject'] = 'Reject';

$lang->refund->reviewAllStatusList['allpass']   = 'Pass All';
$lang->refund->reviewAllStatusList['allreject'] = 'Reject All';

$lang->refund->descTip = "%s requested %s.";

$lang->refund->uniqueReviewer    = 'The 1st reviewer and the 2nd reviewer cannot be the same.';
$lang->refund->createTradeTip    = 'Do you want to keep this reimbursement in accounting?';
$lang->refund->secondReviewerTip = 'If reimbursement requires a 2nd review, please set 2nd reviewer.';
$lang->refund->correctMoney      = 'The reimbursed amount should not be more than the requested amount.';
$lang->refund->categoryTips      = 'Expense category is not set yet.';
$lang->refund->setExpense        = 'Set Category';
$lang->refund->moneyTip          = 'If requested amont is less than this, 1st review is required. If not, 2nd review is required.';
$lang->refund->total             = 'Total:';
$lang->refund->totalMoney        = '%s%s；';

$lang->refund->settings = new stdclass();
$lang->refund->settings->setReviewer  = "Reviewer|refund|setreviewer";
$lang->refund->settings->setCategory  = "Category|refund|setcategory";
$lang->refund->settings->setDepositor = "Account|refund|setdepositor";
$lang->refund->settings->setRefundBy  = "ReimbursedBy|refund|setrefundby";
