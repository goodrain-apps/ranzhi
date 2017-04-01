<?php
if(!isset($config->dynamic)) $config->dynamic = new stdclass();

$config->dynamic->search['module'] = 'my';

global $lang;
$this->loadLang('action');
$config->dynamic->search['fields']['date']       = $lang->action->date;
$config->dynamic->search['fields']['actor']      = $lang->action->actor;
$config->dynamic->search['fields']['action']     = $lang->action->action;
$config->dynamic->search['fields']['objectType'] = $lang->action->objectType;
$config->dynamic->search['fields']['objectID']   = $lang->action->objectID;
$config->dynamic->search['fields']['objectName'] = $lang->action->objectName;

$config->dynamic->search['params']['date']       = array('operator' => '>=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->dynamic->search['params']['actor']      = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->dynamic->search['params']['action']     = array('operator' => '=',       'control' => 'select', 'values' => $lang->action->search->label);
$config->dynamic->search['params']['objectType'] = array('operator' => '=',       'control' => 'select', 'values' => $lang->action->objectTypes);
$config->dynamic->search['params']['objectID']   = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->dynamic->search['params']['objectName'] = array('operator' => 'include', 'control' => 'input',  'values' => '');
