<?php
/**
 * The model file of project module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     project 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class projectModel extends model
{
    /**
     * Get project by id. 
     * 
     * @param  int    $projectID 
     * @access public
     * @return object
     */
    public function getByID($projectID)
    {
        $project = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch();
        if(!$project) return false;

        $members = $this->getMembers($projectID); 
        $project->members = $members;
        $project->PM      = '';
        foreach($members as $member) if($member->role == 'manager') $project->PM = $member->account;

        return $project;
    }

    /**
     * Get members of a project.
     * 
     * @param  int    $project 
     * @access public
     * @return void
     */
    public function getMembers($project)
    {
        return $this->dao->select('*')->from(TABLE_TEAM)->where('type')->eq('project')->andWhere('id')->eq($project)->fetchAll('account');
    }

    /**
     * Get member pairs.
     * 
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getMemberPairs($projectID)
    {
        $members = $this->dao->select('account')->from(TABLE_TEAM)->where('type')->eq('project')->andWhere('id')->eq($projectID)->fetchPairs('account');
        $users   = $this->dao->select('account, realname')->from(TABLE_USER)->where('account')->in($members)->orderBy('id_asc')->fetchPairs();
        foreach($users as $account => $realname) if($realname == '') $users[$account] = $account; 
        return array('' => '') + $users;
    }

    /**
     * Get project list.
     * 
     * @param  string $status 
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($status = null, $orderBy = 'id_desc', $pager = null)
    {
        if($this->session->projectQuery == false) $this->session->set('projectQuery', ' 1 = 1');
        $projectQuery = $this->loadModel('search', 'sys')->replaceDynamic($this->session->projectQuery);

        if($status == 'involved' or $status == 'bysearch')
        {
            $projects = $this->dao->select('t1.*')->from(TABLE_PROJECT)->alias('t1')
                ->leftJoin(TABLE_TEAM)->alias('t2')->on('t1.id=t2.id')
                ->where('t1.deleted')->eq(0)
                ->andWhere('t2.type')->eq('project')
                ->beginIF($status == 'involved')->andWhere('t1.status')->eq('doing')->andWhere('t2.account')->eq($this->app->user->account)->fi()
                ->beginIF($status == 'bysearch')->andWhere($projectQuery)->fi()
                ->orderBy("t1.$orderBy")
                ->fetchAll('id');
        }
        else
        {
            $projects = $this->dao->select('*')->from(TABLE_PROJECT)
                ->where('deleted')->eq(0)
                ->beginIF($status)->andWhere('status')->eq($status)->fi()
                ->orderBy($orderBy)
                ->fetchAll('id');
        }

        $members = $this->dao->select('*')->from(TABLE_TEAM)->where('type')->eq('project')->fetchGroup('id', 'account');

        $idList = array();
        foreach($projects as $project)
        {
            $project->members = isset($members[$project->id]) ? $members[$project->id] : array();

            $project->PM = '';
            foreach($project->members as $member)
            {
                if($member->role != 'manager') continue;
                $project->PM = $member->account;
            }

            if($this->checkPriv($project->id)) $idList[] = $project->id; 
        }

        $projectIDList = $this->dao->select('id')->from(TABLE_PROJECT)->where('id')->in($idList)->orderBy($orderBy)->page($pager)->fetchAll('id');
        foreach($projects as $key => $project)
        {
            if(!isset($projectIDList[$project->id])) unset($projects[$key]);
        }

        return $projects;
    }

    /**
     * Get  project pairs.
     * 
     * @access public
     * @return array
     */
    public function getPairs()
    {
        return $this->dao->select('id,name')->from(TABLE_PROJECT)->where('deleted')->eq(0)->fetchPairs();
    }

    /**
     * Get projects to import 
     * 
     * @access public
     * @return array
     */
    public function getProjectsToImport()
    {
        $projects = $this->dao->select('distinct t1.*')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_TASK)->alias('t2')->on('t1.id=t2.project')
            ->where('t2.status')->notIN('done,closed')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('t1.id desc')
            ->fetchAll('id');

        $pairs = array();
        $now   = date('Y-m-d');
        foreach($projects as $id => $project)
        {
            if($project->status == 'finished' or $project->end < $now) $pairs[$id] = $project->name;
        }
        return $pairs;
    }

    /**
     * Get tasks can be imported.
     * 
     * @param  int    $fromProject 
     * @access public
     * @return array
     */
    public function getTasks2Imported($fromProject)
    {
        $tasks        = array();
        $projectTasks = $this->loadModel('task')->getProjectTasks($fromProject, 'wait,doing,pause,cancel');
        $tasks        = array_merge($tasks, $projectTasks); 
        return $tasks;
    }

    /**
     * create 
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        $members = array_unique(array_merge(array($this->post->manager), (array)$this->post->member));
        $project = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->join('whitelist', ',')
            ->remove('member,manager,master')
            ->stripTags('desc', $this->config->allowedTags)
            ->get();

        $project = $this->loadModel('file')->processEditor($project, $this->config->project->editor->create['id']);
        $this->dao->insert(TABLE_PROJECT)
            ->data($project, $skip = 'uid')
            ->autoCheck()
            ->batchCheck($this->config->project->require->create, 'notempty')
            ->checkIF($project->end, 'end', 'ge', $project->begin)
            ->exec();

        if(dao::isError()) return false;
        $projectID = $this->dao->lastInsertId();

        $user = new stdclass();
        $user->type = 'project';
        $user->id   = $projectID;
        foreach($members as $member)
        {
            if($member == '') continue;
            $user->account = $member;
            $user->role    = $member == $this->post->manager ? 'manager' : 'member';

            $this->dao->insert(TABLE_TEAM)->data($user)->autoCheck()->exec();
            if(dao::isError()) return false;
        }

        /* Create doc lib. */
        $this->app->loadLang('doc', 'doc');
        $lib = new stdclass();
        $lib->project     = $projectID;
        $lib->name        = $this->lang->doc->projectMainLib;
        $lib->main        = '1';
        $lib->private     = 0;
        $lib->createdBy   = $this->app->user->account;
        $lib->createdDate = helper::now(); 

        $teams = $this->dao->select('account')->from(TABLE_TEAM)->where('type')->eq('project')->andWhere('id')->eq($projectID)->fetchPairs('account', 'account');
        $lib->users  = ',' . join(',', $teams) . ',';
        $lib->groups = isset($project->whitelist) ? $project->whitelist : '';
        $this->dao->insert(TABLE_DOCLIB)->data($lib)->exec();

        return $projectID;
    }

    /**
     * Update project 
     * 
     * @param  int    $projectID 
     * @param  mix    $project
     * @access public
     * @return bool
     */
    public function update($projectID, $project = null)
    {
        $oldProject = $this->getByID($projectID);
        if(empty($project))
        {
            $project = fixer::input('post')
                ->add('editedBy', $this->app->user->account)
                ->add('editedDate', helper::now())
                ->join('whitelist', ',')
                ->setDefault('whitelist', '')
                ->stripTags('desc', $this->config->allowedTags)
                ->remove('member,manager,master')
                ->get();
        }

        $project = $this->loadModel('file')->processEditor($project, $this->config->project->editor->edit['id']);
        $this->dao->update(TABLE_PROJECT)
            ->data($project, $skip = 'uid')
            ->autoCheck()
            ->batchCheck($this->config->project->require->create, 'notempty')
            ->checkIF($project->end, 'end', 'ge', $project->begin)
            ->where('id')->eq($projectID)
            ->exec();

        if(dao::isError()) return false;

        /* Update manager. */
        $this->dao->delete()->from(TABLE_TEAM)
            ->where('type')->eq('project')
            ->andWhere('id')->eq($projectID)
            ->andWhere('account')->eq($this->post->manager)
            ->andWhere('role')->ne('manager')
            ->exec();

        $this->dao->update(TABLE_TEAM)
            ->set('account')->eq($this->post->manager)
            ->where('type')->eq('project')
            ->andWhere('id')->eq($projectID)
            ->andWhere('role')->eq('manager')
            ->exec();

        return commonModel::createChanges($oldProject, $project);
    }

    /**
     * Update project's members. 
     * 
     * @param  int    $projectID 
     * @access public
     * @return array
     */
    public function updateMembers($projectID)
    {
        $oldProject = $this->getById($projectID);
        $members    = array();
        $project    = new stdclass();

        /* Delete old member. */
        $this->dao->delete()->from(TABLE_TEAM)
            ->where('type')->eq('project')
            ->andWhere('id')->eq($projectID)
            ->andWhere('role')->ne('manager')
            ->exec();

        /* save new members. */
        if(!empty($_POST['member']))
        {
            foreach($this->post->member as $key => $account)
            {
                if(empty($account)) continue;
                $member = new stdclass();
                $member->type    = 'project';
                $member->id      = $projectID;
                $member->account = $account;
                $member->role    = $this->post->role[$key];
                $this->dao->insert(TABLE_TEAM)->data($member)->autoCheck()->exec();
                $members[$account] = $member;
            }
        }

        $this->loadModel('doc', 'doc')->setLibUsers($projectID);

        $project->members = $members;
        return commonModel::createChanges($oldProject, $project);
    }

    /**
     * Active project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return bool
     */
    public function activate($projectID)
    {
        $project = new stdclass();
        $project->status     = 'doing';
        $project->editedBy   = $this->app->user->account;
        $project->editedDate = helper::now();

        $this->dao->update(TABLE_PROJECT)->data($project)->where('id')->eq((int)$projectID)->exec();
        return !dao::isError();
    }

    /**
     * Suspend project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return bool
     */
    public function suspend($projectID)
    {
        $project = new stdclass();
        $project->status     = 'suspend';
        $project->editedBy   = $this->app->user->account;
        $project->editedDate = helper::now();

        $this->dao->update(TABLE_PROJECT)->data($project)->where('id')->eq((int)$projectID)->exec();
        return !dao::isError();
    }

    /**
     * Finish project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return bool
     */
    public function finish($projectID)
    {
        $project = new stdclass();
        $project->status     = 'finished';
        $project->editedBy   = $this->app->user->account;
        $project->editedDate = helper::now();

        $this->dao->update(TABLE_PROJECT)->data($project)->where('id')->eq((int)$projectID)->exec();
        return !dao::isError();
    }

    /**
     * Delete tasks of project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return bool 
     */
    public function deleteTasks($projectID)
    {
        $this->dao->update(TABLE_TASK)->set('deleted')->eq('1')->where('project')->eq($projectID)->exec();
        return !dao::isError();
    }

    /**
     * Delete doclib of project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return bool 
     */
    public function deleteDoclib($projectID)
    {
        $this->dao->update(TABLE_DOCLIB)->set('deleted')->eq('1')->where('project')->eq($projectID)->exec();
        return !dao::isError();
    }

    /**
     * Import tasks.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function importTask($projectID)
    {
        $this->loadModel('task');

        /* Update tasks. */
        $tasks = $this->dao->select('id, project, assignedTo, consumed, status')->from(TABLE_TASK)->where('id')->in($this->post->tasks)->fetchAll('id');
        foreach($tasks as $task)
        {
            /* Save the assignedToes, should linked to project. */
            $assignedToes[$task->assignedTo] = $task->project;

            $data = new stdclass();
            $data->project = $projectID;

            if($task->status == 'cancel')
            {
                $data->canceledBy = '';
                $data->canceledDate = NULL;
            }

            $data->status = $task->consumed > 0 ? 'doing' : 'wait';
            $this->dao->update(TABLE_TASK)->data($data)->where('id')->eq($task->id)->exec();

            if(dao::isError()) return false;

            $this->loadModel('action')->create('task', $task->id, 'moved', '', $task->project);
        }

        /* Add members to project team. */
        $members = $this->getMemberPairs($projectID);
        foreach($assignedToes as $account => $preProjectID)
        {
            if(!isset($members[$account]))
            {
                $role = $this->dao->select('*')->from(TABLE_TEAM)->where('type')->eq('project')->andWhere('id')->eq($preProjectID)->andWhere('account')->eq($account)->fetch();
                if(!isset($role))
                {
                    $role = new stdclass();
                    $role->type    = 'project';
                    $role->account = $account;
                }
                $role->id   = $projectID;
                $role->join = helper::today();
                $this->dao->insert(TABLE_TEAM)->data($role)->exec();
                
                return !dao::isError();
            }
        }

        return true;
    }

    /**
     * Save the project id user last visited to session.
     * 
     * @param  int   $projectID 
     * @param  array $projects 
     * @access public
     * @return int
     */
    public function saveState($projectID, $projects)
    {
        if($projectID > 0) $this->session->set('project', (int)$projectID);
        if($projectID == 0 and $this->cookie->lastProject)    $this->session->set('project', (int)$this->cookie->lastProject);
        if($projectID == 0 and $this->session->project == '') $this->session->set('project', $projects[0]);
        if(!in_array($this->session->project, $projects)) $this->session->set('project', $projects[0]);
        return $this->session->project;
    }

    /**
     * Set menu.
     * 
     * @param  array  $projects 
     * @param  int    $projectID 
     * @param  string $extra
     * @access public
     * @return void
     */
    public function setMenu($projects, $projectID, $extra = '')
    {
        /* Check the privilege. */
        $project = $this->getById($projectID);

        if($projects and !isset($projects[$projectID]))
        {
            die(js::locate('back'));
        }

        $moduleName = $this->app->getModuleName();
        $methodName = $this->app->getMethodName();

        if($this->cookie->projectMode == 'noclosed' and $project->status == 'finished') 
        {
            setcookie('projectMode', 'all');
            $this->cookie->projectMode = 'all';
        }

        $selectHtml = $this->select($projects, $projectID, $moduleName, $methodName, $extra);

        echo  $selectHtml;
    }

    /**
     * Create the select code of projects. 
     * 
     * @param  array     $projects 
     * @param  int       $projectID 
     * @param  string    $currentModule 
     * @param  string    $currentMethod 
     * @param  string    $extra
     * @access public
     * @return string
     */
    public function select($projects, $projectID, $currentModule, $currentMethod, $extra = '')
    {
        if(!$projectID) return;
        $project = $this->getByID($projectID);

        setCookie("lastProject", $projectID, $this->config->cookieLife, $this->config->webRoot);
        $currentProject = $this->getById($projectID);

        $methodName = $this->app->getMethodName();
        $moduleName = $this->app->getModuleName();
            
        $this->app->loadLang('task', 'sys');

        $menu  = "<nav id='menu'><ul class='nav'>";
        $menu .= "<li><a id='currentItem' href=\"javascript:showDropMenu('project', '$projectID', '$currentModule', '$currentMethod', '$extra')\"><i class='icon-folder-open-alt'></i> <strong>{$currentProject->name}</strong> <span class='icon-caret-down'></span></a><div id='dropMenu'></div></li>";

        $viewIcons = array('browse' => 'list-ul', 'kanban' => 'columns', 'outline' => 'list-alt');
        $this->lang->task->browse = $this->lang->task->list;

        if($methodName ==  'browse' or $methodName == 'importtask' or $moduleName == 'doc')
        {
            $menu .= '<li class="divider angle"></li>';
            $menu .= commonModel::printLink('task', 'browse', "projectID=$projectID&mode=all", $this->lang->task->all, '', false, '', 'li');
            if(isset($currentProject->members[$this->app->user->account])) $menu .= commonModel::printLink('task', 'browse', "projectID=$projectID&mode=assignedTo", $this->lang->task->assignedToMe, '', false, '', 'li');
            $menu .= commonModel::printLink('task', 'browse', "projectID=$projectID&mode=createdBy",  $this->lang->task->createdByMe, '', false, '', 'li');
            $menu .= commonModel::printLink('task', 'browse', "projectID=$projectID&mode=finishedBy", $this->lang->task->finishedByMe, '', false, '', 'li');
            $menu .= commonModel::printLink('task', 'browse', "projectID=$projectID&mode=untilToday", $this->lang->task->untilToday, '', false, '', 'li');
            $menu .= commonModel::printLink('task', 'browse', "projectID=$projectID&mode=expired",    $this->lang->task->expired, '', false, '', 'li');
            $menu .= commonModel::printLink('proj.doc', 'projectLibs', "projectID=$projectID", $this->lang->project->doc, '', false, '', 'li');

            if($this->app->user->admin == 'super' || $this->app->user->account == $project->createdBy || $this->app->user->account == $project->PM)
            {
                $menu .= "<li>";
                $menu .= "<a data-toggle='dropdown' class='dropdown-toggle' href='#'>" . $this->lang->project->project. " <i class='icon-caret-down'></i></a>";
                $menu .= "<ul class='dropdown-menu'>";
                $menu .= commonModel::printLink('project', 'edit', "projectID=$projectID", $this->lang->edit, "data-toggle='modal'", false, '', 'li');
                $menu .= commonModel::printLink('project', 'member', "projectID=$projectID", $this->lang->project->member, "data-toggle='modal''", false, '', 'li');
                if($project->status != 'finished') $menu .= commonModel::printLink('project','finish', "projectID=$projectID", $this->lang->finish, "data-toggle='modal'", false, '', 'li');
                if($project->status != 'doing') $menu .= commonModel::printLink('project', 'activate', "projectID=$projectID", $this->lang->activate, "class='switcher' data-confirm='{$this->lang->project->confirm->activate}'", false, '', 'li');
                if($project->status != 'suspend') $menu .= commonModel::printLink('project', 'suspend', "projectID=$projectID", $this->lang->project->suspend, "class='switcher' data-confirm='{$this->lang->project->confirm->suspend}'", false, '', 'li');
                $menu .= commonModel::printLink('project', 'delete', "projectID=$projectID", $this->lang->delete, "class='deleter'", false, '', 'li');
                $menu .= "</ul></li>";
            }
            else
            {
                $menu .= "<li>" . html::a(helper::createLink('project', 'view', "projectID=$projectID"), $this->lang->project->project, "data-toggle='modal'") . "</li>";
            }
        }
        else if($methodName == 'kanban' || $methodName == 'outline')
        {
            $menu .= '<li class="divider angle"></li>';
            foreach($this->lang->task->groups as $key => $value)
            {
                if(empty($key)) continue;
                $menu .= "<li data-group='{$key}'>" . commonModel::printLink('task', $methodName, "projectID=$projectID&groupBy=$key", $value, '', false) . "</li>";
            }

            if($this->app->user->admin == 'super' or $this->app->user->account == $project->createdBy or $this->app->user->account == $project->PM)
            {
                $menu .= "<li>";
                $menu .= "<a data-toggle='dropdown' class='dropdown-toggle' href='#'>" . $this->lang->project->project. " <i class='icon-caret-down'></i></a>";
                $menu .= "<ul class='dropdown-menu'>";
                $menu .= commonModel::printLink('project', 'edit', "projectID=$projectID", $this->lang->edit, "data-toggle='modal'", false, '', 'li');
                $menu .= commonModel::printLink('project', 'member', "projectID=$projectID", $this->lang->project->member, "data-toggle='modal''", false, '', 'li');
                if($project->status != 'finished') $menu .= commonModel::printLink('project','finish', "projectID=$projectID", $this->lang->finish, "data-toggle='modal'", false, '', 'li');
                if($project->status != 'doing') $menu .= commonModel::printLink('project', 'activate', "projectID=$projectID", $this->lang->activate, "class='switcher' data-confirm='{$this->lang->project->confirm->activate}'", false, '', 'li');
                if($project->status != 'suspend') $menu .= commonModel::printLink('project', 'suspend', "projectID=$projectID", $this->lang->project->suspend, "class='switcher' data-confirm='{$this->lang->project->confirm->suspend}'", false, '', 'li');
                $menu .= commonModel::printLink('project', 'delete', "projectID=$projectID", $this->lang->delete, "class='deleter'", false, '', 'li');
                $menu .= "</ul></li>";
            }
        }
        else if(strpos('view,create,edit', $methodName) !== false)
        {
            $menu .= '<li class="divider angle"></li>';
            $menu .= commonModel::printLink('task', 'browse', "projectID=$projectID", $this->lang->task->browse, '', false, '', 'li');
            $menu .= '<li class="divider angle"></li>';
            $menu .= '<li class="title">' . $this->lang->{$moduleName}->{$methodName} . '</li>';
        }
        else if($methodName == 'batchcreate')
        {
            $menu .= '<li class="divider angle"></li>';
            $menu .= commonModel::printLink('task', 'browse', "projectID=$projectID", $this->lang->task->browse, '', false, '', 'li');
            $menu .= '<li class="divider angle"></li>';
            $menu .= '<li class="title">' . $this->lang->{$moduleName}->batchCreate . '</li>';
        }

        $menu .= "</ul>";

        $menu .= "<div class='btn-group pull-right'>";
        $menu .= "<button data-toggle='dropdown' class='btn btn-primary dropdown-toggle' type='button'><i class='icon-plus'> </i>" . $this->lang->create . " <span class='caret'></span></button>";
        $menu .= "<ul id='exportActionMenu' class='dropdown-menu w-100px'>";
        $menu .= "<li>" . commonModel::printLink('task', 'create', "projectID=$projectID", $this->lang->task->create, '', false) . "</li>";
        $menu .= "<li>" . commonModel::printLink('task', 'batchCreate', "projectID=$projectID", $this->lang->task->batchCreate, '', false) . "</li>";
        $menu .= "</ul>";
        $menu .= "</div>";

        if(commonModel::hasPriv('task', 'export'))
        {
            $menu .= "<div class='btn-group pull-right'>";
            $menu .= "<button data-toggle='dropdown' class='btn btn-primary dropdown-toggle' type='button'>" . $this->lang->exportIcon . $this->lang->export . " <span class='caret'></span></button>";
            $menu .= "<ul id='exportActionMenu' class='dropdown-menu w-100px'>";
            $menu .= "<li>" . commonModel::printLink('task', 'export', "mode=all&projectID=$projectID&orderBy={$extra}", $this->lang->exportAll, "class='iframe' data-width='700'", false) . "</li>";
            $menu .= "<li>" . commonModel::printLink('task', 'export', "mode=thisPage&projectID={$projectID}&orderBy={$extra}", $this->lang->exportThisPage, "class='iframe' data-width='700'", false) . "</li>";
            $menu .= "</ul>";
            $menu .= "</div>";
        }
        $menu .= "<div class='pull-right'>" . commonModel::printLink('project', 'importTask', "projectID=$projectID", $this->lang->importIcon . $this->lang->project->import, 'class="btn btn-primary"', false) . "</div>";

        if($methodName == 'browse' || $methodName == 'kanban' || $methodName == 'outline')
        {
            $taskListType = $methodName;
            $viewName = $this->lang->task->{$methodName};
            $menu .= "<ul class='nav pull-right'>";
            $menu .= "<li id='viewBar' class='dropdown'><a href='javascript:;' id='groupButton' data-toggle='dropdown' class='dropdown-toggle'><i class='icon-" . $viewIcons[$methodName] . "'></i> {$viewName} <i class='icon-caret-down'></i></a><ul class='dropdown-menu'>";
            $menu .= "<li" . ($methodName == 'browse' ? " class='active'" : '') . ">" . commonModel::printLink('task', 'browse', "projectID=$projectID", "<i class='icon-list-ul icon'></i> " . $this->lang->task->list, '', false) . "</li>";
            $menu .= "<li" . ($methodName == 'kanban' ? " class='active'" : '') . ">" . commonModel::printLink('task', 'kanban', "projectID=$projectID", "<i class='icon-columns icon'></i> " . $this->lang->task->kanban, '', false) . "</li>";
            $menu .= "<li" . ($methodName == 'outline' ? " class='active'" : '') . ">" . commonModel::printLink('task', 'outline', "projectID=$projectID", "<i class='icon-list-alt icon'></i> " . $this->lang->task->outline, '', false) . "</li>";
            $menu .= '</ul></li>';

            if($methodName == 'outline')
            {
                $menu .= '<li><a href="javascript:;" id="toggleAll"><i class="icon-plus"></i></a></li>';
            }
            $menu .= "</ul>";
        }

        $menu .= '</nav>';

        return $menu;
    }

    /**
     * Create the link from module,method,extra
     * 
     * @param  string  $module 
     * @param  string  $method 
     * @param  mix     $extra 
     * @access public
     * @return void
     */
    public function getProjectLink($module, $method, $extra)
    {
        $link = '';
        if($module == 'task' && ($method == 'view' || $method == 'edit' || $method == 'batchedit'))
        {   
            $module = 'task';
            $method = 'browse';
        }   

        if($module == 'project' && $method == 'create') return;

        $link = helper::createLink($module, $method, "projectID=%s");
        if($extra != '') $link = helper::createLink($module, $method, "projectID=%s&type=$extra");
        return $link;
    }
    
    /**
     * check project's Priv. 
     * 
     * @param  int    $project 
     * @access public
     * @return bool
     */
    public function checkPriv($projectID)
    {
        if($this->app->user->admin == 'super') return true;
        if($this->app->getModuleName() == 'project' and strpos('edit, member, finish, suspend,delete', $this->app->getMethodName()) !== false)
        {
            $project = $this->getByID($projectID);
            if(!$this->hasActionPriv($project)) return false;
        }

        if(!empty($this->app->user->rights['task']['viewall']))   return true;
        if(!empty($this->app->user->rights['task']['editall']))   return true;
        if(!empty($this->app->user->rights['task']['deleteall'])) return true;

        static $projects, $members, $groups, $groupUsers = array();
        if(empty($groups)) 
        {
            $groups = $this->loadModel('group')->getList(0);
            foreach($groups as $group) $groupUsers[$group->id] = $this->group->getUserPairs($group->id);

            $members  = $this->dao->select('*')->from(TABLE_TEAM)->where('type')->eq('project')->fetchGroup('id', 'account');
            $projects = $this->dao->select('*')->from(TABLE_PROJECT)->fetchAll('id');
            foreach($projects as $project)
            {
                $project->members = isset($members[$project->id]) ? $members[$project->id] : array();
                $accountList = empty($project->members) ? array() : array_keys($project->members);
                $whitelist   = trim($project->whitelist, ',');
                $whitelist   = empty($whitelist) ? array() : explode(',', $whitelist);
                foreach($whitelist as $groupID) foreach($groupUsers[$groupID] as $account => $realname) $accountList[] = $account;

                $project->accountList = $accountList;
            }
        }

        return in_array($this->app->user->account, $projects[$projectID]->accountList);
    }

    /**
     * Check current user has action privilege or not. 
     * 
     * @param  int    $project 
     * @access public
     * @return void
     */
    public function hasActionPriv($project)
    {
        return (($this->app->user->admin == 'super') or (isset($project->members[$this->app->user->account]) and $project->members[$this->app->user->account]->role == 'senior') or ($this->app->user->account == $project->createdBy) or ($this->app->user->account == $project->PM));
    }
}
