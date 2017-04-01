<?php
/**
 * The tree module zh-cn file of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id: zh-cn.php 4103 2016-09-30 09:22:14Z daitingting $
 * @link        http://www.ranzhico.com
 */
$lang->tree->common        = "类目";
$lang->tree->add           = "添加";
$lang->tree->edit          = "编辑";
$lang->tree->children      = "添加子类目";
$lang->tree->delete        = "删除类目";
$lang->tree->browse        = "区域设置、行业设置、收入科目、支出科目、论坛版块、博客类目、维护部门";
$lang->tree->manage        = "维护类目";
$lang->tree->fix           = "修复数据";
$lang->tree->merge         = "合并科目";

$lang->tree->noCategories  = '您还没有添加类目，请添加类目。';
$lang->tree->noBoards      = '您还没有设置版块，请设置版块。';
$lang->tree->timeCountDown = "<strong id='countDown'>3</strong> 秒后转向%s管理页面。";
$lang->tree->redirect      = '立即转向';
$lang->tree->aliasRepeat   = '别名: %s 已经存在,不能重复添加。';
$lang->tree->aliasConflict = '别名: %s 与系统模块冲突，不能添加。';
$lang->tree->hasChildren   = '该版块存在子版块，不能删除。';
$lang->tree->hasThreads    = '该版块存在帖子，不能删除。';
$lang->tree->confirmDelete = "您确定删除该类目吗？";
$lang->tree->successFixed  = "成功修复";
$lang->tree->asParent      = '[%s]存在子科目，不能被合并';

/* Lang items for article, products. */
$lang->category = new stdclass();
$lang->category->common   = '类目';
$lang->category->name     = '类目名称';
$lang->category->alias    = '别名';
$lang->category->parent   = '上级类目';
$lang->category->desc     = '描述';
$lang->category->keywords = '关键词';
$lang->category->children = '子类目';
$lang->category->rights   = '权限';
$lang->category->users    = '授权用户';
$lang->category->groups   = '授权分组';
$lang->category->origin   = '源科目';
$lang->category->target   = '目标科目';

/* Lang items for area. */
$lang->area = new stdclass();
$lang->area->common   = '区域';
$lang->area->name     = '名称';
$lang->area->alias    = '别名';
$lang->area->parent   = '上级区域';
$lang->area->desc     = '描述';
$lang->area->keywords = '关键词';
$lang->area->children = "子区域";

/* Lang items for industry. */
$lang->industry = new stdclass();
$lang->industry->common   = '行业';
$lang->industry->name     = '名称';
$lang->industry->alias    = '别名';
$lang->industry->parent   = '上级行业';
$lang->industry->desc     = '描述';
$lang->industry->keywords = '关键词';
$lang->industry->children = "子行业";

/* Lang items for income. */
$lang->in = new stdclass();
$lang->in->common   = '收入科目';
$lang->in->name     = '名称';
$lang->in->alias    = '别名';
$lang->in->parent   = '上级科目';
$lang->in->desc     = '描述';
$lang->in->keywords = '关键词';
$lang->in->children = '子科目';
$lang->in->merge    = '科目合并';

/* Lang items for expend. */
$lang->out = new stdclass();
$lang->out->common   = '支出科目';
$lang->out->name     = '名称';
$lang->out->alias    = '别名';
$lang->out->parent   = '上级科目';
$lang->out->desc     = '描述';
$lang->out->keywords = '关键词';
$lang->out->children = '子科目';
$lang->out->rights   = '权限';
$lang->out->refund   = '报销科目';
$lang->out->merge    = '合并科目';

$lang->out->refundList[1] = '是';
$lang->out->refundList[0] = '否';

/* Lang items for forum. */
$lang->board = new stdclass();
$lang->board->common     = '版块';
$lang->board->name       = '版块';
$lang->board->alias      = '别名';
$lang->board->parent     = '上级版块';
$lang->board->desc       = '描述';
$lang->board->keywords   = '关键词';
$lang->board->children   = "子版块";
$lang->board->readonly   = '访问权限';
$lang->board->moderators = '版主';
$lang->board->users      = '授权用户';
$lang->board->groups     = '授权分组';

$lang->board->readonlyList[0] = '开放';
$lang->board->readonlyList[1] = '只读';

$lang->board->placeholder = new stdclass();
$lang->board->placeholder->moderators  = '会员用户名, 多个用户名之间用逗号隔开';
$lang->board->placeholder->setChildren = '论坛功能需要设置二级版块。';
