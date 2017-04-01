<?php
if(!isset($config->leave)) $config->leave = new stdclass();
$config->leave->require = new stdclass();
$config->leave->require->create = 'year,begin,end,type,hours';
$config->leave->require->edit   = 'year,begin,end,type,hours';

$config->leave->list = new stdclass();
$config->leave->list->exportFields = 'id, createdBy, dept, type, begin, end, hours, desc, status, reviewedBy';
