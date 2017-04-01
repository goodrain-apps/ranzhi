<?php
/**
 * The control file of makeup of Ranzhi.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com> 
 * @package     makeup
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class makeup extends control
{
    /**
     * index 
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate(inlink('personal'));
    }

    /**
     * Personal's makeup. 
     * 
     * @param  string  $date 
     * @param  string  $orderBy 
     * @access public
     * @return void
     */
    public function personal($date = '', $orderBy = 'id_desc')
    {
        die($this->fetch('makeup', 'browse', "type=personal&date=$date&orderBy=$orderBy", 'oa'));
    }

    /**
     * Department's makeup. 
     * 
     * @param  string   $date 
     * @param  string   $orderBy 
     * @access public
     * @return void
     */
    public function browseReview($date = '', $orderBy = 'id_desc')
    {
        die($this->fetch('makeup', 'browse', "type=browseReview&date=$date&orderBy=$orderBy", 'oa'));
    }

    /**
     * Company's makeup. 
     * 
     * @param  string   $date 
     * @param  string   $orderBy 
     * @access public
     * @return void
     */
    public function company($date = '', $orderBy = 'id_desc')
    {
        die($this->fetch('makeup', 'browse', "type=company&date=$date&orderBy=$orderBy", 'oa'));
    }

    /**
     * Browse makeup.
     * 
     * @param  string   $type 
     * @param  string   $date 
     * @param  string   $orderBy 
     * @access public
     * @return void
     */
    public function browse($type = 'personal', $date = '', $orderBy = 'id_desc')
    {
        if($date == '' or (strlen($date) != 6 and strlen($date) != 4)) $date = date("Ym");
        $currentYear  = substr($date, 0, 4);
        $currentMonth = strlen($date) == 6 ? substr($date, 4, 2) : '';
        $monthList    = $this->makeup->getAllMonth($type);
        $yearList     = array_keys($monthList);
        $deptList     = $this->loadModel('tree')->getPairs(0, 'dept');
        $makeupList = array();

        if($type == 'personal')
        {
            $makeupList = $this->makeup->getList($type, $currentYear, $currentMonth, $this->app->user->account, '', '', $orderBy);
        }
        elseif($type == 'browseReview')
        {
            $this->app->loadModuleConfig('attend');
            $reviewedBy = $this->makeup->getReviewedBy();
            if($reviewedBy)
            { 
                if($reviewedBy == $this->app->user->account)
                {
                    $deptList    = $this->loadModel('tree')->getPairs('', 'dept');
                    $deptList[0] = '';
                    $makeupList  = $this->makeup->getList($type, $currentYear, $currentMonth, '', array_keys($deptList), '', $orderBy);
                }
            }
            else
            {
                $deptList = $this->loadModel('tree')->getDeptManagedByMe($this->app->user->account);
                if(empty($deptList))
                {
                    $makeupList = array();
                }
                else
                {
                    foreach($deptList as $key => $value) $deptList[$key] = $value->name;
                    $makeupList = $this->makeup->getList($type, $currentYear, $currentMonth, '', array_keys($deptList), '', $orderBy);
                }
            }
        }
        elseif($type == 'company')
        {
            $makeupList = $this->makeup->getList($type, $currentYear, $currentMonth, '', '', '', $orderBy);
        }

        $this->session->set('makeupList', $this->app->getURI(true));

        $this->view->title        = $this->lang->makeup->browse;
        $this->view->type         = $type;
        $this->view->currentYear  = $currentYear;
        $this->view->currentMonth = $currentMonth;
        $this->view->monthList    = $monthList;
        $this->view->yearList     = $yearList;
        $this->view->deptList     = $deptList;
        $this->view->users        = $this->loadModel('user')->getPairs();
        $this->view->makeupList   = $makeupList;
        $this->view->date         = $date;
        $this->view->orderBy      = $orderBy;
        $this->display();
    }

    /**
     * Review an makeup.
     * 
     * @param  int      $id 
     * @param  string   $status 
     * @access public
     * @return void
     */
    public function review($id, $status)
    {
        $makeup = $this->makeup->getById($id);

        /* Check privilage. */
        $this->app->loadModuleConfig('attend');
        $reviewedBy = $this->makeup->getReviewedBy();
        if($reviewedBy)
        { 
            if($reviewedBy != $this->app->user->account) $this->send(array('result' => 'fail', 'message' => $this->lang->makeup->denied));
        }
        else
        {
            $createdUser = $this->loadModel('user')->getByAccount($makeup->createdBy);
            $dept        = $this->loadModel('tree')->getByID($createdUser->dept);
            if((empty($dept) or ",{$this->app->user->account}," != $dept->moderators)) $this->send(array('result' => 'fail', 'message' => $this->lang->makeup->denied));
        }

        if($status == 'reject')
        {
            if($_POST)
            {
                $this->makeup->review($id, $status);
                if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

                $actionID = $this->loadModel('action')->create('makeup', $id, 'reviewed', '', zget($this->lang->makeup->statusList, $status));
                $this->sendmail($id, $actionID);
                $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browseReview')));
            }
        }
        else
        {
            $this->makeup->review($id, $status);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->loadModel('action')->create('makeup', $id, 'reviewed', '', zget($this->lang->makeup->statusList, $status));
            $this->sendmail($id, $actionID);

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
        }

        if($status == 'reject')
        {
            $this->view->title  = $this->lang->makeup->review;
            $this->view->makeup = $makeup;
            $this->display();
        }
    }

    /**
     * Create an makeup.
     * 
     * @access public
     * @return void
     */
    public function create($date = '')
    {
        if($_POST)
        {
            $result = $this->makeup->create();
            if(is_array($result)) $this->send($result);

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $makeupID = $result;
            $actionID = $this->loadModel('action')->create('makeup', $makeupID, 'created');
            $this->sendmail($makeupID, $actionID);
            
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('personal')));
        }

        if($date)
        {
            $date   = date('Y-m-d', strtotime($date));
            $makeup = $this->makeup->getByDate($date, $this->app->user->account);
            if($makeup) $this->locate(inlink('edit', "id=$makeup->id"));
        }

        $leavePairs = array('');
        $leaveList  = $this->loadModel('leave', 'oa')->getList('company', '', '', $this->app->user->account, '', 'pass');
        $makeupList = $this->makeup->getList('company', '', '', $this->app->user->account);

        foreach($leaveList as $key => $leave)
        {
            $hasMakeup = false;
            foreach($makeupList as $makeup)
            {
                if($makeup->status == 'reject') continue;
                if(strpos($makeup->leave, ",$leave->id,") !== false)
                {
                    $hasMakeup = true;
                    break;
                }
            }
            if($hasMakeup) continue;

            $leavePairs[$leave->id] = $leave->begin . ' ' . $leave->start . ' ~ ' . $leave->end . ' ' . $leave->finish;
        }

        $this->app->loadModuleConfig('attend');
        $this->view->title      = $this->lang->makeup->create;
        $this->view->date       = $date;
        $this->view->leavePairs = $leavePairs;
        $this->display();
    }

    /**
     * Edit makeup.
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function edit($id)
    {
        $this->app->loadModuleConfig('attend');
        $makeup = $this->makeup->getById($id);
        /* check privilage. */
        if($makeup->createdBy != $this->app->user->account) 
        {
            $locate     = helper::safe64Encode(helper::createLink('oa.makeup', 'browse'));
            $noticeLink = helper::createLink('notice', 'index', "type=accessLimited&locate={$locate}");
            die(js::locate($noticeLink));
        }

        if($_POST)
        {
            $result = $this->makeup->update($id);
            if(is_array($result)) $this->send($result);

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $leavePairs = array();
        $leaveList  = $this->loadModel('leave', 'oa')->getList('company', '', '', $this->app->user->account, '', 'pass');
        $makeupList = $this->makeup->getList('company', '', '', $this->app->user->account);

        foreach($leaveList as $key => $leave)
        {
            $hasMakeup= false;
            foreach($makeupList as $makeup)
            {
                if($makeup->id == $oldLieu->id) continue;
                if(strpos($makeup->leave, ",$leave->id,") !== false)
                {
                    $hasMakeup = true;
                    break;
                }
            }
            if($hasMakeup) continue;

            $leavePairs[$leave->id] = $leave->begin . ' ' . $leave->start . ' ~ ' . $leave->end . ' ' . $leave->finish;
        }

        $this->view->title      = $this->lang->makeup->edit;
        $this->view->makeup     = $makeup;
        $this->view->leavePairs = $leavePairs;
        $this->display();
    }

    /**
     * View makeup.
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function view($id, $type = '')
    {
        $this->view->title      = $this->lang->makeup->view;
        $this->view->makeup     = $this->makeup->getByID($id);
        $this->view->type       = $type;
        $this->view->users      = $this->loadModel('user')->getPairs();
        $this->view->preAndNext = $this->loadModel('common', 'sys')->getPreAndNextObject('makeup', $id);
        $this->display();
    }

    /**
     * Delete makeup.
     * 
     * @param  int     $id 
     * @access public
     * @return void
     */
    public function delete($id)
    {
        $makeup = $this->makeup->getByID($id);
        if($makeup->createdBy != $this->app->user->account) $this->send(array('result' => 'fail', 'message' => $this->lang->makeup->denied));

        $this->makeup->delete($id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success'));
    }

    /**
     * Switch status for a makeup.
     * 
     * @param  int    $makeupID 
     * @access public
     * @return void
     */
    public function switchStatus($makeupID)
    {
        $makeup = $this->makeup->getByID($makeupID);
        if(!$makeup) return false;
        if($makeup->createdBy != $this->app->user->account) $this->send(array('result' => 'fail', 'message' => $this->lang->makeup->denied));

        $dates = range(strtotime($makeup->begin), strtotime($makeup->end), 60 * 60 * 24);
 
        if($makeup->status == 'wait')
        {
            $this->dao->update(TABLE_OVERTIME)->set('status')->eq('draft')->where('id')->eq($makeupID)->exec();

            $actionID = $this->loadModel('action')->create('makeup', $makeupID, 'revoked');
            $this->sendmail($makeupID, $actionID);

            $this->loadModel('attend', 'oa')->batchUpdate($dates, $makeup->createdBy);
        }

        if($makeup->status == 'draft')
        {
            $this->dao->update(TABLE_OVERTIME)->set('status')->eq('wait')->where('id')->eq($makeupID)->exec();

            $actionID = $this->loadModel('action')->create('makeup', $makeupID, 'commited');
            $this->sendmail($makeupID, $actionID);

            $this->loadModel('attend', 'oa')->batchUpdate($dates, $makeup->createdBy, '', 'makeup');
        }

        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success'));
    }

    /**
     * Send email.
     * 
     * @param  int    $makeupID 
     * @param  int    $actionID 
     * @access public
     * @return void
     */
    public function sendmail($makeupID, $actionID)
    {
        /* Reset $this->output. */
        $this->clear();

        /* Get action info. */
        $action          = $this->loadModel('action')->getById($actionID);
        $history         = $this->action->getHistory($actionID);
        $action->history = isset($history[$actionID]) ? $history[$actionID] : array();

        /* Set toList and ccList. */
        $makeup = $this->makeup->getById($makeupID);
        $users  = $this->loadModel('user')->getPairs();
        $toList = '';
        if($action->action == 'reviewed')
        {
            $toList  = $makeup->createdBy;
            $subject = "{$this->lang->makeup->common}{$this->lang->makeup->statusList[$makeup->status]}#{$makeup->id} " . zget($users, $makeup->createdBy) . " {$makeup->begin}~{$makeup->end}";
        }
        if($action->action == 'created' or $action->action == 'revoked' or $action->action == 'commited')
        {
            $this->app->loadModuleConfig('attend');
            $reviewedBy = $this->makeup->getReviewedBy();
            if($reviewedBy)
            {
                $toList = $reviewedBy; 
            }
            else
            {
               $dept = $this->loadModel('tree')->getByID($this->app->user->dept);
               if($dept) $toList = trim($dept->moderators, ',');
            }

            $subject = "{$this->lang->makeup->common}#{$makeup->id} " . zget($users, $makeup->createdBy) . " {$makeup->begin}~{$makeup->end}";
        }

        /* send notice if user is online and return failed accounts. */
        $toList = $this->loadModel('action')->sendNotice($actionID, $toList);

        /* Create the email content. */
        $this->view->makeup = $makeup;
        $this->view->action = $action;
        $this->view->users  = $users;

        $mailContent = $this->parse($this->moduleName, 'sendmail');

        /* Send emails. */
        $this->loadModel('mail')->send($toList, $subject, $mailContent);
        if($this->mail->isError()) trigger_error(join("\n", $this->mail->getError()));
    }

    /**
     * get data to export.
     * 
     * @param  string $mode 
     * @param  string $orderBy 
     * @access public
     * @return void
     */
    public function export($mode = 'all', $orderBy = 'id_desc')
    { 
        if($_POST)
        {
            $deptList  = $this->loadModel('tree')->getPairs('', 'dept');
            $users     = $this->loadModel('user')->getList();
            $userPairs = array();
            $userDepts = array();
            foreach($users as $key => $user) 
            {
                $userPairs[$user->account] = $user->realname;
                $userDepts[$user->account] = zget($deptList, $user->dept, ' ');
            }

            /* Create field lists. */
            $fields = explode(',', $this->config->makeup->list->exportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = isset($this->lang->makeup->$fieldName) ? $this->lang->makeup->$fieldName : $fieldName;
                unset($fields[$key]);
            }
            $fields['dept'] = $this->lang->user->dept;

            $makeups = array();
            if($mode == 'all')
            {
                $makeupQueryCondition = $this->session->makeupQueryCondition;
                if(strpos($makeupQueryCondition, 'limit') !== false) $makeupQueryCondition = substr($makeupQueryCondition, 0, strpos($makeupQueryCondition, 'limit'));
                $stmt = $this->dbh->query($makeupQueryCondition);
                while($row = $stmt->fetch()) $makeups[$row->id] = $row;
            }
            if($mode == 'thisPage')
            {
                $stmt = $this->dbh->query($this->session->makeupQueryCondition);
                while($row = $stmt->fetch()) $makeups[$row->id] = $row;
            }

            foreach($makeups as $makeup)
            {
                $makeup->createdBy  = zget($userPairs, $makeup->createdBy);
                $makeup->dept       = zget($userDepts, $makeup->createdBy);
                $makeup->type       = zget($this->lang->makeup->typeList, $makeup->type);
                $makeup->begin      = $makeup->begin . ' ' . $makeup->start;
                $makeup->end        = $makeup->end   . ' ' . $makeup->finish;
                $makeup->desc       = htmlspecialchars_decode($makeup->desc);
                $makeup->desc       = str_replace("<br />", "\n", $makeup->desc);
                $makeup->desc       = str_replace('"', '""', $makeup->desc);
                $makeup->status     = zget($this->lang->makeup->statusList, $makeup->status);
                $makeup->reviewedBy = zget($userPairs, $makeup->reviewedBy);
            }

            $this->post->set('fields', $fields);
            $this->post->set('rows', $makeups);
            $this->post->set('kind', 'makeup');
            $this->fetch('file', 'export2CSV' , $_POST);
        }

        $this->display();
    }

    /**
     * Set reviewer. 
     * 
     * @param  string $module 
     * @access public
     * @return void
     */
    public function setReviewer($module = '')
    {
        if($_POST)
        {
            $this->loadModel('setting')->setItem('system.oa.makeup..reviewedBy', $this->post->reviewedBy);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
        }

        if($module)
        {
            $this->lang->menuGroups->makeup = $module;
            $this->lang->makeup->menu       = $this->lang->$module->menu;
        }

        $this->view->title      = $this->lang->makeup->setReviewer;
        $this->view->users      = $this->loadModel('user', 'sys')->getPairs('noclosed,noforbidden,nodelete');
        $this->view->reviewedBy = $this->makeup->getReviewedBy();
        $this->display();
    }
}
