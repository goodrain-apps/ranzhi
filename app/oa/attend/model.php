<?php
/**
 * The model file of attend module of Ranzhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     attend
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class attendModel extends model
{
    /**
     * Get by attend id. 
     * 
     * @param  int    $attendID 
     * @access public
     * @return object
     */
    public function getByID($attendID)
    {
        $attend = $this->dao->select('*')->from(TABLE_ATTEND)->where('id')->eq($attendID)->fetch();
        return empty($attend) ? $attend : $this->processAttend($attend);
    }

    /**
     * Get by date and account.
     * 
     * @param  string $date 
     * @param  string $account 
     * @access public
     * @return void
     */
    public function getByDate($date, $account)
    {
        $this->processStatus();
        $attend = $this->dao->select('*')->from(TABLE_ATTEND)->where('date')->eq($date)->andWhere('account')->eq($account)->fetch();
        if(empty($attend))
        {
            $attend = new stdclass();
            $attend->account = $account;
            $attend->date    = $date;
            $attend->signIn  = '00:00:00';
            $attend->signOut = '00:00:00';
            $attend->status  = $this->computeStatus($attend);
            $attend->manualIn     = '';
            $attend->manualOut    = '';
            $attend->reason       = '';
            $attend->desc         = '';
            $attend->reviewStatus = '';
            $attend->reviewedBy   = '';
            $attend->reviewedDate = '';
            $attend->new          = true;
        }

        return $this->processAttend($attend);
    }

    /**
     * Get by account. 
     * 
     * @param  string $account 
     * @param  string $startDate 
     * @param  string $endDate 
     * @access public
     * @return array
     */
    public function getByAccount($account, $startDate = '', $endDate = '')
    {
        $this->processStatus();
        $attends = $this->dao->select('*')->from(TABLE_ATTEND)
            ->where('account')->eq($account)
            ->beginIf($startDate != '')->andWhere('`date`')->ge($startDate)->fi()
            ->beginIf($endDate != '')->andWhere('`date`')->le($endDate)->fi()
            ->orderBy('`date`')
            ->fetchAll('date');
        $beginDate = isset($this->config->attend->beginDate->$account) ? $this->config->attend->beginDate->$account : $this->config->attend->beginDate->company;
        if($beginDate)
        {
            foreach($attends as $date => $attend)
            {
                if($beginDate > $date) unset($attends[$date]);
            }
        }

        $attends = $this->fixUserAttendList($attends, $startDate, $endDate);
        return $this->processAttendList($attends);
    }

    /**
     * Get stat.
     * 
     * @param  string    $date 
     * @access public
     * @return array
     */
    public function getStat($date)
    {
        $attends = $this->dao->select('*')->from(TABLE_ATTENDSTAT)->where('month')->eq($date)->fetchAll('account');
        foreach($attends as $account => $attendList)
        {
            if(strpos(',' . $this->config->attend->noAttendUsers . ',', ',' . $account . ',') !== false) unset($attends[$account]);
            $beginDate = isset($this->config->attend->beginDate->$account) ? $this->config->attend->beginDate->$account : $this->config->attend->beginDate->company;
            if($beginDate)
            {
                foreach($attendList as $key => $attend)
                {
                    if($beginDate > $attend->date) unset($attends[$account][$key]);
                }
            }
        }

        return $attends;
    }

    /**
     * Get attends group by account.
     * 
     * @param  string $startDate 
     * @param  string $endDate 
     * @access public
     * @return array
     */
    public function getGroupByAccount($startDate = '', $endDate = '')
    {
        $this->processStatus();

        $users = $this->loadModel('user')->getPairs('noclosed,noempty,nodeleted,noforbidden');
        $attends = $this->dao->select('*')->from(TABLE_ATTEND)
            ->where(1)
            ->beginIf($startDate != '')->andWhere('`date`')->ge($startDate)->fi()
            ->beginIf($endDate != '')->andWhere('`date`')->le($endDate)->fi()
            ->fetchGroup('account');
        unset($attends['guest']);

        foreach($users as $account => $realname)
        {
            if(!isset($attends[$account])) $attends[$account] = array();
        }

        foreach($attends as $account => $attendList)
        {
            if(strpos(',' . $this->config->attend->noAttendUsers . ',', ',' . $account . ',') !== false) unset($attends[$account]);
            $beginDate = isset($this->config->attend->beginDate->$account) ? $this->config->attend->beginDate->$account : $this->config->attend->beginDate->company;
            if($beginDate)
            {
                foreach($attendList as $key => $attend)
                {
                    if($beginDate > $attend->date) unset($attends[$account][$key]);
                }
            }
        }

        return $attends;
    }

    /**
     * Get department's attend list. 
     * 
     * @param  string $deptID
     * @param  string $startDate 
     * @param  string $endDate 
     * @param  string $reviewStatus 
     * @access public
     * @return array
     */
    public function getByDept($deptID, $startDate = '', $endDate = '', $reviewStatus = '')
    {
        $this->processStatus();
        $users = $this->loadModel('user')->getPairs('noclosed,noempty,nodeleted,noforbidden', $deptID);

        $attends = $this->dao->select('t1.*, t2.dept')
            ->from(TABLE_ATTEND)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on("t1.account=t2.account")
            ->where('t1.account')->in(array_keys($users))
            ->beginIf($startDate != '')->andWhere('t1.date')->ge($startDate)->fi()
            ->beginIf($endDate != '')->andWhere('t1.date')->le($endDate)->fi()
            ->beginIf($reviewStatus != '')->andWhere('t1.reviewStatus')->eq($reviewStatus)->fi()
            ->orderBy('t2.dept,t1.date')
            ->fetchAll();

        /* Format attend list. */
        $newAttends = array();
        foreach($attends as $key => $attend) $newAttends[$attend->dept][$attend->account][$attend->date] = $attend; 

        /* Fix dept's user record. */
        if(!is_array($deptID)) $deptID = explode(',', trim($deptID, ','));
        foreach($deptID as $dept)
        {
            if($dept == 0) continue;
            $deptUsers = $this->user->getPairs('noclosed,noempty,nodeleted,noforbidden', $dept);
            foreach($deptUsers as $account => $realname) if(!isset($newAttends[$dept][$account])) $newAttends[$dept][$account] = array();
        }

        /* Fix user's record. */
        foreach($newAttends as $dept => $deptAttends)
        {
            foreach($newAttends[$dept] as $user => $userAttends)
            {
                if(strpos(",{$this->config->attend->noAttendUsers},", ",$user,") !== false)
                {
                    unset($newAttends[$dept][$user]);
                    continue;
                }
                $beginDate = isset($this->config->attend->beginDate->$user) ? $this->config->attend->beginDate->$user : $this->config->attend->beginDate->company;
                if($beginDate)
                {
                    foreach($userAttends as $key => $attend)
                    {
                        if($beginDate > $attend->date) unset($newAttends[$dept][$user][$key]);
                    }
                }

                if($reviewStatus == '') $newAttends[$dept][$user] = $this->fixUserAttendList($newAttends[$dept][$user], $startDate, $endDate, $user);
                $newAttends[$dept][$user] = $this->processAttendList($newAttends[$dept][$user]);

                if(!$newAttends[$dept][$user]) unset($newAttends[$dept][$user]);
            }
        }

        return $newAttends;
    }

    /**
     * Get wait attends.
     * 
     * @param  string $deptID 
     * @access public
     * @return array()
     */
    public function getWaitAttends($deptID = '')
    {
        if($deptID != '') $users = $this->loadModel('user')->getPairs('noclosed,noempty,nodeleted,noforbidden', $deptID);
        return $this->dao->select('*')->from(TABLE_ATTEND)
            ->where('reviewStatus')->eq('wait')
            ->beginIf($deptID != '')->andWhere('account')->in(array_keys($users))->fi()
            ->fetchAll();
    }

    /**
     * Get detail attends. 
     * 
     * @param  string $date 
     * @param  string $account 
     * @param  int    $deptID 
     * @access public
     * @return array 
     */
    public function getDetailAttends($date = '', $account = '', $deptID = 0)
    {
        $currentYear  = substr($date, 0, 4);
        $currentMonth = substr($date, 4, 2);
        $startDate    = "{$currentYear}-{$currentMonth}-01";
        $endDate      = date('Y-m-d', strtotime("$startDate +1 month -1 days"));
        $dayNum       = date('t', strtotime("{$currentYear}-{$currentMonth}"));
        if($currentYear . $currentMonth == date('Ym') && $dayNum > date('d')) $dayNum = date('d');

        $deptList = array('') + $this->loadModel('tree')->getPairs(0, 'dept');
        $userList = $this->loadModel('user')->getList();
        $users    = array();
        foreach($userList as $user) $users[$user->account] = $user;

        /* Get attends. */
        $attendList = array();
        if($account)
        {
            $user    = $users[$account];
            $attends = $this->getByAccount($account, $startDate, $endDate < helper::today() ? $endDate : helper::today());
            $attendList[$user->dept][$account] = $attends;
        }
        else
        {
            if($deptID) 
            {
                $attendList = $this->getByDept($deptID, $startDate, $endDate < helper::today() ? $endDate : helper::today());
            }
            else
            {
                $attendList = $this->getByDept(array_keys($deptList), $startDate, $endDate < helper::today() ? $endDate : helper::today());
            }
        }

        $attends = array();
        foreach($attendList as $dept => $deptAttends)
        {
            ksort($deptAttends);
            foreach($deptAttends as $account => $userAttends)
            {
                if(strpos(",{$this->config->attend->noAttendUsers},", ",$account,") !== false) continue;

                for($day = 1; $day <= $dayNum; $day++)
                {
                    if($day < 10) $day = '0' . $day;
                    $currentDate = "{$currentYear}-{$currentMonth}-{$day}";

                    $attend = zget($userAttends, $currentDate, '');
                    if(!$attend) continue;

                    $attend->dept     = isset($users[$account]) ? $deptList[$users[$account]->dept] : '';
                    $attend->realname = isset($users[$account]) ? $users[$account]->realname : '';
                    $attend->dayName  = $this->lang->datepicker->dayNames[(int)date('w', strtotime($currentDate))];

                    $desc = zget($this->lang->attend->statusList, $attend->status);
                    if(strpos(',leave,makeup,overtime,trip,egress,', ",$attend->status,") !== false and $attend->desc)
                    {
                        $desc .= $attend->desc . $this->lang->attend->h;
                    }
                    elseif($attend->status == 'late' && !empty($attend->signIn))
                    {
                        $seconds = strtotime($attend->signIn) - strtotime($this->config->attend->signInLimit);
                        if($seconds >= 3600)
                        {
                            $hours   = floor($seconds / 3600);
                            $desc   .= $hours . $this->lang->attend->h;
                            $seconds = $seconds % 3600;
                        }
                        if($seconds >= 60)
                        {
                            $minutes = floor($seconds / 60);
                            $seconds = $seconds % 60;
                            $desc   .= $minutes . $this->lang->attend->m;
                        }
                        if($seconds > 0) $desc .= $seconds . $this->lang->attend->s;
                    }
                    elseif($attend->status == 'early' && !empty($attend->signOut))
                    {
                        $seconds = strtotime($this->config->attend->signOutLimit) - strtotime($attend->signOut);
                        if($seconds >= 3600)
                        {
                            $hours   = floor($seconds / 3600);
                            $desc   .= $hours . $this->lang->attend->h;
                            $seconds = $seconds % 3600;
                        }
                        if($seconds >= 60)
                        {
                            $minutes = floor($seconds / 60);
                            $seconds = $seconds % 60;
                            $desc   .= $minutes . $this->lang->attend->m;
                        }
                        if($seconds > 0) $desc .= $seconds . $this->lang->attend->s;
                    }
                    elseif($attend->status == 'both')
                    {
                        $desc = $this->lang->attend->statusList['late'];
                        if(!empty($attend->signIn))
                        {
                            $seconds = strtotime($attend->signIn) - strtotime($this->config->attend->signInLimit);
                            if($seconds >= 3600)
                            {
                                $hours   = floor($seconds / 3600);
                                $desc   .= $hours . $this->lang->attend->h;
                                $seconds = $seconds % 3600;
                            }
                            if($seconds >= 60)
                            {
                                $minutes = floor($seconds / 60);
                                $seconds = $seconds % 60;
                                $desc   .= $minutes . $this->lang->attend->m;
                            }
                            if($seconds > 0) $desc .= $seconds . $this->lang->attend->s;
                        }

                        $desc .= ', ' . $this->lang->attend->statusList['early'];
                        if(!empty($attend->signOut))
                        {
                            $seconds = strtotime($this->config->attend->signOutLimit) - strtotime($attend->signOut);
                            if($seconds >= 3600)
                            {
                                $hours   = floor($seconds / 3600);
                                $desc   .= $hours . $this->lang->attend->h;
                                $seconds = $seconds % 3600;
                            }
                            if($seconds >= 60)
                            {
                                $minutes = floor($seconds / 60);
                                $seconds = $seconds % 60;
                                $desc   .= $minutes . $this->lang->attend->m;
                            }
                            if($seconds > 0) $desc .= $seconds . $this->lang->attend->s;
                        }
                    }
                    $attend->status = zget($this->lang->attend->statusList, $attend->status);
                    $attend->desc   = $desc == $attend->status ? '' : $desc;

                    $attends[] = $attend;
                }
            }
        }

        return $attends;
    }

    /**
     * Get all month data.
     * return array[year][month]
     * 
     * @param  string $type
     * @access public
     * @return array
     */
    public function getAllMonth($type = '')
    {
        if($type == 'department')
        {
            $deptList = $this->loadModel('tree')->getDeptManagedByMe($this->app->user->account);
            $dateList = $this->dao->select('date')->from(TABLE_ATTEND)->alias('t1')
                ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
                ->where('t2.dept')->in(array_keys($deptList))
                ->groupBy('date')
                ->orderBy('date_desc')
                ->fetchAll();
        }
        else
        {
            $dateList = $this->dao->select('date')->from(TABLE_ATTEND)
                ->beginIF($type == 'personal')->where('account')->eq($this->app->user->account)->fi()
                ->groupBy('date')
                ->orderBy('date_desc')
                ->fetchAll();
        }

        $monthList = array();
        foreach($dateList as $date)
        {
            $year  = substr($date->date, 0, 4);
            $month = substr($date->date, 5, 2);
            if(!isset($monthList[$year][$month])) $monthList[$year][$month] = $month;
        }
        return $monthList;
    }

    /**
     * Get notice.
     * 
     * @access public
     * @return string
     */
    public function getNotice()
    {
        $account = $this->app->user->account;
        $today   = helper::today();
        if(strpos(',' . $this->config->attend->noAttendUsers . ',', ',' . $account . ',') !== false ||
           (isset($this->config->attend->readers->{$today}) and strpos(',' . $this->config->attend->readers->{$today} . ',', ',' . $account . ',') !== false)) return '';
        $beginDate = isset($this->config->attend->beginDate->$account) ? $this->config->attend->beginDate->$account : $this->config->attend->beginDate->company;
        if($beginDate && $beginDate > $today) return '';

        $link    = helper::createLink('oa.attend', 'personal');
        $misc    = "class='app-btn alert-link' data-id='oa'";
        $notice  = '';

        $this->lang->attend->statusList['absent'] = $this->lang->attend->notice['absent'];

        $attend = $this->getByDate($today, $account);
        if(empty($attend)) $notice .= sprintf($this->lang->attend->notice['today'], $this->lang->attend->statusList['absent'], $link, $misc); 
        if(!empty($attend) and strpos('late,early,both,absent', $attend->status) !== false and empty($attend->reason)) 
        {
            $notice .= sprintf($this->lang->attend->notice['today'], zget($this->lang->attend->statusList, $attend->status), $link, $misc); 
        }

        $yestoday = date("Y-m-d", strtotime("-1 day"));
        $attend   = $this->getByDate($yestoday, $account);
        if(empty($attend)) $notice .= sprintf($this->lang->attend->notice['yestoday'], $this->lang->attend->statusList['absent'], $link, $misc); 
        if(!empty($attend) and strpos('late,early,both,absent', $attend->status) !== false and empty($attend->reason)) 
        {
            $notice .= sprintf($this->lang->attend->notice['yestoday'], zget($this->lang->attend->statusList, $attend->status), $link, $misc); 
        }

        $fullNotice = <<<EOT
<div id='noticeAttend' class='alert alert-danger with-icon alert-dismissable' style='width:380px; position:fixed; bottom:25px; right:15px; z-index: 9999;' id='planInfo'>
  <i class='icon icon-envelope-alt'>  </i>
  <div class='content'>{$notice}</div>
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
</div>
EOT;
       
        return empty($notice) ? '' : $fullNotice;
    }

    /**
     * sign in.
     * 
     * @param  string $account 
     * @param  string $date 
     * @access public
     * @return bool
     */
    public function signIn($account = '', $date = '')
    {
        if(!$this->checkIP()) return array('result' => 'fail', 'message' => $this->lang->attend->note->IPDenied);

        if($account == '') $account = $this->app->user->account;
        if($date == '')    $date    = date('Y-m-d');

        $attend = $this->dao->select('*')->from(TABLE_ATTEND)->where('account')->eq($account)->andWhere('`date`')->eq($date)->fetch();
        if(empty($attend))
        {
            $attend = new stdclass();
            $attend->account = $account;
            $attend->date    = $date;
            $attend->signIn  = helper::time();
            $attend->ip      = helper::getRemoteIp();
            $this->dao->insert(TABLE_ATTEND)->data($attend)->autoCheck()->exec();
            return !dao::isError();
        }

        if($attend->signIn == '' or $attend->signIn == '00:00:00')
        {
            $this->dao->update(TABLE_ATTEND)->set('signIn')->eq(helper::time())->where('id')->eq($attend->id)->exec();
            return !dao::isError();
        }

        return true;
    }

    /**
     * Check user is sign or not
     * 
     * @param  string $account
     * @param  string $date
     * @access public
     * @return bool | object
     */
    public function checkSignIn($account = '', $date = '')
    {
        if(!$this->checkIP()) return false;
        if($account == '') $account = $this->app->user->account;
        if($date == '')    $date    = date('Y-m-d');

        $attend = $this->dao->select('*')->from(TABLE_ATTEND)->where('account')->eq($account)->andWhere('`date`')->eq($date)->fetch();
        if(!empty($attend) and $attend->signIn != '' and $attend->signIn != '00:00:00') return $attend;
        return false;
    }

    /**
     * sign out.
     * 
     * @param  string $account 
     * @param  string $date 
     * @access public
     * @return bool
     */
    public function signOut($account = '', $date = '')
    {
        if(!$this->checkIP()) return false;
        if($account == '') $account = $this->app->user->account;
        if($date == '')    $date    = date('Y-m-d');

        $attend = $this->dao->select('*')->from(TABLE_ATTEND)->where('account')->eq($account)->andWhere('`date`')->eq($date)->fetch();
        if(empty($attend))
        {
            $attend = new stdclass();
            $attend->account = $account;
            $attend->date    = $date;
            $attend->signOut = helper::time();
            $this->dao->insert(TABLE_ATTEND)->data($attend)->autoCheck()->exec();
            return !dao::isError();
        }

        $attend->signOut = helper::time();
        $status = $this->computeStatus($attend);

        $this->dao->update(TABLE_ATTEND)
            ->set('signOut')->eq(helper::time())
            ->set('status')->eq($status)
            ->where('id')->eq($attend->id)
            ->exec();

        return !dao::isError();
    }

    /**
     * Pass manual sign date.
     * 
     * @param  int    $attendID 
     * @param  string $status 
     * @access public
     * @return bool
     */
    public function review($attendID, $status)
    {
        if($status == 'pass')
        {
            $attend  = $this->getByID($attendID);
            $signIn  = (!empty($attend->manualIn) and $attend->manualIn != '00:00:00') ? $attend->manualIn : $attend->signIn;
            $signOut = (!empty($attend->manualOut) and $attend->manualOut != '00:00:00') ? $attend->manualOut : $attend->signOut;

            $this->dao->update(TABLE_ATTEND)
                ->set('status')->eq($attend->reason)
                ->set('reviewStatus')->eq('pass')
                ->set('signIn')->eq($signIn)
                ->set('signOut')->eq($signOut)
                ->where('id')->eq($attendID)
                ->exec();
        }

        if($status == 'reject')
        {
            $this->dao->update(TABLE_ATTEND)->set('reviewStatus')->eq('reject')->where('id')->eq($attendID)->exec();
        }

        return !dao::isError();
    }

    /**
     * Reject manual sign data.
     * 
     * @param  int    $attendID 
     * @access public
     * @return bool
     */
    public function reject($attendID)
    {
        return !dao::isError();
    }

    /**
     * add manual sign in and sign out date.
     * 
     * @param  string $date 
     * @param  string $account 
     * @access public
     * @return void
     */
    public function update($date, $account)
    {
        $oldAttend = $this->getByDate($date, $account);
        $attend = fixer::input('post')
            ->remove('date,account,signIn,signOut,status,reviewStatus')
            ->setDefault('manualIn', '')
            ->setDefault('manualOut', '')
            ->add('reviewStatus', 'wait')
            ->add('reason', 'normal')
            ->get();

        $attend->manualIn  = date("H:i", strtotime("{$date} {$attend->manualIn}"));
        $attend->manualOut = date("H:i", strtotime("{$date} {$attend->manualOut}"));

        if(isset($oldAttend->new))
        {
            $attend->date    = $date;
            $attend->account = $account;
            $attend->status  = 'absent';
            $this->dao->insert(TABLE_ATTEND)
                ->data($attend)
                ->autoCheck()
                ->exec();

            return $this->dao->lastInsertID();
        }
        else
        {
            $this->dao->update(TABLE_ATTEND)
                ->data($attend)
                ->autoCheck()
                ->where('date')->eq($date)
                ->andWhere('account')->eq($account)
                ->exec();
        }

        return !dao::isError();
    }

    /**
     * Update status of unknow status attend.
     * 
     * @access public
     * @return bool
     */
    public function processStatus()
    {
        $attends = $this->dao->select('*')->from(TABLE_ATTEND)
            ->where('status')->eq('')
            ->andWhere('date')->lt(helper::today())
            ->orWhere('date')->eq(date("Y-m-d"))
            ->fetchAll('id');

        foreach($attends as $attend)
        {
            $status = $this->computeStatus($attend);
            $this->dao->update(TABLE_ATTEND)->set('status')->eq($status)->where('id')->eq($attend->id)->exec();
        }
        return true;
    }

    /**
     * Compute attend's status. 
     * 
     * @param  object $attend 
     * @access public
     * @return string
     */
    public function computeStatus($attend)
    {
        $beginDate = isset($this->config->attend->beginDate->{$attend->account}) ? $this->config->attend->beginDate->{$attend->account} : $this->config->attend->beginDate->company;
        if($beginDate && $beginDate > $attend->date) return 'normal';

        /* 'leave': ask for leave. 'trip': biz trip. */
        if($this->loadModel('leave', 'oa')->isLeave($attend->date, $attend->account)) return 'leave';
        if($this->loadModel('trip', 'oa')->isTrip('trip', $attend->date, $attend->account)) return 'trip';
        if($this->loadModel('trip', 'oa')->isTrip('egress', $attend->date, $attend->account)) return 'egress';
        if($this->loadModel('overtime', 'oa')->isOvertime($attend->date, $attend->account)) return 'overtime';
        if($this->loadModel('makeup', 'oa')->isMakeup($attend->date, $attend->account)) return 'makeup';
        if($this->loadModel('lieu', 'oa')->isLieu($attend->date, $attend->account)) return 'lieu';

        $status = 'normal';
        if(($attend->signIn == "00:00:00" and $attend->signOut == "00:00:00") or (!$attend->signIn and !$attend->signOut)) 
        {
            /* 'absent', absenteeism */
            $status = 'absent';
        }
        else
        {
            /* normal, late, early, both */
            if(strtotime("{$attend->date} {$attend->signIn}") > strtotime("{$attend->date} {$this->config->attend->signInLimit}")) $status = 'late';
            if($this->config->attend->mustSignOut == 'yes')
            {
                if(!empty($attend->signOut) && $attend->signOut != '00:00:00' && strtotime("{$attend->date} {$attend->signOut}") <  strtotime("{$attend->date} {$this->config->attend->signOutLimit}"))
                {
                    $status = $status == 'late' ? 'both' : 'early';
                }
            }
        }

        /* 'rest': rest day. */
        if($this->isWeekend($attend->date) or $this->loadModel('holiday', 'oa')->isHoliday($attend->date)) $status = 'rest';

        return $status;
    }

    /**
     * Process attend, add dayName, comput today's status.
     * 
     * @param  object $attend 
     * @access public
     * @return object
     */
    public function processAttend($attend)
    {
        /* Compute status and remove signOut if date is today. */
        if($attend->date == helper::today()) 
        {
            /* If the user did't quit system across a day, update signin time. */
            if($attend->signIn == '00:00:00' && $attend->signOut > $this->config->attend->signInLimit) $attend->signIn = $this->config->attend->signInLimit;
            if(time() < strtotime("{$attend->date} {$this->config->attend->signOutLimit}")) $attend->signOut = '00:00:00';
            $status = $this->computeStatus($attend);
            $attend->status = $status;
            if($status == 'early') $attend->status = 'normal';
            if($status == 'both')  $attend->status = 'late';
        }

        if($attend->status == '' or $attend->status == 'rest') $attend->status = $this->computeStatus($attend);

        /* Remove time. */
        if($attend->signIn == '00:00:00')    $attend->signIn = '';
        if($attend->signOut == '00:00:00')   $attend->signOut = '';
        if($attend->manualIn == '00:00:00')  $attend->manualIn = '';
        if($attend->manualOut == '00:00:00') $attend->manualOut = '';

        $dayIndex = date('w', strtotime($attend->date));
        $attend->dayName = $this->lang->datepicker->dayNames[$dayIndex];
        return $attend;
    }

    /**
     * Process attend list. 
     * 
     * @param  array $attends 
     * @access public
     * @return array
     */
    public function processAttendList($attends)
    {
        foreach($attends as $attend) $attend = $this->processAttend($attend);
        return $attends;
    }

    /**
     * Fix user's attendlist, add default data if no this date record. 
     * 
     * @param  array  $attends 
     * @param  string $startDate 
     * @param  string $endDate 
     * @param  string $account 
     * @access public
     * @return void
     */
    public function fixUserAttendList($attends, $startDate = '0000-00-00', $endDate = '0000-00-00', $account = '')
    {
        if(strtotime($endDate) > time()) $endDate = date("Y-m-d");

        /* Get account, start date and end date. */
        foreach($attends as $attend)
        {
            if(strtotime($attend->date) < strtotime($startDate) or $startDate == '0000-00-00') $startDate = $attend->date;
            if(strtotime($attend->date) > strtotime($endDate)) $endDate = $attend->date;
            if($account == '') $account = $attend->account;
        }

        /* Add data if not set. */
        while(strtotime($startDate) <= strtotime($endDate))
        {
            if(!isset($attends[$startDate]))
            {
                $attend = new stdclass();
                $attend->account = $account;
                $attend->date    = $startDate;
                $attend->signIn  = '00:00:00';
                $attend->signOut = '00:00:00';
                $attend->ip      = '';
                $attend->device  = '';
                $attend->reason  = '';
                $attend->desc    = '';
                $attend->status  = $this->computeStatus($attend);
                $attend->manualIn  = '00:00:00';
                $attend->manualOut = '00:00:00';
                $attends[$startDate] = $attend;
            }
            $startDate = date("Y-m-d", strtotime("$startDate +1 day"));
        }

        foreach($attends as $key => $attend)
        {
            $beginDate = isset($this->config->attend->beginDate->{$attend->account}) ? $this->config->attend->beginDate->{$attend->account} : $this->config->attend->beginDate->company;
            if($beginDate && $beginDate > $attend->date) unset($attends[$key]);
        }

        return $attends;
    }

    /**
     * Set reviewer for attend.
     * 
     * @access public
     * @return bool
     */
    public function setManager()
    {
        $deptList = $this->post->dept;
        foreach($deptList as $id => $dept)
        {
            if(!empty($dept)) $dept = ",{$dept}," ;
            $this->dao->update(TABLE_CATEGORY)->set('moderators')->eq($dept)->where('id')->eq($id)->andWhere('type')->eq('dept')->exec();
        }
        return !dao::isError();
    }

    /**
     * Date is weekend or not.
     * 
     * @param  string    $date 
     * @access public
     * @return bool
     */
    public function isWeekend($date)
    {
        if($this->loadModel('holiday')->isWorkingDay($date)) return false;

        $dayIndex = date('w', strtotime($date));
        if( (($this->config->attend->workingDays == '5' and ($dayIndex == 0 or $dayIndex == 6)) or 
            ($this->config->attend->workingDays == '6' and $dayIndex == 0) or
            ($this->config->attend->workingDays == '12' and ($dayIndex == 5 or $dayIndex == 6)) or 
            ($this->config->attend->workingDays == '13' and $dayIndex == 6)) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Compute working dates between time.
     * 
     * @param  date    $begin 
     * @param  date    $end 
     * @access public
     * @return array 
     */
    public function computeWorkingDates($begin, $end)
    {
        $dates = range(strtotime($begin), strtotime($end), 60 * 60 * 24);
        $workingDays = array();
        foreach($dates as $datetime)
        {
            $date = date('Y-m-d', $datetime);
            if($this->isWeekend($date)) continue;
            if($this->loadModel('holiday', 'oa')->isHoliday($date)) continue;
            $workingDays[$date] = $date;
        }
        return $workingDays;
    }

    /**
     * Batch update attends for trip and leave.
     * 
     * @param  string    $dates 
     * @param  string    $account 
     * @param  string    $status 
     * @param  string    $reason 
     * @param  object    $time 
     * @access public
     * @return bool
     */
    public function batchUpdate($dates, $account, $status = '', $reason = '', $time = '')
    {
        if($status != '' and strpos(',normal,leave,makeup,overtime,lieu,trip,egress,', ",$status,") === false) return false;
        if($reason == '') $reason = $status;

        foreach($dates as $datetime)
        {
            $date = date('Y-m-d', $datetime);

            $attend = new stdclass();
            $attend->status       = $status ? $status : (($this->isWeekend($date) or $this->loadModel('holiday', 'oa')->isHoliday($date)) ? 'rest' : 'absent');
            $attend->reason       = $reason;
            $attend->reviewStatus = '';
            $attend->desc         = '';

            if(is_object($time))
            {
                $hours = '';
                if($time->begin == $date and $time->end == $date) 
                {
                    $hours = round((strtotime("{$date} {$time->finish}") - strtotime("{$date} {$time->start}")) / 3600, 2);
                }
                elseif($time->begin == $date and $time->end != $date) 
                {
                    if($status == 'leave' || (($status == 'overtime' || $status == 'makeup') && $time->start < $this->config->attend->signOutLimit))    
                    {
                        $hours = round((strtotime("{$date} {$this->config->attend->signOutLimit}") - strtotime("{$date} {$time->start}")) / 3600, 2);
                    }
                    elseif($status == 'overtime' || $status == 'makeup') 
                    {
                        $hours = round((strtotime("{$date} +1 days") - strtotime("{$date} {$time->start}")) / 3600, 2);
                    }
                }
                elseif($time->begin != $date and $time->end == $date) 
                {
                    if($status == 'leave' || (($status == 'overtime' || $status == 'makeup') && $time->finish > $this->config->attend->signInLimit))    
                    {
                        $hours = round((strtotime("{$date} {$time->finish}") - strtotime("{$date} {$this->config->attend->signInLimit}")) / 3600, 2);
                    }
                    elseif($status == 'overtime' || $status == 'makeup') 
                    {
                        $hours = round((strtotime("{$date} {$time->finish}") - strtotime("{$date}")) / 3600, 2);
                    }
                }
                else
                {
                    if($status == 'leave')    $hours = $this->config->attend->workingHours;
                    if($status == 'overtime' || $status == 'makeup') $hours = $this->config->attend->signOutLimit - $this->config->attend->signInLimit;
                }
                if($hours > $this->config->attend->workingHours && ($status == 'leave' || $status == 'lieu' || $status == 'makeup')) 
                {
                    $hours = $this->config->attend->workingHours;
                }

                $attend->desc = $hours;
            }

            $oldAttend = $this->getByDate($date, $account);
            if(isset($oldAttend->new))
            {
                $attend->date    = $date;
                $attend->account = $account;
                $this->dao->insert(TABLE_ATTEND)->data($attend)->autoCheck()->exec();
            }
            else
            {
                if($status && strpos(',leave,makeup,overtime,lieu,', ",$status,") !== false && !empty($oldAttend->desc)) $attend->desc += (float)$oldAttend->desc;
                $attend->status = $this->computeStatus($oldAttend);
                $this->dao->update(TABLE_ATTEND)->data($attend)->autoCheck()->where('date')->eq($date)->andWhere('account')->eq($account)->exec();
            }
        }

        return !dao::isError();
    }

    /**
     * Save stat.
     * 
     * @param  string    $date 
     * @access public
     * @return bool
     */
    public function saveStat($date)
    {
        foreach($this->post->normal as $account => $normal)
        {
            $data = new stdclass();
            $data->account         = $account;
            $data->normal          = $normal;
            $data->late            = $this->post->late[$account];
            $data->early           = $this->post->early[$account];
            $data->absent          = $this->post->absent[$account];
            $data->trip            = $this->post->trip[$account];
            $data->egress          = $this->post->egress[$account];
            $data->paidLeave       = $this->post->paidLeave[$account];
            $data->unpaidLeave     = $this->post->unpaidLeave[$account];
            $data->timeOvertime    = $this->post->timeOvertime[$account];
            $data->restOvertime    = $this->post->restOvertime[$account];
            $data->holidayOvertime = $this->post->holidayOvertime[$account];
            $data->deserve         = $this->post->deserve[$account];
            $data->actual          = $this->post->actual[$account];
            $data->month           = $date;
            $data->status          = 'wait';

            $this->dao->replace(TABLE_ATTENDSTAT)->data($data)->autoCheck()->exec();
        }

        return !dao::isError();
    }

    /**
     * Check ip if is allowed.
     * 
     * @access public
     * @return bool 
     */
    public function checkIP()
    {
        $ipParts  = explode('.', helper::getRemoteIp());
        $allowIPs = explode(',', $this->config->attend->ip);

        foreach($allowIPs as $allowIP)
        {
            if($allowIP == '*') return true;
            $allowIPParts = explode('.', $allowIP);
            foreach($allowIPParts as $key => $allowIPPart)
            {
                if($allowIPPart == '*') $allowIPParts[$key] = $ipParts[$key];
            }
            if(implode('.', $allowIPParts) == $_SERVER['REMOTE_ADDR']) return true;
        }
        return false;
    }

    /**
     * Save personal settings. 
     * 
     * @access public
     * @return bool 
     */
    public function savePersonalSettings()
    {
        $this->dao->delete()->from(TABLE_CONFIG)
            ->where('`owner`')->eq('system')
            ->andWhere('`app`')->eq('oa')
            ->andWhere('`module`')->eq('attend')
            ->andWhere('`section`')->eq('beginDate')
            ->andWhere('`key`')->ne('company')
            ->exec();
        $beginDates = array();
        foreach($this->post->account as $key => $account)
        {
            $date = $this->post->date[$key];
            if(!$account or !$date) continue;
            $beginDates[$account] = $date;
        }
        if($beginDates) $this->loadModel('setting')->setItems('system.oa.attend.beginDate', $beginDates);

        return !dao::isError();
    }
}
