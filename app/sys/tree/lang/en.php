<?php
/**
 * The tree category zh-cn file of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id: en.php 4103 2016-09-30 09:22:14Z daitingting $
 * @link        http://www.ranzhico.com
 */
$lang->tree->common      = "Tree";
$lang->tree->add         = "Add";
$lang->tree->edit        = "Edit";
$lang->tree->children    = "Add child";
$lang->tree->delete      = "Delete";
$lang->tree->browse      = "Area, Industry, Income Categories, Expend Categories, Forum Boards, Blog Categories, Depts";
$lang->tree->manage      = "Manage";
$lang->tree->fix         = "Fix data";
$lang->tree->merge       = "Merge";

$lang->tree->noCategories  = 'No category yet, add one first.';
$lang->tree->noBoards      = 'No board yet, add one first.';
$lang->tree->timeCountDown = "Locate to %s manage page in <strong id='countDown'>3</strong> seconds.";
$lang->tree->redirect      = 'Create now';
$lang->tree->aliasRepeat   = 'Alias: %s already exists。';
$lang->tree->aliasConflict = 'Alias: %s  conflicts with system modules';
$lang->tree->hasChildren   = "The board has children, can't be deleted.";
$lang->tree->hasThreads    = "The board has threads, can't be deleted.";
$lang->tree->confirmDelete = "Are you sure to delete it?";
$lang->tree->successFixed  = "Successfully fixed.";
$lang->tree->asParent      = "[%s] has children, so it can't be merged.";

/* Lang items for article, products. */
$lang->category = new stdclass();
$lang->category->common   = 'Category';
$lang->category->name     = 'Name';
$lang->category->alias    = 'Alias';
$lang->category->parent   = 'Parent';
$lang->category->desc     = 'Description';
$lang->category->keywords = 'Keywords';
$lang->category->children = "Children";
$lang->category->rights   = 'Rights';
$lang->category->users    = 'Users';
$lang->category->groups   = 'Groups';
$lang->category->origin   = 'Origin Category';
$lang->category->target   = 'Target Category';

/* Lang items for area. */
$lang->area = new stdclass();
$lang->area->common   = 'Area';
$lang->area->name     = 'Name';
$lang->area->alias    = 'Alias';
$lang->area->parent   = 'Parent';
$lang->area->desc     = 'Description';
$lang->area->keywords = 'Keywords';
$lang->area->children = 'Children';

/* Lang items for industry. */
$lang->industry = new stdclass();
$lang->industry->common   = 'Industry';
$lang->industry->name     = 'Name';
$lang->industry->alias    = 'Alias';
$lang->industry->parent   = 'Parent';
$lang->industry->desc     = 'Description';
$lang->industry->keywords = 'Keywords';
$lang->industry->children = "Children";

/* Lang items for income. */
$lang->in = new stdclass();
$lang->in->common   = 'Income';
$lang->in->name     = 'Name';
$lang->in->alias    = 'Alias';
$lang->in->parent   = 'Parent';
$lang->in->desc     = 'Description';
$lang->in->keywords = 'Keywords';
$lang->in->children = "Child";
$lang->in->merge    = 'Merge Categories';

/* Lang items for expend. */
$lang->out = new stdclass();
$lang->out->common   = 'Expense';
$lang->out->name     = 'Name';
$lang->out->alias    = 'Alias';
$lang->out->parent   = 'Parent';
$lang->out->desc     = 'Description';
$lang->out->keywords = 'Keywords';
$lang->out->children = "Child";
$lang->out->rights   = 'Rights';
$lang->out->refund   = 'Reimbursement';
$lang->out->merge    = 'Merge Categories';

$lang->out->refundList[1] = 'Yes';
$lang->out->refundList[0] = 'No';

/* Lang items for forum. */
$lang->board = new stdclass();
$lang->board->common     = 'Board';
$lang->board->name       = 'Board';
$lang->board->alias      = 'Alias';
$lang->board->parent     = 'Parent';
$lang->board->desc       = 'Description';
$lang->board->keywords   = 'Keyword';
$lang->board->children   = "Children";
$lang->board->readonly   = 'Read Only';
$lang->board->moderators = 'Board Moderator';
$lang->board->users      = 'Users';
$lang->board->groups     = 'Groups';

$lang->board->readonlyList[0] = 'Pulic';
$lang->board->readonlyList[1] = 'Read Only';

$lang->board->placeholder = new stdclass();
$lang->board->placeholder->moderators  = "BMs'accounts. Separated with" . '","';
$lang->board->placeholder->setChildren = 'Forum needs two levels of boards to show.';
