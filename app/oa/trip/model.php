<?php
/**
 * The model file of trip module of Ranzhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     trip
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class tripModel extends model
{
    /**
     * Get a trip by id. 
     * 
     * @param  int    $id 
     * @access public
     * @return object
     */
    public function getById($id)
    {
        return $this->dao->select('*')->from(TABLE_TRIP)->where('id')->eq($id)->fetch();
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
        return $this->dao->select('*')->from(TABLE_TRIP)->where('begin')->le($date)->andWhere('end')->ge($date)->andWhere('createdBy')->eq($account)->fetch();
    }

    /**
     * Get trip list. 
     * 
     * @param  string $type
     * @param  string $year 
     * @param  string $month 
     * @param  string $account 
     * @param  string $dept 
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getList($type = 'trip', $year = '', $month = '', $account = '', $dept = '', $orderBy = 'id_desc')
    {
        return $this->dao->select('t1.*, t2.realname, t2.dept')
            ->from(TABLE_TRIP)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on("t1.createdBy=t2.account")
            ->where('1=1')
            ->beginIF($type != '')->andWhere('t1.type')->eq($type)->fi()
            ->beginIF($year != '')->andWhere('t1.year')->eq($year)->fi()
            ->beginIF($month != '')->andWhere('t1.begin')->like("%-$month-%")->fi()
            ->beginIF($account != '')->andWhere('t1.createdBy')->eq($account)->fi()
            ->beginIF($dept != '')->andWhere('t2.dept')->in($dept)->fi()
            ->orderBy("t2.dept,t1.{$orderBy}")
            ->fetchAll();
    }

    /**
     * Get all month of trip's begin.
     * 
     * @param  string $type
     * @param  string $mode
     * @access public
     * @return array
     */
    public function getAllMonth($type = 'trip', $mode)
    {
        if($mode == 'department')
        {
            $deptList = $this->loadModel('tree')->getDeptManagedByMe($this->app->user->account);
            $dateList = $this->dao->select('t1.begin')->from(TABLE_TRIP)->alias('t1')
                ->leftJoin(TABLE_USER)->alias('t2')->on('t1.createdBy=t2.account')
                ->where('t2.dept')->in(array_keys($deptList))
                ->beginIF($type)->andWhere('type')->eq($type)->fi()
                ->groupBy('begin')
                ->orderBy('begin_desc')
                ->fetchAll('begin');
        }
        else
        {
            $dateList = $this->dao->select('begin')->from(TABLE_TRIP)
                ->where(1)
                ->beginIF($type)->andWhere('type')->eq($type)->fi()
                ->beginIF($mode == 'personal')->andWhere('createdBy')->eq($this->app->user->account)->fi()
                ->groupBy('begin')
                ->orderBy('begin_desc')
                ->fetchAll('begin');
        }
        $monthList = array();
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
     * @param  int    $date 
     * @param  int    $account 
     * @access public
     * @return void
     */
    public function getListByDate($date, $account)
    {
        $begin = strtolower($date['begin']);
        $end   = strtolower($date['end']);

        return $this->dao->select('*')->from(TABLE_TRIP)
            ->where('createdBy')->eq($account)
            ->andWhere('begin')->ge($begin)
            ->andWhere('end')->le($end)
            ->fetchAll();
    }

    /**
     * Create a trip.
     * 
     * @access public
     * @return bool
     */
    public function create()
    {
        $trip = fixer::input('post')
            ->join('customers', ',')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->get();
        $trip->customers = trim($trip->customers, ',');
        if(isset($trip->begin) and $trip->begin != '') $trip->year = substr($trip->begin, 0, 4);

        $result = $this->checkDate($trip);
        if($result['result'] == 'fail') return $result;

        $this->dao->insert(TABLE_TRIP)
            ->data($trip)
            ->autoCheck()
            ->batchCheck($this->config->trip->require->create, 'notempty')
            ->check('end', 'ge', $trip->begin)
            ->exec();

        if(!dao::isError())
        {
            $dates = range(strtotime($trip->begin), strtotime($trip->end), 60*60*24);
            $this->loadModel('attend', 'oa')->batchUpdate($dates, $trip->createdBy, 'trip', '', $trip);
        }

        return $this->dao->lastInsertID();
    }

    /**
     * update trip.
     * 
     * @param  int    $id 
     * @access public
     * @return bool
     */
    public function update($id)
    {
        $oldTrip = $this->getByID($id);

        $trip = fixer::input('post')
            ->join('customers', ',')
            ->remove('createdBy')
            ->remove('createdDate')
            ->get();
        $trip->customers = trim($trip->customers, ',');
        if(isset($trip->begin) and $trip->begin != '') $trip->year = substr($trip->begin, 0, 4);

        $result = $this->checkDate($trip, $id);
        if($result['result'] == 'fail') return $result;

        $this->dao->update(TABLE_TRIP)
            ->data($trip)
            ->autoCheck()
            ->batchCheck($this->config->trip->require->edit, 'notempty')
            ->check('end', 'ge', $trip->begin)
            ->where('id')->eq($id)
            ->exec();

        if(!dao::isError())
        {
            $dates = range(strtotime($trip->begin), strtotime($trip->end), 60*60*24);
            $this->loadModel('attend', 'oa')->batchUpdate($dates, $oldTrip->createdBy, 'trip', '', $trip);
        }

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
        if($date->type == 'egress') $this->app->loadLang('egress', 'oa');

        if(substr($date->begin, 0, 7) != substr($date->end, 0, 7)) return array('result' => 'fail', 'message' => $this->lang->{$date->type}->sameMonth);
        if("$date->end $date->finish" <= "$date->begin $date->start") return array('result' => 'fail', 'message' => $this->lang->{$date->type}->wrongEnd);

        $existTrip = $this->checkTrip('trip', $date, $this->app->user->account, $id); 
        if(!empty($existTrip)) return array('result' => 'fail', 'message' => sprintf($this->lang->trip->unique, implode(', ', $existTrip))); 

        $existEgress = $this->checkTrip('egress', $date, $this->app->user->account, $id); 
        if(!empty($existEgress)) return array('result' => 'fail', 'message' => sprintf($this->lang->egress->unique, implode(', ', $existEgress))); 
        
        $existLeave = $this->loadModel('leave', 'oa')->checkLeave($date, $this->app->user->account);
        if(!empty($existLeave)) return array('result' => 'fail', 'message' => sprintf($this->lang->leave->unique, implode(', ', $existLeave))); 
        
        $existMakeup = $this->loadModel('makeup', 'oa')->checkMakeup($date, $this->app->user->account);
        if(!empty($existMakeup)) return array('result' => 'fail', 'message' => sprintf($this->lang->makeup->unique, implode(', ', $existMakeup))); 
        
        $existOvertime = $this->loadModel('overtime', 'oa')->checkOvertime($date, $this->app->user->account);
        if(!empty($existOvertime)) return array('result' => 'fail', 'message' => sprintf($this->lang->overtime->unique, implode(', ', $existOvertime))); 

        $existLieu = $this->loadModel('lieu', 'oa')->checkLieu($date, $this->app->user->account);
        if(!empty($existLieu)) return array('result' => 'fail', 'message' => sprintf($this->lang->lieu->unique, implode(', ', $existLieu)));  

        return array('result' => 'success');
    }

    /**
     * Check trip.
     * 
     * @param  string $type
     * @param  object $currentTrip
     * @param  string $account 
     * @param  int    $id
     * @access public
     * @return bool 
     */
    public function checkTrip($type = 'trip', $currentTrip = null, $account = '', $id = 0)
    {
        $beginTime = date('Y-m-d H:i:s', strtotime($currentTrip->begin . ' ' . $currentTrip->start));
        $endTime   = date('Y-m-d H:i:s', strtotime($currentTrip->end   . ' ' . $currentTrip->finish));
        $tripList  = $this->getList($type, $year = '', $month = '', $account, $dept = '', $orderBy = 'begin, start');
        $existTrip = array();
        foreach($tripList as $trip)
        {
            if($trip->id == $id) continue;

            $begin = $trip->begin . ' ' . $trip->start;
            $end   = $trip->end   . ' ' . $trip->finish;
            if(($beginTime > $begin && $beginTime < $end) 
                || ($endTime > $begin && $endTime < $end) 
                || ($beginTime <= $begin && $endTime >= $end))
            {
                $existTrip[] = substr($begin, 0, 16) . ' ~ ' . substr($end, 0, 16);
            }
        }
        return $existTrip;
    }

    /**
     * Check date is in trip. 
     * 
     * @param  string $type
     * @param  string $date 
     * @param  string $account 
     * @access public
     * @return bool
     */
    public function isTrip($type = 'trip', $date, $account)
    {
        static $tripList   = array();
        static $egressList = array();

        if($type == 'trip')
        {
            if(!isset($tripList[$account])) $tripList[$account] = $this->getList($type, $year = '', $month = '', $account);

            foreach($tripList[$account] as $trip)
            {
                if(strtotime($date) >= strtotime($trip->begin) and strtotime($date) <= strtotime($trip->end)) return true;
            }
        }
        elseif($type == 'egress')
        {
            if(!isset($egressList[$account])) $egressList[$account] = $this->getList($type, $year = '', $month = '', $account);

            foreach($egressList[$account] as $trip)
            {
                if(strtotime($date) >= strtotime($trip->begin) and strtotime($date) <= strtotime($trip->end)) return true;
            }
        }

        return false;
    }

    /**
     * delete trip.
     * 
     * @param  int    $id 
     * @access public
     * @return bool
     */
    public function delete($id, $null = null)
    {
        $oldTrip = $this->getByID($id);

        $this->dao->delete()->from(TABLE_TRIP)->where('id')->eq($id)->exec();

        if(!dao::isError())
        {
            $oldDates = range(strtotime($oldTrip->begin), strtotime($oldTrip->end), 60*60*24);
            $this->loadModel('attend', 'oa')->batchUpdate($oldDates, $oldTrip->createdBy, '');
        }

        return !dao::isError();
    }
}
