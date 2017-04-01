<?php
/**
 * The control file of lieu of Ranzhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     lieu
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class lieu extends control
{
    /**
     * Index.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate(inlink('personal'));
    }

    /**
     * personal's lieu. 
     * 
     * @param  string $date 
     * @access public
     * @return void
     */
    public function personal($date = '', $orderBy = 'id_desc')
    {
        die($this->fetch('lieu', 'browse', "type=personal&date=$date&orderBy=$orderBy", 'oa'));
    }

    /**
     * The lieu browse for review. 
     * 
     * @param  string  $date 
     * @access public
     * @return void
     */
    public function browseReview($date = '', $orderBy = 'id_desc')
    {
        die($this->fetch('lieu', 'browse', "type=browseReview&date=$date&orderBy=$orderBy", 'oa'));
    }

    /**
     * Company's lieu. 
     * 
     * @param  string $date 
     * @access public
     * @return void
     */
    public function company($date = '', $orderBy = 'id_desc')
    {
        die($this->fetch('lieu', 'browse', "type=company&date=$date&orderBy=$orderBy", 'oa'));
    }

    /**
     * Browse lieu list.
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
        $monthList    = $this->lieu->getAllMonth($type);
        $yearList     = array_keys($monthList);
        $deptList     = $this->loadModel('tree')->getPairs(0, 'dept');
        $lieuList     = array();

        if($type == 'personal')
        {
            $lieuList = $this->lieu->getList($type, $currentYear, $currentMonth, $this->app->user->account, '', '', $orderBy);
        }
        elseif($type == 'browseReview')
        {
            $this->app->loadModuleConfig('attend');
            $reviewedBy = $this->lieu->getReviewedBy();
            if($reviewedBy)
            { 
                if($reviewedBy == $this->app->user->account)
                {
                    $deptList    = $this->loadModel('tree')->getPairs('', 'dept');
                    $deptList[0] = '/';
                    $lieuList    = $this->lieu->getList($type, $currentYear, $currentMonth, '', array_keys($deptList), '', $orderBy);
                }
            }
            else
            {
                $deptList = $this->loadModel('tree')->getDeptManagedByMe($this->app->user->account);
                if(empty($deptList))
                {
                    $lieuList = array();
                }
                else
                {
                    foreach($deptList as $key => $value) $deptList[$key] = $value->name;
                    $lieuList = $this->lieu->getList($type, $currentYear, $currentMonth, '', array_keys($deptList), '', $orderBy);
                }
            }
        }
        elseif($type == 'company')
        {
            $lieuList = $this->lieu->getList($type, $currentYear, $currentMonth, '', '', '', $orderBy);
        }

        $this->view->title        = $this->lang->lieu->browse;
        $this->view->type         = $type;
        $this->view->currentYear  = $currentYear;
        $this->view->currentMonth = $currentMonth;
        $this->view->monthList    = $monthList;
        $this->view->yearList     = $yearList;
        $this->view->deptList     = $deptList;
        $this->view->users        = $this->loadModel('user')->getPairs();
        $this->view->lieuList     = $lieuList;
        $this->view->date         = $date;
        $this->view->orderBy      = $orderBy;
        $this->display();
    }

    /**
     * View a lieu.
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function view($id, $type = '')
    {
        $lieu = $this->lieu->getByID($id);
        $lieu->overtimeList = explode(',', trim($lieu->overtime, ','));

        $overtimePairs = array();
        $overtimeList  = $this->loadModel('overtime', 'oa')->getList('company', '', '', $this->app->user->account, '', 'pass');
        foreach($overtimeList as $overtime) $overtimePairs[$overtime->id] = $overtime->begin . ' ' . $overtime->start . ' ~ ' . $overtime->end . ' ' . $overtime->finish;

        $this->view->title         = $this->lang->lieu->view;
        $this->view->lieu          = $lieu;
        $this->view->users         = $this->loadModel('user', 'sys')->getPairs();
        $this->view->overtimePairs = $overtimePairs;
        $this->view->type          = $type;
        $this->display();
    }

    /**
     * Create lieu.
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        $this->app->loadModuleConfig('attend');
        if($_POST)
        {
            $result = $this->lieu->create();
            if(is_array($result)) $this->send($result);

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $lieuID  = $result;
            $actionID = $this->loadModel('action')->create('lieu', $lieuID, 'created');
            $this->sendmail($lieuID, $actionID);

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('personal')));
        }

        $overtimePairs = array('');
        $overtimeList  = $this->loadModel('overtime', 'oa')->getList('company', '', '', $this->app->user->account, '', 'pass');
        $lieuList      = $this->lieu->getList('company', '', '', $this->app->user->account);

        foreach($overtimeList as $key => $overtime)
        {
            $hasLieu = false;
            foreach($lieuList as $lieu)
            {
                if($lieu->status == 'reject') continue;
                if(strpos($lieu->overtime, ',' . $overtime->id . ',') !== false)
                {
                    $hasLieu = true;
                    break;
                }
            }
            if($hasLieu) continue;

            $overtimePairs[$overtime->id] = $overtime->begin . ' ' . $overtime->start . ' ~ ' . $overtime->end . ' ' . $overtime->finish;
        }

        $this->view->title         = $this->lang->lieu->create;
        $this->view->overtimePairs = $overtimePairs;
        $this->display();
    }

    /**
     * Edit lieu.
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function edit($id)
    {
        $this->app->loadModuleConfig('attend');
        $oldLieu = $this->lieu->getByID($id);

        /* check privilage. */
        if($oldLieu->createdBy != $this->app->user->account) 
        {
            $locate     = helper::safe64Encode(helper::createLink('oa.lieu', 'browse'));
            $noticeLink = helper::createLink('notice', 'index', "type=accessLimited&locate={$locate}");
            die(js::locate($noticeLink));
        }

        if($_POST)
        {
            $result = $this->lieu->update($id);
            if(is_array($result)) $this->send($result);

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $overtimePairs = array();
        $overtimeList  = $this->loadModel('overtime', 'oa')->getList('company', '', '', $this->app->user->account, '', 'pass');
        $lieuList      = $this->lieu->getList('company', '', '', $this->app->user->account);

        foreach($overtimeList as $key => $overtime)
        {
            $hasLieu = false;
            foreach($lieuList as $lieu)
            {
                if($lieu->id == $oldLieu->id) continue;
                if(strpos($lieu->overtime, ',' . $overtime->id . ',') !== false)
                {
                    $hasLieu = true;
                    break;
                }
            }
            if($hasLieu) continue;

            $overtimePairs[$overtime->id] = $overtime->begin . ' ' . $overtime->start . ' ~ ' . $overtime->end . ' ' . $overtime->finish;
        }

        $this->view->title         = $this->lang->lieu->edit;
        $this->view->lieu          = $oldLieu;
        $this->view->overtimePairs = array(0 => '') + $overtimePairs;
        $this->display();
    }

    /**
     * Delete lieu.
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function delete($id)
    {
        $lieu = $this->lieu->getByID($id);
        if($lieu->createdBy != $this->app->user->account) $this->send(array('result' => 'fail', 'message' => $this->lang->lieu->denied));

        $this->lieu->delete($id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success'));
    }

    /**
     * Cancel or commit a lieu.
     * 
     * @param  int    $lieuID 
     * @access public
     * @return void
     */
    public function switchStatus($lieuID)
    {
        $lieu = $this->lieu->getByID($lieuID);
        if(!$lieu) return false;

        if($lieu->createdBy != $this->app->user->account) $this->send(array('result' => 'fail', 'message' => $this->lang->liue->denied));

        if($lieu->status == 'wait')
        {
            $this->dao->update(TABLE_LIEU)->set('status')->eq('draft')->where('id')->eq($lieuID)->exec();

            $actionID = $this->loadModel('action')->create('lieu', $lieuID, 'revoked');
            $this->sendmail($lieuID, $actionID);
        }

        if($lieu->status == 'draft')
        {
            $this->dao->update(TABLE_LIEU)->set('status')->eq('wait')->where('id')->eq($lieuID)->exec();

            $actionID = $this->loadModel('action')->create('lieu', $lieuID, 'commited');
            $this->sendmail($lieuID, $actionID);
        }

        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
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
        $lieu = $this->lieu->getById($id);

        /* Check privilage. */
        $this->app->loadModuleConfig('attend');
        $reviewedBy = $this->lieu->getReviewedBy();
        if($reviewedBy)
        { 
            if($reviewedBy != $this->app->user->account) $this->send(array('result' => 'fail', 'message' => $this->lang->lieu->denied));
        }
        else
        {
            $createdUser = $this->loadModel('user')->getByAccount($lieu->createdBy);
            $dept = $this->loadModel('tree')->getByID($createdUser->dept);
            if((empty($dept) or ",{$this->app->user->account}," != $dept->moderators)) $this->send(array('result' => 'fail', 'message' => $this->lang->lieu->denied));
        }

        $this->lieu->review($id, $status);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $actionID = $this->loadModel('action')->create('lieu', $id, 'reviewed', '', zget($this->lang->lieu->statusList, $status));
        $this->sendmail($id, $actionID);

        $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
    }

    /**
     * Send email.
     * 
     * @param  int    $lieuID 
     * @param  int    $actionID 
     * @access public
     * @return void
     */
    public function sendmail($lieuID, $actionID)
    {
        /* Reset $this->output. */
        $this->clear();

        /* Get action info. */
        $action          = $this->loadModel('action')->getById($actionID);
        $history         = $this->action->getHistory($actionID);
        $action->history = isset($history[$actionID]) ? $history[$actionID] : array();

        /* Set toList and ccList. */
        $lieu   = $this->lieu->getById($lieuID);
        $users  = $this->loadModel('user')->getPairs();
        $toList = '';
        if($action->action == 'reviewed')
        {
            $toList = $lieu->createdBy;
            $subject = "{$this->lang->lieu->common}{$this->lang->lieu->statusList[$lieu->status]}#{$lieu->id} " . zget($users, $lieu->createdBy) . " {$lieu->begin}~{$lieu->end}";
        }
        if($action->action == 'created' or $action->action == 'revoked' or $action->action == 'commited')
        {
            $this->app->loadModuleConfig('attend');
            $reviewedBy = $this->lieu->getReviewedBy();
            if($reviewedBy)
            {
                $toList = $reviewedBy; 
            }
            else
            {
               $dept   = $this->loadModel('tree')->getByID($this->app->user->dept);
               $toList = isset($dept->moderators) ? trim($dept->moderators, ',') : '';
            }

            $subject = "{$this->lang->lieu->common}#{$lieu->id} " . zget($users, $lieu->createdBy) . " {$lieu->begin}~{$lieu->end}";
        }

        /* send notice if user is online and return failed accounts. */
        $toList = $this->loadModel('action')->sendNotice($actionID, $toList);

        /* Create the email content. */
        $this->view->lieu   = $lieu;
        $this->view->action = $action;
        $this->view->users  = $users;

        $mailContent = $this->parse($this->moduleName, 'sendmail');

        /* Send emails. */
        $this->loadModel('mail')->send($toList, $subject, $mailContent);
        if($this->mail->isError()) trigger_error(join("\n", $this->mail->getError()));
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
            $this->loadModel('setting')->setItem('system.oa.lieu..reviewedBy', $this->post->reviewedBy);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
        }

        if($module)
        {
            $this->lang->menuGroups->lieu = $module;
            $this->lang->lieu->menu       = $this->lang->$module->menu;
        }

        $this->view->title      = $this->lang->lieu->setReviewer;
        $this->view->users      = $this->loadModel('user', 'sys')->getPairs('noclosed,noforbidden,nodelete');
        $this->view->reviewedBy = $this->lieu->getReviewedBy();
        $this->display();
    }
}
