<?php
/**
 * The control file of sales of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     sales
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class sales extends control
{
    /**
     * Construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('user');
        $this->lang->menuGroups->sales = 'setting';
    }

    /**
     * Manage groups' privileges.
     * 
     * @param  int    $companyID 
     * @access public
     * @return void
     */
    public function admin()
    {
        $groups = $this->sales->getGroupList();
        $users  = $this->user->getPairs('nodeleted, noempty, noclosed, noforbidden');

        if($_POST)
        {
            $this->sales->updatePriv();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('admin')));
        }

        $this->view->title  = $this->lang->sales->admin;
        $this->view->groups = $groups;
        $this->view->users  = $users;
        $this->view->privs  = $this->sales->getAllPrivs();

        $this->display();
    }

    /**
     * Browse groups. 
     * 
     * @access public
     * @return void
     */
    public function browse()
    {
        $groups = $this->sales->getGroupList();
        $users  = $this->user->getPairs('nodeleted, noempty, noclosed, noforbidden');
        foreach($groups as $group) 
        {
            $accounts = explode(',', $group->users);
            $group->users = '';
            foreach($accounts as $account) if($account != '' and isset($users[$account])) $group->users .= " " . $users[$account]; 
        }

        $this->view->title  = $this->lang->sales->browse;
        $this->view->groups = $groups;

        $this->display();
    }

    /**
     * Create a group.
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        if(!empty($_POST))
        {
            $this->sales->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title = $this->lang->sales->create;
        $this->view->users = $this->user->getPairs('nodeleted, noempty, noclosed, noforbidden');
        $this->display();
    }

    /**
     * edit 
     * 
     * @param  int    $groupID 
     * @access public
     * @return void
     */
    public function edit($groupID)
    {
        if(!empty($_POST))
        {
            $this->sales->edit($groupID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title  = $this->lang->sales->edit;
        $this->view->group  = $this->sales->getByID($groupID);
        $this->view->users  = $this->user->getPairs('nodeleted, noempty, noclosed, noforbidden');
        $this->display();
    }

    /**
     * delete a group.
     * 
     * @param  int    $groupID 
     * @access public
     * @return void
     */
    public function delete($groupID)
    {
        $this->sales->delete($groupID);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'locate' => inlink('browse')));
    }
}
