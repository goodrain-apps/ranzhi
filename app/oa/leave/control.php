<?php
/**
 * The control file of leave of Ranzhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
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
        die($this->fetch('leave', 'browse', "type=company&date=$date", 'oa'));
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
        $monthList    = $this->leave->getAllMonth();
        $yearList     = array_reverse(array_keys($monthList));
        $deptList     = $this->loadModel('tree')->getPairs(0, 'dept');
        $leaveList    = array();

        if($type == 'personal')
        {
            $leaveList = $this->leave->getList($type, $currentYear, $currentMonth, $this->app->user->account, '', '', $orderBy);
        }
        elseif($type == 'browseReview')
        {
            if(!empty($this->config->attend->reviewedBy))
            { 
                if($this->config->attend->reviewedBy == $this->app->user->account)
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
        if(!empty($this->config->attend->reviewedBy))
        { 
            if($this->config->attend->reviewedBy != $this->app->user->account) $this->send(array('result' => 'fail', 'message' => $this->lang->leave->denied));
        }
        else
        {
            $createdUser = $this->loadModel('user')->getByAccount($leave->createdBy);
            $dept = $this->loadModel('tree')->getByID($createdUser->dept);
            if((empty($dept) or ",{$this->app->user->account}," != $dept->moderators)) $this->send(array('result' => 'fail', 'message' => $this->lang->leave->denied));
        }

        $this->leave->review($id, $status);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $actionID = $this->loadModel('action')->create('leave', $id, 'reviewed', '', $status);
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

            if(is_numeric($result))
            {
                $leaveID = $result;
                $actionID = $this->loadModel('action')->create('leave', $leaveID, 'created');
                $this->sendmail($leaveID, $actionID);
            }

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        if($date)
        {
            $date = date('Y-m-d', strtotime($date));
            $leave = $this->leave->getByDate($date, $this->app->user->account);
            if($leave) $this->locate(inlink('edit', "id=$leave->id"));
        }

        $this->app->loadConfig('attend');
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
        if($leave->createdBy != $this->app->user->account) 
        {
            $locate = helper::safe64Encode(helper::createLink('oa.leave', 'browse'));
            $errorLink = helper::createLink('error', 'index', "type=accessLimited&locate={$locate}");
            die(js::locate($errorLink));
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
        $users  = $this->loadModel('user')->getPairs('noletter');
        if($action->action == 'reviewed')
        {
            $toList = $leave->createdBy;
            $subject = "{$this->lang->leave->common}{$this->lang->leave->statusList[$leave->status]}#{$leave->id}{$this->lang->colon}{$leave->begin}~{$leave->end}";
        }
        if($action->action == 'created' or $action->action == 'revoked' or $action->action == 'commited')
        {
            if(!empty($this->config->attend->reviewedBy))
            {
                $toList = $this->config->attend->reviewedBy; 
            }
            else
            {
               $dept   = $this->loadModel('tree')->getByID($this->app->user->dept);
               $toList = isset($dept->moderators) ? trim($dept->moderators, ',') : '';
            }

            $subject = "{$this->lang->leave->common}#{$leave->id}{$this->lang->colon}{$leave->begin}~{$leave->end}";
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

            $this->loadModel('attend')->batchUpdate($dates, $leave->createdBy);
        }

        if($leave->status == 'draft')
        {
            $this->dao->update(TABLE_LEAVE)->set('status')->eq('wait')->where('id')->eq($leaveID)->exec();

            $actionID = $this->loadModel('action')->create('leave', $leaveID, 'commited');
            $this->sendmail($leaveID, $actionID);

            $this->loadModel('attend')->batchUpdate($dates, $leave->createdBy, '', 'leave');
        }

        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('personal')));
    }
}
