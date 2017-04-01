<?php
/**
 * The model file for contract of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     contract
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class contractModel extends model
{
    /**
     * Get contract by ID.
     * 
     * @param  int    $contractID 
     * @access public
     * @return object.
     */
    public function getByID($contractID = 0)
    {
        $contract = $this->dao->select('*')->from(TABLE_CONTRACT)->where('id')->eq($contractID)->fetch();

        if($contract)
        {
            $contract->order = array();
            $contractOrders  = $this->dao->select('*')->from(TABLE_CONTRACTORDER)->where('contract')->eq($contractID)->fetchAll();
            foreach($contractOrders as $contractOrder) $contract->order[] = $contractOrder->order;

            $contract->files        = $this->loadModel('file', 'sys')->getByObject('contract', $contractID);
            $contract->returnList   = $this->getReturnList($contractID);
            $contract->deliveryList = $this->getDeliveryList($contractID);
        }

        return $contract;
    }

    /**
     * Get contract list.
     * 
     * @param  int    $customer
     * @param  string $mode
     * @param  string $owner
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getList($customer = 0, $mode = 'all', $owner = 'all', $orderBy = 'id_desc', $pager = null)
    {
        $customerIdList = $this->loadModel('customer')->getCustomersSawByMe();
        if(empty($customerIdList)) return array();
        
        /* process search condition. */
        if($this->session->contractQuery == false) $this->session->set('contractQuery', ' 1 = 1');
        $contractQuery = $this->loadModel('search', 'sys')->replaceDynamic($this->session->contractQuery);

        if(strpos($orderBy, 'id') === false) $orderBy .= ', id_desc';

        return $this->dao->select('*')->from(TABLE_CONTRACT)
            ->where('deleted')->eq(0)
            ->beginIF($owner == 'my' and strpos('returnedBy,deliveredBy', $mode) === false)
            ->andWhere('createdBy', true)->eq($this->app->user->account)
            ->orWhere('editedBy')->eq($this->app->user->account)
            ->orWhere('signedBy')->eq($this->app->user->account)
            ->orWhere('returnedBy')->eq($this->app->user->account)
            ->orWhere('deliveredBy')->eq($this->app->user->account)
            ->orWhere('finishedBy')->eq($this->app->user->account)
            ->orWhere('canceledBy')->eq($this->app->user->account)
            ->orWhere('contactedBy')->eq($this->app->user->account)
            ->orWhere('handlers')->like("%{$this->app->user->account}%")
            ->markRight(1)
            ->fi()
            ->beginIF($customer)->andWhere('customer')->eq($customer)->fi()
            ->andWhere('customer')->in($customerIdList)
            ->beginIF($mode == 'unfinished')->andWhere('`status`')->eq('normal')->fi()
            ->beginIF($mode == 'unreceived')->andWhere('`return`')->ne('done')->andWhere('`status`')->ne('canceled')->fi()
            ->beginIF($mode == 'undeliveried')->andWhere('`delivery`')->ne('done')->andWhere('`status`')->ne('canceled')->fi()
            ->beginIF($mode == 'canceled')->andWhere('`status`')->eq('canceled')->fi()
            ->beginIF($mode == 'finished')->andWhere('`status`')->eq('closed')->fi()
            ->beginIF($mode == 'expired')->andWhere('`end`')->lt(date(DT_DATE1))->andWhere('`status`')->ne('canceled')->fi()
            ->beginIF($mode == 'returnedBy')->andWhere('returnedBy')->eq($this->app->user->account)->fi()
            ->beginIF($mode == 'deliveredBy')->andWhere('deliveredBy')->eq($this->app->user->account)->fi()
            ->beginIF($mode == 'expire')
            ->andWhere('`end`')->ge(date(DT_DATE1))
            ->andWhere('`end`')->lt(date(DT_DATE1, strtotime('+1 month')))
            ->andWhere('`status`')->ne('canceled')
            ->fi()
            ->beginIF($mode == 'bysearch')->andWhere($contractQuery)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get contract pairs.
     * 
     * @param  int    $customerID
     * @access public
     * @return array
     */
    public function getPairs($customerID = 0)
    {
        return $this->dao->select('id, name')->from(TABLE_CONTRACT)
            ->where(1)
            ->beginIF($customerID)->andWhere('customer')->eq($customerID)->fi()
            ->andWhere('deleted')->eq(0)
            ->fetchPairs('id', 'name');
    }

    /**
     * Get my contract id list.
     * 
     * @param  string $type        view|edit
     * @param  array  $contractIdList 
     * @access public
     * @return array
     */
    public function getContractsSawByMe($type = 'view', $contractIdList = array())
    {
        $customerIdList = $this->loadModel('customer')->getCustomersSawByMe($type);
        $contractList = $this->dao->select('*')->from(TABLE_CONTRACT)
            ->where('deleted')->eq(0)
            ->beginIF(!empty($contractIdList))->andWhere('id')->in($contractIdList)->fi()
            ->beginIF(!isset($this->app->user->rights['crm']['manageall']) and ($this->app->user->admin != 'super'))
            ->andWhere('customer')->in($customerIdList)
            ->fi()
            ->fetchAll('id');

        return array_keys($contractList);
    }

    /**
     * Get return by ID.
     * 
     * @param  int    $returnID 
     * @access public
     * @return object
     */
    public function getReturnByID($returnID = 0)
    {
        return $this->dao->select('*')->from(TABLE_PLAN)->where('id')->eq($returnID)->fetch();
    }

    /**
     * Get returnList of its contract.
     * 
     * @param  int    $contractID 
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getReturnList($contractID = 0, $orderBy = 'id_desc')
    {
        return $this->dao->select('*')->from(TABLE_PLAN)->where('contract')->eq($contractID)->orderBy($orderBy)->fetchAll();
    }

    /**
     * Get delivery by ID.
     * 
     * @param  int    $deliveryID 
     * @access public
     * @return object
     */
    public function getDeliveryByID($deliveryID = 0)
    {
        return $this->dao->select('*')->from(TABLE_DELIVERY)->where('id')->eq($deliveryID)->fetch();
    }

    /**
     * Get deliveryList of its contract.
     * 
     * @param  int    $contractID 
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getDeliveryList($contractID = 0, $orderBy = 'id_desc')
    {
        return $this->dao->select('*')->from(TABLE_DELIVERY)->where('contract')->eq($contractID)->orderBy($orderBy)->fetchAll();
    }

    /**
     * Create contract.
     * 
     * @access public
     * @return int|bool
     */
    public function create()
    {
        $now = helper::now();
        $contract = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', $now)
            ->add('status', 'normal')
            ->add('delivery', 'wait')
            ->add('return', 'wait')
            ->setDefault('order', array())
            ->setDefault('real', array())
            ->setDefault('begin', '0000-00-00')
            ->setDefault('end', '0000-00-00')
            ->setDefault('signedDate', substr($now, 0, 10))
            ->join('handlers', ',')
            ->stripTags('items', $this->config->allowedTags)
            ->get();

        $contract = $this->loadModel('file', 'sys')->processEditor($contract, $this->config->contract->editor->create['id']);
        $this->dao->insert(TABLE_CONTRACT)->data($contract, 'order,uid,files,labels,real')
            ->autoCheck()
            ->batchCheck($this->config->contract->require->create, 'notempty')
            ->checkIF($contract->end != '0000-00-00', 'end', 'ge', $contract->begin)
            ->exec();

        $contractID = $this->dao->lastInsertID();

        if(!dao::isError())
        {
            foreach($contract->order as $key => $orderID)
            {
                if($orderID)
                {
                    $data = new stdclass();
                    $data->contract = $contractID;
                    $data->order    = $orderID;
                    $this->dao->insert(TABLE_CONTRACTORDER)->data($data)->exec();

                    $order = new stdclass();
                    $order->status     = 'signed';
                    $order->real       = $contract->real[$key];
                    $order->signedBy   = $contract->signedBy;
                    $order->signedDate = $contract->signedDate;
                    $this->dao->update(TABLE_ORDER)->data($order)->where('id')->eq($orderID)->exec();

                    if(dao::isError()) return false;
                    $this->loadModel('action', 'sys')->create('order', $orderID, 'Signed', '', $contract->real[$key]);
                }
            }

            /* Update customer info. */
            $customer = new stdclass();
            $customer->status = 'signed';
            $customer->editedDate = helper::now();
            $this->dao->update(TABLE_CUSTOMER)->data($customer)->where('id')->eq($contract->customer)->exec();

            $this->loadModel('file', 'sys')->saveUpload('contract', $contractID);

            return $contractID;
        }

        return false;
    }

    /**
     * Update contract.
     * 
     * @param  int    $contractID 
     * @access public
     * @return bool
     */
    public function update($contractID)
    {
        $now      = helper::now();
        $contract = $this->getByID($contractID);
        $data     = fixer::input('post')
            ->join('handlers', ',')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', $now)
            ->setDefault('order', array())
            ->setDefault('real', array())
            ->setDefault('customer', $contract->customer)
            ->setDefault('signedDate', '0000-00-00')
            ->setDefault('finishedDate', '0000-00-00')
            ->setDefault('canceledDate', '0000-00-00')
            ->setDefault('deliveredDate', '0000-00-00')
            ->setDefault('returnedDate', '0000-00-00')
            ->setDefault('begin', '0000-00-00')
            ->setDefault('end', '0000-00-00')
            ->setIF($this->post->status == 'normal', 'canceledBy', '')
            ->setIF($this->post->status == 'normal', 'canceledDate', '0000-00-00')
            ->setIF($this->post->status == 'normal', 'finishedBy', '')
            ->setIF($this->post->status == 'normal', 'finishedDate', '0000-00-00')
            ->setIF($this->post->status == 'cancel' and $this->post->canceledBy == '', 'canceledBy', $this->app->user->account)
            ->setIF($this->post->status == 'cancel' and $this->post->canceledDate == '0000-00-00', 'canceledDate', $now)
            ->setIF($this->post->status == 'finished' and $this->post->finishedBy == '', 'finishedBy', $this->app->user->account)
            ->setIF($this->post->status == 'finished' and $this->post->finishedDate == '0000-00-00', 'finishedDate', $now)
            ->remove('files,labels')
            ->stripTags('items', $this->config->allowedTags)
            ->get();

        $data = $this->loadModel('file', 'sys')->processEditor($data, $this->config->contract->editor->edit['id']);
        $this->dao->update(TABLE_CONTRACT)->data($data, 'uid,order,real')
            ->where('id')->eq($contractID)
            ->autoCheck()
            ->batchCheck($this->config->contract->require->edit, 'notempty')
            ->checkIF($contract->end != '0000-00-00', 'end', 'ge', $contract->begin)
            ->exec();
        
        if(!dao::isError())
        {
            if($data->order)
            {
                $oldOrders = $this->loadModel('order', 'crm')->getByIdList($data->order);
                foreach($data->order as $key => $orderID)
                {
                    if(!$orderID) continue;
                    $real[$key] = $oldOrders[$orderID]->real;
                }

                if($contract->order != $data->order || $real != $data->real)
                {
                    $this->dao->delete()->from(TABLE_CONTRACTORDER)->where('contract')->eq($contractID)->exec();
                    foreach($data->order as $key => $orderID)
                    {
                        $oldOrder = $this->loadModel('order', 'crm')->getByID($orderID);

                        $contractOrder = new stdclass();
                        $contractOrder->contract = $contractID;
                        $contractOrder->order    = $orderID;
                        $this->dao->insert(TABLE_CONTRACTORDER)->data($contractOrder)->exec();

                        $order = new stdclass();
                        $order->real       = $data->real[$key];
                        $order->signedBy   = $data->signedBy;
                        $order->signedDate = $data->signedDate;
                        $order->status     = 'signed';

                        $this->dao->update(TABLE_ORDER)->data($order)->where('id')->eq($orderID)->exec();

                        if(dao::isError()) return false;

                        $changes  = commonModel::createChanges($oldOrder, $order);
                        $actionID = $this->loadModel('action', 'sys')->create('order', $orderID, 'Edited');
                        $this->action->logHistory($actionID, $changes);
                    }
                }
            }

            if($contract->status == 'canceled' and $data->status == 'normal')
            {
                foreach($data->order as $key => $orderID)
                {
                    $order = new stdclass();
                    $order->status     = 'signed';
                    $order->real       = $data->real[$key];
                    $order->signedBy   = $data->signedBy;
                    $order->signedDate = $data->signedDate;

                    $this->dao->update(TABLE_ORDER)->data($order)->where('id')->eq($orderID)->exec();
                    if(dao::isError()) return false;
                }
            }

            if($contract->status == 'normal' and $data->status == 'canceled')
            {
                foreach($data->order as $orderID)
                {
                    $order = new stdclass();
                    $order->status     = 'normal';
                    $order->real       = 0;
                    $order->signedBy   = '';
                    $order->signedDate = '0000-00-00';

                    $this->dao->update(TABLE_ORDER)->data($order)->where('id')->eq($orderID)->exec();
                    if(dao::isError()) return false;
                }
            }
            
            return commonModel::createChanges($contract, $data);
        }

        return false;
    }

    /**
     * The delivery of the contract.
     * 
     * @param  int    $contractID 
     * @access public
     * @return bool
     */
    public function delivery($contractID)
    {
        $now = helper::now();
        $data = fixer::input('post')
            ->add('contract', $contractID)
            ->setDefault('deliveredBy', $this->app->user->account)
            ->setDefault('deliveredDate', $now)
            ->stripTags('comment', $this->config->allowedTags)
            ->get();

        $data = $this->loadModel('file', 'sys')->processEditor($data, $this->config->contract->editor->delivery['id']);
        $this->dao->insert(TABLE_DELIVERY)->data($data, $skip = 'uid, handlers, finish')->autoCheck()->exec();

        if(!dao::isError())
        {
            $contract = fixer::input('post')
                ->add('delivery', 'doing')
                ->add('editedBy', $this->app->user->account)
                ->add('editedDate', $now)
                ->setDefault('deliveredBy', $this->app->user->account)
                ->setDefault('deliveredDate', $now)
                ->setIF($this->post->finish, 'delivery', 'done')
                ->join('handlers', ',')
                ->remove('finish')
                ->get();

            $this->dao->update(TABLE_CONTRACT)->data($contract, $skip = 'uid, comment')
                ->autoCheck()
                ->where('id')->eq($contractID)
                ->exec();

            return !dao::isError();
        }

        return false;
    }

    /**
     * Edit delivery of the contract.
     * 
     * @param  object $delivery 
     * @param  object $contract 
     * @access public
     * @return bool
     */
    public function editDelivery($delivery, $contract)
    {
        $now = helper::now();
        $data = fixer::input('post')
            ->add('contract', $contract->id)
            ->setDefault('deliveredBy', $this->app->user->account)
            ->setDefault('deliveredDate', $now)
            ->stripTags('comment', $this->config->allowedTags)
            ->get();

        $data = $this->loadModel('file', 'sys')->processEditor($data, $this->config->contract->editor->editdelivery['id']);
        $this->dao->update(TABLE_DELIVERY)->data($data, $skip = 'uid, handlers, finish')->where('id')->eq($delivery->id)->autoCheck()->exec();

        if(!dao::isError())
        {
            $changes = commonModel::createChanges($delivery, $data);
            if($changes)
            {
                $actionID = $this->loadModel('action', 'sys')->create('contract', $contract->id, 'editDelivered');
                $this->action->logHistory($actionID, $changes);
            }

            $deliveryList = $this->getDeliveryList($delivery->contract, 'deliveredDate_desc');

            $contractData = new stdclass();
            $contractData->delivery      = 'doing';
            $contractData->editedBy      = $this->app->user->account;
            $contractData->editedDate    = $now;
            $contractData->handlers      = implode(',', $this->post->handlers);
            $contractData->deliveredBy   = current($deliveryList)->deliveredBy;
            $contractData->deliveredDate = current($deliveryList)->deliveredDate;

            if($this->post->finish) $contractData->delivery = 'done';

            $this->dao->update(TABLE_CONTRACT)->data($contractData, $skip = 'uid, comment')->where('id')->eq($contract->id)->exec();

            return !dao::isError();
        }
        return false;
    }

    /**
     * Delete return.
     * 
     * @param  int   $returnID
     * @access public
     * @return bool
     */
    public function deleteDelivery($deliveryID)
    {
        $delivery = $this->getDeliveryByID($deliveryID);

        $this->dao->delete()->from(TABLE_DELIVERY)->where('id')->eq($deliveryID)->exec();

        $deliveryList = $this->getDeliveryList($delivery->contract, 'deliveredDate_desc');
        $contract = new stdclass();
        if(empty($deliveryList))
        {
            $contract->delivery      = 'wait';
            $contract->deliveredBy   = '';
            $contract->deliveredDate = '0000-00-00';
        }
        else
        {
            $contract->delivery       = 'doing';
            $contract->deliveredBy   = current($deliveryList)->deliveredBy;
            $contract->deliveredDate = current($deliveryList)->deliveredDate;
        }

        $this->dao->update(TABLE_CONTRACT)->data($contract)->where('id')->eq($delivery->contract)->autoCheck()->exec();

        return !dao::isError();
    }

    /**
     * Receive payments of the contract.
     * 
     * @param  int    $contractID 
     * @access public
     * @return bool
     */
    public function receive($contractID)
    {
        $contract = $this->getByID($contractID);

        $now = helper::now();
        $data = fixer::input('post')
            ->add('contract', $contractID)
            ->setDefault('returnedBy', $this->app->user->account)
            ->setDefault('returnedDate', $now)
            ->remove('finish,handlers,createTrade,depositor,category,dept,product,continue')
            ->get();

        if(!$this->post->continue and $this->post->createTrade)
        {
            $existTrades = $this->dao->select('*')->from(TABLE_TRADE)
                ->where('money')->eq($data->amount)
                ->andWhere('date')->eq(substr($data->returnedDate, 0, 10))
                ->andWhere('contract')->eq($contractID)
                ->fetchAll();

            if(!empty($existTrades)) return array('result' => 'fail', 'error' => $this->lang->trade->unique);
        }

        $this->dao->insert(TABLE_PLAN)
            ->data($data, $skip = 'uid, comment')
            ->autoCheck()
            ->batchCheck($this->config->contract->require->receive, 'notempty')
            ->exec();

        if(!dao::isError())
        {
            $contractData = new stdclass();
            $contractData->return       = 'doing';
            $contractData->editedBy     = $this->app->user->account;
            $contractData->editedDate   = $now;
            $contractData->handlers     = implode(',', $this->post->handlers);
            $contractData->returnedBy   = $this->post->returnedBy ? $this->post->returnedBy : $this->app->user->account;
            $contractData->returnedDate = $this->post->returnedDate ? $this->post->returnedDate : $now;
            if($this->post->finish) $contractData->return = 'done';

            $this->dao->update(TABLE_CONTRACT)->data($contractData, $skip = 'uid, comment')->where('id')->eq($contractID)->exec();

            if(!dao::isError() and $this->post->finish) $this->dao->update(TABLE_CUSTOMER)->set('status')->eq('payed')->where('id')->eq($contract->customer)->exec();

            if($this->post->createTrade)
            {
                $trade = fixer::input('post')
                    ->add('money', $this->post->amount)
                    ->add('type', 'in')
                    ->add('date', substr($contractData->returnedDate, 0, 10))
                    ->add('createdBy', $this->app->user->account)
                    ->add('createdDate', $now)
                    ->add('editedBy', $this->app->user->account)
                    ->add('editedDate', $now)
                    ->join('handlers', ',')
                    ->add('trader', $contract->customer)
                    ->add('contract', $contractID)
                    ->add('desc', strip_tags($this->post->comment))
                    ->remove('finish,amount,returnedBy,returnedDate,createTrade,continue')
                    ->get();

                $depositor = $this->loadModel('depositor', 'cash')->getByID($trade->depositor);
                $trade->currency = $depositor->currency;
                
                $this->dao->insert(TABLE_TRADE)->data($trade, $skip = 'uid,comment')->autoCheck()->exec();
            }
            
            if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());
            return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('view', "contractID=$contractID"));
        }

        return array('result' => 'fail', 'message' => dao::getError());
    }

    /**
     * Edit return.
     * 
     * @param  object    $return 
     * @access public
     * @return bool
     */
    public function editReturn($return, $contract)
    {
        $now = helper::now();
        $data = fixer::input('post')
            ->add('contract', $contract->id)
            ->setDefault('returnedBy', $this->app->user->account)
            ->setDefault('returnedDate', $now)
            ->remove('finish,handlers')
            ->get();

        $this->dao->update(TABLE_PLAN)
            ->data($data, $skip = 'uid, comment')
            ->where('id')->eq($return->id)
            ->autoCheck()
            ->batchCheck($this->config->contract->require->receive, 'notempty')
            ->exec();

        if(!dao::isError())
        {
            $changes = commonModel::createChanges($return, $data);
            if($changes or $this->post->comment)
            {
                $actionID = $this->loadModel('action', 'sys')->create('contract', $contract->id, 'editReturned', $this->post->comment);
                if($changes) $this->action->logHistory($actionID, $changes);
            }

            $returnList = $this->getReturnList($return->contract, 'returnedDate_desc');

            $contractData = new stdclass();
            $contractData->return       = 'doing';
            $contractData->editedBy     = $this->app->user->account;
            $contractData->editedDate   = $now;
            $contractData->handlers     = implode(',', $this->post->handlers);
            $contractData->returnedBy   = current($returnList)->returnedBy;
            $contractData->returnedDate = current($returnList)->returnedDate;

            if($this->post->finish) $contractData->return = 'done';

            $this->dao->update(TABLE_CONTRACT)->data($contractData, $skip = 'uid, comment')->where('id')->eq($contract->id)->exec();

            if(!dao::isError() and $this->post->finish) $this->dao->update(TABLE_CUSTOMER)->set('status')->eq('payed')->where('id')->eq($contract->customer)->exec();

            return !dao::isError();
        }

        return false;
    }

    /**
     * Delete return.
     * 
     * @param  int   $returnID
     * @access public
     * @return bool
     */
    public function deleteReturn($returnID)
    {
        $return = $this->getReturnByID($returnID);

        $this->dao->delete()->from(TABLE_PLAN)->where('id')->eq($returnID)->exec();

        $returnList = $this->getReturnList($return->contract, 'returnedDate_desc');
        $contract = new stdclass();
        if(empty($returnList))
        {
            $contract->return       = 'wait';
            $contract->returnedBy   = '';
            $contract->returnedDate = '0000-00-00';
        }
        else
        {
            $contract->return       = 'doing';
            $contract->returnedBy   = current($returnList)->returnedBy;
            $contract->returnedDate = current($returnList)->returnedDate;
        }

        $this->dao->update(TABLE_CONTRACT)->data($contract)->where('id')->eq($return->contract)->autoCheck()->exec();

        return !dao::isError();
    }

    /**
     * Cancel contract.
     * 
     * @param  int    $contractID 
     * @access public
     * @return bool
     */
    public function cancel($contractID)
    {
        $contract = new stdclass();
        $contract->status       = 'canceled';
        $contract->canceledBy   = $this->app->user->account;
        $contract->canceledDate = helper::now();
        $contract->editedBy     = $this->app->user->account;
        $contract->editedDate   = helper::now();

        $this->dao->update(TABLE_CONTRACT)->data($contract, $skip = 'uid, comment')
            ->autoCheck()
            ->where('id')->eq($contractID)
            ->exec();

        if(!dao::isError()) 
        {
            $contract = $this->getByID($contractID);
            if($contract->order)
            {
                foreach($contract->order as $orderID)
                {
                    $order = new stdclass(); 
                    $order->status     = 'normal';
                    $order->signedDate = '0000-00-00';
                    $order->real       = 0;
                    $order->signedBy   = '';

                    $this->dao->update(TABLE_ORDER)->data($order)->autoCheck()->where('id')->eq($orderID)->exec();
                }
                return !dao::isError();
            }
            return true;
        }
        return false;
    }

    /**
     * Finish contract.
     * 
     * @param  int    $contractID 
     * @access public
     * @return bool
     */
    public function finish($contractID)
    {
        $contract = new stdclass();
        $contract->status       = 'closed';
        $contract->finishedBy   = $this->app->user->account;
        $contract->finishedDate = helper::now();
        $contract->editedBy     = $this->app->user->account;
        $contract->editedDate   = helper::now();

        $this->dao->update(TABLE_CONTRACT)->data($contract, $skip = 'uid, comment')
            ->autoCheck()
            ->where('id')->eq($contractID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Build operate menu.
     * 
     * @param  object $contract 
     * @param  string $class 
     * @param  string $type 
     * @access public
     * @return string
     */
    public function buildOperateMenu($contract, $class = '', $type = 'browse')
    {
        $menu  = '';

        $canCreateRecord = commonModel::hasPriv('action', 'createRecord');
        $canReceive      = commonModel::hasPriv('contract', 'receive');
        $canDelivery     = commonModel::hasPriv('contract', 'delivery');
        $canFinish       = commonModel::hasPriv('contract', 'finish');
        $canEdit         = commonModel::hasPriv('contract', 'edit');
        $canCancel       = commonModel::hasPriv('contract', 'cancel');
        $canDelete       = commonModel::hasPriv('contract', 'delete');

        if($type == 'view') $menu .= "<div class='btn-group'>";

        $history = $type == 'view' ? '&history=' : '';
        if($canCreateRecord) $menu .= commonModel::printLink('action', 'createRecord', "objectType=contract&objectID={$contract->id}&customer={$contract->customer}$history", $this->lang->contract->record, "class='$class' data-toggle='modal' data-width='860'", false);

        if($contract->return != 'done' and $contract->status == 'normal' and $canReceive)
        {
            $menu .= commonModel::printLink('crm.contract', 'receive',  "contractID=$contract->id", $this->lang->contract->return, "data-toggle='modal' class='$class'", false);
        }
        else
        {
            $menu .= "<a href='###' disabled='disabled' class='disabled  $class'>" . $this->lang->contract->return . '</a> ';
        }

        if($contract->delivery != 'done' and $contract->status == 'normal' and $canDelivery)
        {
            $menu .= commonModel::printLink('crm.contract', 'delivery', "contractID=$contract->id", $this->lang->contract->delivery, "data-toggle='modal' class='$class'", false);
        }
        else
        {
            $menu .= "<a href='###' disabled='disabled' class='disabled $class'>" . $this->lang->contract->delivery . '</a> ';
        }

        if($type == 'view') $menu .= "</div><div class='btn-group'>";

        if($contract->status == 'normal' and $contract->return == 'done' and $contract->delivery == 'done' and $canFinish)
        {
            $menu .= commonModel::printLink('crm.contract', 'finish', "contractID=$contract->id", $this->lang->finish, "data-toggle='modal' class='$class'", false);
        }
        else
        {
            $menu .= "<a href='###' disabled='disabled' class='disabled $class'>" . $this->lang->finish . '</a> ';
        }

        if($canEdit) $menu .= commonModel::printLink('crm.contract', 'edit', "contractID=$contract->id", $this->lang->edit, "class='$class'", false);

        if($type == 'view')
        {
            $menu .= "</div><div class='btn-group'>";
            if($contract->status == 'normal' and !($contract->return == 'done' and $contract->delivery == 'done') and $canCancel)
            {
                $menu .= commonModel::printLink('crm.contract', 'cancel', "contractID=$contract->id", $this->lang->cancel, "data-toggle='modal' class='$class'", false);
            }
            else
            {
                $menu .= "<a href='###' disabled='disabled' class='disabled $class'>" . $this->lang->cancel . '</a> ';
            }

            if($contract->status == 'canceled' or ($contract->status == 'normal' and !($contract->return == 'done' and $contract->delivery == 'done')) and $canDelete)
            {
                $menu .= commonModel::printLink('crm.contract', 'delete', "contractID=$contract->id", $this->lang->delete, "class='deleter $class'", false);
            }
            else
            {
                $menu .= "<a href='###' disabled='disabled' class='disabled $class'>" . $this->lang->delete . '</a> ';
            }
            if(commonModel::hasPriv('crm.crontract', 'edit')) $menu .= html::a('#commentBox', $this->lang->comment, "class='btn btn-default' onclick=setComment()");
        }

        if($type == 'browse')
        {
            $menu .="<div class='dropdown'><a data-toggle='dropdown' href='javascript:;'>" . $this->lang->more . "<span class='caret'></span> </a><ul class='dropdown-menu pull-right'>";

            if($contract->status == 'normal' and !($contract->return == 'done' and $contract->delivery == 'done') and $canCancel)
            {
                $menu .= commonModel::printLink('crm.contract', 'cancel', "contractID=$contract->id", $this->lang->cancel, "data-toggle='modal' class='$class'", false, '', 'li');
            }
            else
            {
                $menu .= "<li><a href='###' disabled='disabled' class='disabled $class'>" . $this->lang->cancel . '</a></li> ';
            }

            if($contract->status == 'canceled' or ($contract->status == 'normal' and !($contract->return == 'done' and $contract->delivery == 'done')) and $canDelete)
            {
                $menu .= commonModel::printLink('crm.contract', 'delete', "contractID=$contract->id", $this->lang->delete, "class='reloadDeleter $class'", false, '', 'li');
            }
            else
            {
                $menu .= "<li><a href='###' disabled='disabled' class='disabled $class'>" . $this->lang->delete . '</a></li> ';
            }
            $menu .= '</ul>';
        }

        $menu .= "</div>";

        return $menu;
    }

    /**
     * Count amount.
     * 
     * @param  array  $contracts 
     * @access public
     * @return array
     */
    public function countAmount($contracts)
    {
        $totalAmount  = array();
        $currencyList = $this->loadModel('common', 'sys')->getCurrencyList();
        $currencySign = $this->common->getCurrencySign();
        $totalReturn  = $this->dao->select('*')->from(TABLE_PLAN)->fetchGroup('contract');

        foreach($contracts as $contract)
        {
            if($contract->status == 'canceled') continue;
            foreach($currencyList as $key => $currency)
            {
                if($contract->currency == $key)
                {
                    if(!isset($totalAmount['contract'][$key]))     $totalAmount['contract'][$key] = 0;
                    if(!isset($totalAmount['return'][$key]))       $totalAmount['return'][$key] = 0;
                    if(!isset($totalAmount['currentMonth'][$key])) $totalAmount['currentMonth'][$key] = 0;

                    $totalAmount['contract'][$key] += $contract->amount;
                    
                    if(isset($totalReturn[$contract->id])) 
                    {
                        foreach($totalReturn[$contract->id] as $return) 
                        {
                            $totalAmount['return'][$key] += $return->amount;
                            if(date('Y-m', strtotime($return->returnedDate)) == date('Y-m')) $totalAmount['currentMonth'][$key] += $return->amount;
                        }
                    }
                }
            }
        }

        foreach($totalAmount as $type => $currencyAmount) foreach($currencyAmount as $currency => $amount) $totalAmount[$type][$currency] = "<span title='$amount'>" . $currencySign[$currency] . commonModel::tidyMoney($amount) . "</span>";

        return $totalAmount;
    }
}
