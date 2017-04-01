<?php
/**
 * The control file of project module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     project
 * @version     $Id: control.php 7417 2013-12-23 07:51:50Z wwccss $
 * @link        http://www.ranzhico.com
 */
class project extends control
{
    public function __construct()
    {
        parent::__construct();
        $this->projects = $this->project->getPairs();
    }

    /**
     * index page of project module.
     * 
     * @param  string $status 
     * @access public
     * @return void
     */
    public function index($status = 'involved', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        
        if(empty($this->projects)) $this->locate(inlink('create'));

        /* Build search form. */
        $this->loadModel('search', 'sys');
        $users = $this->loadModel('user')->getPairs('noclosed');
        $this->config->project->search['actionURL'] = $this->createLink('project', 'index', "status=bysearch");
        $this->config->project->search['params']['t1.createdBy']['values'] = $users;
        $this->config->project->search['params']['t2.account']['values']   = $users;
        $this->search->setSearchParams($this->config->project->search);

        /* Save session. */
        $this->app->session->set('projectList',  $this->app->getURI(true));

        $this->view->title    = $this->lang->project->common;
        $this->view->status   = $status;
        $this->view->projects = $this->project->getList($status, $orderBy, $pager);
        $this->view->users    = $this->user->getPairs('noclosed');
        $this->view->orderBy  = $orderBy;
        $this->view->pager    = $pager;
        $this->display();
    }

    /**
     * create a project.
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $projectID = $this->project->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('project', $projectID, 'Created');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('task', 'browse', "projectID={$projectID}")));
        }

        $this->view->title  = $this->lang->project->create;
        $this->view->users  = $this->loadModel('user')->getPairs('noclosed,nodeleted,noforbidden');
        $this->view->groups = $this->loadModel('group')->getPairs();
        $this->display();
    }

    /**
     * Edit project. 
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function edit($projectID)
    {
        $this->checkPriv($projectID);

        if($_POST)
        {
            $changes  = $this->project->update($projectID);
            $actionID = $this->loadModel('action')->create('project', $projectID, 'Edited');
            if($changes) $this->action->logHistory($actionID, $changes);

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $this->view->title   = $this->lang->project->edit;
        $this->view->users   = $this->loadModel('user')->getPairs('noclosed,nodeleted,noforbidden');
        $this->view->project = $this->project->getByID($projectID);
        $this->view->groups  = $this->loadModel('group')->getPairs();
        $this->display();
    }

    /**
     * View a project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function view($projectID = 0)
    {
        $this->view->title      = $this->lang->project->view;
        $this->view->project    = $this->project->getById($projectID);
        $this->view->users      = $this->loadModel('user')->getPairs();
        $this->view->modalWidth = 500;
        $this->display();
    }

    /**
     * Edit project's member.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function member($projectID)
    {
        $this->checkPriv($projectID);

        if($_POST)
        {
            $changes  = $this->project->updateMembers($projectID);
            $actionID = $this->loadModel('action')->create('project', $projectID, 'Edited');
            if($changes) $this->action->logHistory($actionID, $changes);

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }
        $userList = $this->loadModel('user')->getList($dept = 0, $mode = 'all');
        $users    = array('' => '');
        $members  = array('' => '');
        foreach($userList as $user)
        {
            $members[$user->account] = $user->realname;
            if($user->deleted == '0' && ($user->locked == '0000-00-00 00:00:00' or $user->locked < helper::now()))
            {
                $users[$user->account] = $user->realname;
            }
        }

        $this->view->title   = $this->lang->project->member;
        $this->view->project = $this->project->getByID($projectID);
        $this->view->users   = $users;
        $this->view->members = $members; 
        $this->display();
    }

    /**
     * Finish project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function finish($projectID) 
    {
        $this->checkPriv($projectID);

        if($_POST)
        {
            $changes = $this->project->finish($projectID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('project', $projectID, 'Finished', $this->post->comment);
            }

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $project = $this->project->getByID($projectID);

        $this->view->title     = $project->name;
        $this->view->projectID = $projectID;
        $this->view->project   = $project;
        $this->display();
    }

    /**
     * Active project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function activate($projectID)
    {
        $this->checkPriv($projectID, '', 'json');
        $result = $this->project->activate($projectID);
        if($result) $this->send(array('result' => 'success', 'message' => $this->lang->project->activateSuccess));
        $this->send(array('result' => 'fail', 'message' => dao::getError()));
    }

    /**
     * Suspend project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function suspend($projectID)
    {
        $this->checkPriv($projectID, '', 'json');
        if($this->project->suspend($projectID)) $this->send(array('result' => 'success', 'message' => $this->lang->project->suspendSuccess));
        $this->send(array('result' => 'fail', 'message' => dao::getError()));
    }

    /**
     * Delete a project.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function delete($projectID)
    {
        $this->checkPriv($projectID, '', 'json');
        $this->project->delete(TABLE_PROJECT, $projectID);
        $this->project->deleteTasks($projectID);
        $this->project->deleteDoclib($projectID);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success'));
    }

    /**
     * Import tasks undoned from other projects.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function importTask($toProject, $fromProject = 0)
    {   
        $this->checkPriv($toProject);

        if(!empty($_POST))
        {
            $this->project->importTask($toProject);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->post->referer));
        }

        /* Get projects and products info. */
        $projectID = $this->project->saveState($toProject, array_keys($this->projects));
        $project   = $this->project->getById($projectID);
        $projects  = $this->project->getProjectsToImport();
        unset($projects[$toProject]);

        $fromProject = ($fromProject == 0 and !empty($projects)) ? key($projects) : $fromProject;

        /* Save session. */
        $this->app->session->set('taskList',  $this->app->getURI(true));

        $this->view->title          = $project->name . $this->lang->colon . $this->lang->project->importTask;
        $this->view->tasks2Imported = $this->project->getTasks2Imported($fromProject);
        $this->view->projects       = $projects;
        $this->view->projectID      = $project->id;
        $this->view->fromProject    = $fromProject;
        $this->view->users          = $this->loadModel('user')->getPairs();
        $this->display();
    }

    /**
     * Check project privilege and locate index if no privilege. 
     * 
     * @param  int    $projectID 
     * @param  string $action 
     * @param  string $errorType   html|json
     * @access private
     * @return void
     */
    public function checkPriv($projectID, $action = '', $errorType = '')
    {
        if(!$this->project->checkPriv($projectID))
        {
            if($errorType == '') $errorType = empty($_POST) ? 'html' : 'json';
            if($errorType == 'json')
            {
                $this->app->loadLang('notice');
                $this->send(array('result' => 'fail', 'message' => $this->lang->notice->typeList['accessLimited']));
            }
            else
            {
                $locate = helper::safe64Encode($this->server->http_referer);
                $noticeLink = helper::createLink('notice', 'index', "type=accessLimited&locate={$locate}");
                $this->locate($noticeLink);
            }
        }
        return true;
    }

    /**
     * Drop menu page.
     * 
     * @param  int    $projectID 
     * @param  int    $module 
     * @param  int    $method 
     * @param  int    $extra 
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu($projectID, $module, $method, $extra)
    {
        $projects       = $this->project->getList();
        $currentProject = $projects[$projectID];

        $this->view->link            = $this->project->getProjectLink($module, $method, $extra);
        $this->view->projectID       = $projectID;
        $this->view->currentProject  = $currentProject;
        $this->view->module          = $module;
        $this->view->method          = $method;
        $this->view->extra           = $extra;
        $this->view->projects        = $projects;
        $this->display();
    }

    /**
     * The results page of search.
     * 
     * @param  string  $keywords 
     * @param  string  $module 
     * @param  string  $method 
     * @param  mix     $extra 
     * @access public
     * @return void
     */
    public function ajaxGetMatchedItems($keywords, $module, $method, $extra)
    {
        $projects = $this->dao->select('*')->from(TABLE_PROJECT)->where('deleted')->eq(0)->andWhere('name')->like("%$keywords%")->fetchAll();

        $this->view->link     = $this->project->getProjectLink($module, $method, $extra);
        $this->view->projects = $projects;
        $this->view->keywords = $keywords;
        $this->display();
    }
}
