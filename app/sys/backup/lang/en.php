<?php
$lang->backup->common      = 'Backup';
$lang->backup->index       = 'Home';
$lang->backup->history     = 'History';
$lang->backup->delete      = 'Delete';
$lang->backup->backup      = 'Backup';
$lang->backup->restore     = 'Restore';
$lang->backup->setSaveDays = 'Set hold days';

$lang->backup->name     = 'Name';
$lang->backup->time     = 'Time';
$lang->backup->files    = 'Files';
$lang->backup->size     = 'Size';
$lang->backup->saveDays = 'Hold days';

$lang->backup->waitting       = 'Restoring...';
$lang->backup->confirmDelete  = 'Do you want to delete this backup?';
$lang->backup->confirmRestore = 'Do you want to restore this backup?';
$lang->backup->deleteInfo     = 'Delete backup before %s days';

$lang->backup->success = new stdclass();
$lang->backup->success->backup  = 'Success backup!';
$lang->backup->success->restore = 'Success restore!';

$lang->backup->error = new stdclass();
$lang->backup->error->noWritable  = "Cannot backup! <code>%s</code> do not write! Please check the directory permissions.";
$lang->backup->error->noDelete    = "The file %s cannot delete, modify permissions or deleted manually.";
$lang->backup->error->restoreSQL  = "The database restore failed. Error: %s";
$lang->backup->error->restoreFile = "Attachments failed to restore. Error: %s";
$lang->backup->error->backupFile  = "Attachments failed to backup. Error: %s";
$lang->backup->error->setSaveDays = "Backup should save more than 0 days";
