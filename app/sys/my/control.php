<?php
/**
 * The control file of my module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     my
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class my extends control
{
    /**
     * review 
     * 
     * @param  string $type 
     * @param  string $orderBy 
     * @access public
     * @return void
     */
    public function review($type = 'attend', $orderBy = 'status')
    {
        if(!commonModel::isAvailable($type)) $this->loadModel('common')->deny('my', 'review');

        $this->loadModel('attend', 'oa');
        $this->loadModel('leave', 'oa');
        $this->loadModel('overtime', 'oa');
        $this->loadModel('refund', 'oa');
        $this->loadModel('lieu', 'oa');
        $account = $this->app->user->account;

        /* Get dept info. */
        $allDeptList = $this->loadModel('tree')->getPairs('', 'dept');
        $allDeptList['0'] = '/';
        $managedDeptList = array();
        $tmpDept = $this->loadModel('tree')->getDeptManagedByMe($account);
        foreach($tmpDept as $d) $managedDeptList[$d->id] = $d->name;

        /* Get deptments managed by me. used when get attend and leave. */
        $deptList = array();
        if(!empty($this->config->attend->reviewedBy) and $this->config->attend->reviewedBy == $account) $deptList = $allDeptList;
        if(empty($this->config->attend->reviewedBy)) $deptList = $managedDeptList;

        /* Get attend list. */
        $attends  = array();
        if($type == 'attend' and !empty($deptList)) $attends = $this->attend->getWaitAttends(array_keys($deptList));
        
        /* Get leave list. */
        $leaves = array();
        if($type == 'leave' and !empty($deptList)) $leaves = $this->leave->getList('browseReview', $year = '', $month = '', '', array_keys($deptList), $status = 'wait,pass', $orderBy);

        /* Get overtime list. */
        $overtimes = array();
        if($type == 'overtime' and !empty($deptList)) $overtimes = $this->overtime->getList('browseReview', $year = '', $month = '', '', array_keys($deptList), $status = 'wait', $orderBy);

        /* Get lieu list. */
        $lieus = array();
        if($type == 'lieu' and !empty($deptList)) $lieus = $this->lieu->getList('browseReview', $year = '', $month = '', '', array_keys($deptList), $status = 'wait', $orderBy);

        /* Get refund list. */
        $refunds = array();
        if($type == 'refund')
        {
            /* Get refund list for secondReviewer. */
            $secondRefunds = array();
            if(!empty($this->config->refund->secondReviewer) and $this->config->refund->secondReviewer == $account)
            {
                $secondRefunds = $this->refund->getList($mode = 'browseReview', '', $date = '', $deptIDList = '', 'doing');
            }

            /* Get refund list for firstReviewer. */
            $firstRefunds = array();
            if(!empty($this->config->refund->firstReviewer) and $this->config->refund->firstReviewer == $account)
            {
                $deptList = $allDeptList;
            }
            else if(empty($this->config->refund->firstReviewer))
            {
                $deptList = $managedDeptList;
            }
            if(!empty($deptList)) $firstRefunds = $this->refund->getList($mode = 'browseReview', '', $date = '',  $deptIDList = array_keys($deptList), 'wait');
            $refunds = array_merge($secondRefunds, $firstRefunds);
        }

        $this->view->title        = $this->lang->refund->review;
        $this->view->attends      = $attends;
        $this->view->leaveList    = $leaves;
        $this->view->overtimeList = $overtimes;
        $this->view->lieuList     = $lieus;
        $this->view->refunds      = $refunds;
        $this->view->deptList     = $allDeptList;
        $this->view->users        = $this->loadModel('user')->getPairs();
        $this->view->type         = $type;
        $this->view->orderBy      = $orderBy;
        $this->view->categories   = $this->refund->getCategoryPairs();
        $this->view->currencySign = $this->loadModel('common', 'sys')->getCurrencySign();
        $this->display();
    }

    /**
     * company's todo list.
     * 
     * @param  string $type 
     * @param  string $dept 
     * @param  string $account 
     * @param  string $begin 
     * @param  string $end 
     * @access public
     * @return void
     */
    public function company($type = 'todo', $dept = '', $account = '', $begin = '', $end = '')
    {
        $this->loadModel('todo', 'sys');

        /* compute begin and end. */
        if($begin == '')
        {
            $today = helper::today();
            if($end == '') $end = date('Y-m-d', strtotime("$today +6 days"));
            $dateList = range(strtotime($today), strtotime($end), 86400);
            $begin = date('Y-m-d', strtotime("$today -1 days"));
            while($this->loadModel('attend', 'oa')->isWeekend($begin) or $this->loadModel('holiday', 'oa')->isHoliday($begin))
            {
                $begin = date('Y-m-d', strtotime("$begin -1 days")); 
            }

            array_unshift($dateList, strtotime($begin));
        }
        else
        {
            if($end == '') $end = date('Y-m-d', strtotime("$begin +7 days"));
            if(strtotime($begin) > strtotime($end)) $end = date('Y-m-d', strtotime("$begin +7 days"));
            $dateList = range(strtotime($begin), strtotime($end), 86400);
        }
        $date = array();
        $date['begin'] = date('Y-m-d', strtotime($begin));
        $date['end']   = date('Y-m-d', strtotime($end));

        /* compute account list. */
        $acountList = array();
        if($account == '')
        {
            if($dept == '') 
            {
                $users = $this->dao->select('account, realname')->from(TABLE_USER)
                    ->where('deleted')->eq(0)
                    ->andWhere('locked', true)->eq('0000-00-00 00:00:00')
                    ->orWhere('locked')->lt(helper::now())
                    ->markRight(1)
                    ->orderBy('dept')
                    ->fetchPairs();
            }
            else 
            {
                $users = $this->loadModel('user')->getPairs('nodeleted,noforbidden,noclosed,noempty', $dept);
            }
            $accountList = array_keys($users);
        }
        else
        {
            $accountList[] = $account;
        }

        $todoList   = array();
        foreach($accountList as $user)
        {
            $todos = $this->todo->getList('self', $user, $date);

            $leaves = $this->loadModel('leave', 'oa')->getListByDate($date, $user);
            $trips  = $this->loadModel('trip', 'oa')->getListByDate($date, $user);
            foreach($leaves as $leave)
            {
                $leaveDates = range(strtotime($leave->begin), strtotime($leave->end), 60*60*24);
                foreach($leaveDates as $leaveDate)
                {
                    $leaveDate = date('Y-m-d', $leaveDate);

                    $data = new stdclass();
                    $data->id      = 'leave' . $leaveDate;
                    $data->name    = $this->lang->leave->common;
                    $data->type    = 'leave';
                    $data->date    = $leaveDate;
                    $data->desc    = $leave->desc;
                    $data->start   = strtotime($leaveDate) * 1000;
                    $data->end     = strtotime($leaveDate) * 1000;
                    $data->account = $leave->createdBy;
                    $data->status  = $leaveDate > helper::today() ? 'wait' : 'done';
                    $todos[] = $data;
                }
            }

            $this->app->loadLang('egress', 'oa');
            foreach($trips as $trip)
            {
                $tripDates = range(strtotime($trip->begin), strtotime($trip->end), 60*60*24);
                foreach($tripDates as $tripDate)
                {
                    $tripDate = date('Y-m-d', $tripDate);

                    $data = new stdclass();
                    $data->id      = 'trip' . $tripDate;
                    $data->name    = $this->lang->{$trip->type}->common . $this->lang->minus . $trip->name;
                    $data->type    = 'trip';
                    $data->date    = $tripDate;
                    $data->desc    = $trip->desc;
                    $data->start   = strtotime($tripDate) * 1000;
                    $data->end     = strtotime($tripDate) * 1000;
                    $data->account = $trip->createdBy;
                    $data->status  = $tripDate > helper::today() ? 'wait' : 'done';
                    $todos[] = $data;
                }
            }
            $todoList[$user] = $todos;
        }

        $deptList = $this->loadModel('tree')->getPairs('', 'dept');
        $deptList[''] = $this->lang->my->company->all;
        $deptList = array_reverse($deptList, true);

        $this->view->title    = $this->lang->todo->common;
        $this->view->todoList = $todoList;
        $this->view->type     = $type;
        $this->view->dept     = $dept;
        $this->view->account  = $account;
        $this->view->begin    = $date['begin'];
        $this->view->end      = $date['end'];
        $this->view->deptList = $deptList;
        $this->view->users    = $this->loadModel('user')->getPairs('nodeleted,noforbidden,noclosed');
        $this->view->userDept = $this->dao->select('account,dept')->from(TABLE_USER)->fetchPairs();
        $this->view->dateList = $dateList;
        $this->display();
    }

    /**
     * order list.
     * 
     * @param  string $type 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function order($type = 'past', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {

        $this->loadModel('common', 'sys');
        if(!commonModel::hasPriv('order', 'browse')) $this->common->deny('my', 'order');

        $this->loadModel('order', 'crm');
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $orders = $this->order->getList($type, '', $owner = 'my', $orderBy, $pager);

        /* Set pre and next condition. */
        $this->session->set('orderQueryCondition', $this->dao->get());
        $this->session->set('orderList', "javascript:$.openEntry(\"dashboard\")");

        /* Set allowed edit order ID list. */
        $this->app->user->canEditOrderIdList = ',' . implode(',', $this->order->getOrdersSawByMe('edit', array_keys($orders))) . ',';

        $this->view->title        = $this->lang->order->browse;
        $this->view->orders       = $orders;
        $this->view->customers    = $this->loadModel('customer')->getList('client');
        $this->view->users        = $this->loadModel('user')->getPairs();
        $this->view->pager        = $pager;
        $this->view->type         = $type;
        $this->view->orderBy      = $orderBy;
        $this->view->currencySign = $this->loadModel('common', 'sys')->getCurrencySign();
        $this->view->currencyList = $this->common->getCurrencyList();
        $this->display();
    }

    /**
     * contract list.
     * 
     * @param  string $type 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function contract($type = 'unfinished', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('common', 'sys');
        if(!commonModel::hasPriv('order', 'browse')) $this->common->deny('my', 'order');

        $this->loadModel('contract', 'crm');
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $contracts = $this->contract->getList(0, $type, $owner = 'my', $orderBy, $pager);

        /* Set preAndNext condition. */
        $this->session->set('contractQueryCondition', $this->dao->get());

        /* Save session for return link. */
        $this->session->set('contractList', "javascript:$.openEntry(\"dashboard\")");

        $this->app->user->canEditContractIdList = ',' . implode(',', $this->contract->getContractsSawByMe('edit', array_keys($contracts))) . ',';

        $this->view->title        = $this->lang->contract->browse;
        $this->view->contracts    = $contracts;
        $this->view->customers    = $this->loadModel('customer')->getPairs('client');
        $this->view->pager        = $pager;
        $this->view->type         = $type;
        $this->view->orderBy      = $orderBy;
        $this->view->currencySign = $this->loadModel('common', 'sys')->getCurrencySign();
        $this->view->currencyList = $this->common->getCurrencyList();

        $this->display();
    }

    /**
     * Browse task list.
     * 
     * @param  string  $type 
     * @param  string  $orderBy 
     * @param  int     $recTotal 
     * @param  int     $recPerPage 
     * @param  int     $pageID 
     * @access public
     * @return void
     */
    public function task($type = 'assignedTo', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->session->set('taskList', "javascript:$.openEntry(\"dashboard\")");

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title   = $this->lang->my->task->$type;
        $this->view->type    = $type;
        $this->view->orderBy = $orderBy;
        $this->view->pager   = $pager;
        $this->view->tasks   = $this->loadModel('task')->getList(0, $type, $orderBy, $pager);
        $this->view->users   = $this->loadModel('user')->getPairs();
        $this->display();
    }

    /**
     *  Involved projects list.
     * 
     * @access public
     * @return void
     */
    public function project($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->loadModel('project', 'proj');
        $this->view->title    = $this->lang->my->project->common;
        $this->view->projects = $this->project->getList('involved', $orderBy, $pager);
        $this->view->users    = $this->loadModel('user')->getPairs('noclosed');
        $this->view->pager    = $pager;
        $this->view->orderBy  = $orderBy;
        $this->display();
    }

    /**
     * My dynamic. 
     * 
     * @param  string   $type 
     * @param  string   $orderBy 
     * @param  int      $recTotal 
     * @param  int      $recPerPage 
     * @param  int      $pageID 
     * @access public
     * @return void
     */
    public function dynamic($type = 'today', $orderBy = 'date_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build search form. */
        $this->loadModel('search', 'sys');
        $this->config->dynamic->search['actionURL'] = $this->createLink('my', 'dynamic', 'type=bysearch');
        $this->search->setSearchParams($this->config->dynamic->search);

        $this->view->title   = $this->lang->my->dynamic->common;
        $this->view->type    = $type;
        $this->view->pager   = $pager;
        $this->view->orderBy = $orderBy;
        $this->view->actions = $this->loadModel('action')->getDynamic('all', $type, $orderBy, $pager);
        $this->view->users   = $this->loadModel('user')->getPairs();
        $this->display();
    }
}
