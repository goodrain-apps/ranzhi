<?php
/**
 * The model file of leave module of Ranzhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     leave
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class leaveModel extends model
{
    /**
     * Get a leave by id. 
     * 
     * @param  int    $id 
     * @access public
     * @return object
     */
    public function getById($id)
    {
        return $this->dao->select('*')->from(TABLE_LEAVE)->where('id')->eq($id)->fetch();
    }

    /**
     * Get leave list. 
     * 
     * @param  string $type 
     * @param  string $year 
     * @param  string $month 
     * @param  string $account 
     * @param  string $dept 
     * @param  string $status 
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getList($type = 'personal', $year = '', $month = '', $account = '', $dept = '', $status = '', $orderBy = 'id_desc')
    {
        $leaveList = $this->dao->select('t1.*, t2.realname, t2.dept')
            ->from(TABLE_LEAVE)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on("t1.createdBy=t2.account")
            ->where('1=1')
            ->beginIf($year != '')->andWhere('t1.year')->eq($year)->fi()
            ->beginIf($month != '')->andWhere('t1.begin')->like("%-$month-%")->fi()
            ->beginIf($account != '')->andWhere('t1.createdBy')->eq($account)->fi()
            ->beginIf($dept != '')->andWhere('t2.dept')->in($dept)->fi()
            ->beginIf($status != '')->andWhere('t1.status')->in($status)->fi()
            ->beginIf($type != 'personal')->andWhere('t1.status')->ne('draft')->fi()
            ->orderBy("t2.dept,t1.{$orderBy}")
            ->fetchAll();
        $this->session->set('leaveQueryCondition', $this->dao->get());

        if($type == 'browseReview')
        {
            foreach($leaveList as $key => $leave)
            {
                if($leave->status == 'pass' and ($leave->backDate == '0000-00-00 00:00:00' or $leave->backDate == $leave->end . ' ' . $leave->finish)) unset($leaveList[$key]);
            }
        }

        return $leaveList;
    }

    /**
     * Get leave by date and account.
     * 
     * @param  string    $date 
     * @param  string    $account 
     * @access public
     * @return object
     */
    public function getByDate($date, $account)
    {
        return $this->dao->select('*')->from(TABLE_LEAVE)->where('begin')->le($date)->andWhere('end')->ge($date)->andWhere('createdBy')->eq($account)->fetch();
    }

    /**
     * Get all month of leave's begin.
     * 
     * @param  string $type
     * @access public
     * @return array
     */
    public function getAllMonth($type)
    {
        $monthList = array();
        $dateList  = $this->dao->select('begin')->from(TABLE_LEAVE)
            ->beginIF($type == 'personal')->where('createdBy')->eq($this->app->user->account)->fi()
            ->groupBy('begin')
            ->orderBy('begin_desc')
            ->fetchAll('begin');
        foreach($dateList as $date)
        {
            $year  = substr($date->begin, 0, 4);
            $month = substr($date->begin, 5, 2);
            if(!isset($monthList[$year][$month])) $monthList[$year][$month] = $month;
        }
        return $monthList;
    }

    /**
     * Get list by date.
     * 
     * @param  string    $date 
     * @param  string    $account 
     * @access public
     * @return array
     */
    public function getListByDate($date, $account)
    {
        $begin = strtolower($date['begin']);
        $end   = strtolower($date['end']);

        return $this->dao->select('*')->from(TABLE_LEAVE)
            ->where('status')->eq('pass')
            ->andWhere('createdBy')->eq($account)
            ->andWhere('begin')->ge($begin)
            ->andWhere('end')->le($end)
            ->fetchAll();
    }

    /**
     * Get reviewed by. 
     * 
     * @access public
     * @return string
     */
    public function getReviewedBy()
    {
        return empty($this->config->leave->reviewedBy) ? (empty($this->config->attend->reviewedBy) ? '' : $this->config->attend->reviewedBy) : $this->config->leave->reviewedBy;
    }

    /**
     * Create a leave.
     * 
     * @access public
     * @return bool
     */
    public function create()
    {
        $leave = fixer::input('post')
            ->add('status', 'wait')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->get();
        if(isset($leave->begin) and $leave->begin != '') $leave->year = substr($leave->begin, 0, 4);

        $result = $this->checkDate($leave);
        if($result['result'] == 'fail') return $result;

        $this->dao->insert(TABLE_LEAVE)
            ->data($leave)
            ->autoCheck()
            ->batchCheck($this->config->leave->require->create, 'notempty')
            ->check('end', 'ge', $leave->begin)
            ->exec();

        return $this->dao->lastInsertID();
    }

    /**
     * update leave.
     * 
     * @param  int    $id 
     * @access public
     * @return bool
     */
    public function update($id)
    {
        $oldLeave = $this->getByID($id);

        $leave = fixer::input('post')
            ->remove('status')
            ->remove('createdBy')
            ->remove('createdDate')
            ->get();

        if(isset($leave->begin) and $leave->begin != '') $leave->year = substr($leave->begin, 0, 4);

        $result = $this->checkDate($leave, $id);
        if($result['result'] == 'fail') return $result;

        $this->dao->update(TABLE_LEAVE)
            ->data($leave)
            ->autoCheck()
            ->batchCheck($this->config->leave->require->edit, 'notempty')
            ->check('end', 'ge', $leave->begin)
            ->where('id')->eq($id)
            ->exec();

        return !dao::isError();
    }

    /**
     * Check date.
     * 
     * @param  object $date 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function checkDate($date, $id = 0)
    {
        if(substr($date->begin, 0, 7) != substr($date->end, 0, 7)) return array('result' => 'fail', 'message' => $this->lang->leave->sameMonth);
        if("$date->end $date->finish" <= "$date->begin $date->start") return array('result' => 'fail', 'message' => $this->lang->leave->wrongEnd);

        $existLeave = $this->checkLeave($date, $this->app->user->account, $id);
        if(!empty($existLeave)) return array('result' => 'fail', 'message' => sprintf($this->lang->leave->unique, implode(', ', $existLeave))); 
        
        $existMakeup = $this->loadModel('makeup', 'oa')->checkMakeup($date, $this->app->user->account);
        if(!empty($existMakeup)) return array('result' => 'fail', 'message' => sprintf($this->lang->makeup->unique, implode(', ', $existMakeup))); 
        
        $existOvertime = $this->loadModel('overtime', 'oa')->checkOvertime($date, $this->app->user->account);
        if(!empty($existOvertime)) return array('result' => 'fail', 'message' => sprintf($this->lang->overtime->unique, implode(', ', $existOvertime))); 

        $existTrip = $this->loadModel('trip', 'oa')->checkTrip('trip', $date, $this->app->user->account); 
        if(!empty($existTrip)) return array('result' => 'fail', 'message' => sprintf($this->lang->trip->unique, implode(', ', $existTrip))); 

        $this->app->loadLang('egress', 'oa');
        $existEgress = $this->trip->checkTrip('egress', $date, $this->app->user->account); 
        if(!empty($existEgress)) return array('result' => 'fail', 'message' => sprintf($this->lang->egress->unique, implode(', ', $existEgress))); 

        $existLieu = $this->loadModel('lieu', 'oa')->checkLieu($date, $this->app->user->account);
        if(!empty($existLieu)) return array('result' => 'fail', 'message' => sprintf($this->lang->lieu->unique, implode(', ', $existLieu)));  

        return array('result' => 'success');
    }

    /**
     * Check leave.
     * 
     * @param  object $currentLeave
     * @param  string $account 
     * @param  int    $id
     * @access public
     * @return bool 
     */
    public function checkLeave($currentLeave = null, $account = '', $id = 0)
    {
        $beginTime  = date('Y-m-d H:i:s', strtotime($currentLeave->begin . ' ' . $currentLeave->start));
        $endTime    = date('Y-m-d H:i:s', strtotime($currentLeave->end   . ' ' . $currentLeave->finish));
        $leaveList  = $this->getList($type = '', $year = '', $month = '', $account, $dept = '', $status = '', $orderBy = 'begin, start');
        $existLeave = array();
        foreach($leaveList as $leave)
        {
            if($leave->id == $id) continue;
            if($leave->status == 'reject') continue;

            $begin = $leave->begin . ' ' . $leave->start;
            $end   = $leave->end   . ' ' . $leave->finish;
            if(($beginTime > $begin && $beginTime < $end) 
                || ($endTime > $begin && $endTime < $end) 
                || ($beginTime <= $begin && $endTime >= $end))
            {
                $existLeave[] = substr($begin, 0, 16) . ' ~ ' . substr($end, 0, 16);
            }
        }
        return $existLeave;
    }

    /**
     * Back to the company.
     * 
     * @param  int    $id 
     * @access public
     * @return bool
     */
    public function back($id)
    {
        $this->dao->update(TABLE_LEAVE)->set('backDate')->eq($this->post->backDate)->autoCheck()->where('id')->eq($id)->exec();
        return !dao::isError();
    }

    /**
     * review 
     * 
     * @param  int    $id 
     * @param  string $status 
     * @access public
     * @return bool
     */
    public function review($id, $status)
    {
        if(!isset($this->lang->leave->statusList[$status])) return false;

        $leave = $this->getByID($id);

        $this->dao->update(TABLE_LEAVE)
            ->set('status')->eq($status)
            ->set('reviewedBy')->eq($this->app->user->account)
            ->set('reviewedDate')->eq(helper::now())
            ->where('id')->eq($id)
            ->exec();

        if(!dao::isError() and $status == 'pass')
        {
            $dates = range(strtotime($leave->begin), strtotime($leave->end), 60*60*24);
            $this->loadModel('attend', 'oa')->batchUpdate($dates, $leave->createdBy, 'leave', '', $leave);
        }

        return !dao::isError();
    }

    /**
     * Review back date.
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function reviewBackDate($id)
    {
        $oldLeave = $this->getByID($id);
        $backDate = substr($oldLeave->backDate, 0, 10);
        $backTime = substr($oldLeave->backDate, 11);
        $begin  = $oldLeave->begin;
        $start  = $oldLeave->start;
        $end    = $backDate;
        $finish = $backTime;

        if($oldLeave->begin == $backDate) 
        {
            $hours = round((strtotime("{$backDate} {$backTime}") - strtotime("{$backDate} {$oldLeave->start}")) / 3600, 2);
            if($hours > $this->config->attend->workingHours) $hours = $this->config->attend->workingHours;
        }
        else
        {
            $hoursStart   = round((strtotime("{$begin} {$this->config->attend->signOutLimit}") - strtotime("{$begin} {$start}")) / 3600, 2);
            $hoursEnd     = round((strtotime("{$end} {$finish}") - strtotime("{$end} {$this->config->attend->signInLimit}")) / 3600, 2);
            $days         = (strtotime("{$end}") - strtotime("{$begin}")) / 3600 / 24;
            $hoursContent = $days > 1 ? (($days - 1)  * $this->config->attend->workingHours) : 0;

            if($hoursStart > $this->config->attend->workingHours) $hoursStart = $this->config->attend->workingHours;
            if($hoursEnd > $this->config->attend->workingHours) $hoursEnd = $this->config->attend->workingHours;
            $hours = $hoursStart + $hoursEnd + $hoursContent;
        }

        $this->dao->update(TABLE_LEAVE)
            ->set('end')->eq($backDate)
            ->set('finish')->eq($backTime)
            ->set('hours')->eq($hours)
            ->set('reviewedBy')->eq($this->app->user->account)
            ->set('reviewedDate')->eq(helper::now())
            ->where('id')->eq($id)
            ->exec();

        if(!dao::isError())
        {
            $leave = $this->getByID($id);
            $dates = range(strtotime($leave->begin), strtotime($backDate), 60*60*24);
            $this->loadModel('attend', 'oa')->batchUpdate($dates, $leave->createdBy, 'leave', '', $leave);

            if($oldLeave->end > $leave->end)
            {
                $oldDates = range(strtotime($leave->end), strtotime($oldLeave->end), 60*60*24);
                $this->loadModel('attend', 'oa')->batchUpdate($oldDates, $leave->createdBy);
            }
        }
        return !dao::isError();
    }

    /**
     * delete leave.
     * 
     * @param  int    $id 
     * @access public
     * @return bool
     */
    public function delete($id, $null = null)
    {
        $oldLeave = $this->getByID($id);

        $this->dao->delete()->from(TABLE_LEAVE)->where('id')->eq($id)->exec();

        if(!dao::isError())
        {
            $oldDates = range(strtotime($oldLeave->begin), strtotime($oldLeave->end), 60*60*24);
            $this->loadModel('attend', 'oa')->batchUpdate($oldDates, $oldLeave->createdBy, '');
        }

        return !dao::isError();
    }

    /**
     * check date is in leave. 
     * 
     * @param  string $date 
     * @param  string $account 
     * @access public
     * @return bool
     */
    public function isLeave($date, $account)
    {
        static $leaveList = array();
        if(!isset($leaveList[$account])) $leaveList[$account] = $this->getList($type = 'company', $year = '', $month = '', $account, $dept = '', 'pass');

        foreach($leaveList[$account] as $leave)
        {
            if(strtotime($date) >= strtotime($leave->begin) and strtotime($date) <= strtotime($leave->end)) return true;
        }

        return false;
    }
}
