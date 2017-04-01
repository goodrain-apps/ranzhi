<?php
$config->makeup->require = new stdclass();
$config->makeup->require->create = 'year,begin,end,type,hours,leave';
$config->makeup->require->edit   = 'year,begin,end,type,hours,leave';

$config->makeup->list = new stdclass();
$config->makeup->list->exportFields = 'id, createdBy, dept, type, begin, end, hours, desc, status, reviewedBy';
