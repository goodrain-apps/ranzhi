<?php
if(!isset($lang->makeup)) $lang->makeup = new stdclass();
$lang->makeup->common = 'Makeup';
$lang->makeup->browse = 'Browse Makeup';
$lang->makeup->create = 'Apply';
$lang->makeup->edit   = 'Edit';
$lang->makeup->view   = 'View';
$lang->makeup->delete = 'Delete';
$lang->makeup->review = 'Review';
$lang->makeup->cancel = 'Cancel';
$lang->makeup->commit = 'Commit';
$lang->makeup->export = 'Export';

$lang->makeup->personal     = 'My Makeup';
$lang->makeup->browseReview = 'Review List';
$lang->makeup->company      = 'All';
$lang->makeup->setReviewer  = 'Set Reviewer';

$lang->makeup->id           = 'ID';
$lang->makeup->year         = 'Year';
$lang->makeup->begin        = 'Begin';
$lang->makeup->end          = 'End';
$lang->makeup->start        = 'Start';
$lang->makeup->finish       = 'Finish';
$lang->makeup->hours        = 'Hours';
$lang->makeup->leave        = 'Leaves';
$lang->makeup->type         = 'Type';
$lang->makeup->desc         = 'Desc';
$lang->makeup->status       = 'Status';
$lang->makeup->createdBy    = 'Created By';
$lang->makeup->createdDate  = 'Created Date';
$lang->makeup->reviewedBy   = 'Reviewed By';
$lang->makeup->reviewedDate = 'Reviewed Date';
$lang->makeup->date         = 'Date';
$lang->makeup->time         = 'Time';
$lang->makeup->rejectReason = 'Reject Reason';

$lang->makeup->typeList['compensate'] = 'Compensated Leave';

$lang->makeup->statusList['draft']  = 'Draft';
$lang->makeup->statusList['wait']   = 'Wait';
$lang->makeup->statusList['pass']   = 'Pass';
$lang->makeup->statusList['reject'] = 'Reject';

$lang->makeup->denied    = 'Access denied';
$lang->makeup->unique    = 'There was a record of makeup in %s.';
$lang->makeup->sameMonth = 'Makeup must be in the same month.';
$lang->makeup->wrongEnd  = 'End time should be greater than begin time.';

$lang->makeup->confirmReview['pass']   = 'Are you sure to pass it?';
$lang->makeup->confirmReview['reject'] = 'Are you sure to reject it?';

$lang->makeup->hoursTip = 'Hours';
$lang->makeup->baseInfo = 'Basic Info';
