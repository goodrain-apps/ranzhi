<?php
/**
 * The zh-tw file of block module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     block 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
$lang->block->common    = '區塊';
$lang->block->depositor = '付款賬號';
$lang->block->lblBlock  = '區塊';
$lang->block->admin     = '管理區塊';
$lang->block->num       = '數量';
$lang->block->orderBy   = '排序';

$lang->block->availableBlocks = new stdclass();
$lang->block->availableBlocks->depositor = '付款賬號';
$lang->block->availableBlocks->trade     = '賬目';
$lang->block->availableBlocks->provider  = '供應商';
$lang->block->availableBlocks->report    = '報表';

$this->lang->block->orderByList->trade['id_asc']  = 'ID 遞增';
$this->lang->block->orderByList->trade['id_desc'] = 'ID 遞減';

$this->lang->block->typeList->trade['all']  = '全部';
$this->lang->block->typeList->trade['in']   = '收入';
$this->lang->block->typeList->trade['out']  = '支出';

$this->lang->block->groupByList = new stdclass();
$this->lang->block->groupByList->trade['category'] = '按科目統計';
$this->lang->block->groupByList->trade['dept']     = '按部門統計';

$this->lang->block->orderByList->provider['id_asc']  = 'ID 遞增';
$this->lang->block->orderByList->provider['id_desc'] = 'ID 遞減';
