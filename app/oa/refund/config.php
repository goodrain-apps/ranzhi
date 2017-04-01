<?php
if(!isset($config->refund)) $config->refund = new stdclass();
$config->refund->require = new stdclass();
$config->refund->require->create = 'name,money';
$config->refund->require->edit   = 'name,money';

$config->refund->list = new stdclass();
$config->refund->list->exportFields = 'id, createdBy, createdDate, dept, name, category, money, status, related, reviewer, refundBy, refundDate';

global $lang, $app;
$app->loadLang('refund', 'oa');

$config->refund->search['module'] = 'refund';

$config->refund->search['fields']['name']             = $lang->refund->name;
$config->refund->search['fields']['category']         = $lang->refund->category;
$config->refund->search['fields']['date']             = $lang->refund->date;
$config->refund->search['fields']['money']            = $lang->refund->money;
$config->refund->search['fields']['status']           = $lang->refund->status;
$config->refund->search['fields']['related']          = $lang->refund->related;
$config->refund->search['fields']['id']               = $lang->refund->id;
$config->refund->search['fields']['createdBy']        = $lang->refund->createdBy;
$config->refund->search['fields']['createdDate']      = $lang->refund->createdDate;
$config->refund->search['fields']['firstReviewer']    = $lang->refund->firstReviewer;
$config->refund->search['fields']['firstReviewDate']  = $lang->refund->firstReviewDate;
$config->refund->search['fields']['secondReviewer']   = $lang->refund->secondReviewer;
$config->refund->search['fields']['secondReviewDate'] = $lang->refund->secondReviewDate;
$config->refund->search['fields']['refundBy']         = $lang->refund->refundBy;
$config->refund->search['fields']['refundDate']       = $lang->refund->refundDate;

$config->refund->search['params']['name']             = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->refund->search['params']['category']         = array('operator' => '=', 'control' => 'select',  'values' => '');
$config->refund->search['params']['date']             = array('operator' => '>=', 'control' => 'select',  'values' => '', 'class' => 'date');
$config->refund->search['params']['money']            = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->refund->search['params']['status']           = array('operator' => '=', 'control' => 'select', 'values' => array('' => '') + $lang->refund->statusList);
$config->refund->search['params']['related']          = array('operator' => 'include', 'control' => 'select', 'values' => '');
$config->refund->search['params']['id']               = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->refund->search['params']['createdBy']        = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->refund->search['params']['createdDate']      = array('operator' => '>=', 'control' => 'select',  'values' => '', 'class' => 'date');
$config->refund->search['params']['firstReviewer']    = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->refund->search['params']['firstReviewDate']  = array('operator' => '>=', 'control' => 'select',  'values' => '', 'class' => 'date');
$config->refund->search['params']['secondReviewer']   = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->refund->search['params']['secondReviewDate'] = array('operator' => '>=', 'control' => 'select',  'values' => '', 'class' => 'date');
$config->refund->search['params']['refundBy']         = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->refund->search['params']['refundDate']       = array('operator' => '>=', 'control' => 'select',  'values' => '', 'class' => 'date');
