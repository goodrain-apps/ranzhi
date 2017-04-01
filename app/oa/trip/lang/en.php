<?php
if(!isset($lang->trip)) $lang->trip = new stdclass();
$lang->trip->common = 'Trip';
$lang->trip->browse = 'Trips';
$lang->trip->create = 'Create';
$lang->trip->edit   = 'Edit';
$lang->trip->view   = 'Details';
$lang->trip->delete = 'Delete';

$lang->trip->personal   = 'My trip';
$lang->trip->department = 'Department';
$lang->trip->company    = 'Company';

$lang->trip->id          = 'ID';
$lang->trip->customer    = 'Customer/Provider';
$lang->trip->name        = 'Name';
$lang->trip->begin       = 'Start';
$lang->trip->end         = 'End';
$lang->trip->from        = 'From';
$lang->trip->to          = 'To';
$lang->trip->desc        = 'Description';
$lang->trip->createdBy   = 'Created By';
$lang->trip->createdDate = 'Created On';
$lang->trip->date        = 'Date';
$lang->trip->time        = 'Time';

$lang->trip->denied    = 'Access denied';
$lang->trip->unique    = 'There was a record of trip in %s.';
$lang->trip->wrongEnd  = 'End time should be greater than begin time.';
$lang->trip->sameMonth = 'Trip must be in the same month.';
