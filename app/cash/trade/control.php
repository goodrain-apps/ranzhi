<?php
/**
 * The control file of trade module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     trade
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class trade extends control
{
    /** 
     * The index page, locate to the browse page.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate(inlink('browse'));
    }

    /**
     * Browse trade.
     * 
     * @param string $orderBy     the order by
     * @param int    $recTotal 
     * @param int    $recPerPage 
     * @param int    $pageID 
     * @access public
     * @return void
     */
    public function browse($mode = 'all', $date = '', $orderBy = 'date_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {   
        if(!$this->trade->checkPriv($mode)) die(js::error($this->lang->trade->denied) . js::locate('back'));

        if($mode == 'all' and $date == '' and $orderBy == 'date_desc') $this->session->set('date', '');

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->session->set('tradeList', $this->app->getURI(true));
        if($date) $this->session->set('date', $date);

        /* Build search form. */
        $this->loadModel('search', 'sys');
        $this->config->trade->search['actionURL'] = $this->createLink('trade', 'browse', 'mode=bysearch');
        $this->config->trade->search['params']['depositor']['values'] = array('' => '') + $this->loadModel('depositor', 'cash')->getPairs();
        $this->config->trade->search['params']['product']['values']   = array('' => '') + $this->loadModel('product')->getPairs();
        $this->config->trade->search['params']['trader']['values']    = $this->loadModel('customer')->getPairs();

        $incomeCategories  = $this->loadModel('tree')->getOptionMenu('in', 0, $removeRoot = true);
        $expenseCategories = $this->tree->getOptionMenu('out', 0, $removeRoot = true);
        $investCategories  = $this->trade->getSystemCategoryPairs('invest');
        $loanCategories    = $this->trade->getSystemCategoryPairs('interest');
        $searchCategories  = array('' => '') + $this->lang->trade->categoryList + $incomeCategories + $expenseCategories;
        if($mode == 'in')       $searchCategories = array('' => '') + $incomeCategories;
        if($mode == 'out')      $searchCategories = array('' => '') + $expenseCategories;
        if($mode == 'transfer') $searchCategories = array('' => '') + $this->lang->trade->transferCategoryList;
        if($mode == 'invest')   $searchCategories = array('' => '') + $this->lang->trade->investTypeList + $investCategories;
        if($mode == 'loan')     $searchCategories = array('' => '') + $this->lang->trade->loanTypeList + $loanCategories;
        $this->config->trade->search['params']['category']['values'] = $searchCategories;
        $this->search->setSearchParams($this->config->trade->search);

        $type = 'all';
        if($mode == 'in')       $type = 'in';
        if($mode == 'out')      $type = 'out';
        if($mode == 'transfer') $type = 'transferin,transferout';
        if($mode == 'invest')   $type = 'invest,redeem';
        if($mode == 'loan')     $type = 'loan,repay';
        $tradeDates = $this->trade->getDatePairs($type);

        $tradeYears    = array();
        $tradeQuarters = array();
        $tradeMonths   = array();
        foreach($tradeDates as $tradeDate)
        {
            $year  = substr($tradeDate, 0, 4);
            $month = substr($tradeDate, 5, 2);

            if(!in_array($year, $tradeYears)) $tradeYears[] = $year;

            if(!isset($tradeQuarters[$year])) $tradeQuarters[$year] = array();
            foreach($this->lang->trade->quarters as $key => $quarterMonth)
            {
                if(strpos($quarterMonth, $month) !== false)    
                {
                    $quarter = $key;
                    if(!in_array($key, $tradeQuarters[$year])) $tradeQuarters[$year][] = $key;
                }
            }

            if(!isset($tradeMonths[$year][$quarter])) $tradeMonths[$year][$quarter] = array();

            if(!in_array($month, $tradeMonths[$year][$quarter]))
            {
                $tradeMonths[$year][$quarter][] = $month;
            }
        }

        $currentYear = current($tradeYears);
        if($mode != 'invest' and $mode != 'loan' and !empty($tradeDates))
        {
            $currentQuarter = current($tradeQuarters[$currentYear]);
            $currentMonth   = current($tradeMonths[$currentYear][$currentQuarter]);
            $currentDate    = $date ? $date : ($this->session->date ? $this->session->date : $currentYear . $currentMonth);
        }
        else
        {
            $currentDate = $date ? $date : ($this->session->date ? $this->session->date : '');
        }
        if($currentDate == 'all') $currentDate = '';

        $trades = $this->trade->getList($mode, $currentDate, $orderBy, $pager);

        $moduleMenu = "<nav id='menu'><ul class='nav'>";
        if(!empty($tradeYears))
        {
            $selectYear  = $currentDate ? substr($currentDate, 0, 4) : $currentYear;
            $selectMonth = substr($currentDate, 4, 2);

            $class = $currentDate ? '' : 'active';
            $moduleMenu .= "<li class='$class'>" . commonModel::printLink('trade', 'browse', "mode=$mode&date=all", $this->lang->trade->all, '', false) . '</li>';
            $class = $currentDate ? 'active' : '';
            $moduleMenu .= "<li class='$class dropdown'>";
            $moduleMenu .= html::a('#', $selectYear . $this->lang->year . "<span class='caret'></span>", "class='dropdown-toggle' data-toggle='dropdown'");
            $moduleMenu .= "<ul role='menu' class='dropdown-menu'>";
            foreach($tradeYears as $year)
            {
                $moduleMenu .= commonModel::printLink('trade', 'browse', "mode=$mode&date=$year", $year . $this->lang->year, '', false, '', 'li');
            }
            $moduleMenu .= "</ul></li>";

            $class = $selectMonth ? 'active' : '';
            $moduleMenu .= "<li class='$class dropdown'>";
            $moduleMenu .= html::a('#', zget($this->lang->trade->quarterList, $selectMonth, zget($this->lang->trade->monthList, $selectMonth, $this->lang->trade->month)) . "<span class='caret'></span>", "class='dropdown-toggle' data-toggle='dropdown'");
            $moduleMenu .= "<ul role='menu' class='dropdown-menu'>";
            foreach($tradeQuarters[$selectYear] as $quarter)
            {
                $moduleMenu .= commonModel::printLink('trade', 'browse', "mode=$mode&date=$selectYear$quarter", $this->lang->trade->quarterList[$quarter], '', false, '', 'li');
                foreach($tradeMonths[$selectYear][$quarter] as $month)
                {
                    $moduleMenu .= commonModel::printLink('trade', 'browse', "mode=$mode&date=$selectYear$month", $this->lang->trade->monthList[$month], '', false, '', 'li');
                }
            }
            $moduleMenu .= "</ul></li>";
        }
        $moduleMenu .= "</ul></nav>";

        $this->view->title         = $this->lang->trade->browse;
        $this->view->trades        = $trades;
        $this->view->mode          = $mode;
        $this->view->date          = $currentDate;
        $this->view->pager         = $pager;
        $this->view->orderBy       = $orderBy;
        $this->view->depositorList = $this->loadModel('depositor', 'cash')->getPairs();
        $this->view->customerList  = $this->loadModel('customer')->getPairs();
        $this->view->deptList      = $this->tree->getPairs(0, 'dept');
        $this->view->categories    = $this->lang->trade->categoryList + $this->tree->getPairs(0, 'out') + $this->tree->getPairs(0, 'in');
        $this->view->users         = $this->loadModel('user')->getPairs();
        $this->view->currencySign  = $this->loadModel('common', 'sys')->getCurrencySign();
        $this->view->currencyList  = $this->common->getCurrencyList();
        $this->view->productList   = $this->loadModel('product')->getPairs();
        $this->view->currentYear   = $currentYear;
        $this->view->moduleMenu    = $moduleMenu; 

        $this->display();
    }   

    /**
     * Create a contact.
     * 
     * @param  string $type 
     * @access public
     * @return void
     */
    public function create($type = '')
    {
        if($_POST)
        {
            $tradeID = $this->trade->create($type); 
            if(dao::isError())$this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('trade', $tradeID, 'Created', '');

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse', "type=$type")));
        }
        $orderList = $this->loadModel('order', 'crm')->getList();
        $orders    = $this->order->getPairs();
        foreach($orderList as $id => $order) $order->name = $orders[$id];

        unset($this->lang->trade->menu);
        $this->view->title         = $this->lang->trade->{$type};
        $this->view->type          = $type;
        $this->view->depositorList = array('' => '') + $this->loadModel('depositor', 'cash')->getPairs();
        $this->view->productList   = $this->loadModel('product')->getPairs();
        $this->view->orderList     = $orderList;
        $this->view->customerList  = $this->loadModel('customer')->getPairs('client');
        $this->view->traderList    = $this->customer->getPairs('provider');
        $this->view->contractList  = $this->loadModel('contract', 'crm')->getList($customerID = 0);
        $this->view->deptList      = $this->loadModel('tree')->getOptionMenu('dept', 0, $removeRoot = true);
        $this->view->users         = $this->loadModel('user')->getPairs('nodeleted,noforbidden,noclosed');

        if($type == 'in' or $type == 'out') $this->view->categories = $this->loadModel('tree')->getOptionMenu($type, 0, $removeRoot = true);

        $this->display();
    }

    /**
     * Batch create trade.
     * 
     * @access public
     * @return void
     */
    public function batchCreate()
    {
        if($_POST)
        {
            $result = $this->trade->batchCreate();
            if($result['result'] != 'success') $this->send($result);

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        unset($this->lang->trade->menu);
        unset($this->lang->trade->typeList['transferin']);
        unset($this->lang->trade->typeList['transferout']);
        unset($this->lang->trade->typeList['invest']);
        unset($this->lang->trade->typeList['redeem']);
        unset($this->lang->trade->typeList['loan']);
        unset($this->lang->trade->typeList['repay']);

        $this->view->title        = $this->lang->trade->batchCreate;
        $this->view->depositors   = array('' => '') + $this->loadModel('depositor', 'cash')->getPairs();
        $this->view->users        = $this->loadModel('user')->getPairs('nodeleted,noforbidden,noclosed');
        $this->view->customerList = $this->loadModel('customer')->getPairs('client');
        $this->view->traderList   = $this->loadModel('customer')->getPairs('provider');
        $this->view->expenseTypes = array('' => '') + $this->loadModel('tree')->getOptionMenu('out', 0, $removeRoot = true);
        $this->view->incomeTypes  = array('' => '') + $this->loadModel('tree')->getOptionMenu('in', 0, $removeRoot = true);
        $this->view->deptList     = $this->loadModel('tree')->getOptionMenu('dept', 0, $removeRoot = true);
        $this->view->productList  = array(0 => '') + $this->loadModel('product')->getPairs();

        $this->display();
    }

    /**
     * Edit a trade.
     * 
     * @param  int    $tradeID 
     * @param  string $mode
     * @access public
     * @return void
     */
    public function edit($tradeID = 0, $mode = '')
    {
        $trade = $this->trade->getByID($tradeID);
        if(empty($trade)) die();
        if($trade->type == 'out' and $trade->category != 'loss' and $trade->category != 'fee') $this->loadModel('tree')->checkRight($trade->category);

        if($_POST)
        {
            $changes = $this->trade->update($tradeID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('trade', $tradeID, 'Edited', '');
                $this->action->logHistory($actionID, $changes);
            }
            
            $backURL = $this->session->tradeList == false ? inlink('browse') : $this->session->tradeList;
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $backURL));
        }

        $orderList = $this->loadModel('order', 'crm')->getList();
        $orders    = $this->order->getPairs();
        foreach($orderList as $id => $order) $order->name = $orders[$id];
        
        $objectType = array();
        if($trade->order)    $objectType[] = 'order';
        if($trade->contract) $objectType[] = 'contract';
        $this->view->objectType = $objectType;

        $depositorList = $this->loadModel('depositor', 'cash')->getPairs();
        $currencySign  = $this->loadModel('common', 'sys')->getCurrencySign();
       
        $this->view->title         = $this->lang->trade->edit;
        $this->view->productList   = $this->loadModel('product')->getPairs();
        $this->view->customerList  = $this->loadModel('customer')->getPairs('client');
        $this->view->traderList    = $this->customer->getPairs('provider');
        $this->view->contractList  = $this->loadModel('contract', 'crm')->getList($customerID = 0);
        $this->view->tradeContract = array('' => '') + $this->loadModel('contract', 'crm')->getPairs($customerID = $trade->trader);
        $this->view->users         = $this->loadModel('user')->getPairs('nodeleted,noforbidden,noclosed');
        $this->view->deptList      = $this->loadModel('tree')->getOptionMenu('dept', 0, $removeRoot = true);
        $this->view->depositorList = $depositorList;
        $this->view->orderList     = $orderList;
        $this->view->trade         = $trade;
        $this->view->mode          = $mode;

        if($trade->type == 'in' or $trade->type == 'out') $this->view->categories = $this->loadModel('tree')->getOptionMenu($trade->type, 0, $removeRoot = true);

        if($trade->type == 'invest')
        {
            $redeems     = array();
            $profits     = array();
            $tradePairs  = array();
            $redeemPairs = array();
            $categories  = $this->trade->getSystemCategoryPairs('invest');
            $investList  = $this->trade->getList('invest');
            foreach($investList as $key => $invest)
            {
                if($invest->type == 'redeem')
                {
                    if($invest->investID == $tradeID) $redeems[] = $invest->id;
                    if($invest->date > $trade->date)
                    {
                        $redeemPairs[$invest->id] = $invest->date . $depositorList[$invest->depositor] . $this->lang->trade->redeem . zget($currencySign, $invest->currency) . $invest->money;
                    }
                }
                if($invest->type == 'in')
                {
                    if($invest->investID == $tradeID) $profits[] = $invest->id;
                    if($invest->date > $trade->date)
                    {
                        $tradePairs[$invest->id] = $invest->date . $depositorList[$invest->depositor] . zget($categories, $invest->category) . zget($currencySign, $invest->currency) . $invest->money;
                    }
                } 
            }
            $trade->redeems = implode(',', $redeems);
            $trade->profits = implode(',', $profits);
            $this->view->redeemPairs = $redeemPairs;
            $this->view->tradePairs  = $tradePairs;
        }

        $this->display();
    }

    /**
     * View a trade.
     * 
     * @param  int    $tradeID 
     * @param  string $mode
     * @access public
     * @return void
     */
    public function view($tradeID = 0, $mode = '')
    {
        $trade = $this->trade->getByID($tradeID);

        $this->view->trade         = $trade;
        $this->view->mode          = $mode;
        $this->view->title         = $this->lang->trade->view;
        $this->view->depositorList = $this->loadModel('depositor', 'cash')->getPairs();
        $this->view->customerList  = $this->loadModel('customer')->getPairs('client');
        $this->view->traderList    = $this->loadModel('customer')->getPairs('provider');
        $this->view->productList   = $this->loadModel('product')->getPairs();
        $this->view->orderList     = $this->loadModel('order', 'crm')->getPairs($customerID = 0);
        $this->view->contractList  = array('' => '') + $this->loadModel('contract', 'crm')->getPairs($customerID = 0);
        $this->view->users         = $this->loadModel('user')->getPairs();
        $this->view->deptList      = $this->loadModel('tree')->getOptionMenu('dept', 0, $removeRoot = true);
        $this->view->preAndNext    = $this->loadModel('common')->getPreAndNextObject('trade', $tradeID);
        $this->view->currencySign  = $this->loadModel('common', 'sys')->getCurrencySign();
        if($trade->type == 'in' or $trade->type == 'out') $this->view->categories = $this->loadModel('tree')->getOptionMenu($trade->type, 0, $removeRoot = true);
        $this->display();
    }

    /**
     * Transfer.
     * 
     * @access public
     * @return void
     */
    public function transfer()
    {
        if($_POST)
        {
            $result = $this->trade->transfer(); 
            $this->send($result);
        }

        unset($this->lang->trade->menu);
        $this->view->title         = $this->lang->trade->transfer;
        $this->view->users         = $this->loadModel('user')->getPairs('nodeleted,noforbidden');
        $this->view->deptList      = $this->loadModel('tree')->getOptionMenu('dept', 0, $removeRoot = true);
        $this->view->depositorList = $this->loadModel('depositor', 'cash')->getList();

        $this->display();
    }

    /**
     * Invest or redeem.
     * 
     * @param  string $type
     * @access public
     * @return void
     */
    public function invest($type = 'invest')
    {
        if($_POST)
        {
            $this->trade->invest($type); 
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse', 'mode=invest')));
        }

        $currencySign     = $this->loadModel('common', 'sys')->getCurrencySign();
        $depositorList    = array('' => '') + $this->loadModel('depositor', 'cash')->getPairs();
        $investCategories = $this->trade->getSystemCategoryPairs('invest');

        $invests = $this->dao->select('*')->from(TABLE_TRADE)->where('type')->eq('invest')->fetchAll();
        $investList = array('' => '');
        foreach($invests as $invest)
        {
            $redeem = $this->dao->select("sum(money) as value")->from(TABLE_TRADE)->where('investID')->eq($invest->id)->andWhere('type')->eq('redeem')->fetch('value');
            if($redeem >= $invest->money) continue;
            $investList[$invest->id] = $invest->date . $depositorList[$invest->depositor] . $this->lang->trade->invest . zget($currencySign, $invest->currency) . $invest->money;
        }

        unset($this->lang->trade->menu);
        $this->view->title              = $this->lang->trade->invest;
        $this->view->type               = $type;
        $this->view->users              = $this->loadModel('user')->getPairs('nodeleted,noforbidden');
        $this->view->deptList           = $this->loadModel('tree')->getOptionMenu('dept', 0, $removeRoot = true);
        $this->view->depositorList      = $depositorList;
        $this->view->traderList         = $this->loadModel('customer')->getPairs();
        $this->view->investCategoryList = $investCategories;
        $this->view->investList         = $investList;

        $this->display();
    }

    /**
     * Loan or repay.
     * 
     * @param  string $type
     * @access public
     * @return void
     */
    public function loan($type = 'loan')
    {
        if($_POST)
        {
            $this->trade->loan($type);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse', 'mode=loan')));
        }

        $depositorList = array('' => '') + $this->loadModel('depositor', 'cash')->getPairs();
        $currencySign  = $this->loadModel('common', 'sys')->getCurrencySign();

        $loans = $this->dao->select('*')->from(TABLE_TRADE)->where('type')->eq('loan')->fetchAll();
        $loanList = array('' => '');
        foreach($loans as $loan)
        {
            $repay = $this->dao->select("sum(money) as value")->from(TABLE_TRADE)->where('loanID')->eq($loan->id)->andWhere('type')->eq('repay')->fetch('value');
            if($repay >= $loan->money) continue;
            $loanList[$loan->id] = $loan->date . $depositorList[$loan->depositor] . $this->lang->trade->loan . zget($currencySign, $loan->currency) . $loan->money;
        }

        unset($this->lang->trade->menu);
        $this->view->title         = $this->lang->trade->loan;
        $this->view->type          = $type;
        $this->view->users         = $this->loadModel('user')->getPairs('nodeleted,noforbidden');
        $this->view->deptList      = $this->loadModel('tree')->getOptionMenu('dept', 0, $removeRoot = true);
        $this->view->depositorList = $depositorList;
        $this->view->traderList    = $this->loadModel('customer')->getPairs();
        $this->view->loanList      = $loanList;

        $this->display();
    }

    /**
     * manage detail of a trade.
     * 
     * @param  int    $tradeID 
     * @param  string $mode
     * @access public
     * @return void
     */
    public function detail($tradeID, $mode = '')
    {
        $trade = $this->trade->getByID($tradeID);
        if($trade->type == 'out') $this->loadModel('tree')->checkRight($trade->category);

        if($_POST)
        {
            $result = $this->trade->saveDetail($tradeID); 
            if($result) $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
            $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        $details = $this->trade->getDetail($tradeID);
        if(empty($details))
        {
            $detail = $trade;
            $detail->desc = '';
            $detail->money = '';
            $details[] = $detail;
        }

        $this->view->title      = $this->lang->trade->detail;
        $this->view->modalWidth = 900;
        $this->view->trade      = $trade;
        $this->view->details    = $details;
        $this->view->users      = $this->loadModel('user')->getPairs('nodeleted,noforbidden');

        if($trade->type == 'in' or $trade->type == 'out') $this->view->categories = $this->loadModel('tree')->getOptionMenu($trade->type, 0, $removeRoot = true);

        $this->display();
    }

    /**
     * Import csv. 
     * 
     * @access public
     * @return void
     */
    public function import()
    {
        if($_POST)
        {
            $file = $this->loadModel('file')->getUpload('files');
            $file = $file[0];

            $fc = file_get_contents($file['tmpname']);
            if($this->post->encode != "utf8") 
            {
                if(function_exists('mb_convert_encoding'))
                {
                    $fc = @mb_convert_encoding($fc, 'utf-8', $this->post->encode);
                }              
                elseif(function_exists('iconv'))
                {
                    $fc = @iconv($this->post->encode, 'utf-8', $fc);
                }
                else
                {              
                    $this->send(array('result' => 'fail', 'message' => $this->lang->noConvertFun));
                }              
            }                  
            file_put_contents($this->file->savePath . $file['pathname'], $fc);

            $file = $this->file->savePath . $file['pathname'];
            $this->session->set('importFile', $file);
            $this->send(array('result' => 'success', 'locate' => inlink('showImport', "depositorID={$this->post->depositor}&schemaID={$this->post->schema}")));
        }

        $this->view->title      = $this->lang->trade->import;
        $this->view->modalWidth = 600;
        $this->view->schemas    = $this->loadModel('schema')->getPairs();
        $this->view->depositors = array('' => '') + $this->loadModel('depositor', 'cash')->getPairs();
        $this->display();
    }

    /**
     * Batch edit trades.
     * 
     * @param  string $step  form|save
     * @access public
     * @return void
     */
    public function batchEdit($step = 'form')
    {
        if($step == 'save')
        {
            $result =  $this->trade->batchUpdate();
            $this->send($result);
        }

        unset($this->lang->trade->menu);

        $this->view->title              = $this->lang->trade->batchCreate;
        $this->view->trades             = $this->trade->getByIdList($this->post->tradeIDList);
        $this->view->depositors         = $this->loadModel('depositor', 'cash')->getPairs();
        $this->view->users              = $this->loadModel('user')->getPairs('nodeleted,noforbidden,noclosed');
        $this->view->customerList       = $this->loadModel('customer')->getPairs('client');
        $this->view->traderList         = $this->loadModel('customer')->getPairs('provider');
        $this->view->expenseTypes       = array('' => '') + $this->loadModel('tree')->getOptionMenu('out', 0, $removeRoot = true);
        $this->view->incomeTypes        = array('' => '') + $this->loadModel('tree')->getOptionMenu('in', 0, $removeRoot = true);
        $this->view->deptList           = $this->loadModel('tree')->getOptionMenu('dept', 0, $removeRoot = true);
        $this->view->productList        = array(0 => '') + $this->loadModel('product')->getPairs();
        $this->view->disabledCategories = $this->dao->select('*')->from(TABLE_CATEGORY)->where('major')->in('5,6,7,8')->fetchAll('id');

        $this->display();
    }

    /**
     * Show import data.
     * 
     * @param  int    $depositorID 
     * @param  int    $schemaID 
     * @access public
     * @return void
     */
    public function showImport($depositorID, $schemaID)
    {
        if($_POST)
        {
            $return = $this->trade->saveImport($depositorID);

            if($return['result'] == 'success') $this->session->set('importFile', '');
            $this->send($return);
        }

        $schema = $this->loadModel('schema')->getByID($schemaID);

        /* Parse field to col. */
        $fields = explode(',', $this->config->trade->importField);
        $fields = array_flip($fields);
        foreach($fields as $field => $col)
        {
            $col = $schema->$field;

            if($field == 'desc' and $col)
            {
                $cols = explode(',', str_replace(' ', '', $col));
                $fields[$field] = array();
                foreach($cols as $col)
                {
                    if(empty($col)) continue;
                    $order = ord(strtoupper($col)) - ord('A');
                    $fields[$field][$order] = $order;
                }
                continue;
            }
            
            /* When the money of in and out is different then parse them. */
            if($field == 'money' and strpos($schema->money, ',') !== false)
            {
                list($in, $out) = explode(',', str_replace(' ', '', $col));
                $fields[$field] = array();
                $fields[$field]['in']  = ord(strtoupper($in)) - ord('A');
                $fields[$field]['out'] = ord(strtoupper($out)) - ord('A');
                continue;
            }

            $fields[$field] = empty($col) ? '' : ord(strtoupper($col)) - ord('A');
        }

        $rows = $this->schema->parseCSV($this->session->importFile);

        unset($this->lang->trade->menu);
        unset($this->lang->trade->typeList['transferin']);
        unset($this->lang->trade->typeList['transferout']);
        unset($this->lang->trade->typeList['invest']);
        unset($this->lang->trade->typeList['redeem']);

        $customerList       = $this->loadModel('customer')->getPairs('client');
        $traderList         = $this->customer->getPairs('provider,partner');
        $expenseTypes       = array('' => '') + $this->loadModel('tree')->getOptionMenu('out', 0, $removeRoot = true);
        $incomeTypes        = array('' => '') + $this->tree->getOptionMenu('in', 0, $removeRoot = true);
        $deptList           = $this->loadModel('tree')->getPairs(0, 'dept');
        $productList        = $this->loadModel('product')->getPairs();
        $flipCustomers      = array_flip($customerList);
        $flipTraders        = array_flip($traderList);
        $flipTypeList       = array_flip($this->lang->trade->typeList);
        $flipDeptList       = array_flip($deptList);
        $disabledCategories = $this->dao->select('*')->from(TABLE_CATEGORY)->where('major')->in('5,6,7,8')->fetchAll('id');

        $dataList = array();
        $existTrades = array(); 
        $i = 0;
        foreach($rows as $row)
        {
            /* Exclude invalid column. */
            if(is_array($fields['money']))
            {
                if(!isset($row[$fields['money']['in']]) or !isset($row[$fields['money']['out']])) continue;

                /* if money is 1,600 or 1 600 then replace them. */
                $row[$fields['money']['in']]  = str_replace(array(',', ' '), '', $row[$fields['money']['in']]);
                $row[$fields['money']['out']] = str_replace(array(',', ' '), '', $row[$fields['money']['out']]);
                if(!is_numeric($row[$fields['money']['in']]) and !is_numeric($row[$fields['money']['out']])) continue;
            }
            else
            {
                if(!isset($row[$fields['money']])) continue;

                $row[$fields['money']] = str_replace(array(',', ' '), '', $row[$fields['money']]);
                if(!is_numeric($row[$fields['money']])) continue;
            }

            $data = array();
            foreach($fields as $field => $col)
            {
                /* Desc can multiseriate. */
                if($field == 'desc' and !empty($col))
                {
                    $data[$field] = '';
                    foreach($fields[$field] as $col) $data[$field] .= isset($row[$col]) ? trim($row[$col]) . "\n" : '';
                    $data[$field] = trim($data[$field]);
                    continue;
                }

                /* if money has in and out items, then type can judging by their. */
                if($field == 'type' and is_array($col)) continue;
                if($field == 'money' and is_array($col))
                {
                    $data[$field] = is_numeric($row[$col['in']]) ? trim($row[$col['in']]) : trim($row[$col['out']]);
                    $data['type'] = is_numeric($row[$col['in']]) ? 'in' : 'out';
                    continue;
                }

                $data[$field] = (is_int($col) and isset($row[$col])) ? trim($row[$col]) : '';
                if($field == 'date') $data[$field] = date('Y-m-d', strtotime($data[$field]));
            }

            if(isset($flipDeptList[$data['dept']])) $data['dept'] = $flipDeptList[$data['dept']];

            if(isset($flipTypeList[$data['type']])) $data['type'] = $flipTypeList[$data['type']];


            $matchs = $data['type'] == 'out' ? $flipTraders : ($data['type'] == 'in' ? $flipCustomers : '');
            if($data['trader'] and $matchs and !empty($matchs[$data['trader']])) $data['trader'] = $matchs[$data['trader']];

            if(!empty($data['category']) and in_array($data['type'], array('in', 'out')))
            {
                $categories = $data['type'] == 'out' ? $expenseTypes : $incomeTypes;
                foreach($categories as $id => $category)
                {
                    if(strpos($category, $data['category']) !== false)
                    {
                        $data['category'] = $id;
                        break;
                    }
                }
            }

            if(!empty($data['product']))
            {
                foreach($productList as $id => $product)
                {
                    if(strpos($product, $data['product']) !== false)
                    {
                        $data['product'] = $id;
                        break;
                    }
                }
            }

            if(!$fields['fee'] and isset($disabledCategories[$data['category']]) and $data['trader']) continue;
 
            $fee = $data['fee'];
            unset($data['fee']);
            $dataList[$i] = $data;

            $existTrade = $this->dao->select('*')->from(TABLE_TRADE)
                ->where('money')->eq($data['money'])
                ->andWhere('date')->eq($data['date'])
                ->andWhere('type')->eq($data['type'])
                ->andWhere('category')->eq($data['category'])
                ->fetch();
            if($existTrade) $existTrades[$i] = $existTrade;

            if($schema->fee and $fee)
            {
                $i + 1;
                $data['type']  = 'out';
                $data['money'] = $fee;
                $data['desc']  = '';
                $dataList[$i]    = $data;
            }
            $i++;
        }

        $this->view->title        = $this->lang->trade->showImport;
        $this->view->trades       = $dataList;
        $this->view->depositor    = $this->loadModel('depositor', 'cash')->getByID($depositorID);
        $this->view->users        = $this->loadModel('user')->getPairs('nodeleted,noforbidden');
        $this->view->customerList = $customerList;
        $this->view->traderList   = $traderList;
        $this->view->expenseTypes = $expenseTypes;
        $this->view->incomeTypes  = $incomeTypes;
        $this->view->deptList     = $this->tree->getOptionMenu('dept', 0, $removeRoot = true);
        $this->view->productList  = array(0 => '') + $productList;
        $this->view->existTrades  = $existTrades;

        $this->display();
    }

    /**
     * Delete a trade.
     * 
     * @param  int      $tradeID 
     * @access public
     * @return void
     */
    public function delete($tradeID)
    {
        $trade = $this->trade->getByID($tradeID);
        if($trade->type == 'out') $this->loadModel('tree')->checkRight($trade->category);

        if($this->trade->delete($tradeID)) $this->send(array('result' => 'success'));
        $this->send(array('result' => 'fail', 'message' => dao::getError()));
    }

    /**
     * get data to export.
     * 
     * @param  int $projectID 
     * @param  string $orderBy 
     * @access public
     * @return void
     */
    public function export($mode, $orderBy = 'id_desc')
    {
        if($_POST)
        {
            $tradeLang   = $this->lang->trade;
            $tradeConfig = $this->config->trade;

            /* Create field lists. */
            $fields = explode(',', $tradeConfig->exportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = isset($tradeLang->$fieldName) ? $tradeLang->$fieldName : $fieldName;
                unset($fields[$key]);
            }

            /* Get trades. */
            $trades = array();
            if($mode == 'all')
            {
                $tradeQueryCondition = $this->session->tradeQueryCondition;
                if(strpos($tradeQueryCondition, 'limit') !== false) $tradeQueryCondition = substr($tradeQueryCondition, 0, strpos($tradeQueryCondition, 'limit'));
                $stmt = $this->dbh->query($tradeQueryCondition);
                while($row = $stmt->fetch()) $trades[$row->id] = $row;
            }

            if($mode == 'thisPage')
            {
                $stmt = $this->dbh->query($this->session->tradeQueryCondition);
                while($row = $stmt->fetch()) $trades[$row->id] = $row;
            }

            /* Get users and projects. */
            $expenseTypes = $this->loadModel('tree')->getPairs(0, 'out');
            $incomeTypes  = $this->tree->getPairs(0, 'in');
            
            $users      = $this->loadModel('user')->getPairs();
            $depositors = $this->loadModel('depositor', 'cash')->getPairs();
            $customers  = $this->loadModel('customer')->getPairs();
            $deptList   = $this->tree->getPairs(0, 'dept');
            $categories = $this->lang->trade->categoryList + $expenseTypes + $incomeTypes;
            $products   = $this->loadModel('product')->getPairs();
            $orders     = $this->loadModel('order', 'crm')->getPairs();
            $contracts  = $this->loadModel('contract', 'crm')->getPairs();

            $details = $this->dao->select('*')->from(TABLE_TRADE)->where('parent')->ne('')->fetchGroup('parent');

            foreach($trades as $trade)
            {
                $trade->detail = array();
                if(isset($details[$trade->id]))
                {
                    foreach($details[$trade->id] as $detail)
                    {
                        $detail->desc = htmlspecialchars_decode($detail->desc);
                        $detail->desc = str_replace("<br />", "\n", $detail->desc);
                        $detail->desc = str_replace('"', '""', $detail->desc);

                        $trade->detail[] = $categories[$detail->category] . $detail->money . '(' . $detail->desc . ')';
                    }
                }
            }

            foreach($trades as $trade)
            {
                $trade->desc = htmlspecialchars_decode($trade->desc);
                $trade->desc = str_replace("<br />", "\n", $trade->desc);
                $trade->desc = str_replace('"', '""', $trade->desc);

                $trade->depositor = zget($depositors, $trade->depositor, '');
                $trade->trader    = isset($customers[$trade->trader]) ? $customers[$trade->trader] . "(#$trade->trader)" : '';
                $trade->dept      = zget($deptList, $trade->dept, '');
                $trade->category  = zget($categories, $trade->category, '');
                $trade->product   = zget($products, $trade->product, '');
                $trade->order     = zget($orders, $trade->order, '');
                $trade->contract  = zget($contracts, $trade->contract, '');
                $trade->type      = zget($tradeLang->typeList, $trade->type, '');
                $trade->currency  = zget($this->lang->currencyList, $trade->currency, '');

                $trade->createdBy = zget($users, $trade->createdBy, '');
                $trade->editedBy  = zget($users, $trade->editedBy, '');

                $trade->createdDate = substr($trade->createdDate, 0, 10);
                $trade->editedDate  = substr($trade->editedDate,  0, 10);

                if($trade->handlers)
                {
                    $tmpHandlers = array();
                    $handlers = explode(',', $trade->handlers);
                    foreach($handlers as $handler)
                    {
                        if(!$handler) continue;
                        $handler = trim($handler);
                        $tmpHandlers[] = isset($users[$handler]) ? $users[$handler] : $handler;
                    }

                    $trade->handlers = join("; \n", $tmpHandlers);
                }

                $trade->detail = join("; \n", $trade->detail);
            }

            $this->post->set('fields', $fields);
            $this->post->set('rows', $trades);
            $this->post->set('kind', 'trade');
            $this->fetch('file', 'export2CSV', $_POST);
        }

        $this->display();
    }

    /**
     * Export data to excel.
     * 
     * @param  string $mode 
     * @access public
     * @return void
     */
    public function export2Excel($mode = 'depositor')
    {
        if($_POST)
        {
            $data = $this->trade->getExportData($mode);

            $excelData = new stdclass();
            $excelData->dataList = $data;
            $excelData->fileName = $this->post->fileName ? $this->post->fileName : $this->lang->trade->excel->title->$mode;

            $this->app->loadClass('excel')->export($excelData, $this->post->fileType);
        }

        $tradeDates = $this->trade->getDatePairs('all');

        $years = array(date('Y') => date('Y'));
        foreach($tradeDates as $tradeDate)
        {
            $year = substr($tradeDate, 0, 4);
            $years[$year] = $year;
        }

        $this->view->title    = $this->lang->export;
        $this->view->fileName = $this->lang->trade->excel->title->$mode;
        $this->view->years    = $years;
        $this->display();
    }

    /**
     * Report for trade.
     * 
     * @param  string $date 
     * @param  string $currency 
     * @param  string $unit     1 | 1000 | 10000 | 1000000
     * @access public
     * @return void
     */
    public function report($date = '', $currency = 'rmb', $unit = '')
    {
        $tradeYears  = array();
        $tradeMonths = array();
        $tradeDates  = $this->trade->getDatePairs();
        foreach($tradeDates as $tradeDate)
        {
            $year  = substr($tradeDate, 0, 4);
            $month = substr($tradeDate, 5, 2);

            if(!in_array($year, $tradeYears)) $tradeYears[] = $year;

            if(!isset($tradeMonths[$year])) $tradeMonths[$year] = array();
            if(!in_array($month, $tradeMonths[$year])) $tradeMonths[$year][] = $month;

            sort($tradeMonths[$year]);
        }
        rsort($tradeYears);

        $currentYear  = current($tradeYears);
        $currentMonth = '00';
        if(!empty($date))
        {
            $currentYear = substr($date, 0, 4);
            if(strlen($date) == 6) $currentMonth = substr($date, 4, 2);
        }

        $trades = $this->trade->getByYear($currentYear, $currency);
        
        $annualChartDatas = array();
        $annualChartDatas['all']['in']   = 0;
        $annualChartDatas['all']['out']  = 0;
        $annualChartDatas['all']['profit']  = 0;
        foreach($trades as $month => $monthTrades)
        {
            $annualChartDatas[$month]['in']  = 0;
            $annualChartDatas[$month]['out'] = 0;
            foreach($monthTrades as $trade)
            {
                if($trade->type == 'in')  $annualChartDatas[$month]['in']  += $trade->money;
                if($trade->type == 'out') $annualChartDatas[$month]['out'] += $trade->money;
            }
            $annualChartDatas[$month]['profit'] = $annualChartDatas[$month]['in'] - $annualChartDatas[$month]['out'];

            $annualChartDatas['all']['in']     += $annualChartDatas[$month]['in'];
            $annualChartDatas['all']['out']    += $annualChartDatas[$month]['out'];
            $annualChartDatas['all']['profit'] += $annualChartDatas[$month]['profit'];
        }
        ksort($annualChartDatas, SORT_STRING);

        $this->loadModel('report');
        foreach($this->config->trade->report->annual as $groupBy)
        {
            $monthlyChartDatas[$groupBy]['in'] = $this->trade->getChartData('in', $currentYear, $currentMonth, $groupBy, $currency);
            $monthlyChartDatas[$groupBy]['in'] = $this->report->computePercent($monthlyChartDatas[$groupBy]['in']);

            $monthlyChartDatas[$groupBy]['out'] = $this->trade->getChartData('out', $currentYear, $currentMonth, $groupBy, $currency);
            $monthlyChartDatas[$groupBy]['out'] = $this->report->computePercent($monthlyChartDatas[$groupBy]['out']);
        }

        $unit = $unit ? $unit : (empty($this->config->trade->report->unit) ? 1 : $this->config->trade->report->unit);
        foreach($annualChartDatas as $month => $datas) 
        {
            foreach($datas as $key => $money) 
            {
                $annualChartDatas[$month][$key] = round($money / $unit, 2);
            }
        }
        foreach($monthlyChartDatas as $datas) 
        {
            foreach($datas as $typeDatas) 
            {
                foreach($typeDatas as $data) 
                {
                    $data->value = round($data->value / $unit, 2);
                }
            }
        }

        $this->lang->trade->menu = $this->lang->report->menu;

        $this->view->title             = $this->lang->trade->report->common . '#' . $this->lang->trade->report->annual;
        $this->view->annualChartDatas  = $annualChartDatas;
        $this->view->monthlyChartDatas = $monthlyChartDatas;
        $this->view->tradeYears        = $tradeYears;
        $this->view->tradeMonths       = $tradeMonths;
        $this->view->currentYear       = $currentYear;
        $this->view->currentMonth      = $currentMonth;
        $this->view->currentCurrency   = $currency;
        $this->view->currentUnit       = $unit;
        $this->view->currencyList      = $this->loadModel('common', 'sys')->getCurrencyList();
        $this->display();
    }

    /**
     * Annual comparision report
     * 
     * @access public
     * @return void
     */
    public function compare()
    {
        $currencyList = $this->loadModel('common', 'sys')->getCurrencyList();
        $tradeYears   = array();
        $tradeDates   = $this->trade->getDatePairs();

        foreach($tradeDates as $tradeDate)
        {
            $year = substr($tradeDate, 0, 4);
            if(!in_array($year, $tradeYears)) $tradeYears[$year] = $year;
        }

        $selectYears = $this->post->years ? $this->post->years : array_slice($tradeYears, 0, 2);
        $currency    = $this->post->currency ? $this->post->currency : current(array_flip($currencyList));
        $unit        = $this->post->unit ? $this->post->unit : (empty($this->config->trade->report->unit) ? 1 : $this->config->trade->report->unit);

        asort($selectYears);
        $selectYears  = array_values($selectYears);
        $incomeDatas  = array();
        $expenseDatas = array();
        $profitDatas  = array();
        $this->trade->getCompareDatas($selectYears, $incomeDatas, $expenseDatas, $profitDatas, $currency, $unit);

        $this->lang->trade->menu = $this->lang->report->menu;

        $this->view->title        = $this->lang->trade->report->common . '#' . $this->lang->trade->report->compare;
        $this->view->tradeYears   = $tradeYears;
        $this->view->selectYears  = $selectYears;
        $this->view->incomeDatas  = $incomeDatas;
        $this->view->expenseDatas = $expenseDatas;
        $this->view->profitDatas  = $profitDatas;
        $this->view->currency     = $currency;
        $this->view->unit         = $unit;
        $this->view->currencyList = $currencyList;
        $this->display();
    }

    /**
     * Ajax get depositor of customer.
     * 
     * @param  int    $customerID 
     * @access public
     * @return string
     */
    public function ajaxGetDepositor($customerID)
    {
        $customer = $this->loadModel('customer')->getByID($customerID);
        if(!$customer) die();
        die($customer->depositor);
    }

    /**
     * Set report unit. 
     * 
     * @access public
     * @return void
     */
    public function setReportUnit()
    {
        if($_POST)
        {
            $this->loadModel('setting')->setItem('system.cash.trade.report.unit', $this->post->unit);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->send(array('result' => 'success', 'locate' => 'reload'));
        }

        $this->view->title = $this->lang->trade->setReportUnit;
        $this->display();
    }
}
