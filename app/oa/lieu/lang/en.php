<?php
if(!isset($lang->lieu)) $lang->lieu = new stdclass();
$lang->lieu->common = 'Lieu';
$lang->lieu->browse = 'Lieus';
$lang->lieu->create = 'Create';
$lang->lieu->edit   = 'Edit';
$lang->lieu->view   = 'View';
$lang->lieu->delete = 'Delete';
$lang->lieu->review = 'Review';
$lang->lieu->cancel = 'Cancel';
$lang->lieu->commit = 'Commit';

$lang->lieu->personal     = 'My Lieus';
$lang->lieu->browseReview = 'Reviews';
$lang->lieu->company      = 'All';
$lang->lieu->setReviewer  = 'Set Reviewer';

$lang->lieu->id           = 'ID';
$lang->lieu->year         = 'Year';
$lang->lieu->begin        = 'Begin';
$lang->lieu->end          = 'End';
$lang->lieu->start        = 'Start';
$lang->lieu->finish       = 'Finish';
$lang->lieu->hours        = 'Hours';
$lang->lieu->overtime     = 'Overtimes';
$lang->lieu->status       = 'Status';
$lang->lieu->desc         = 'Description';
$lang->lieu->createdBy    = 'Created By';
$lang->lieu->createdDate  = 'Created On';
$lang->lieu->reviewedBy   = 'Reviewed By';
$lang->lieu->reviewedDate = 'Reviewed On';
$lang->lieu->date         = 'Date';
$lang->lieu->time         = 'Time';

$lang->lieu->statusList['draft']  = 'Draft';
$lang->lieu->statusList['wait']   = 'Wait';
$lang->lieu->statusList['pass']   = 'Pass';
$lang->lieu->statusList['reject'] = 'Reject';

$lang->lieu->confirmReview['pass']   = 'Are you sure to pass it?';
$lang->lieu->confirmReview['reject'] = 'Are you sure to reject it?';

$lang->lieu->denied    = 'Access denied.';
$lang->lieu->unique    = 'There was a record of lieu in %s.';
$lang->lieu->sameMonth = 'Lieu must be in the same month.';
$lang->lieu->wrongEnd  = 'End time should be greater than begin time.';
