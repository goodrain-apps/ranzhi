<?php
global $app;
$app->loadLang('trip', 'oa');
$menu = isset($lang->egress->menu) ? $lang->egress->menu : '';
$lang->egress = clone $lang->trip;
$lang->egress->menu = $menu;

$lang->egress->common   = 'Egress';
$lang->egress->browse   = 'Browse';
$lang->egress->personal = 'My Egress';
$lang->egress->view     = 'Details';

$lang->egress->from = 'Departure';
$lang->egress->to   = 'Destination';

$lang->egress->unique    = 'There was a record of egress in %s.';
$lang->egress->sameMonth = 'Egress must be in the same month.';
