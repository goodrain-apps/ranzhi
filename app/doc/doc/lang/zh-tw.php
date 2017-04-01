<?php
/**
 * The doc module zh-tw file of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     doc
 * @version     $Id: zh-tw.php 824 2010-05-02 15:32:06Z wwccss $
 * @link        http://www.ranzhico.com
 */
if(!isset($lang->doc)) $lang->doc = new stdclass();
$lang->doc->common         = '文檔視圖';
$lang->doc->id             = '文檔編號';
$lang->doc->product        = '所屬產品';
$lang->doc->project        = '所屬項目';
$lang->doc->lib            = '所屬文檔庫';
$lang->doc->category       = '所屬分類';
$lang->doc->title          = '文檔標題';
$lang->doc->digest         = '文檔摘要';
$lang->doc->comment        = '文檔備註';
$lang->doc->type           = '文檔類型';
$lang->doc->content        = '文檔正文';
$lang->doc->keywords       = '關鍵字';
$lang->doc->url            = '文檔URL';
$lang->doc->files          = '附件';
$lang->doc->views          = '查閲次數';
$lang->doc->createdBy      = '由誰添加';
$lang->doc->createdDate    = '添加時間';
$lang->doc->editedBy       = '由誰編輯';
$lang->doc->editedDate     = '編輯時間';
$lang->doc->basicInfo      = '基本信息';
$lang->doc->deleted        = '已刪除';

$lang->doc->index          = '首頁';
$lang->doc->create         = '創建文檔';
$lang->doc->edit           = '編輯文檔';
$lang->doc->delete         = '刪除文檔';
$lang->doc->browse         = '文檔列表';
$lang->doc->view           = '文檔詳情';
$lang->doc->manageType     = '維護分類';
$lang->doc->showFiles      = '附件庫';
$lang->doc->sort           = '文檔庫排序';

$lang->doc->libName        = '文檔庫名稱';
$lang->doc->libType        = '文檔庫類型';
$lang->doc->allLibs        = '所有文檔庫';
$lang->doc->projectLibs    = '項目文檔庫';
$lang->doc->customLibs     = '自定義文檔庫';
$lang->doc->projectMainLib = '項目主庫';
$lang->doc->fileLib        = '附件庫';

$lang->doc->createLib      = '創建文檔庫';
$lang->doc->editLib        = '編輯文檔庫';
$lang->doc->deleteLib      = '刪除文檔庫';
$lang->doc->fixedMenu      = '固定到菜單欄';
$lang->doc->removedMenu    = '從菜單欄移除';

$lang->doc->editCategory   = '編輯分類';
$lang->doc->deleteCategory = '刪除分類';

$lang->doc->allProject     = '所有項目';

$lang->doc->private        = '設為私密';
$lang->doc->users          = '授權用戶';
$lang->doc->groups         = '授權分組';

$lang->doc->libTypeList = array();
$lang->doc->libTypeList['custom']  = '自定義文檔庫';
$lang->doc->libTypeList['project'] = '項目文檔庫';

$lang->doc->types['text'] = '文檔';
$lang->doc->types['url']  = '連結';

$lang->doc->browseType = '瀏覽方式';
$lang->doc->browseTypeList['list'] = '列表';
$lang->doc->browseTypeList['menu'] = '目錄';
$lang->doc->browseTypeList['tree'] = '樹狀圖';

$lang->doc->confirmDelete      = "您確定刪除該文檔嗎？";
$lang->doc->confirmDeleteLib   = "您確定刪除該文檔庫嗎？";
$lang->doc->errorEditSystemDoc = "系統文檔庫無需修改。";

$lang->doc->placeholder = new stdclass();
$lang->doc->placeholder->url = '相應的連結地址';

$lang->doc->notFound     = '該文檔不存在';
$lang->doc->libNotFound  = '該文檔庫不存在';
$lang->doc->errorMainLib = '該系統文檔庫不能刪除！';
