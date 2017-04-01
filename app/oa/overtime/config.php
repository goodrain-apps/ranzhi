<?php
$config->overtime->require = new stdclass();
$config->overtime->require->create = 'year,begin,end,type,hours';
$config->overtime->require->edit   = 'year,begin,end,type,hours';

$config->overtime->list = new stdclass();
$config->overtime->list->exportFields = 'id, createdBy, dept, type, begin, end, hours, desc, status, reviewedBy';
