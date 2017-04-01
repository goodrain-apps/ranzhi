<?php
/**
 * The config file of project module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     project 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
$config->project = new stdclass();
$config->project->require = new stdclass();
$config->project->require->create = 'name, begin, end';
$config->project->require->edit   = 'name, begin, end';

$config->project->editor = new stdclass();
$config->project->editor->create = array('id' => 'desc', 'tools' => 'simple');
$config->project->editor->edit   = array('id' => 'desc', 'tools' => 'simple');
$config->project->editor->finish = array('id' => 'comment', 'tools' => 'simple');

global $lang, $app;
$app->loadLang('project', 'proj');
$config->project->search['module'] = 'project';

$config->project->search['fields']['t1.name']        = $lang->project->name;
$config->project->search['fields']['t2.account']     = $lang->project->member;
$config->project->search['fields']['t1.begin']       = $lang->project->begin;
$config->project->search['fields']['t1.end']         = $lang->project->end;
$config->project->search['fields']['t1.status']      = $lang->project->status;
$config->project->search['fields']['t1.id']          = $lang->project->id;
$config->project->search['fields']['t1.createdBy']   = $lang->project->createdBy;
$config->project->search['fields']['t1.createdDate'] = $lang->project->createdDate;

$config->project->search['params']['t1.name']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->project->search['params']['t2.account']     = array('operator' => 'include', 'control' => 'select', 'values' => '');
$config->project->search['params']['t1.begin']       = array('operator' => '>=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->project->search['params']['t1.end']         = array('operator' => '>=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->project->search['params']['t1.status']      = array('operator' => '=',  'control' => 'select', 'values' => $lang->project->statusList);
$config->project->search['params']['t1.id']          = array('operator' => '=',  'control' => 'input',  'values' => '');
$config->project->search['params']['t1.createdBy']   = array('operator' => '=',  'control' => 'select', 'values' => '');
$config->project->search['params']['t1.createdDate'] = array('operator' => '>=', 'control' => 'input',  'values' => '', 'class' => 'date');
