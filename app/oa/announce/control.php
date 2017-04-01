<?php
/**
 * The control file of announce module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     announce
 * @version     $Id: control.php 8180 2014-04-08 07:22:52Z guanxiying $
 * @link        http://www.ranzhico.com
 */
class announce extends control
{
    /**
     * __construct 
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadModel('article');
    }

    /** 
     * The index page, locate to the first category or home page if no category.
     * 
     * @access public
     * @return void
     */
    public function index()
    {   
        $this->locate(inlink('browse'));
    }   

    /**
     * Browse announces.
     * 
     * @param string $type        the article type
     * @param int    $categoryID  the category id
     * @param int    $recTotal 
     * @param int    $recPerPage 
     * @param int    $pageID 
     * @access public
     * @return void
     */
    public function browse($type = 'announce', $categoryID = 0, $mode = 'all', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {   
        $this->lang->article->menu = $this->lang->$type->menu;
        $this->lang->menuGroups->article = $type;

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build search form. */
        $this->loadModel('search', 'sys');
        $this->config->announce->search['actionURL'] = $this->createLink('announce', 'browse', "type=announce&categoryID=$categoryID&mode=bysearch");
        $this->search->setSearchParams($this->config->announce->search);

        $families  = $categoryID ? $this->loadModel('tree')->getFamily($categoryID, $type) : '';
        $announces = $this->article->getList($type, $families, $mode, $param = null, $orderBy, $pager);

        $this->view->title      = $this->lang->announce->browse;
        $this->view->mode       = $mode;
        $this->view->users      = $this->loadModel('user')->getPairs();
        $this->view->categories = $this->loadModel('tree')->getPairs($categoryID, $type);
        $this->view->announces  = $announces;
        $this->view->pager      = $pager;

        $this->display();
    }   

    /**
     * Create an announce.
     * 
     * @param  int    $categoryID
     * @access public
     * @return void
     */
    public function create($categoryID = '')
    {
        $categories = $this->loadModel('tree')->getOptionMenu('announce', 0, $removeRoot = true);
        if(empty($categories))
        {
            die(js::locate($this->createLink('tree', 'redirect', "type=announce")));
        }

        if($_POST)
        {
            $announceID = $this->article->create('announce');
            $actionID = $this->loadModel('action')->create('announce', $announceID, 'created');
            $users = $this->loadModel('user')->getPairs('nodeleted,noforbidden,noclosed,noempty');
            $this->action->sendNotice($actionID, array_keys($users), true);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('announce', 'browse')));
        }

        unset($this->lang->announce->menu);
        $this->view->title           = $this->lang->announce->create;
        $this->view->currentCategory = $categoryID;
        $this->view->categories      = $this->tree->getOptionMenu('announce', 0, $removeRoot = true);

        $this->display();
    }

    /**
     * Edit an announce.
     * 
     * @param  int    $articleID 
     * @access public
     * @return void
     */
    public function edit($articleID)
    {
        $article    = $this->article->getByID($articleID, $replaceTag = false);
        $categories = $this->loadModel('tree')->getOptionMenu('announce', 0, $removeRoot = true);

        if($_POST)
        {
            $this->article->update($articleID, 'announce');
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('announce', 'browse')));
        }

        $this->view->title      = $this->lang->article->edit;
        $this->view->article    = $article;
        $this->view->categories = $categories;
        $this->display();
    }

    /**
     * View an announce.
     * 
     * @param int $announceID 
     * @access public
     * @return void
     */
    public function view($announceID)
    {
        $announce = $this->article->getByID($announceID);

        /* fetch category for display. */
        $category = array_slice($announce->categories, 0, 1);
        $category = $category[0]->id;

        $currentCategory = $this->session->articleCategory;
        if($currentCategory > 0 && isset($announce->categories[$currentCategory])) $category = $currentCategory;  

        $category = $this->loadModel('tree')->getByID($category);
        $users    = $this->loadModel('user')->getPairs();

        $this->view->title       = $announce->title . ' - ' . $category->name;
        $this->view->category    = $category;
        $this->view->announce    = $announce;
        $this->view->author      = $this->loadModel('user')->getByAccount($announce->author);
        $this->view->users       = $users; 
        $this->view->prevAndNext = $this->article->getPrevAndNext($announce->id, $category->id);
        $this->view->modalWidth  = 800;

        if(!in_array($this->app->user->account, $announce->readers)) $announce->readers[] = $this->app->user->account;
        $readers = array();
        foreach($announce->readers as $reader) $readers[] = zget($users, $reader);
        $announce->readers = implode(',', $announce->readers);
        $this->dao->update(TABLE_ARTICLE)->set('views = views + 1')->set('readers')->eq($announce->readers)->where('id')->eq($announceID)->exec(false);

        $this->view->readers = $readers;

        $this->display();
    }

    /**
     * View readers. 
     * 
     * @param  int    $announceID 
     * @access public
     * @return void
     */
    public function viewReaders($announceID = 0)
    {
        $announce = $this->article->getById($announceID);
        $users    = $this->loadModel('user')->getPairs();

        $readers = array();
        foreach($announce->readers as $reader) $readers[] = zget($users, $reader);

        $this->view->title   = $announce->title . ' - ' . sprintf($this->lang->article->lblReaders, count($readers));
        $this->view->readers = implode(', ', $readers);
        $this->display();
    }

    /**
     * Delete an article.
     * 
     * @param  int      $articleID 
     * @access public
     * @return void
     */
    public function delete($articleID)
    {
        if($this->article->delete($articleID)) $this->send(array('result' => 'success'));
        $this->send(array('result' => 'fail', 'message' => dao::getError()));
    }
}
