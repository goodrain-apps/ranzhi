<?php
/**
 * The model file of overtime module of Ranzhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     overtime
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class overtimeModel extends model
{
    /**
     * Get a overtime by id. 
     * 
     * @param  int    $id 
     * @access public
     * @return object
     */
    public function getById($id)
    {
        return $this->dao->select('*')->from(TABLE_OVERTIME)->where('type')->ne('compensate')->andWhere('id')->eq($id)->fetch();
    }

    /**
     * Get overtime list. 
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
        $overtimeList = $this->dao->select('t1.*, t2.realname, t2.dept')
            ->from(TABLE_OVERTIME)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on("t1.createdBy=t2.account")
            ->where('t1.type')->ne('compensate')
            ->beginIf($year != '')->andWhere('t1.year')->eq($year)->fi()
            ->beginIf($month != '')->andWhere('t1.begin')->like("%-$month-%")->fi()
            ->beginIf($account != '')->andWhere('t1.createdBy')->eq($account)->fi()
            ->beginIf($dept != '')->andWhere('t2.dept')->in($dept)->fi()
            ->beginIf($status != '')->andWhere('t1.status')->eq($status)->fi()
            ->beginIf($type != 'personal')->andWhere('t1.status')->ne('draft')->fi()
            ->orderBy("t2.dept,t1.{$orderBy}")
            ->fetchAll();
        $this->session->set('overtimeQueryCondition', $this->dao->get());

        return $overtimeList;
    }

    /**
     * Get overtime by date and account.
     * 
     * @param  string    $date 
     * @param  string    $account 
     * @access public
     * @return object
     */
    public function getByDate($date, $account)
    {
        return $this->dao->select('*')->from(TABLE_OVERTIME)->where('type')->ne('compensate')->andWhere('begin')->le($date)->andWhere('end')->ge($date)->andWhere('createdBy')->eq($account)->fetch();
    }

    /**
     * Get all month of overtime's begin.
     * 
     * @param  string $type
     * @access public
     * @return array
     */
    public function getAllMonth($type)
    {
        $monthList = array();
        $dateList  = $this->dao->select('begin')->from(TABLE_OVERTIME)
            ->where('type')->ne('compensate')
            ->beginIF($type == 'personal')->andWhere('createdBy')->eq($this->app->user->account)->fi()
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
     * Get reviewed by. 
     * 
     * @access public
     * @return string
     */
    public function getReviewedBy()
    {
        return empty($this->config->overtime->reviewedBy) ? (empty($this->config->attend->reviewedBy) ? '' : $this->config->attend->reviewedBy) : $this->config->overtime->reviewedBy;
    }

    /**
     * Create a overtime.
     * 
     * @access public
     * @return bool
     */
    public function create()
    {
        $overtime = fixer::input('post')
            ->add('status', 'wait')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->get();

        if(isset($overtime->begin) and $overtime->begin != '') $overtime->year = substr($overtime->begin, 0, 4);

        $return = $this->checkDate($overtime);
        if($return['result'] == 'fail') return $return;

        $this->dao->insert(TABLE_OVERTIME)
            ->data($overtime)
            ->autoCheck()
            ->batchCheck($this->config->overtime->require->create, 'notempty')
            ->check('end', 'ge', $overtime->begin)
            ->exec();

        return $this->dao->lastInsertID();
    }

    /**
     * update overtime.
     * 
     * @param  int    $id 
     * @access public
     * @return bool
     */
    public function update($id)
    {
        $oldOvertime = $this->getByID($id);

        $overtime = fixer::input('post')
            ->remove('status')
            ->remove('createdBy')
            ->remove('createdDate')
            ->get();

        if(isset($overtime->begin) and $overtime->begin != '') $overtime->year = substr($overtime->begin, 0, 4);

        $return = $this->checkDate($overtime, $id);
        if($return['result'] == 'fail') return $return;

        $this->dao->update(TABLE_OVERTIME)
            ->data($overtime)
            ->autoCheck()
            ->batchCheck($this->config->overtime->require->edit, 'notempty')
            ->check('end', 'ge', $overtime->begin)
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
        if(substr($date->begin, 0, 7) != substr($date->end, 0, 7)) return array('result' => 'fail', 'message' => $this->lang->overtime->sameMonth);
        if("$date->end $date->finish" <= "$date->begin $date->start") return array('result' => 'fail', 'message' => $this->lang->overtime->wrongEnd);

        $existOvertime = $this->checkOvertime($date, $this->app->user->account, $id);
        if(!empty($existOvertime)) return array('result' => 'fail', 'message' => sprintf($this->lang->overtime->unique, implode(', ', $existOvertime))); 

        $existLeave = $this->loadModel('leave', 'oa')->checkLeave($date, $this->app->user->account);
        if(!empty($existLeave)) return array('result' => 'fail', 'message' => sprintf($this->lang->leave->unique, implode(', ', $existLeave))); 
        
        $existMakeup = $this->loadModel('makeup', 'oa')->checkMakeup($date, $this->app->user->account);
        if(!empty($existMakeup)) return array('result' => 'fail', 'message' => sprintf($this->lang->makeup->unique, implode(', ', $existMakeup))); 
        
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
     * Check overtime.
     * 
     * @param  object $currentOvertime
     * @param  string $account 
     * @param  int    $id
     * @access public
     * @return bool 
     */
    public function checkOvertime($currentOvertime = null, $account = '', $id = 0)
    {
        $beginTime     = date('Y-m-d H:i:s', strtotime($currentOvertime->begin . ' ' . $currentOvertime->start));
        $endTime       = date('Y-m-d H:i:s', strtotime($currentOvertime->end   . ' ' . $currentOvertime->finish));
        $overtimeList  = $this->getList($type = '', $year = '', $month = '', $account, $dept = '', $status = '', $orderBy = 'begin, start');
        $existOvertime = array();
        foreach($overtimeList as $overtime)
        {
            if($overtime->id == $id) continue;
            if($overtime->status == 'rejcet') continue;

            $begin = $overtime->begin . ' ' . $overtime->start;
            $end   = $overtime->end   . ' ' . $overtime->finish;
            if(($beginTime > $begin && $beginTime < $end) 
                || ($endTime > $begin && $endTime < $end) 
                || ($beginTime <= $begin && $endTime >= $end))
            {
                $existOvertime[] = substr($begin, 0, 16) . ' ~ ' . substr($end, 0, 16);
            }
        }
        return $existOvertime;
    }

    /**
     * delete overtime.
     * 
     * @param  int    $id 
     * @access public
     * @return bool
     */
    public function delete($id, $null = null)
    {
        $oldOvertime = $this->getByID($id);
        $this->dao->delete()->from(TABLE_OVERTIME)->where('id')->eq($id)->exec();

        if(!dao::isError())
        {
            $oldDates = range(strtotime($oldOvertime->begin), strtotime($oldOvertime->end), 60 * 60 * 24);
            $this->loadModel('attend', 'oa')->batchUpdate($oldDates, $oldOvertime->createdBy, '');
        }
        return !dao::isError();
    }

    /**
     * Review an overtime.
     * 
     * @param  int     $id 
     * @param  string  $status 
     * @access public
     * @return bool
     */
    public function review($id, $status)
    {
        if(!isset($this->lang->overtime->statusList[$status])) return false;

        $data = new stdclass();
        $data->status       = $status;
        $data->reviewedBy   = $this->app->user->account;
        $data->reviewedDate = helper::now();
        $data->rejectReason = $status == 'reject' ? $this->post->rejectReason : '';

        $this->dao->update(TABLE_OVERTIME)->data($data)->autoCheck()->where('id')->eq($id)->exec();

        if(!dao::isError() and $status == 'pass')
        {
            $overtime = $this->getByID($id);
            $dates = range(strtotime($overtime->begin), strtotime($overtime->end), 60 * 60 * 24);
            $this->loadModel('attend', 'oa')->batchUpdate($dates, $overtime->createdBy, 'overtime', '', $overtime);
        }

        return !dao::isError();
    }

    /**
     * check date is in overtime. 
     * 
     * @param  string $date 
     * @param  string $account 
     * @access public
     * @return bool
     */
    public function isOvertime($date, $account)
    {
        static $overtimeList = array();
        if(!isset($overtimeList[$account])) $overtimeList[$account] = $this->getList($type = 'company', $year = '', $month = '', $account, $dept = '', 'pass');

        foreach($overtimeList[$account] as $overtime)
        {
            if(($overtime->status == 'pass') and strtotime($date) >= strtotime($overtime->begin) and strtotime($date) <= strtotime($overtime->end)) return true;
        }

        return false;
    }
}
