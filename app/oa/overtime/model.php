<?php
/**
 * The model file of overtime module of Ranzhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
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
        return $this->dao->select('*')->from(TABLE_OVERTIME)->where('id')->eq($id)->fetch();
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
     * @access public
     * @return array
     */
    public function getList($type = 'personal', $year = '', $month = '', $account = '', $dept = '', $status = '', $orderBy = 'id_desc')
    {
        return $this->dao->select('t1.*, t2.realname, t2.dept')->from(TABLE_OVERTIME)->alias('t1')->leftJoin(TABLE_USER)->alias('t2')->on("t1.createdBy=t2.account")
            ->where('1=1')
            ->beginIf($year != '')->andWhere('t1.year')->eq($year)->fi()
            ->beginIf($month != '')->andWhere('t1.begin')->like("%-$month-%")->fi()
            ->beginIf($account != '')->andWhere('t1.createdBy')->eq($account)->fi()
            ->beginIf($dept != '')->andWhere('t2.dept')->in($dept)->fi()
            ->beginIf($status != '')->andWhere('t1.status')->eq($status)->fi()
            ->beginIf($type != 'personal')->andWhere('t1.status')->ne('draft')->fi()
            ->orderBy("t2.dept,t1.{$orderBy}")
            ->fetchAll();
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
        return $this->dao->select('*')->from(TABLE_OVERTIME)->where('begin')->le($date)->andWhere('end')->ge($date)->andWhere('createdBy')->eq($account)->fetch();
    }

    /**
     * Get all month of overtime's begin.
     * 
     * @access public
     * @return array
     */
    public function getAllMonth()
    {
        $monthList = array();
        $dateList  = $this->dao->select('begin')->from(TABLE_OVERTIME)->groupBy('begin')->orderBy('begin_asc')->fetchAll('begin');
        foreach($dateList as $date)
        {
            $year  = substr($date->begin, 0, 4);
            $month = substr($date->begin, 5, 2);
            if(!isset($monthList[$year][$month])) $monthList[$year][$month] = $month;
        }
        return $monthList;
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

        $dates = range(strtotime($overtime->begin), strtotime($overtime->end), 60 * 60 * 24);
        foreach($dates as $date)
        {
            $date = date('Y-m-d', $date);
            $existOvertime = $this->getByDate($date, $this->app->user->account);
            if($existOvertime) return array('result' => 'fail', 'message' => sprintf($this->lang->overtime->unique, $date)); 
        }

        $this->dao->insert(TABLE_OVERTIME)
            ->data($overtime)
            ->autoCheck()
            ->batchCheck($this->config->overtime->require->create, 'notempty')
            ->check('end', 'ge', $overtime->begin)
            ->exec();

        $overtimeID = $this->dao->lastInsertID();
        if(!dao::isError())
        {
            $this->loadModel('attend')->batchUpdate($dates, $overtime->createdBy, '', 'overtime');
            return $overtimeID;
        }
        return false;
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

        $dates = range(strtotime($overtime->begin), strtotime($overtime->end), 60 * 60 * 24);
        foreach($dates as $date)
        {
            $date = date('Y-m-d', $date);
            $existOvertime = $this->getByDate($date, $this->app->user->account);
            if($existOvertime and $existOvertime->id != $oldOvertime->id) return array('result' => 'fail', 'message' => sprintf($this->lang->overtime->unique, $date)); 
        }

        $this->dao->update(TABLE_OVERTIME)
            ->data($overtime)
            ->autoCheck()
            ->batchCheck($this->config->overtime->require->edit, 'notempty')
            ->check('end', 'ge', $overtime->begin)
            ->where('id')->eq($id)
            ->exec();

        if(!dao::isError())
        {
            $oldDates = range(strtotime($oldOvertime->begin), strtotime($oldovertime->end), 60 * 60 * 24);
            $this->loadModel('attend')->batchUpdate($oldDates, $oldOvertime->createdBy, '');

            $this->loadModel('attend')->batchUpdate($dates, $oldOvertime->createdBy, '', 'overtime');
        }

        return !dao::isError();
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
            $this->loadModel('attend')->batchUpdate($oldDates, $oldOvertime->createdBy, '');
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

        $overtime = $this->getByID($id);

        $this->dao->update(TABLE_OVERTIME)
            ->set('status')->eq($status)
            ->set('reviewedBy')->eq($this->app->user->account)
            ->set('reviewedDate')->eq(helper::now())
            ->where('id')->eq($id)
            ->exec();

        if(!dao::isError() and $status == 'pass')
        {
            $dates = range(strtotime($overtime->begin), strtotime($overtime->end), 60 * 60 * 24);
            $this->loadModel('attend')->batchUpdate($dates, $overtime->createdBy, 'overtime', '', $overtime);
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
