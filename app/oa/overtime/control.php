<?php
/**
 * The control file of overtime of Ranzhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     overtime
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class overtime extends control
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
     * Personal's overtime. 
     * 
     * @param  string  $date 
     * @param  string  $orderBy 
     * @access public
     * @return void
     */
    public function personal($date = '', $orderBy = 'id_desc')
    {
        die($this->fetch('overtime', 'browse', "type=personal&date=$date&orderBy=$orderBy", 'oa'));
    }

    /**
     * Department's overtime. 
     * 
     * @param  string   $date 
     * @param  string   $orderBy 
     * @access public
     * @return void
     */
    public function browseReview($date = '', $orderBy = 'id_desc')
    {
        die($this->fetch('overtime', 'browse', "type=browseReview&date=$date&orderBy=$orderBy", 'oa'));
    }

    /**
     * Company's overtime. 
     * 
     * @param  string   $date 
     * @param  string   $orderBy 
     * @access public
     * @return void
     */
    public function company($date = '', $orderBy = 'id_desc')
    {
        die($this->fetch('overtime', 'browse', "type=company&date=$date", 'oa'));
    }

    /**
     * Browse overtime.
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
        $monthList    = $this->overtime->getAllMonth();
        $yearList     = array_reverse(array_keys($monthList));
        $deptList     = $this->loadModel('tree')->getPairs(0, 'dept');
        $overtimeList = array();

        if($type == 'personal')
        {
            $overtimeList = $this->overtime->getList($type, $currentYear, $currentMonth, $this->app->user->account, '', '', $orderBy);
        }
        elseif($type == 'browseReview')
        {
            if(!empty($this->config->attend->reviewedBy))
            { 
                if($this->config->attend->reviewedBy == $this->app->user->account)
                {
                    $deptList = $this->loadModel('tree')->getPairs('', 'dept');
                    $deptList[0] = '/';
                    $overtimeList = $this->overtime->getList($type, $currentYear, $currentMonth, '', array_keys($deptList), '', $orderBy);
                }
            }
            else
            {
                $deptList = $this->loadModel('tree')->getDeptManagedByMe($this->app->user->account);
                if(empty($deptList))
                {
                    $overtimeList = array();
                }
                else
                {
                    foreach($deptList as $key => $value) $deptList[$key] = $value->name;
                    $overtimeList = $this->overtime->getList($type, $currentYear, $currentMonth, '', array_keys($deptList), '', $orderBy);
                }
            }
        }
        elseif($type == 'company')
        {
            $overtimeList = $this->overtime->getList($type, $currentYear, $currentMonth, '', '', '', $orderBy);
        }

        $this->view->title        = $this->lang->overtime->browse;
        $this->view->type         = $type;
        $this->view->currentYear  = $currentYear;
        $this->view->currentMonth = $currentMonth;
        $this->view->monthList    = $monthList;
        $this->view->yearList     = $yearList;
        $this->view->deptList     = $deptList;
        $this->view->users        = $this->loadModel('user')->getPairs();
        $this->view->overtimeList = $overtimeList;
        $this->view->date         = $date;
        $this->view->orderBy      = $orderBy;
        $this->display();
    }

    /**
     * Review an overtime.
     * 
     * @param  int      $id 
     * @param  string   $status 
     * @access public
     * @return void
     */
    public function review($id, $status)
    {
        $overtime = $this->overtime->getById($id);

        /* Check privilage. */
        if(!empty($this->config->attend->reviewedBy))
        { 
            if($this->config->attend->reviewedBy != $this->app->user->account) $this->send(array('result' => 'fail', 'message' => $this->lang->overtime->denied));
        }
        else
        {
            $createdUser = $this->loadModel('user')->getByAccount($overtime->createdBy);
            $dept = $this->loadModel('tree')->getByID($createdUser->dept);
            if((empty($dept) or ",{$this->app->user->account}," != $dept->moderators)) $this->send(array('result' => 'fail', 'message' => $this->lang->overtime->denied));
        }

        $this->overtime->review($id, $status);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $actionID = $this->loadModel('action')->create('overtime', $id, 'reviewed', '', $status);
        $this->sendmail($id, $actionID);

        $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
    }

    /**
     * Create an overtime.
     * 
     * @access public
     * @return void
     */
    public function create($date = '')
    {
        if($_POST)
        {
            $result = $this->overtime->create();
            if(is_array($result)) $this->send($result);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if(is_numeric($result))
            {
                $overtimeID = $result;
                $actionID = $this->loadModel('action')->create('overtime', $overtimeID, 'created');
                $this->sendmail($overtimeID, $actionID);
            }
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        if($date)
        {
            $date = date('Y-m-d', strtotime($date));
            $overtime = $this->overtime->getByDate($date, $this->app->user->account);
            if($overtime) $this->locate(inlink('edit', "id=$overtime->id"));
        }

        $this->app->loadConfig('attend');
        $this->view->title = $this->lang->overtime->create;
        $this->view->date  = $date;
        $this->display();
    }

    /**
     * Edit overtime.
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function edit($id)
    {
        $overtime = $this->overtime->getById($id);
        /* check privilage. */
        if($overtime->createdBy != $this->app->user->account) 
        {
            $locate = helper::safe64Encode(helper::createLink('oa.overtime', 'browse'));
            $errorLink = helper::createLink('error', 'index', "type=accessLimited&locate={$locate}");
            die(js::locate($errorLink));
        }

        if($_POST)
        {
            $result = $this->overtime->update($id);
            if(is_array($result)) $this->send($result);

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $this->view->title    = $this->lang->overtime->edit;
        $this->view->overtime = $overtime;
        $this->display();
    }

    /**
     * Delete overtime.
     * 
     * @param  int     $id 
     * @access public
     * @return void
     */
    public function delete($id)
    {
        $overtime = $this->overtime->getByID($id);
        if($overtime->createdBy != $this->app->user->account) $this->send(array('result' => 'fail', 'message' => $this->lang->overtime->denied));

        $this->overtime->delete($id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success'));
    }

    /**
     * Switch status for a overtime.
     * 
     * @param  int    $overtimeID 
     * @access public
     * @return void
     */
    public function switchStatus($overtimeID)
    {
        $overtime = $this->overtime->getByID($overtimeID);
        if(!$overtime) return false;
        if($overtime->createdBy != $this->app->user->account) $this->send(array('result' => 'fail', 'message' => $this->lang->overtime->denied));

        $dates = range(strtotime($overtime->begin), strtotime($overtime->end), 60 * 60 * 24);
 
        if($overtime->status == 'wait')
        {
            $this->dao->update(TABLE_OVERTIME)->set('status')->eq('draft')->where('id')->eq($overtimeID)->exec();

            $actionID = $this->loadModel('action')->create('overtime', $overtimeID, 'revoked');
            $this->sendmail($overtimeID, $actionID);

            $this->loadModel('attend')->batchUpdate($dates, $overtime->createdBy);
        }

        if($overtime->status == 'draft')
        {
            $this->dao->update(TABLE_OVERTIME)->set('status')->eq('wait')->where('id')->eq($overtimeID)->exec();

            $actionID = $this->loadModel('action')->create('overtime', $overtimeID, 'commited');
            $this->sendmail($overtimeID, $actionID);

            $this->loadModel('attend')->batchUpdate($dates, $overtime->createdBy, '', 'overtime');
        }

        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('personal')));
    }

    /**
     * Send email.
     * 
     * @param  int    $overtimeID 
     * @param  int    $actionID 
     * @access public
     * @return void
     */
    public function sendmail($overtimeID, $actionID)
    {
        /* Reset $this->output. */
        $this->clear();

        /* Get action info. */
        $action          = $this->loadModel('action')->getById($actionID);
        $history         = $this->action->getHistory($actionID);
        $action->history = isset($history[$actionID]) ? $history[$actionID] : array();

        /* Set toList and ccList. */
        $overtime = $this->overtime->getById($overtimeID);
        $users  = $this->loadModel('user')->getPairs('noletter');
        if($action->action == 'reviewed')
        {
            $toList = $overtime->createdBy;
            $subject = "{$this->lang->overtime->common}{$this->lang->overtime->statusList[$overtime->status]}#{$overtime->id}{$this->lang->colon}{$overtime->begin}~{$overtime->end}";
        }
        if($action->action == 'created' or $action->action == 'revoked' or $action->action == 'commited')
        {
            if(!empty($this->config->attend->reviewedBy))
            {
                $toList = $this->config->attend->reviewedBy; 
            }
            else
            {
               $dept = $this->loadModel('tree')->getByID($this->app->user->dept);
               if($dept) $toList = trim($dept->moderators, ',');
            }

            $subject = "{$this->lang->overtime->common}#{$overtime->id}{$this->lang->colon}{$overtime->begin}~{$overtime->end}";
        }

        /* send notice if user is online and return failed accounts. */
        $toList = $this->loadModel('action')->sendNotice($actionID, $toList);

        /* Create the email content. */
        $this->view->overtime  = $overtime;
        $this->view->action = $action;
        $this->view->users  = $users;

        $mailContent = $this->parse($this->moduleName, 'sendmail');

        /* Send emails. */
        $this->loadModel('mail')->send($toList, $subject, $mailContent);
        if($this->mail->isError()) trigger_error(join("\n", $this->mail->getError()));
    }
}
