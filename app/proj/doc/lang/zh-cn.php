<?php
/**
 * The doc module zh-cn file of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     doc
 * @version     $Id: zh-cn.php 824 2010-05-02 15:32:06Z wwccss $
 * @link        http://www.ranzhico.com
 */
if(!isset($lang->doc)) $lang->doc = new stdclass();
$lang->doc->common         = '文档视图';
$lang->doc->id             = '文档编号';
$lang->doc->product        = '所属产品';
$lang->doc->project        = '所属项目';
$lang->doc->lib            = '所属文档库';
$lang->doc->category       = '所属分类';
$lang->doc->title          = '文档标题';
$lang->doc->digest         = '文档摘要';
$lang->doc->comment        = '文档备注';
$lang->doc->type           = '文档类型';
$lang->doc->content        = '文档正文';
$lang->doc->keywords       = '关键字';
$lang->doc->url            = '文档URL';
$lang->doc->files          = '附件';
$lang->doc->views          = '查阅次数';
$lang->doc->createdBy      = '由谁添加';
$lang->doc->createdDate    = '添加时间';
$lang->doc->editedBy       = '由谁编辑';
$lang->doc->editedDate     = '编辑时间';
$lang->doc->basicInfo      = '基本信息';
$lang->doc->deleted        = '已删除';

$lang->doc->index          = '首页';
$lang->doc->create         = '创建文档';
$lang->doc->edit           = '编辑文档';
$lang->doc->delete         = '删除文档';
$lang->doc->browse         = '文档列表';
$lang->doc->view           = '文档详情';
$lang->doc->manageType     = '维护分类';
$lang->doc->showFiles      = '附件库';
$lang->doc->sort           = '文档库排序';

$lang->doc->libName        = '文档库名称';
$lang->doc->libType        = '文档库类型';
$lang->doc->allLibs        = '所有文档库';
$lang->doc->projectLibs    = '项目文档库';
$lang->doc->customLibs     = '自定义文档库';
$lang->doc->projectMainLib = '项目主库';
$lang->doc->fileLib        = '附件库';

$lang->doc->createLib      = '创建文档库';
$lang->doc->editLib        = '编辑文档库';
$lang->doc->deleteLib      = '删除文档库';
$lang->doc->fixedMenu      = '固定到菜单栏';
$lang->doc->removedMenu    = '从菜单栏移除';

$lang->doc->editCategory   = '编辑分类';
$lang->doc->deleteCategory = '删除分类';

$lang->doc->allProject     = '所有项目';

$lang->doc->private        = '设为私密';
$lang->doc->users          = '授权用户';
$lang->doc->groups         = '授权分组';

$lang->doc->libTypeList = array();
$lang->doc->libTypeList['custom']  = '自定义文档库';
$lang->doc->libTypeList['project'] = '项目文档库';

$lang->doc->types['text'] = '文档';
$lang->doc->types['url']  = '链接';

$lang->doc->browseType = '浏览方式';
$lang->doc->browseTypeList['list'] = '列表';
$lang->doc->browseTypeList['menu'] = '目录';
$lang->doc->browseTypeList['tree'] = '树状图';

$lang->doc->confirmDelete      = "您确定删除该文档吗？";
$lang->doc->confirmDeleteLib   = "您确定删除该文档库吗？";
$lang->doc->errorEditSystemDoc = "系统文档库无需修改。";

$lang->doc->placeholder = new stdclass();
$lang->doc->placeholder->url = '相应的链接地址';

$lang->doc->notFound     = '该文档不存在';
$lang->doc->libNotFound  = '该文档库不存在';
$lang->doc->errorMainLib = '该系统文档库不能删除！';
