<?php
if(!isset($lang->overtime)) $lang->overtime = new stdclass();
$lang->overtime->common = 'Overtime';
$lang->overtime->browse = 'Browse Overtime';
$lang->overtime->create = 'Apply';
$lang->overtime->edit   = 'Edit';
$lang->overtime->view   = 'View';
$lang->overtime->delete = 'Delete';
$lang->overtime->review = 'Review';
$lang->overtime->cancel = 'Cancel';
$lang->overtime->commit = 'Commit';
$lang->overtime->export = 'Export';

$lang->overtime->personal     = 'My Overtime';
$lang->overtime->browseReview = 'Review List';
$lang->overtime->company      = 'All';
$lang->overtime->setReviewer  = 'Set Reviewer';

$lang->overtime->id           = 'ID';
$lang->overtime->year         = 'Year';
$lang->overtime->begin        = 'Begin';
$lang->overtime->end          = 'End';
$lang->overtime->start        = 'Start';
$lang->overtime->finish       = 'Finish';
$lang->overtime->hours        = 'Hours';
$lang->overtime->leave        = 'Leaves';
$lang->overtime->type         = 'Type';
$lang->overtime->desc         = 'Desc';
$lang->overtime->status       = 'Status';
$lang->overtime->createdBy    = 'Created By';
$lang->overtime->createdDate  = 'Created Date';
$lang->overtime->reviewedBy   = 'Reviewed By';
$lang->overtime->reviewedDate = 'Reviewed Date';
$lang->overtime->date         = 'Date';
$lang->overtime->time         = 'Time';
$lang->overtime->rejectReason = 'Reject Reason';

$lang->overtime->typeList['time']    = 'After work';
$lang->overtime->typeList['rest']    = 'On weekends';
$lang->overtime->typeList['holiday'] = 'On holiday';

$lang->overtime->statusList['draft']  = 'Draft';
$lang->overtime->statusList['wait']   = 'Wait';
$lang->overtime->statusList['pass']   = 'Pass';
$lang->overtime->statusList['reject'] = 'Reject';

$lang->overtime->denied    = 'Access denied';
$lang->overtime->unique    = 'There was a record of overtime in %s.';
$lang->overtime->sameMonth = 'Overtime must be in the same month.';
$lang->overtime->wrongEnd  = 'End time should be greater than begin time.';

$lang->overtime->confirmReview['pass']   = 'Are you sure to pass it?';
$lang->overtime->confirmReview['reject'] = 'Are you sure to reject it?';

$lang->overtime->hoursTip = 'Hours';
$lang->overtime->baseInfo = 'Basic Info';
