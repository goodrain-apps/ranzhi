<?php
/**
 * The control file of trip module of Ranzhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     trip
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class trip extends control
{
    public function __construct()
    {
        parent::__construct();
        $this->type = 'trip';
    }

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
     * personal's trip. 
     * 
     * @param  string $date 
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function personal($date = '', $orderBy = 'id_desc')
    {
        die($this->fetch('trip', 'browse', "mode=personal&date=$date&orderBy=$orderBy", 'oa'));
    }

    /**
     * Department's trip. 
     * 
     * @param  string $date 
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function department($date = '', $orderBy = 'id_desc')
    {
        die($this->fetch('trip', 'browse', "mode=department&date=$date&orderBy=$orderBy", 'oa'));
    }

    /**
     * Company's trip. 
     * 
     * @param  string $date 
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function company($date = '', $orderBy = 'id_desc')
    {
        die($this->fetch('trip', 'browse', "mode=company&date=$date&orderBy=$orderBy", 'oa'));
    }

    /**
     * browse 
     * 
     * @param  string $mode
     * @param  string $date 
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function browse($mode = 'personal', $date = '', $orderBy = 'id_desc')
    {
        if($date == '' or (strlen($date) != 6 and strlen($date) != 4)) $date = date("Ym");
        $currentYear  = substr($date, 0, 4);
        $currentMonth = strlen($date) == 6 ? substr($date, 4, 2) : '';
        $monthList    = $this->trip->getAllMonth($this->type, $mode);
        $yearList     = array_keys($monthList);
        $deptList     = array();

        if($mode == 'personal')
        {
            $tripList = $this->trip->getList($this->type, $currentYear, $currentMonth, $this->app->user->account, '', $orderBy);
        }
        elseif($mode == 'department')
        {
            $deptList = $this->loadModel('tree')->getDeptManagedByMe($this->app->user->account);
            foreach($deptList as $key => $value) $deptList[$key] = $value->name;
            $tripList = $this->trip->getList($this->type, $currentYear, $currentMonth, '', array_keys($deptList), $orderBy);
        }
        elseif($mode == 'company')
        {
            $tripList = $this->trip->getList($this->type, $currentYear, $currentMonth, '', '', $orderBy);
        }

        $this->view->title        = $this->lang->{$this->type}->browse;
        $this->view->type         = $this->type;
        $this->view->mode         = $mode;
        $this->view->currentYear  = $currentYear;
        $this->view->currentMonth = $currentMonth;
        $this->view->monthList    = $monthList;
        $this->view->yearList     = $yearList;
        $this->view->deptList     = $deptList;
        $this->view->users        = $this->loadModel('user')->getPairs();
        $this->view->customers    = $this->loadModel('customer')->getPairs();
        $this->view->tripList     = $tripList;
        $this->view->date         = $date;
        $this->view->orderBy      = $orderBy;
        $this->display('trip', 'browse');
    }

    /**
     * create trip.
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $result = $this->trip->create();
            if(is_array($result)) $this->send($result);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $tripID   = $result;
            $actionID = $this->loadModel('action')->create($this->type, $tripID, 'created');
            if($this->post->customers)
            {
                $customers = trim(implode(',', $this->post->customers), ',');
                if($customers)
                {
                    $customers = $this->loadModel('customer')->getList($mode = 'query', $params = "id in ($customers)", $relation = '');
                    foreach($customers as $customer)
                    {
                        $this->action->create($customer->relation == 'provider' ? 'provider' : 'customer', $customer->id, "create{$this->type}", '', html::a(inlink('view', "tripID={$tripID}"), $this->post->name, "data-toggle='modal'"));
                    }
                }
            }

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('personal')));
        }

        $this->app->loadModuleConfig('attend');
        $this->view->title     = $this->lang->{$this->type}->create;
        $this->view->type      = $this->type;
        $this->view->customers = $this->loadModel('customer')->getPairs();
        $this->display('trip', 'create');
    }

    /**
     * Edit trip.
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function edit($id)
    {
        $trip = $this->trip->getById($id);
        /* check privilage. */
        if($trip->createdBy != $this->app->user->account) 
        {
            $locate = helper::safe64Encode(helper::createLink("oa.{$this->type}", 'browse'));
            $noticeLink = helper::createLink('notice', 'index', "type=accessLimited&locate={$locate}");
            die(js::locate($noticeLink));
        }

        if($_POST)
        {
            $result = $this->trip->update($id);
            if(is_array($result)) $this->send($result);

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $this->view->title     = $this->lang->{$this->type}->edit;
        $this->view->trip      = $trip;
        $this->view->type      = $this->type;
        $this->view->customers = $this->loadModel('customer')->getPairs();
        $this->display('trip', 'edit');
    }

    /**
     * Delete trip.
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function delete($id)
    {
        $trip = $this->trip->getByID($id);
        if($trip->createdBy != $this->app->user->account) $this->send(array('result' => 'fail', 'message' => $this->lang->{$this->type}->denied));

        $this->trip->delete($id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success'));
    }

    /**
     * Detail view of a trip. 
     * 
     * @param  int    $id 
     * @access public
     * @return void
     */
    public function view($id)
    {
        $this->view->title     = $this->lang->{$this->type}->view;
        $this->view->trip      = $this->trip->getByID($id);
        $this->view->type      = $this->type;
        $this->view->customers = $this->loadModel('customer')->getPairs();
        $this->view->users     = $this->loadModel('user')->getPairs();
        $this->display('trip', 'view');
    }
}

