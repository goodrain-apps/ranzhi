<?php
/**
 * The control file of attend of Ranzhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     attend
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class attend extends control
{
    /**
     * personal 
     * 
     * @param  string $date 
     * @access public
     * @return void
     */
    public function personal($date = '')
    {
        if($date == '' or strlen($date) != 6) $date = date('Ym');
        $currentYear  = substr($date, 0, 4);
        $currentMonth = substr($date, 4, 2);
        $startDate    = "{$currentYear}-{$currentMonth}-01";
        $endDate      = date('Y-m-d', strtotime("$startDate +1 month"));
        $dayNum       = (int)date('d', strtotime("$endDate -1 day"));
        $weekNum      = (int)ceil($dayNum / 7);

        $attends   = $this->attend->getByAccount($this->app->user->account, $startDate, $endDate > helper::today() ? helper::today() : $endDate);
        $monthList = $this->attend->getAllMonth();
        $yearList  = array_reverse(array_keys($monthList));

        $this->view->title        = $this->lang->attend->personal;
        $this->view->attends      = $attends;
        $this->view->dayNum       = $dayNum;
        $this->view->weekNum      = $weekNum;
        $this->view->currentYear  = $currentYear;
        $this->view->currentMonth = $currentMonth;
        $this->view->yearList     = $yearList;
        $this->view->monthList    = $monthList;
        $this->display();
    }

    /**
     * department's attend. 
     * 
     * @param  string $date 
     * @access public
     * @return void
     */
    public function department($date = '')
    {
        die($this->fetch('attend', 'browse', "date=$date", 'oa'));
    }

    /**
     * company's attend. 
     * 
     * @param  string $date 
     * @access public
     * @return void
     */
    public function company($date = '')
    {
        die($this->fetch('attend', 'browse', "date=$date&company=true", 'oa'));
    }

    /**
     * Browse attend. 
     * 
     * @param  string $date 
     * @param  bool   $company 
     * @access public
     * @return void
     */
    public function browse($date = '', $company = false)
    {
        if($date == '' or strlen($date) != 6) $date = date('Ym');
        $currentYear  = substr($date, 0, 4);
        $currentMonth = substr($date, 4, 2);
        $startDate    = "{$currentYear}-{$currentMonth}-01";
        $endDate      = date('Y-m-d', strtotime("$startDate +1 month"));

        $dayNum    = (int)date('d', strtotime("$endDate -1 day"));
        $weekNum   = (int)ceil($dayNum / 7);
        $monthList = $this->attend->getAllMonth();
        $yearList  = array_reverse(array_keys($monthList));

        /* Get deptList. */
        if($company) 
        {
            $deptList = $this->loadModel('tree')->getPairs('', 'dept');
            $deptList[0] = '/';
        }
        else
        {
            $deptList = $this->loadModel('tree')->getDeptManagedByMe($this->app->user->account);
            foreach($deptList as $key => $value) $deptList[$key] = $value->name;
        }

        /* Get attend. */
        $attends = array();
        if(!empty($deptList)) 
        {
            $dept = array_keys($deptList);
            $attends = $this->attend->getByDept($dept, $startDate, $endDate < helper::today() ? $endDate : helper::today());
        }

        $users    = $this->loadModel('user')->getList();
        $newUsers = array();
        foreach($users as $key => $user) $newUsers[$user->account] = $user;

        $this->view->title        = $this->lang->attend->department;
        $this->view->attends      = $attends;
        $this->view->dayNum       = $dayNum;
        $this->view->weekNum      = $weekNum;
        $this->view->currentYear  = $currentYear;
        $this->view->currentMonth = $currentMonth;
        $this->view->yearList     = $yearList;
        $this->view->monthList    = $monthList;
        $this->view->deptList     = $deptList;
        $this->view->users        = $newUsers;
        $this->view->company      = $company;
        $this->display();
    }

    /**
     * Export attend data.
     * 
     * @param  string $date 
     * @param  bool   $company 
     * @access public
     * @return void
     */
    public function export($date = '', $company = false)
    {
        if($date == '' or strlen($date) != 6) $date = date('Ym');
        $currentYear  = substr($date, 0, 4);
        $currentMonth = substr($date, 4, 2);
        $startDate    = "{$currentYear}-{$currentMonth}-01";
        $endDate      = date('Y-m-d', strtotime("$startDate +1 month"));
        $dayNum       = (int)date('d', strtotime("$endDate -1 day"));

        if($_POST)
        {
            /* Get deptList. */
            if($company) 
            {
                $deptList = $this->loadModel('tree')->getPairs('', 'dept');
                $deptList[0] = '/';
            }
            else
            {
                $deptList = $this->loadModel('tree')->getDeptManagedByMe($this->app->user->account);
                foreach($deptList as $key => $value) $deptList[$key] = $value->name;
            }

            /* Get attend. */
            $attends = array();
            if(!empty($deptList)) 
            {
                $dept = array_keys($deptList);
                $attends = $this->attend->getByDept($dept, $startDate, $endDate < helper::today() ? $endDate : helper::today());
            }

            $users    = $this->loadModel('user')->getList();
            $tmpUsers = array();
            foreach($users as $key => $user) $tmpUsers[$user->account] = $user;
            $users = $tmpUsers;

            /* Get fields. */
            $this->app->loadLang('user');
            $fields['dept']     = $this->lang->user->dept;
            $fields['realname'] = $this->lang->user->realname;
            for($i = 1; $i <= $dayNum; $i++)
            {
                $currentDate  = date("Y-m-d", strtotime("$currentYear-$currentMonth-$i"));
                $fields[$currentDate] = $i;
            }

            /* Get dayname */
            $datas = array();
            $data  = new stdclass();
            $data->dept     = '';
            $data->realname = '';
            for($i = 1; $i <= $dayNum; $i++)
            {
                $currentDate  = date("Y-m-d", strtotime("$currentYear-$currentMonth-$i"));
                $dayNameIndex = date('w', strtotime($currentDate));
                $data->$currentDate = $this->lang->datepicker->abbrDayNames[$dayNameIndex];
            }
            $datas[] = $data;

            /* Get row data. */
            foreach($attends as $dept => $deptAttendList)
            {
                foreach($deptAttendList as $account => $attendList)
                {
                    $data = new stdclass();
                    $data->dept     = $deptList[$users[$account]->dept];
                    $data->realname = $users[$account]->realname;
                    for($i = 1; $i <= $dayNum; $i++)
                    {
                        $currentDate  = date("Y-m-d", strtotime("$currentYear-$currentMonth-$i"));
                        $data->$currentDate = isset($attendList[$currentDate]) ? zget($this->lang->attend->markStatusList, $attendList[$currentDate]->status) : '';
                    }
                    $datas[] = $data;
                }
            }

            /* Get legend. */
            if(!empty($datas))
            {
                $data = new stdclass();
                $data->dept = '';
                $datas[] = $data;

                $legend = array();
                foreach($this->lang->attend->markStatusList as $key => $value)
                {
                    $data = new stdclass();
                    $data->dept = $value;
                    $data->realname = $this->lang->attend->statusList[$key];
                    $datas[] = $data;
                }
            }

            $this->post->set('fields', $fields);
            $this->post->set('rows', $datas);
            $this->post->set('kind', 'attendance');
            $this->fetch('file', 'export2CSV', $_POST);
        }

        $this->view->fileName = $currentYear . $this->lang->year . $currentMonth . $this->lang->month . $this->lang->attend->report;
        $this->display();
    }

    /**
     * Sign in. 
     * 
     * @access public
     * @return void
     */
    public function signIn()
    {
        $result = $this->attend->signIn();
        if(!$result) $this->send(array('result' => 'fail', 'message' => $this->lang->attend->inFail));
        $this->send(array('result' => 'success', 'message' => $this->lang->attend->inSuccess));
    }

    /**
     * Sign out. 
     * 
     * @access public
     * @return void
     */
    public function signOut()
    {
        $result  = $this->attend->signOut();
        if(!$result) $this->send(array('result' => 'fail', 'message' => $this->lang->attend->outFail));
        $this->send(array('result' => 'success', 'message' => $this->lang->attend->outSuccess));
    }

    /**
     * settings 
     * 
     * @access public
     * @return void
     */
    public function settings()
    {
        if($_POST)
        {
            $settings = fixer::input('post')
                ->setDefault('mustSignOut', 'no')
                ->join('mustSignOut', '')
                ->get();

            $settings->signInLimit  = date("H:i", strtotime($settings->signInLimit));
            $settings->signOutLimit = date("H:i", strtotime($settings->signOutLimit));
            $this->loadModel('setting')->setItems('system.oa.attend', $settings);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $this->loadModel('user');
        $this->view->title        = $this->lang->attend->settings; 
        $this->view->signInLimit  = $this->config->attend->signInLimit;
        $this->view->signOutLimit = $this->config->attend->signOutLimit;
        $this->view->workingDays  = $this->config->attend->workingDays;
        $this->view->workingHours = $this->config->attend->workingHours;
        $this->view->mustSignOut  = $this->config->attend->mustSignOut;
        $this->view->reviewedBy   = isset($this->config->attend->reviewedBy) ? $this->config->attend->reviewedBy : '';
        $this->view->users        = array('' => $this->lang->dept->moderators) + $this->user->getPairs('noempty,noclosed,nodeleted');
        $this->display();
    }

    /**
     * add manual sign in and sign out data. 
     * 
     * @param  string $date 
     * @access public
     * @return void
     */
    public function edit($date)
    {
        $account = $this->app->user->account;
        $attend  = $this->attend->getByDate($date, $account);

        if($_POST)
        {
            $result = $this->attend->update($date, $account);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(isset($attend->new)) $attend->id = $result;
            $actionID = $this->loadModel('action')->create('attend', $attend->id, 'commited');
            $this->sendmail($attend->id, $actionID);
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $this->view->title  = $this->lang->attend->edit;
        $this->view->attend = $attend;
        $this->view->date   = $date;
        $this->display();
    }

    /**
     * browse review list.
     * 
     * @param  int    $dept 
     * @access public
     * @return void
     */
    public function browseReview($dept = '')
    {
        $attends  = array();
        $deptList = array();
        /* Get deptments managed by me. */
        if(!empty($this->config->attend->reviewedBy))
        { 
            if($this->config->attend->reviewedBy == $this->app->user->account)
            {
                $deptList = $this->loadModel('tree')->getPairs('', 'dept');
                $deptList['0'] = '/';
            }
        }
        else
        {
            $depts = $this->loadModel('tree')->getDeptManagedByMe($this->app->user->account);
            foreach($depts as $d) $deptList[$d->id] = $d->name;
        }
        if(!empty($deptList)) $attends = $this->attend->getWaitAttends(array_keys($deptList));

        /* Get users info. */
        $users    = $this->loadModel('user')->getList($dept);
        $newUsers = array();
        foreach($users as $key => $user) $newUsers[$user->account] = $user;

        $this->view->title    = $this->lang->attend->review;
        $this->view->users    = $newUsers;
        $this->view->attends  = $attends;
        $this->view->deptList = $deptList;
        $this->display();
    }

    /**
     * Review manual sign data. 
     * 
     * @param  int    $attendID 
     * @param  string $reviewStatus 
     * @access public
     * @return void
     */
    public function review($attendID, $reviewStatus)
    {
        $result = $this->attend->review($attendID, $reviewStatus);
        if(!$result) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $actionID = $this->loadModel('action')->create('attend', $attendID, 'reviewed', '', $reviewStatus);
        $this->sendmail($attendID, $actionID);
        $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('attend', 'browseReview')));
    }

    /**
     * Send email.
     * 
     * @param  int    $attendID 
     * @param  int    $actionID 
     * @access public
     * @return void
     */
    public function sendmail($attendID, $actionID)
    {
        /* Reset $this->output. */
        $this->clear();

        /* Get action info. */
        $action          = $this->loadModel('action')->getById($actionID);
        $history         = $this->action->getHistory($actionID);
        $action->history = isset($history[$actionID]) ? $history[$actionID] : array();

        /* Set toList. */
        $attend  = $this->attend->getById($attendID);
        $users   = $this->loadModel('user')->getPairs('noletter');
        $toList  = $attend->account;
        if($action->action == 'commited')
        {
            if(!empty($this->config->attend->reviewedBy))
            {
                $toList = $this->config->attend->reviewedBy; 
            }
            else
            {
               $dept = $this->loadModel('tree')->getByID($this->app->user->dept);
               if(!empty($dept->moderators)) $toList = trim($dept->moderators, ','); 
            }
        }
        $subject = "{$this->lang->attend->common}#{$attend->account}{$this->lang->colon}{$attend->date}";

        /* send notice if user is online and return failed accounts. */
        $toList = $this->loadModel('action')->sendNotice($actionID, $toList);

        /* Create the email content. */
        $this->view->attend = $attend;
        $this->view->action = $action;
        $this->view->users  = $users;

        $mailContent = $this->parse($this->moduleName, 'sendmail');

        /* Send emails. */
        $this->loadModel('mail')->send($toList, $subject, $mailContent);
        if($this->mail->isError()) trigger_error(join("\n", $this->mail->getError()));
    }

    /**
     * Set reviewer for attend.
     * 
     * @access public
     * @return void
     */
    public function setManager()
    {
        $deptList = $this->loadModel('tree')->getListByType('dept');

        if($_POST)
        {
            $this->attend->setManager();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
        }

        $this->view->deptList = $deptList;
        $this->view->users    = $this->loadModel('user')->getPairs('noclosed,nodeleted');
        $this->display();
    }

    /**
     * Browse stat of attend.
     * 
     * @param  string  $date 
     * @param  string  $mode 
     * @access public
     * @return void
     */
    public function stat($date = '', $mode = '')
    {
        if($date == '' or strlen($date) != 6) $date = date('Ym');
        $currentYear  = substr($date, 0, 4);
        $currentMonth = substr($date, 4, 2);
        $startDate    = "{$currentYear}-{$currentMonth}-01";
        $endDate      = date('Y-m-d', strtotime("$startDate +1 month"));
        $endDate      = date('Y-m-d', strtotime("$endDate -1 day"));
        $workingDays  = $this->attend->computeWorkingDays($startDate, $endDate);

        $stat = $this->attend->getStat($date);
        if(!empty($stat))
        {
            $mode = $mode ? $mode : 'view';
            $this->app->loadLang('leave');
            $this->app->loadLang('overtime');
        }
        else
        {
            $mode = 'edit';

            $attends   = $this->attend->getGroupByAccount($startDate, $endDate < helper::today() ? $endDate : helper::today());
            $trips     = $this->loadModel('trip')->getList($currentYear, $currentMonth);
            $leaves    = $this->loadModel('leave')->getList($type = 'company', $currentYear, $currentMonth, '', '', $status = 'pass');
            $overtimes = $this->loadModel('overtime')->getList($type = 'company', $currentYear, $currentMonth, '', '', $status = 'pass');

            $stat = array();
            foreach($attends as $account => $accountAttends)
            {
                $stat[$account] = new stdclass(); 
                $stat[$account]->deserve  = $workingDays;
                $stat[$account]->normal   = 0;
                $stat[$account]->abnormal = 0;
                $stat[$account]->late     = 0;
                $stat[$account]->early    = 0;
                $stat[$account]->trip     = 0;

                $stat[$account]->paidLeave   = 0;
                $stat[$account]->unpaidLeave = 0;

                $stat[$account]->timeOvertime    = 0;
                $stat[$account]->restOvertime    = 0;
                $stat[$account]->holidayOvertime = 0;

                foreach($accountAttends as $attend)
                {
                    if($attend->status == 'normal') $stat[$account]->normal ++;
                    if($attend->status == 'absent') $stat[$account]->absent ++;

                    if($attend->status == 'late' or $attend->status == 'both')
                    {
                        $stat[$account]->late ++;
                        $stat[$account]->abnormal ++;
                    }
                    if($attend->status == 'early' or $attend->status == 'both')
                    {
                        $stat[$account]->early ++;
                        $stat[$account]->abnormal ++;
                    }

                    if($attend->status == 'trip')
                    {
                        foreach($trips as $trip)
                        {
                            if($trip->end >= $attend->date and $attend->date >= $trip->begin and $trip->createdBy == $account)
                            {
                                if($attend->desc < ($this->config->attend->signOutLimit - $this->config->attend->signInLimit))
                                {
                                    $stat[$account]->trip += round($attend->desc / $this->config->attend->workingHours, 2);
                                    $stat[$account]->normal += 1 - round($attend->desc / $this->config->attend->workingHours, 2);
                                }
                                else
                                {
                                    $stat[$account]->trip ++;
                                }
                            }
                        }
                    }

                    if($attend->status == 'leave')
                    {
                        foreach($leaves as $leave)
                        {
                            if($leave->end >= $attend->date and $attend->date >= $leave->begin and $leave->createdBy == $account)
                            {
                                if($attend->desc < ($this->config->attend->signOutLimit - $this->config->attend->signInLimit))
                                {
                                    if(strpos('affairs,sick', $leave->type) !== false)
                                    {
                                        $stat[$account]->unpaidLeave += round($attend->desc / $this->config->attend->workingHours, 2);
                                        $stat[$account]->normal += 1 - round($attend->desc / $this->config->attend->workingHours, 2);
                                    }
                                    if(strpos('annual,home,marry,maternity', $leave->type) !== false)
                                    {
                                        $stat[$account]->paidLeave += round($attend->desc / $this->config->attend->workingHours, 2);
                                        $stat[$account]->normal += 1 - round($attend->desc / $this->config->attend->workingHours, 2);
                                    }
                                }
                                else
                                {
                                    if(strpos('affairs,sick', $leave->type) !== false)  $stat[$account]->unpaidLeave ++;
                                    if(strpos('annual,home,marry,maternity', $leave->type) !== false) $stat[$account]->paidLeave ++;
                                }
                            }
                        }
                    }
                    if($attend->status == 'overtime')
                    {
                        foreach($overtimes as $overtime)
                        {
                            if($overtime->end >= $attend->date and $attend->date >= $overtime->begin and $overtime->createdBy == $account)
                            {
                                if($overtime->type == 'time')
                                {
                                    $stat[$account]->timeOvertime += round($attend->desc / $this->config->attend->workingHours, 2);
                                    $stat[$account]->normal ++;
                                }

                                if($attend->desc < ($this->config->attend->signOutLimit - $this->config->attend->signInLimit))
                                {
                                    if($overtime->type == 'rest') $stat[$account]->restOvertime += round($attend->desc / $this->config->attend->workingHours, 2);
                                    if($overtime->type == 'holiday') $stat[$account]->holidayOvertime += round($attend->desc / $this->config->attend->workingHours, 2);
                                    if($overtime->type == 'lieu') $stat[$account]->normal += round($attend->desc / $this->config->attend->workingHours, 2);
                                }
                                else
                                {
                                    if($overtime->type == 'rest') $stat[$account]->restOvertime ++;
                                    if($overtime->type == 'holiday') $stat[$account]->holidayOvertime ++;
                                    if($overtime->type == 'lieu') $stat[$account]->normal ++;
                                }
                            }
                        }
                    }
                }

                $stat[$account]->actual = $stat[$account]->normal + $stat[$account]->restOvertime + $stat[$account]->holidayOvertime + $stat[$account]->timeOvertime + $stat[$account]->trip + $stat[$account]->abnormal;
                $stat[$account]->absent = ($workingDays - count($accountAttends) + $stat[$account]->restOvertime + $stat[$account]->holidayOvertime + $stat[$account]->timeOvertime > 0) ? ($workingDays - count($accountAttends) + $stat[$account]->restOvertime + $stat[$account]->holidayOvertime + $stat[$account]->timeOvertime) : 0;
            }
        }

        $monthList = $this->attend->getAllMonth();
        $yearList  = array_reverse(array_keys($monthList));

        $this->view->title        = $this->lang->attend->stat;
        $this->view->mode         = $mode;
        $this->view->stat         = $stat;
        $this->view->date         = $date;
        $this->view->currentYear  = $currentYear;
        $this->view->currentMonth = $currentMonth;
        $this->view->yearList     = $yearList;
        $this->view->monthList    = $monthList;
        $this->view->users        = $this->loadModel('user')->getPairs();
        $this->display();
    }

    /**
     * Save stat for attend.
     * 
     * @param  string    $date 
     * @access public
     * @return void
     */
    public function saveStat($date)
    {
        if($_POST)
        {
            $this->attend->saveStat($date);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('stat', "date=$date")));
        }
    }

    /**
     * Export attend stat.
     * 
     * @param  string   $date 
     * @access public
     * @return void
     */
    public function exportStat($date = '')
    {
        if($date == '' or strlen($date) != 6) $date = date('Ym', strtotime('last month'));
        $currentYear  = substr($date, 0, 4);
        $currentMonth = substr($date, 4, 2);

        if($_POST)
        {
            $statList = $this->attend->getStat($date);
            $users    = $this->loadModel('user')->getPairs();
            $this->app->loadLang('leave');
            $this->app->loadLang('overtime');

            /* Get fields. */
            $fields['realname']        = $this->lang->user->realname;
            $fields['normal']          = $this->lang->attend->statusList['normal'];
            $fields['late']            = $this->lang->attend->statusList['late'];
            $fields['early']           = $this->lang->attend->statusList['early'];
            $fields['absent']          = $this->lang->attend->statusList['absent'];
            $fields['trip']            = $this->lang->attend->statusList['trip'];
            $fields['paidLeave']       = $this->lang->leave->paid;
            $fields['unpaidLeave']     = $this->lang->leave->unpaid;
            $fields['timeOvertime']    = $this->lang->overtime->typeList['time'];
            $fields['restOvertime']    = $this->lang->overtime->typeList['rest'];
            $fields['holidayOvertime'] = $this->lang->overtime->typeList['holiday'];
            $fields['deserve']         = $this->lang->attend->deserveDays;
            $fields['actual']          = $this->lang->attend->actualDays;

            $datas = array();
            foreach($statList as $account => $stat)
            {
                $data  = new stdclass();
                $data->realname        = $users[$account];
                $data->normal          = $stat->normal;
                $data->late            = $stat->late;
                $data->early           = $stat->early;
                $data->absent          = $stat->absent;
                $data->trip            = $stat->trip;
                $data->paidLeave       = $stat->paidLeave;
                $data->unpaidLeave     = $stat->unpaidLeave;
                $data->timeOvertime    = $stat->timeOvertime;
                $data->restOvertime    = $stat->restOvertime;
                $data->holidayOvertime = $stat->holidayOvertime;
                $data->deserve         = $stat->deserve;
                $data->actual          = $stat->actual;

                $datas[] = $data;
            }

            $this->post->set('fields', $fields);
            $this->post->set('rows', $datas);
            $this->post->set('kind', 'attendstat');
            $this->fetch('file', 'export2CSV', $_POST);
        }

        $this->view->fileName = $currentYear . $this->lang->year . $currentMonth . $this->lang->month . $this->lang->attend->report;
        $this->display();
    }
}
