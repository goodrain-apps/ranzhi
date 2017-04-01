<?php
/**
 * The model file of trade module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     contact
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class tradeModel extends model
{
    /**
     * Get trade by id.
     * 
     * @param  int    $id 
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        $trade = $this->dao->select('*')->from(TABLE_TRADE)->where('id')->eq($id)->fetch();
        if($trade) $trade->files = $this->loadModel('file')->getByObject('trade', $id);
        return $trade;
    }

    /** 
     * Get trade list.
     * 
     * @param  string $mode 
     * @param  string $date 
     * @param  string $orderBy 
     * @param  int    $pager 
     * @access public
     * @return array 
     */
    public function getList($mode = 'all', $date = '', $orderBy = 'id_desc', $pager = null)
    {
        if($mode == 'bysearch') $date = '';
        if($this->session->tradeQuery === false) $this->session->set('tradeQuery', ' 1 = 1');
        $tradeQuery = $this->loadModel('search', 'sys')->replaceDynamic($this->session->tradeQuery);

        if(strpos($orderBy, 'id') === false) $orderBy .= ', id_desc';

        /* Do not get trades which user has no privilege to browse their categories. */
        $denyCategories = array();
        $outCategories = $this->dao->select('*')->from(TABLE_CATEGORY)->where('type')->eq('out')->fetchAll('id');
        foreach($outCategories as $id => $outCategory)
        {
            if(!$this->loadModel('tree')->hasRight($outCategory)) $denyCategories[] = $id; 
        }

        $rights = $this->app->user->rights;
        $expensePriv = ($this->app->user->admin == 'super' or isset($rights['tradebrowse']['out'])) ? true : false; 

        $startDate = '';
        $endDate   = '';

        if(strlen($date) == 4)
        {
            $startDate = $date . '-01-01';
            $endDate   = ($date + 1) . '-01-01';
        }

        if(strlen($date) == 6 and strpos($date, 'Q') === false)
        {
            if(substr($date, 4, 1) == 0) $nextMonth = '0' . (substr($date, 5, 1) + 1);
            if(substr($date, 4, 1) == 1) $nextMonth = substr($date, 4, 2) + 1;
            $startDate = substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-01';
            $endDate   = substr($date, 0, 4) . '-' . $nextMonth . '-01';
            if(substr($date, 4, 2) == 12) $endDate = (substr($date, 0, 4) + 1) . '-01-01';
        }

        if(strlen($date) == 6 and strpos($date, 'Q') !== false)
        {
            $startMonth = (substr($date, 5, 1) - 1) * 3 + 1;
            $endMonth   = substr($date, 5, 1) * 3 + 1;
            $startDate  = substr($date, 0, 4) . '-' . $startMonth . '-01';
            $endDate    = substr($date, 5, 1) == 4 ? (substr($date, 0, 4) + 1) . '-01-01' : substr($date, 0, 4) . '-' . $endMonth . '-01';
        }

        $feeCategories      = $this->getSystemCategoryPairs('fee');
        $interestCategories = $this->getSystemCategoryPairs('interest');
        $investCategories   = $this->getSystemCategoryPairs('invest');

        $trades = $this->dao->select('*')->from(TABLE_TRADE)
            ->where('parent')->eq('')
            ->beginIF($startDate and $endDate)->andWhere('date')->ge($startDate)->andWhere('date')->lt($endDate)->fi()
            ->beginIF($mode == 'in')->andWhere('type')->eq('in')->fi()
            ->beginIF($mode == 'out')->andWhere('type')->eq('out')->fi()
            ->beginIF($mode == 'transfer')->andWhere('type', true)->like('transfer%')->orWhere('category')->in(array_keys($feeCategories))->markRight(1)->fi()
            ->beginIF($mode == 'invest')->andWhere('type', true)->in('invest,redeem')->orWhere('category')->in(array_keys($investCategories))->markRight(1)->fi()
            ->beginIF($mode == 'loan')->andWhere('type', true)->in('loan,repay')->orWhere('category')->in(array_keys($interestCategories))->markRight(1)->fi()
            ->beginIF($mode == 'bysearch')->andWhere($tradeQuery)->fi()
            ->beginIF(!empty($denyCategories))->andWhere('category')->notin($denyCategories)
            ->beginIF(!$expensePriv)->andWhere('type')->ne('out')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        if($mode == 'invest')
        {
            $matches = $this->dao->select('*')->from(TABLE_TRADE)->where('type')->eq('redeem')->andWhere('investID')->ne(0)->fetchGroup('investID');
            $returns = $this->dao->select('*')->from(TABLE_TRADE)->where('type')->in('in,out')->andWhere('investID')->ne(0)->fetchGroup('investID');

            foreach($trades as $trade)
            {
                $redeem = 0;
                $trade->status = '';
                $trade->return = 0;

                if($trade->type == 'invest')
                {
                    $trade->status = 'unReturned';
                    if(isset($matches[$trade->id]))
                    {
                        foreach($matches[$trade->id] as $match) $redeem += $match->money;
                        $trade->status = $redeem < $trade->money ? 'returning' : 'returned';
                    }

                    if(isset($returns[$trade->id]))
                    {
                        foreach($returns[$trade->id] as $return)
                        {
                            if($return->type == 'in')  $trade->return += $return->money;
                            if($return->type == 'out') $trade->return -= $return->money;
                        }
                        $trade->return = round($trade->return / $trade->money * 100, 4) . '%';
                    }
                }

            }
        }

        if($mode == 'loan')
        {
            $matches   = $this->dao->select('*')->from(TABLE_TRADE)->where('type')->eq('repay')->andWhere('loanID')->ne(0)->fetchGroup('loanID');
            $interests = $this->dao->select('*')->from(TABLE_TRADE)->where('type')->in('out')->andWhere('loanID')->ne(0)->fetchGroup('loanID');

            foreach($trades as $trade)
            {
                $repay = 0;
                $trade->status   = '';
                $trade->interest = 0;
                if($trade->type == 'loan')
                {
                    $trade->status = 'unRepaied';
                    if(isset($matches[$trade->id]))
                    {
                        foreach($matches[$trade->id] as $match) $repay += $match->money;
                        $trade->status = $repay < $trade->money ? 'repaying' : 'repaied';
                    }

                    if(isset($interests[$trade->id]))
                    {
                        foreach($interests[$trade->id] as $interest)
                        {
                            $trade->interest += $interest->money;
                        }
                        $trade->interest = round($trade->interest / $trade->money * 100, 4) . '%';
                    }
                }
            }
        }

        $this->session->set('tradeQueryCondition', $this->dao->get());

        return $trades;
    }

    /**
     * Get date pairs of trade.
     * 
     * @param  string
     * @access public
     * @return array
     */
    public function getDatePairs($type = 'all')
    {
        return $this->dao->select('id, date')->from(TABLE_TRADE)
            ->where('1')
            ->beginIf($type != 'all')->andWhere('type')->in($type)->fi()
            ->orderBy('date_desc')
            ->fetchPairs();
    }

    /**
     * Get monthly chart data.
     * 
     * @param  string    $type 
     * @param  string    $currentYear 
     * @param  string    $currentMonth 00-12
     * @param  string    $groupBy 
     * @param  string    $currency 
     * @access public
     * @return array
     */
    public function getChartData($type, $currentYear, $currentMonth, $groupBy, $currency)
    {
        list($module, $groupBy, $field) = explode('|', $this->config->trade->groupBy[$groupBy]);

        /* Get this year data if currentMonth == '00'. */
        $startDate = $currentMonth == '00' ? $currentYear . '-01-01' : $currentYear . '-' . $currentMonth . '-01';
        $endDate   = $currentMonth == '00' ? date('Y-m-d', strtotime('+12 months', strtotime($startDate))) : date('Y-m-d', strtotime('+1 months', strtotime($startDate)));

        if($groupBy == 'category')
        {
            if($type == 'in')  $list = $this->loadModel('tree')->getPairs(0, 'in');
            if($type == 'out') $list = $this->loadModel('tree')->getPairs(0, 'out');
            $list = array('' => '') + $list;
        }

        if($groupBy == 'dept')     $list = $this->loadModel('tree')->getOptionMenu('dept', 0, true);
        if($groupBy == 'area')     $list = $this->loadModel('tree')->getOptionMenu('area', 0, true);
        if($groupBy == 'industry') $list = $this->loadModel('tree')->getOptionMenu('industry', 0, true);
        if($groupBy == 'size')
        {
            $this->app->loadLang('customer');
            $list = $this->lang->customer->sizeNameList;
        }
        if($groupBy == 'line')
        {
            $this->app->loadLang('product');
            $list = $this->lang->product->lineList;
        }

        if($module == 'trade')
        {
            $datas = $this->dao->select("$groupBy as name, sum(money) as value")->from(TABLE_TRADE)
                ->where('type')->eq($type)
                ->beginIf($currency != '')->andWhere('currency')->eq($currency)->fi()
                ->beginIf($startDate != '' and $endDate != '')->andWhere('date')->ge($startDate)->andWhere('date')->lt($endDate)->fi()
                ->beginIf($groupBy == 'category')->andWhere('category')->in(array_keys($list))->fi()
                ->groupBy($groupBy)
                ->orderBy('value_desc')
                ->fetchAll('name');
        }
        else
        {
            $t2 = $this->config->report->moduleList[$module];
            $datas = $this->dao->select("ifnull(t2.{$groupBy}, 'null') as name, sum(money) as value")->from(TABLE_TRADE)->alias('t1')
                ->leftJoin($t2)->alias('t2')->on("t1.$field = t2.id")
                ->where('t1.type')->eq($type)
                ->beginIf($currency != '')->andWhere('currency')->eq($currency)->fi()
                ->beginIf($startDate != '' and $endDate != '')->andWhere('date')->ge($startDate)->andWhere('date')->lt($endDate)->fi()
                ->groupBy("t2.{$groupBy}")
                ->orderBy('value_desc')
                ->fetchAll("name");
        }

        if(empty($datas)) return array();

        if($groupBy == 'area')
        {
            $areaParents = $this->dao->select('id')->from(TABLE_CATEGORY)->where('parent')->eq(0)->andWhere('type')->eq('area')->fetchPairs();
            $areas       = $this->dao->select('id,path')->from(TABLE_CATEGORY)->where('type')->eq('area')->fetchPairs();
            $areaList    = array();
            foreach($areaParents as $areaParent)
            {
                foreach($areas as $id => $path)
                {
                    if(strpos($path, ',' . $areaParent . ',') !== false) $areaList[$areaParent][] = $id;
                }
            }

            foreach($datas as $name => $data)
            {
                if(empty($list[$name]))
                {
                    if(!isset($datas['unset'])) $datas['unset'] = new stdclass();
                    $datas['unset']->name  = $this->lang->trade->report->undefined;
                    $datas['unset']->value = isset($datas['unset']->value) ? $datas['unset']->value + $data->value : $data->value;
                    unset($datas[$name]);
                }
            }

            foreach($areaList as $parent => $areaChildren)
            {
                foreach($areaChildren as $areaChild)
                {
                    foreach($datas as $name => $data)
                    {
                        if($name == $areaChild)
                        {
                            if(!isset($datas[$parent])) $datas[$parent] = new stdclass();
                            $datas[$parent]->name  = $list[$parent];
                            $datas[$parent]->value = isset($datas[$parent]->value) ? $datas[$parent]->value + $data->value : $data->value;
                            unset($datas[$name]);
                        }
                    }
                }
            }
        }
        else
        {
            foreach($datas as $name => $data)
            {
                if(empty($list[$name]))
                {
                    $data->name  = $this->lang->trade->report->undefined;
                    $data->value = isset($datas['unset']) ? $datas['unset']->value + $data->value : $data->value;
                    $datas['unset'] = $data;
                    unset($datas[$name]);
                }
                else
                {
                    $data->name = $list[$name];
                }
            }
        }

        $chartDatas = array();
        foreach($datas as $data) $chartDatas[$data->value] = $data;
        krsort($chartDatas);

        return $chartDatas;
    }

    /** 
     * Get trade list by trade's id list.
     * 
     * @param  array    $idList 
     * @access public
     * @return void
     */
    public function getByIdList($idList)
    {
        return $this->dao->select('*')->from(TABLE_TRADE)->where('id')->in($idList)->fetchAll('id');
    }

    /**
     * Get trades by year.
     * 
     * @param  string    $year 
     * @param  string    $currency 
     * @access public
     * @return void
     */
    public function getByYear($year, $currency)
    {
        return $this->dao->select('*, substr(date, 6, 2) as month')->from(TABLE_TRADE)
            ->where('date')->like("$year%")
            ->andWhere('currency')->eq($currency)
            ->orderBy('date_desc')
            ->fetchGroup('month');
    }

    /** 
     * Get details of a trade.
     * 
     * @param  int    $tradeID 
     * @access public
     * @return array
     */
    public function getDetail($tradeID)
    {
        return $this->dao->select('*')->from(TABLE_TRADE)->where('parent')->eq($tradeID)->fetchAll();
    }

    /**
     * Get system category pairs.
     * 
     * @param  string    $type 
     * @access public
     * @return array
     */
    public function getSystemCategoryPairs($type)
    {
        return $this->dao->select('id,name')->from(TABLE_CATEGORY)
            ->where(1)
            ->beginIF($type == 'fee')->andWhere('major')->eq('7')->fi()
            ->beginIF($type == 'invest')->andWhere('major')->in('5,6')->fi()
            ->beginIF($type == 'interest')->andWhere('major')->eq('8')->fi()
            ->fetchPairs();
    }

    /**
     * Create a trade.
     * 
     * @param  string    $type   in|out
     * @access public
     * @return void
     */
    public function create($type)
    {
        $now = helper::now();
        if($type == 'in') $_POST['objectType'] = array('contract');

        $trade = fixer::input('post')
            ->add('type', $type)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', $now)
            ->add('handlers', trim(join(',', $this->post->handlers), ','))
            ->setDefault('contract', 0)
            ->setIf($this->post->trader == '', 'trader', 0)
            ->setIf($this->post->createTrader, 'trader', 0)
            ->setIf($this->post->customer, 'trader', $this->post->customer)
            ->setIf($type == 'in', 'order', 0)
            ->setIf(!$this->post->objectType or !in_array('order', $this->post->objectType), 'order', 0)
            ->setIf(!$this->post->objectType or !in_array('contract', $this->post->objectType), 'contract', 0)
            ->remove('objectType,customer')
            ->striptags('desc')
            ->get();

        $depositor = $this->loadModel('depositor', 'cash')->getByID($trade->depositor);
        if(!empty($depositor)) $trade->currency = $depositor->currency;

        $this->dao->insert(TABLE_TRADE)
            ->data($trade, $skip = 'createTrader,traderName,files,labels')
            ->autoCheck()
            ->batchCheck($this->config->trade->require->create, 'notempty')
            ->exec();

        $tradeID = $this->dao->lastInsertID();
        if(!dao::isError()) $this->loadModel('file')->saveUpload('trade', $tradeID);

        if($this->post->createTrader and $type == 'out')
        {
            $trader = new stdclass();
            $trader->relation    = 'provider';
            $trader->name        = $this->post->traderName;
            $trader->createdBy   = $this->app->user->account;
            $trader->createdDate = helper::now();
            $trader->public      = 1;

            $this->dao->insert(TABLE_CUSTOMER)->data($trader)->check('name', 'notempty')->exec();
            $trader = $this->dao->lastInsertID();
            $this->loadModel('action')->create('customer', $trader, 'Created');

            $this->dao->update(TABLE_TRADE)->set('trader')->eq($trader)->where('id')->eq($tradeID)->exec();
        }

        return $tradeID;
    }

    /**
     * Batch create.
     * 
     * @access public
     * @return array
     */
    public function batchCreate()
    {
        $now    = helper::now();
        $trades = array();

        $depositorList = $this->loadModel('depositor', 'cash')->getList();

        $this->loadModel('action');
        /* Get data. */
        foreach($this->post->type as $key => $type)
        {
            if(empty($type)) break;
            if(!$this->post->money[$key]) continue;

            $depositor = $this->post->depositor[$key] == 'ditto' ? $depositor : $this->post->depositor[$key];
            $category  = $this->post->category[$key]  == 'ditto' ? $category : $this->post->category[$key];
            $dept      = $this->post->dept[$key]      == 'ditto' ? $dept : $this->post->dept[$key];
            $trader    = $this->post->trader[$key]    == 'ditto' ? $trader : ($this->post->trader[$key] ? $this->post->trader[$key] : 0);
            $product   = $this->post->product[$key]   == 'ditto' ? $product : $this->post->product[$key];

            $trade = new stdclass();
            $trade->type           = $type;
            $trade->depositor      = $depositor;
            $trade->money          = $this->post->money[$key];
            $trade->category       = $category;
            $trade->dept           = $dept;
            $trade->trader         = $trader;
            $trade->createTrader   = isset($this->post->createTrader[$key]) ? $this->post->createTrader[$key] : false;
            $trade->createCustomer = false;
            $trade->traderName     = isset($this->post->traderName[$key]) ? $this->post->traderName[$key] : '';
            $trade->handlers       = !empty($this->post->handlers[$key]) ? join(',', $this->post->handlers[$key]) : '';
            $trade->product        = $product;
            $trade->date           = $this->post->date[$key];
            $trade->desc           = strip_tags(nl2br($this->post->desc[$key]), $this->config->allowedTags);
            $trade->currency       = isset($depositorList[$trade->depositor]) ? $depositorList[$trade->depositor]->currency : '';
            $trade->createdBy      = $this->app->user->account;
            $trade->createdDate    = $now;

            if($trade->createTrader)
            {
                $this->dao->insert(TABLE_CUSTOMER)->data(array('relation' => 'provider', 'name' => $trade->traderName, 'public' => 1))->exec();
                $trade->trader = $this->dao->lastInsertID();
                $this->action->create('customer', $trade->trader, 'Created');
            }

            $trades[$key] = $trade;
        }

        if(empty($trades)) return array('result' => 'fail');

        $errors = $this->batchCheck($trades);
        if(!empty($errors)) return array('result' => 'fail', 'message' => $errors);

        foreach($trades as $trade)
        {
            $this->dao->insert(TABLE_TRADE)->data($trade, $skip = 'createTrader,traderName,createCustomer')->autoCheck()->exec();
            $tradeID = $this->dao->lastInsertID();
            if(!dao::isError()) $this->action->create('trade', $tradeID, 'Created');
        }

        if(!dao::isError()) return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse'));
        return array('result' => 'fail', 'message' => dao::getError());
    }

    /**
     * Batch update trades.
     * 
     * @access public
     * @return void
     */
    public function batchUpdate()
    {
        $trades = array();

        $depositorList = $this->loadModel('depositor', 'cash')->getList();

        /* Get data. */
        if($this->post->type === false) return array('result' => 'fail');

        foreach($this->post->type as $key => $type)
        {
            if(empty($type)) break;
            $trade = new stdclass();
            $trade->depositor      = $this->post->depositor[$key];
            $trade->money          = $this->post->money[$key];
            $trade->type           = $type;
            $trade->dept           = $this->post->dept[$key];
            $trade->trader         = $this->post->trader[$key] ? $this->post->trader[$key] : 0;
            $trade->createTrader   = false;
            $trade->createCustomer = false;
            $trade->traderName     = $this->post->traderName[$key];
            $trade->handlers       = !empty($this->post->handlers[$key]) ? join(',', $this->post->handlers[$key]) : '';
            $trade->product        = $this->post->product[$key];
            $trade->date           = $this->post->date[$key];
            $trade->desc           = strip_tags(nl2br($this->post->desc[$key]));
            $trade->currency       = $depositorList[$trade->depositor]->currency;
            if(isset($this->post->category[$key])) $trade->category = $this->post->category[$key];

            $trades[$key] = $trade;
        }

        if(empty($trades)) return array('result' => 'fail');

        $errors = $this->batchCheck($trades);
        if(!empty($errors)) return array('result' => 'fail', 'message' => $errors);

        $tradeIDList = array();
        foreach($trades as $tradeID => $trade)
        {
            $this->dao->update(TABLE_TRADE)->data($trade, $skip = 'createTrader,traderName,createCustomer')->where('id')->eq($tradeID)->autoCheck()->exec();
        }
        if(!dao::isError()) return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse'));
        return array('result' => 'fail', 'message' => dao::getError());
    }

    /**
     * Batch check trades.
     * 
     * @param  array    $trades 
     * @access public
     * @return void
     */
    public function batchCheck($trades)
    {
        $this->app->loadClass('filter', true);

        $errors = array();
        foreach($trades as $key => $trade)
        {
            $item = $this->lang->trade->money; 
            if(empty($trade->money) or !validater::checkFloat($trade->money)) $errors["money" . $key] = sprintf($this->lang->error->notempty, $item) . sprintf($this->lang->error->float, $item);

            $item = $this->lang->trade->handlers;
            if(empty($trade->handlers)) $errors['handlers'. $key] = sprintf($this->lang->error->notempty, $item);

            $item = $this->lang->trade->date;
            if(empty($trade->date) or !validater::checkDate($trade->date)) $errors['date' . $key] = sprintf($this->lang->error->date, $item) . sprintf($this->lang->error->notempty, $item);

        }

        return $errors;
    }

    /**
     * Update a trade.
     * 
     * @param  int    $tradeID 
     * @access public
     * @return string|bool
     */
    public function update($tradeID)
    {
        $oldTrade = $this->getByID($tradeID);

        if($oldTrade->type == 'in') $_POST['objectType'] = array('contract');

        $trade = fixer::input('post')
            ->add('type', $oldTrade->type)
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->add('handlers', trim(join(',', $this->post->handlers), ','))
            ->setDefault('contract', 0)
            ->setIf($this->post->trader == '', 'trader', 0)
            ->setIf($this->post->createTrader, 'trader', 0)
            ->setIf($this->post->customer, 'trader', $this->post->customer)
            ->setIf($oldTrade->type == 'in', 'order', 0)
            ->setIf(!$this->post->objectType or !in_array('order', $this->post->objectType), 'order', 0)
            ->setIf(!$this->post->objectType or !in_array('contract', $this->post->objectType), 'contract', 0)
            ->remove('objectType,customer')
            ->striptags('desc')
            ->get();

        $this->dao->update(TABLE_TRADE)
            ->data($trade, $skip = 'createTrader,traderName,files,labels,invests,redeems,profits')
            ->autoCheck()
            ->batchCheck($this->config->trade->require->edit, 'notempty')
            ->where('id')->eq($tradeID)->exec();

        if($this->post->createTrader and $trade->type == 'out')
        {
            $trader = new stdclass();
            $trader->relation = 'provider';
            $trader->name     = $this->post->traderName;
            $trader->public   = 1;

            $this->dao->insert(TABLE_CUSTOMER)->data($trader)->check('name', 'notempty')->exec();
            $traderID = $this->dao->lastInsertID();

            $this->loadModel('action')->create('customer', $traderID, 'Created');

            $this->dao->update(TABLE_TRADE)->set('trader')->eq($traderID)->where('id')->eq($tradeID)->exec();
        }

        if($trade->type == 'invest')
        {
            $this->dao->update(TABLE_TRADE)->set('investID')->eq(0)->where('investID')->eq($tradeID)->exec();
            if($this->post->redeems)
            {
                $this->dao->update(TABLE_TRADE)->set('investID')->eq($tradeID)->where('id')->in($this->post->redeems)->exec();
            }
            if($this->post->profits)
            {
                $this->dao->update(TABLE_TRADE)->set('investID')->eq($tradeID)->where('id')->in($this->post->profits)->exec();
            }
        }

        if(!dao::isError())
        {
            $this->loadModel('file')->saveUpload('trade', $tradeID);
            return commonModel::createChanges($oldTrade, $trade);
        }

        return false;
    }

    /**
     * Save imported trades. 
     * 
     * @param  int    $depositorID 
     * @access public
     * @return void
     */
    public function saveImport($depositorID)
    {
        $now       = helper::now();
        $trades    = array();
        $depositor = $this->loadModel('depositor', 'cash')->getByID($depositorID);

        $this->loadModel('action');

        $newCustomer = array();
        $newTrader   = array();
        $category    = '';
        $dept        = '';

        /* Get data. */
        foreach($this->post->type as $key => $type)
        {
            if(empty($type)) break;
            if(!$this->post->money[$key]) continue;
            if(isset($this->post->ignoreUnique[$key]) and $this->post->ignoreUnique[$key]) continue;

            $category = $this->post->category[$key] == 'ditto' ? $category : $this->post->category[$key];
            $dept     = $this->post->dept[$key]     == 'ditto' ? $dept : $this->post->dept[$key];

            $trade = new stdclass();
            $trade->type           = $type;
            $trade->depositor      = $depositorID;
            $trade->money          = $this->post->money[$key];
            $trade->category       = $category;
            $trade->dept           = $dept;
            $trade->trader         = $this->post->trader[$key];
            $trade->createTrader   = isset($this->post->createTrader[$key])   ? $this->post->createTrader[$key] : '';
            $trade->traderName     = isset($this->post->traderName[$key])     ? $this->post->traderName[$key] : '';
            $trade->createCustomer = isset($this->post->createCustomer[$key]) ? $this->post->createCustomer[$key] : '';
            $trade->customerName   = isset($this->post->customerName[$key])   ? $this->post->customerName[$key] : '';
            $trade->handlers       = !empty($this->post->handlers[$key]) ? join(',', $this->post->handlers[$key]) : '';
            $trade->product        = isset($this->post->product[$key])        ? $this->post->product[$key] : 0;
            $trade->date           = $this->post->date[$key];
            $trade->desc           = strip_tags(nl2br($this->post->desc[$key]));
            $trade->currency       = $depositor->currency;
            $trade->createdBy      = $this->app->user->account;
            $trade->createdDate    = $now;

            if($trade->createTrader)
            {
                if(isset($newTrader[$trade->traderName]))
                {
                    $trade->trader = $newTrader[$trade->traderName];
                }
                else
                {
                    $data = new stdclass();
                    $data->relation    = 'provider';
                    $data->name        = $trade->traderName;
                    $data->level       = 0;
                    $data->public      = 1;
                    $data->createdBy   = $this->app->user->account;
                    $data->createdDate = $now;

                    $this->dao->insert(TABLE_CUSTOMER)->data($data)->exec();
                    $trade->trader = $this->dao->lastInsertID();
                    $this->action->create('customer', $trade->trader, 'Created');

                    $newTrader[$data->name] = $trade->trader;
                }
            }

            if($trade->createCustomer)
            {
                if(isset($newCustomer[$trade->customerName]))
                {
                    $trade->trader = $newCustomer[$trade->customerName];
                }
                else
                {
                    $customer = new stdclass();
                    $customer->relation    = 'client';
                    $customer->name        = $trade->customerName;
                    $customer->level       = 0;
                    $customer->status      = 'payed';
                    $customer->intension   = $trade->desc;
                    $customer->createdBy   = $this->app->user->account;
                    $customer->assignedTo  = $this->app->user->account;
                    $customer->createdDate = $now;

                    $this->dao->insert(TABLE_CUSTOMER)->data($customer)->exec();
                    $trade->trader = $this->dao->lastInsertID();
                    $this->action->create('customer', $trade->trader, 'Created');

                    $newCustomer[$customer->name] = $trade->trader;
                }
            }

            if(empty($trade->trader)) $trade->trader = 0; 

            $trades[$key] = $trade;
        }

        if(empty($trades)) return array('result' => 'fail');

        $errors = $this->batchCheck($trades);
        if(!empty($errors)) return array('result' => 'fail', 'message' => $errors);

        $tradeIDList = array();
        foreach($trades as $trade)
        {
            $this->dao->insert(TABLE_TRADE)->data($trade, $skip = 'createTrader,traderName,createCustomer,customerName')->autoCheck()->exec();
        }

        if(!dao::isError()) return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse'));
        return array('result' => 'fail', 'message' => dao::getError());
    }

    /**
     * Transfer.
     * 
     * @access public
     * @return int|bool
     */
    public function transfer()
    {
        if($this->post->receipt == $this->post->payment) return array('result' => 'fail', 'message' => $this->lang->trade->notEqual);

        $receiptDepositor = $this->loadModel('depositor', 'cash')->getByID($this->post->receipt);
        $paymentDepositor = $this->loadModel('depositor', 'cash')->getByID($this->post->payment);

        $diffCurrency = $receiptDepositor->currency != $paymentDepositor->currency;

        $now = helper::now();

        $payment = fixer::input('post')
            ->add('type', 'transferout')
            ->add('category', 'transferout')
            ->add('depositor', $this->post->payment)
            ->add('currency', $paymentDepositor->currency)
            ->add('handlers', trim(join(',', $this->post->handlers), ','))
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', $now)
            ->add('editedDate', $now)
            ->setIF($diffCurrency, 'money', $this->post->transferOut)
            ->get();

        $receipt = $payment;
        $fee     = $payment;

        $this->dao->insert(TABLE_TRADE)
            ->data($payment, $skip = 'receipt, payment, fee, transferIn, transferOut')
            ->autoCheck()
            ->check('handlers', 'notempty')
            ->batchCheckIF($diffCurrency, 'transferOut,transferIn', 'notempty')
            ->batchCheckIF($diffCurrency, 'transferOut,transferIn', 'float')
            ->checkIF(!$diffCurrency, 'money', 'notempty')
            ->exec();

        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        $paymentID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('trade', $paymentID, 'Created');

        $receipt->type      = 'transferin';
        $receipt->category  = 'transferin';
        $receipt->depositor = $this->post->receipt;
        $receipt->currency  = $receiptDepositor->currency;
        if($diffCurrency) $receipt->money = $this->post->transferIn;

        $this->dao->insert(TABLE_TRADE)
            ->data($receipt, $skip = 'receipt, payment, fee, transferIn, transferOut')
            ->autoCheck()
            ->check('handlers', 'notempty')
            ->batchCheckIF($diffCurrency, 'transferOut, transferIn', 'notempty')
            ->checkIF(!$diffCurrency, 'money', 'notempty')
            ->exec();

        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        $receiptID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('trade', $receiptID, 'Created');

        if($this->post->fee)
        {
            $fee->type      = 'out';
            $fee->category  = 'fee';
            $fee->depositor = $this->post->payment;
            $fee->money     = $this->post->fee;
            $fee->desc      = sprintf($this->lang->trade->feeDesc, $fee->date, $paymentDepositor->abbr, $receiptDepositor->abbr);
            if($diffCurrency) $fee->desc = sprintf($this->lang->trade->feeDesc, $fee->date, $paymentDepositor->abbr, $receiptDepositor->abbr);

            $this->dao->insert(TABLE_TRADE)->data($fee, $skip = 'receipt, payment, fee, transferIn, transferOut')->exec();
            if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

            $feeID = $this->dao->lastInsertID();
            $this->loadModel('action')->create('trade', $feeID, 'Created');
        }

        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse', 'mode=transfer'));
    }

    /**
     * Invest.
     * 
     * @param  string $type
     * @access public
     * @return int|bool
     */
    public function invest($type)
    {
        $depositor = $this->loadModel('depositor', 'cash')->getByID($this->post->depositor);
        $now = helper::now();

        $trade = fixer::input('post')
            ->add('type', $type)
            ->add('category', $type)
            ->add('currency', !empty($depositor) ? $depositor->currency : '')
            ->add('handlers', trim(join(',', $this->post->handlers), ','))
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', $now)
            ->add('editedDate', $now)
            ->setIf(!$this->post->depositor, 'depositor', 0)
            ->setIf($type == 'invest', 'investID', 0)
            ->setIf($this->post->createTrader or !$this->post->trader, 'trader', 0)
            ->get();

        $this->dao->insert(TABLE_TRADE)
            ->data($trade, $skip = 'investCategory,investMoney,createTrader,traderName')
            ->autoCheck()
            ->batchCheck($this->config->trade->require->invest, 'notempty')
            ->exec();

        if(dao::isError()) return false;

        $tradeID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('trade', $tradeID, 'Created');

        if($this->post->createTrader and $type == 'invest')
        {
            $trader = new stdclass();
            $trader->relation    = 'provider';
            $trader->name        = $this->post->traderName;
            $trader->createdBy   = $this->app->user->account;
            $trader->createdDate = helper::now();
            $trader->public      = 1;

            $this->dao->insert(TABLE_CUSTOMER)->data($trader)->check('name', 'notempty')->exec();
            if(dao::isError()) return false;

            $traderID = $this->dao->lastInsertID();
            $this->loadModel('action')->create('customer', $traderID, 'Created');

            $this->dao->update(TABLE_TRADE)->set('trader')->eq($traderID)->where('id')->eq($tradeID)->exec();
        }

        $profitCategory = $this->dao->select('*')->from(TABLE_CATEGORY)->where('major')->eq(5)->fetch();
        $lossCategory   = $this->dao->select('*')->from(TABLE_CATEGORY)->where('major')->eq(6)->fetch();
        if($type == 'redeem' and $this->post->investMoney)
        {
            $invest = fixer::input('post') 
                ->add('category', $this->post->investCategory)
                ->add('money', $this->post->investMoney)
                ->add('currency', !empty($depositor) ? $depositor->currency : '')
                ->add('handlers', trim(join(',', $this->post->handlers), ','))
                ->add('createdBy', $this->app->user->account)
                ->add('createdDate', $now)
                ->add('editedDate', $now)
                ->setIf($this->post->createTrader or !$this->post->trader, 'trader', 0)
                ->setIF($this->post->investCategory == $profitCategory->id, 'type', 'in')
                ->setIF($this->post->investCategory == $lossCategory->id, 'type', 'out')
                ->get();

            $this->config->trade->require->invest = 'depositor,money,type,handlers,investID';
            $this->dao->insert(TABLE_TRADE)
                ->data($invest, $skip = 'investCategory,investMoney,createTrader,traderName')
                ->autoCheck()
                ->batchCheck($this->config->trade->require->invest, 'notempty')
                ->exec();

            if(dao::isError()) return false;

            $revesetID = $this->dao->lastInsertID();
            $this->loadModel('action')->create('trade', $revesetID, 'Created');
        }

        return !dao::isError();
    }

    /**
     * Loan.
     * 
     * @param  string $type
     * @access public
     * @return int|bool
     */
    public function loan($type)
    {
        $depositor = $this->loadModel('depositor', 'cash')->getByID($this->post->depositor);
        $now = helper::now();

        $trade = fixer::input('post')
            ->add('type', $type)
            ->add('category', $type)
            ->add('currency', !empty($depositor) ? $depositor->currency : '')
            ->add('handlers', trim(join(',', $this->post->handlers), ','))
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', $now)
            ->add('editedDate', $now)
            ->setIf(!$this->post->depositor, 'depositor', 0)
            ->setIf($type == 'loan', 'loanID', 0)
            ->setIf($this->post->createTrader or !$this->post->trader, 'trader', 0)
            ->get();

        $this->dao->insert(TABLE_TRADE)
            ->data($trade, $skip = 'loanCategory,interest,createTrader,traderName')
            ->autoCheck()
            ->batchCheck($this->config->trade->require->loan, 'notempty')
            ->exec();

        if(dao::isError()) return false;

        $tradeID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('trade', $tradeID, 'Created');

        if($this->post->createTrader and $type == 'loan')
        {
            $trader = new stdclass();
            $trader->relation    = 'provider';
            $trader->name        = $this->post->traderName;
            $trader->createdBy   = $this->app->user->account;
            $trader->createdDate = helper::now();
            $trader->public      = 1;

            $this->dao->insert(TABLE_CUSTOMER)->data($trader)->check('name', 'notempty')->exec();
            if(dao::isError()) return false;

            $traderID = $this->dao->lastInsertID();
            $this->loadModel('action')->create('customer', $traderID, 'Created');

            $this->dao->update(TABLE_TRADE)->set('trader')->eq($traderID)->where('id')->eq($tradeID)->exec();
        }

        if($type == 'repay' and $this->post->interest)
        {
            $interestCategory = $this->dao->select('*')->from(TABLE_CATEGORY)->where('major')->eq(8)->fetch();

            $interest = fixer::input('post') 
                ->add('type', 'out')
                ->add('category', $interestCategory->id)
                ->add('money', $this->post->interest)
                ->add('currency', !empty($depositor) ? $depositor->currency : '')
                ->add('handlers', trim(join(',', $this->post->handlers), ','))
                ->add('createdBy', $this->app->user->account)
                ->add('createdDate', $now)
                ->add('editedDate', $now)
                ->setIf($this->post->createTrader or !$this->post->trader, 'trader', 0)
                ->get();

            $this->config->trade->require->create = 'depositor,money,type,handlers,loanID';
            $this->dao->insert(TABLE_TRADE)
                ->data($interest, $skip = 'interest,createTrader,traderName')
                ->autoCheck()
                ->batchCheck($this->config->trade->require->create, 'notempty')
                ->exec();

            if(dao::isError()) return false;

            $interestID = $this->dao->lastInsertID();
            $this->loadModel('action')->create('trade', $interestID, 'Created');
        }

        return !dao::isError();
    }

    /**
     * Save details of a trade. 
     * 
     * @param  int    $tradeID 
     * @access public
     * @return void
     */
    public function saveDetail($tradeID)
    {
        $trade = $this->getByID($tradeID);
        $trade->parent = $tradeID;

        $now = helper::now();
        $trade->createdDate = $now;
        $trade->createdBy   = $this->app->user->account;
        $trade->editedDate  = $now;
        $trade->editedBy    = $this->app->user->account;
        $trade->category    = 0;
        $trade->handlers    = '';

        $this->dao->delete()->from(TABLE_TRADE)->where('parent')->eq($tradeID)->exec();

        foreach($this->post->money as $key => $money)
        {
            if($money === '') continue;

            $trade->money = $money;
            $trade->desc  = $this->post->desc[$key];
            if(isset($this->post->category[$key])) $trade->category = join(',', $this->post->category[$key]);
            if(isset($this->post->handlers[$key])) $trade->handlers = join(',', $this->post->handlers[$key]);

            $this->dao->insert(TABLE_TRADE)->data($trade, 'id,files')->exec();
        }

        return !dao::isError();
    }

    /**
     * Delete a trade.
     * 
     * @param  int      $tradeID 
     * @access public
     * @return void
     */
    public function delete($tradeID, $null = null)
    {
        $this->dao->delete()->from(TABLE_TRADE)->where('id')->eq($tradeID)->exec();
        return !dao::isError();
    }

    /**
     *  Count money.
     * 
     * @param  array   $trades 
     * @access public
     * @return array
     */
    public function countMoney($trades, $mode)
    {
        $totalMoney  = array();
        $currencyList = $this->loadModel('common', 'sys')->getCurrencyList();

        foreach($currencyList as $key => $currency)
        {
            $totalMoney[$key]['in']  = 0;
            $totalMoney[$key]['out'] = 0;
            if($mode == 'invest')
            {
                $totalMoney[$key]['invest'] = 0;
                $totalMoney[$key]['redeem'] = 0;
            }

            foreach($trades as $trade)
            {
                if($trade->currency != $key) continue;
                if($trade->type == 'in' or $trade->type == 'out') $totalMoney[$key][$trade->type] += $trade->money;
                if($mode == 'invest' and ($trade->type == 'invest' or $trade->type == 'redeem')) $totalMoney[$key][$trade->type] += $trade->money;
            }
        }

        foreach($totalMoney as $currency => $money)
        {
            if($mode != 'invest' and $money['in'] == 0 and $money['out'] == 0) continue;
            if($mode == 'invest')
            {
                if($money['invest'] == 0 and $money['redeem'] == 0 and $money['in'] == 0 and $money['out'] == 0) continue;

                $money['invest'] = round($money['invest'], 2);
                $money['redeem'] = round($money['redeem'], 2);

                $tidyMoneyInvest   = "<span title='" . $money['invest'] . "'>" . commonModel::tidyMoney($money['invest']) . '</span>';
                $tidyMoneyRedeem   = "<span title='" . $money['redeem'] . "'>" . commonModel::tidyMoney($money['redeem']) . '</span>';
                $tidyUnRedeemMoney = "<span title='" . ($money['invest'] - $money['redeem']) . "'>" .  commonModel::tidyMoney($money['invest'] - $money['redeem']) . '</span>';
            }

            $money['in']  = round($money['in'], 2);
            $money['out'] = round($money['out'], 2);

            $tidyMoneyIn  = "<span title='" . $money['in'] . "'>" . commonModel::tidyMoney($money['in']) . '</span>';
            $tidyMoneyOut = "<span title='" . $money['out'] . "'>" . commonModel::tidyMoney($money['out']) . '</span>';
            
            if($mode == 'in')  printf($this->lang->trade->totalIn, $currencyList[$currency], $tidyMoneyIn);
            if($mode == 'out') printf($this->lang->trade->totalOut, $currencyList[$currency], $tidyMoneyOut);

            if($mode == 'all' or $mode == 'bysearch' or $mode == 'invest') 
            {
                $profitsMoney = round(($money['in'] - $money['out']), 2);
                if($profitsMoney > 0)  $profits = "<span title='$profitsMoney'>" . $this->lang->trade->profit . commonModel::tidyMoney($profitsMoney) . '</span>';
                if($profitsMoney < 0)  $profits = "<span title='" . -$profitsMoney . "'>" . $this->lang->trade->loss . commonModel::tidyMoney(-$profitsMoney) . '</span>';
                if($profitsMoney == 0) $profits = $this->lang->trade->balance;

                if($mode == 'invest') printf($this->lang->trade->totalInvest, $currencyList[$currency], $tidyMoneyInvest, $tidyMoneyRedeem, $tidyUnRedeemMoney, $profits);
                if($mode != 'invest') printf($this->lang->trade->totalAmount, $currencyList[$currency], $tidyMoneyIn, $tidyMoneyOut, $profits);
            }
        }
    }

    /**
     * Check privilege for expense.
     *
     * @access public
     * @return void
     */
    public function checkPriv($mode)
    {
        if($this->app->user->admin == 'super') return true;
        
        $rights = $this->app->user->rights;
        if($mode == 'out' and !isset($rights['tradebrowse']['out'])) return false;
        if(strpos(',all,in,', ',' . $mode . ',') !== false and !isset($rights['trade']['browse'])) return false;
        if(strpos(',transfer,invest,loan,', ',' . $mode . ',') !== false and !isset($rights['trade'][$mode])) return false;
        return true;
    }

    /**
     * Get data to export. 
     * 
     * @param  string $mode 
     * @access public
     * @return object 
     */
    public function getExportData($mode = '')
    {
        $year       = $this->post->year;
        $totalMonth = $year == date('Y') ? date('m') : 12;
        $trades     = $this->getList($mode = 'all', $year, $orderBy = 'date');
        $depositors = $this->loadModel('depositor', 'cash')->getPairs();
        $lastDates  = $this->dao->select('depositor, max(date)')
            ->from(TABLE_BALANCE)
            ->where('date')->lt("$year-01-01")
            ->groupBy('depositor')
            ->fetchPairs();
        $balances = array();
        foreach($lastDates as $depositor => $date)
        {
            $balances[$depositor] = $this->dao->select('money')
                ->from(TABLE_BALANCE)
                ->where('depositor')->eq($depositor)
                ->andWhere('date')->eq($date)
                ->fetch('money');
        }

        $numberFields = $this->config->trade->excel->numberFields;
        $customWidth  = $this->config->trade->excel->customWidth;

        $titles = array();
        $titles['in']          = $this->lang->trade->in;
        $titles['out']         = $this->lang->trade->out;
        $titles['profit']      = $this->lang->trade->profit . $this->lang->trade->loss;
        $titles['transferin']  = $this->lang->trade->typeList['transferin'];
        $titles['transferout'] = $this->lang->trade->typeList['transferout'];
        $titles['invest']      = $this->lang->trade->invest;
        $titles['redeem']      = $this->lang->trade->redeem;
        $titles['loan']        = $this->lang->trade->loan;
        $titles['repay']       = $this->lang->trade->repay;
        $titles['balance']     = $this->lang->depositor->balance;

        $depositors += array('undefined' => $this->lang->trade->report->undefined, 'total' => $this->lang->trade->total);

        $fields = array();
        $rows   = array();
        foreach($depositors as $depositorID => $depositorName)
        {
            $fields[$depositorID]['month'] = '';
            foreach($titles as $titleKey => $title)
            {
                if($depositorID == 'total' && ($title == 'transferin' or $title == 'transferout')) continue;

                $fields[$depositorID][$titleKey] = $title;

                if(!in_array($titleKey, $numberFields)) $numberFields[] = $titleKey; 
                if(!in_array($titleKey, $customWidth))  $customWidth[$titleKey] = 10;
            }
        }

        /* Initial rows. */
        foreach($this->lang->trade->monthList as $monthKey => $month)
        {
            foreach($fields as $depositorID => $field)
            {
                foreach($field as $fieldKey => $fieldName)
                {
                    $rows[$depositorID][$monthKey][$fieldKey] = 0;
                }
                $rows[$depositorID][$monthKey]['month'] = $month;
            }
        }

        /* Add last year balance. */
        foreach($balances as $depositor => $money)
        {
            if(!isset($depositors[$depositor]))
            {
                $depositor = 'undefined';
            }

            /* Add money to balance of last year. */
            $rows[$depositor]['last']['balance'] += $money;
            $rows['total']['last']['balance']    += $money;

            /* Add money to balance of every month in this year. */
            for($i = 1; $i <= $totalMonth; $i++)
            {
                $month = $i < 10 ? '0' . $i : $i;
                $rows[$depositor][$month]['balance'] += $money;
                $rows['total'][$month]['balance']    += $money;
            }

            /* Add money to total balance. */
            $rows[$depositor]['total']['balance'] += $money;
            $rows['total']['total']['balance']    += $money;
        }

        foreach($trades as $trade)
        {
            if(isset($depositors[$trade->depositor]))
            {
                $depositor = $trade->depositor;
            }
            else
            {
                $depositor = 'undefined';
            }

            $month = date('m', strtotime($trade->date));
            $type  = $trade->type;
            $money = $trade->money;
            if($type == 'in'  or $type == 'transferin'  or $type == 'redeem' or $type == 'loan')  $money = $trade->money; 
            if($type == 'out' or $type == 'transferout' or $type == 'invest' or $type == 'repay') $money = -$trade->money; 

            /* Add money to profit and balance of this month. */
            $rows[$depositor][$month][$type]     += $trade->money;
            $rows[$depositor][$month]['balance'] += $money;
            $rows['total'][$month]['balance']    += $money;
            if($type !='transferin' && $type != 'transferout')
            {
                $rows['total'][$month][$type] += $trade->money;
            }
            if($type == 'in' or $type == 'out') 
            {
                $rows[$depositor][$month]['profit'] += $money;
                $rows['total'][$month]['profit']    += $money;
            }

            /* Add money to profit and balance of every month that after this month. */
            for($i = (int)$month + 1; $i <= $totalMonth; $i++)
            {
                $m = $i < 10 ? '0' . $i : $i;
                $rows[$depositor][$m]['balance'] += $money;
                $rows['total'][$m]['balance']    += $money;
            }

            /* Add money to total profit and balance. */
            $rows[$depositor]['total'][$type]     += $trade->money;
            $rows[$depositor]['total']['balance'] += $money;
            $rows['total']['total']['balance']    += $money;
            if($type !='transferin' && $type != 'transferout')
            {
                $rows['total']['total'][$type] += $trade->money;
            }
            if($trade->type == 'in' or $trade->type == 'out') 
            {
                $rows[$depositor]['total']['profit'] += $money;
                $rows['total']['total']['profit']    += $money;
            }
        }

        /* Remove empty columns and depositors. */
        foreach($rows as $depositor => $row)
        {
            /* Init emptyDepositor and emptyColumns. */
            $emptyDepositor = true;
            $emptyColumns   = array();
            foreach($titles as $key => $title)
            {
                $emptyColumns[$key] = true;
            }

            foreach($row as $month => $data)
            {
                foreach($data as $key => $money)
                {
                    if($key != 'month' && $money) 
                    {
                        $emptyDepositor = false;
                        unset($emptyColumns[$key]);
                    }
                }
            }

            /* Remove empty columns. */
            foreach($emptyColumns as $key => $value)
            {
                if($key == 'profit' or $key == 'balance') continue;
                unset($fields[$depositor][$key]);
            }

            /* Remove empty depositor. */
            if($emptyDepositor) 
            {
                unset($fields[$depositor]);
                unset($rows[$depositor]);
            }
        }
        
        $datas = array();
        foreach($fields as $depositor => $fieldData)
        {
            $data = new stdclass();
            $data->fields       = $fieldData;
            $data->numberFields = $numberFields;
            $data->kind         = 'depositor';
            $data->rows         = $rows[$depositor];
            $data->title        = $depositors[$depositor];
            $data->customWidth  = $customWidth;
            $data->help         = $this->lang->trade->excel->help->depositor;

            $datas[] = $data;
        }

        return $datas;
    }

    /**
     * Get compare datas. 
     * 
     * @param  array  $selectYears 
     * @param  array  $incomeDatas 
     * @param  array  $expenseDatas 
     * @param  array  $profitDatas 
     * @param  string $currency 
     * @param  string $unit 
     * @access public
     * @return void
     */
    public function getCompareDatas($selectYears = array(), &$incomeDatas = array(), &$expenseDatas = array(), &$profitDatas = array(), $currency = 'rmb', $unit = '1')
    {
        foreach($selectYears as $year)
        {
            $trades = $this->getByYear($year, $currency);
            $incomeDatas['all'][$year]  = 0; 
            $expenseDatas['all'][$year] = 0; 
            $profitDatas['all'][$year]  = 0; 
            foreach($trades as $month => $monthTrades)
            {
                $incomeDatas[$month][$year]  = 0;
                $expenseDatas[$month][$year] = 0;
                $profitDatas[$month][$year]  = 0;
                foreach($monthTrades as $trade)
                {
                    if($trade->type == 'in')  $incomeDatas[$month][$year]  += $trade->money;
                    if($trade->type == 'out') $expenseDatas[$month][$year] += $trade->money;
                }

                $profitDatas[$month][$year]  = $incomeDatas[$month][$year] - $expenseDatas[$month][$year];
                $profitDatas['all'][$year]  += $incomeDatas[$month][$year] - $expenseDatas[$month][$year];
                $incomeDatas['all'][$year]  += $incomeDatas[$month][$year];
                $expenseDatas['all'][$year] += $expenseDatas[$month][$year];
            }
        }

        ksort($incomeDatas, SORT_STRING);
        ksort($expenseDatas, SORT_STRING);
        ksort($profitDatas, SORT_STRING);

        foreach($incomeDatas as $month => $data)  foreach($data as $year => $money) $incomeDatas[$month][$year]  = round($money / $unit, 2);
        foreach($expenseDatas as $month => $data) foreach($data as $year => $money) $expenseDatas[$month][$year] = round($money / $unit, 2);
        foreach($profitDatas as $month => $data)  foreach($data as $year => $money) $profitDatas[$month][$year]  = round($money / $unit, 2);
    }
}
