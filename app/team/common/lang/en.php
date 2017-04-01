<?php
/**
 * The en file of common module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     common 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
$lang->app = new stdclass();
$lang->app->name = 'TEAM';

$lang->menu->team = new stdclass();
$lang->menu->team->dashboard = 'Home|dashboard|index|';
$lang->menu->team->forum     = 'Forum|forum|index|';
$lang->menu->team->blog      = 'Blog|blog|index|';
$lang->menu->team->user      = 'Colleague|user|colleague|';
$lang->menu->team->company   = 'Company|company|index|';
$lang->menu->team->setting   = 'Settings|tree|browse|type=forum|';

/* Menu of forum module. */
if(!isset($lang->forum)) $lang->forum = new stdclass();

/* Menu of blog module. */
if(!isset($lang->blog)) $lang->blog = new stdclass();
$lang->blog->menu = new stdclass();
$lang->blog->menu->index    = 'Blogs|blog|index|';
$lang->blog->menu->category = 'Blog Categories|tree|browse|type=blog';

/* Menu of setting module. */
$lang->setting = new stdclass();
$lang->setting->menu = new stdclass();
$lang->setting->menu->board = 'Boards|tree|browse|type=forum|';
$lang->setting->menu->blog  = 'Blogs|tree|browse|type=blog|';
$lang->setting->menu->dept  = 'Departments|tree|browse|type=dept|';
$lang->setting->menu->role  = 'Roles|setting|lang|module=user&field=roleList&appName=team|';
include(dirname(__FILE__) . '/menuOrder.php');
