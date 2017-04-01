<?php
/**
 * The control file of leave of Ranzhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     leave
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class leave extends control
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
     * personal's leave. 
     * 
     * @param  string $date 
     * @access public
     * @return void
     */
    public function personal($date = '', $orderBy = 'id_desc')
    {
        die($this->fetch('leave', 'browse', "type=personal&date=$date&orderBy=$orderBy", 'oa'));
    }

    /**
     * Department's leave. 
     * 
     * @param  string $date 
     * @access public
     * @return void
     */
    public function browseReview($date = '', $orderBy = 'id_desc')
    {
        die($this->fetch('leave', 'browse', "type=browseReview&date=$date&orderBy=$orderBy", 'oa'));
    }

    /**
     * Company's leave. 
     * 
     * @param  string $date 
     * @access public
     * @return void
     */
    public function company($date = '', $orderBy = 'id_desc')
    {
        die($this->fetch('leave', 'browse', "type=company&date=$date&orderBy=$orderBy", 'oa'));
    }

    /**
     * browse 
     * 
     * @param  string $type 
     * @param  string $date 
     * @access public
     * @return void
     */
    public function browse($type = 'personal', $date = '', $orderBy = 'id_desc')
    {
        if($date == '' or (strlen($date) != 6 and strlen($date) != 4)) $date = date("Ym");
        $currentYear  = substr($date, 0, 4);
        $currentMonth = strlen($date) == 6 ? substr($date, 4, 2) : '';
        $monthList    = $this->leave->getAllMonth($type);
        $yearList     = array_keys($monthList);
        $deptList     = $this->loadModel('tree')->getPairs(0, 'dept');
        $leaveList    = array();

        if($type == 'personal')
        {
            $leaveList = $this->leave->getList($type, $currentYear, $currentMonth, $this->app->user->account, '', '', $orderBy);
        }
        elseif($type == 'browseReview')
        {
            $this->app->loadModuleConfig('attend');
            $reviewedBy = $this->leave->getReviewedBy();
            if($reviewedBy)
            { 
                if($reviewedBy == $this->app->user->account)
                {
                    $deptList = $this->loadModel('tree')->getPairs('', 'dept');
                    $deptList[0] = '/';
                    $leaveList = $this->leave->getList($type, $currentYear, $currentMonth, '', array_keys($deptList), '', $orderBy);
                }
            }
            else
            {
                $deptList = $this->loadModel('tree')->getDeptManagedByMe($this->app->user->account);
                if(empty($deptList))
                {
                    $leaveList = array();
                }
                else
                {
                    foreach($deptList as $key => $value) $deptList[$key] = $value->name;
                    $leaveList = $this->leave->getList($type, $currentYear, $currentMonth, '', array_keys($deptList), '', $orderBy);
                }
            }
        }
        elseif($type == 'company')
        {
            $leaveList = $this->leave->getList($type, $currentYear, $currentMonth, '', '', '', $orderBy);
        }

        $this->view->title        = $this->lang->leave->browse;
        $this->view->type         = $type;
        $this->view->currentYear  = $currentYear;
        $this->view->currentMonth = $currentMonth;
        $this->view->monthList    = $monthList;
        $this->view->yearList     = $yearList;
        $this->view->deptList     = $deptList;
        $this->view->users        = $this->loadModel('user')->getPairs();
        $this->view->leaveList    = $leaveList;
        $this->view->date         = $date;
        $this->view->orderBy      = $orderBy;
        $this->display();
    }

    public function view($leaveID, $type = '')
    {
        $leave = $this->leave->getByID($leaveID);

        $this->view->title = $this->lang->leave->view;
        $this->view->depts = $this->loadModel('tree')->getPairs(0, 'dept');
        $this->view->user  = $this->loadModel('user')->getByAccount($leave->createdBy);
        $this->view->users = $this->user->getPairs();
        $this->view->type  = $type;
        $this->view->leave = $leave;
        $this->display();
    }

    /**
     * review 
     * 
     * @param  int    $id 
     * @param  string $status 
     * @access public
     * @return void
     */
    public function review($id, $status)
    {
        $leave = $this->leave->getById($id);

        /* Check privilage. */
        $this->app->loadModuleConfig('attend');
        $reviewedBy = $this->leave->getReviewedBy();
        if($reviewedBy)
        { 
            if($reviewedBy != $this->app->user->account) $this->send(array('result' => 'fail', 'message' => $this->lang->leave->denied));
        }
        else
        {
            $createdUser = $this->loadModel('user')->getByAccount($leave->createdBy);
            $dept = $this->loadModel('tree')->getByID($createdUser->dept);
            if((empty($dept) or ",{$this->app->user->account}," != $dept->moderators)) $this->send(array('result' => 'fail', 'message' => $this->lang->leave->denied));
        }

        if($status == 'back')
        {
            $this->leave->reviewBackDate($id);
            $status = 'pass';
        }
        else
        {
            $this->leave->review($id, $status);
        }
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $actionID = $this->loadModel('action')->create('leave', $id, 'reviewed', '', zget($this->lang->leave->statusList, $status));
        $this->sendmail($id, $actionID);

        $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
    }

    /**
     * create leave.
     * 
     * @access public
     * @return void
     */
    public function create($date = '')
    {
        if($_POST)
        {
            $result = $this->leave->create();
            if(is_array($result)) $this->send($result);

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $leaveID  = $result;
            $actionID = $this->loadModel('action')->create('leave', $leaveID, 'created');
            $this->sendmail($leaveID, $actionID);

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('personal')));
        }

        if($date)
        {
            $date  = date('Y-m-d', strtotime($date));
            $leave = $this->leave->getByDate($date, $this->app->user->account);
            if($leave) $this->locate(inlink('edit', "id=$leave->id"));
        }

        $this->app->loadModuleConfig('attend');
        $this->view->title = $this->lang->leave->create;
        $this->view->date  = $date;
        $this->display();
    }

    /**
     * Edit leave.
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function edit($id)
    {
        $leave = $this->leave->getById($id);

        /* check privilage. */
        $this->app->loadModuleConfig('attend');
        $reviewedBy = $this->leave->getReviewedBy();
        if(!reviewedBy)
        {
            $createdUser = $this->loadModel('user')->getByAccount($leave->createdBy);
            $dept        = $this->loadModel('tree')->getByID($createdUser->dept);
            $reviewedBy  = empty($dept) ? '' : trim($dept->moderators, ',');
        }

        if($leave->createdBy != $this->app->user->account and $this->app->user->account != $reviewedBy) 
        {
            $locate     = helper::safe64Encode(helper::createLink('oa.leave', 'browse'));
            $noticeLink = helper::createLink('notice', 'index', "type=accessLimited&locate={$locate}");
            die(js::locate($noticeLink));
        }

        if($_POST)
        {
            $result = $this->leave->update($id);
            if(is_array($result)) $this->send($result);

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $this->view->title = $this->lang->leave->edit;
        $this->view->leave = $leave;
        $this->display();
    }

    /**
     * Back to report.
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function back($id)
    {
        $leave = $this->leave->getByID($id);
        if($leave->createdBy != $this->app->user->account) 
        {
            $locate     = helper::safe64Encode(helper::createLink('oa.leave', 'personal'));
            $noticeLink = helper::createLink('notice', 'index', "type=accessLimited&locate={$locate}");
            die(js::locate($noticeLink));
        }

        if($_POST)
        {
            if($this->post->backDate < ($leave->begin . ' ' . $leave->start)) $this->send(array('result' => 'fail', 'message' => array('backDate' => $this->lang->leave->wrongBackDate)));
            $this->leave->back($id);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $this->view->title = $this->lang->leave->back;
        $this->view->leave = $leave;
        $this->display();
    }

    /**
     * Delete leave.
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function delete($id)
    {
        $leave = $this->leave->getByID($id);
        if($leave->createdBy != $this->app->user->account) $this->send(array('result' => 'fail', 'message' => $this->lang->leave->denied));

        $this->leave->delete($id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success'));
    }

    /**
     * Send email.
     * 
     * @param  int    $leaveID 
     * @param  int    $actionID 
     * @access public
     * @return void
     */
    public function sendmail($leaveID, $actionID)
    {
        /* Reset $this->output. */
        $this->clear();

        /* Get action info. */
        $action          = $this->loadModel('action')->getById($actionID);
        $history         = $this->action->getHistory($actionID);
        $action->history = isset($history[$actionID]) ? $history[$actionID] : array();

        /* Set toList and ccList. */
        $leave  = $this->leave->getById($leaveID);
        $users  = $this->loadModel('user')->getPairs();
        $toList = '';
        if($action->action == 'reviewed')
        {
            $toList = $leave->createdBy;
            $subject = "{$this->lang->leave->common}{$this->lang->leave->statusList[$leave->status]}#{$leave->id} " . zget($users, $leave->createdBy) . " {$leave->begin}~{$leave->end}";
        }
        if($action->action == 'created' or $action->action == 'revoked' or $action->action == 'commited')
        {
            $this->app->loadModuleConfig('attend');
            $reviewedBy = $this->leave->getReviewedBy();
            if($reviewedBy)
            {
                $toList = $reviewedBy; 
            }
            else
            {
               $dept   = $this->loadModel('tree')->getByID($this->app->user->dept);
               $toList = isset($dept->moderators) ? trim($dept->moderators, ',') : '';
            }

            $subject = "{$this->lang->leave->common}#{$leave->id} " . zget($users, $leave->createdBy) . " {$leave->begin}~{$leave->end}";
        }

        /* send notice if user is online and return failed accounts. */
        $toList = $this->loadModel('action')->sendNotice($actionID, $toList);

        /* Create the email content. */
        $this->view->leave  = $leave;
        $this->view->action = $action;
        $this->view->users  = $users;

        $mailContent = $this->parse($this->moduleName, 'sendmail');

        /* Send emails. */
        $this->loadModel('mail')->send($toList, $subject, $mailContent);
        if($this->mail->isError()) trigger_error(join("\n", $this->mail->getError()));
    }

    /**
     * Cancel a leave.
     * 
     * @param  int    $leaveID 
     * @access public
     * @return void
     */
    public function switchStatus($leaveID)
    {
        $leave = $this->leave->getByID($leaveID);
        if(!$leave) return false;
        if($leave->createdBy != $this->app->user->account) $this->send(array('result' => 'fail', 'message' => $this->lang->leave->denied));

        $dates = range(strtotime($leave->begin), strtotime($leave->end), 60*60*24);

        if($leave->status == 'wait')
        {
            $this->dao->update(TABLE_LEAVE)->set('status')->eq('draft')->where('id')->eq($leaveID)->exec();

            $actionID = $this->loadModel('action')->create('leave', $leaveID, 'revoked');
            $this->sendmail($leaveID, $actionID);

            $this->loadModel('attend', 'oa')->batchUpdate($dates, $leave->createdBy);
        }

        if($leave->status == 'draft')
        {
            $this->dao->update(TABLE_LEAVE)->set('status')->eq('wait')->where('id')->eq($leaveID)->exec();

            $actionID = $this->loadModel('action')->create('leave', $leaveID, 'commited');
            $this->sendmail($leaveID, $actionID);

            $this->loadModel('attend', 'oa')->batchUpdate($dates, $leave->createdBy, '', 'leave');
        }

        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success'));
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
            $fields = explode(',', $this->config->leave->list->exportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = isset($this->lang->leave->$fieldName) ? $this->lang->leave->$fieldName : $fieldName;
                unset($fields[$key]);
            }
            $fields['dept'] = $this->lang->user->dept;

            $leaves = array();
            if($mode == 'all')
            {
                $leaveQueryCondition = $this->session->leaveQueryCondition;
                if(strpos($leaveQueryCondition, 'limit') !== false) $leaveQueryCondition = substr($leaveQueryCondition, 0, strpos($leaveQueryCondition, 'limit'));
                $stmt = $this->dbh->query($leaveQueryCondition);
                while($row = $stmt->fetch()) $leaves[$row->id] = $row;
            }
            if($mode == 'thisPage')
            {
                $stmt = $this->dbh->query($this->session->leaveQueryCondition);
                while($row = $stmt->fetch()) $leaves[$row->id] = $row;
            }

            foreach($leaves as $leave)
            {
                $leave->createdBy  = zget($userPairs, $leave->createdBy);
                $leave->dept       = zget($userDepts, $leave->createdBy);
                $leave->type       = zget($this->lang->leave->typeList, $leave->type);
                $leave->begin      = $leave->begin . ' ' . $leave->start;
                $leave->end        = $leave->end   . ' ' . $leave->finish;
                $leave->desc       = htmlspecialchars_decode($leave->desc);
                $leave->desc       = str_replace("<br />", "\n", $leave->desc);
                $leave->desc       = str_replace('"', '""', $leave->desc);
                $leave->status     = zget($this->lang->leave->statusList, $leave->status);
                $leave->reviewedBy = zget($userPairs, $leave->reviewedBy);
            }

            $this->post->set('fields', $fields);
            $this->post->set('rows', $leaves);
            $this->post->set('kind', 'leave');
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
            $this->loadModel('setting')->setItem('system.oa.leave..reviewedBy', $this->post->reviewedBy);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
        }

        if($module)
        {
            $this->lang->menuGroups->leave = $module;
            $this->lang->leave->menu      = $this->lang->$module->menu;
        }

        $this->view->title      = $this->lang->leave->setReviewer;
        $this->view->users      = $this->loadModel('user', 'sys')->getPairs('noclosed,noforbidden,nodelete');
        $this->view->reviewedBy = $this->leave->getReviewedBy();
        $this->display();
    }
}
