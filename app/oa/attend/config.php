<?php
if(!isset($config->attend)) $config->attend = new stdclass();
$config->attend->signInLimit   = '09:00';
$config->attend->signOutLimit  = '18:00';
$config->attend->workingHours  = '8';
$config->attend->workingDays   = '5';
$config->attend->mustSignOut   = 'yes';
$config->attend->ip            = '*';
$config->attend->noAttendUsers = '';

$config->attend->beginDate = new stdclass();
$config->attend->beginDate->company = '';

$config->attend->list = new stdclass();
$config->attend->list->exportFields = 'dept, realname, date, dayName, status, signIn, signOut, ip, desc';
