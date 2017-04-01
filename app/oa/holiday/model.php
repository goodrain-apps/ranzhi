<?php
/**
 * The model file of holiday module of ranzhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     holiday
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class holidayModel extends model
{
    /**
     * Get holiday by id. 
     * 
     * @param  int    $id 
     * @access public
     * @return object
     */
    public function getById($id)
    {
        return $this->dao->select('*')->from(TABLE_HOLIDAY)->where('id')->eq($id)->fetch();
    }

    /**
     * Get holiday list. 
     * 
     * @param  string $year 
     * @access public
     * @return array
     */
    public function getList($year = '', $type = 'all')
    {
        return $this->dao->select('*')->from(TABLE_HOLIDAY)
            ->where('1')
            ->beginIf($year != '')
            ->andWhere('year', true)->eq($year)
            ->orWhere('begin')->like("$year-%")
            ->orWhere('end')->like("$year-%")
            ->markright(1)
            ->fi()
            ->beginIf($type != 'all' && $type)->andWhere('type')->eq($type)->fi()
            ->fetchAll('id');
    }

    /**
     * check a date is in holiday. 
     * 
     * @param  int    $date 
     * @access public
     * @return bool
     */
    public function isHoliday($date)
    {
        static $holidayList = null;
        if($holidayList == null)
        {
            $year = substr($date, 0, 4);
            $holidayList = $this->getList($year, 'holiday');
        }

        $result = false;
        foreach($holidayList as $holiday)
        {
            if(strtotime($date) >= strtotime($holiday->begin) and strtotime($date) <= strtotime($holiday->end))
            {
                $result = true;
                break;
            }
        }
        return $result;
    }

    /**
     * Check a date is in working. 
     * 
     * @param  string    $date 
     * @access public
     * @return bool
     */
    public function isWorkingDay($date)
    {
        static $workingDays = null;
        if($workingDays == null)
        {
            $year = substr($date, 0, 4);
            $workingDays = $this->getList($year, 'working');
        }

        $result = false;
        foreach($workingDays as $workingDay)
        {
            if(strtotime($date) >= strtotime($workingDay->begin) and strtotime($date) <= strtotime($workingDay->end))
            {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * Get year pairs. 
     * 
     * @access public
     * @return array
     */
    public function getYearPairs()
    {
        return $this->dao->select('year,year')->from(TABLE_HOLIDAY)->groupBy('year')->orderBy('year_desc')->fetchPairs();
    }

    /**
     * create a holiday.
     * 
     * @access public
     * @return bool
     */
    public function create()
    {
        $holiday = fixer::input('post')->get();
        $holiday->year = substr($holiday->begin, 0, 4);

        $this->dao->insert(TABLE_HOLIDAY)
            ->data($holiday)
            ->autoCheck()
            ->batchCheck($this->config->holiday->require->create, 'notempty')
            ->check('end', 'ge', $holiday->begin)
            ->exec();
        return $this->dao->lastInsertID();
    }

    /**
     * Edit holiday. 
     * 
     * @access public
     * @return bool
     */
    public function update($id)
    {
        $holiday = fixer::input('post')->get();
        $holiday->year = substr($holiday->begin, 0, 4);

        $this->dao->update(TABLE_HOLIDAY)
            ->data($holiday)
            ->autoCheck()
            ->batchCheck($this->config->holiday->require->edit, 'notempty')
            ->check('end', 'ge', $holiday->begin)
            ->where('id')->eq($id)
            ->exec();
        return !dao::isError();
    }

    /**
     * Delete a holiday 
     * 
     * @param  int    $id 
     * @param  null $null 
     * @access public
     * @return bool
     */
    public function delete($id, $null = null)
    {
        $this->dao->delete()->from(TABLE_HOLIDAY)->where('id')->eq($id)->exec();
        return !dao::isError();
    }
}

