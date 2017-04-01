<?php
/**
 * The model file of customer module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     customer
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class customerModel extends model
{
    /**
     * Get customer by id.
     * 
     * @param  int    $id 
     * @access public
     * @return int|bool
     */
    public function getByID($id = 0)
    {
        $customerIdList = $this->getCustomersSawByMe();
        if(empty($customerIdList)) return false;
        if(!in_array($id, $customerIdList)) return false;

        return $this->dao->select('*')->from(TABLE_CUSTOMER)->where('id')->eq($id)->limit(1)->fetch();
    }

    /**
     * Get my customer id list or allowed view customer.
     * 
     * @param  string $type           view|edit
     * @param  array  $customerIdList 
     * @access public
     * @return array
     */
    public function getCustomersSawByMe($type = 'view', $customerIdList = array())
    {
        $accountsSawByMe = $this->loadModel('sales', 'crm')->getAccountsSawByMe($this->app->user->account, $type);

        $customerList = $this->dao->select('id')->from(TABLE_CUSTOMER)
            ->where('deleted')->eq(0)
            ->beginIF(!isset($this->app->user->rights['crm']['manageall']) and ($this->app->user->admin != 'super'))
            ->andWhere('assignedTo')->in($accountsSawByMe)
            ->orWhere('public')->eq('1')
            ->fi()
            ->fetchPairs();

        if($customerIdList)
        {
            foreach($customerList as $id => $customer)
            {
                if(!in_array($id, $customerIdList)) unset($customerList[$id]);
            }
        }

        /* Get customers not assigned to these accounts but theirs orders assigned to. */
        $accountsSawByMe = trim($accountsSawByMe, ',');
        $customers = $this->dao->select('customer')->from(TABLE_ORDER)->where('assignedTo')->in($accountsSawByMe)->fetchPairs();
        foreach($customers as $customer) $customerList[$customer] = $customer;

        return $customerList;
    }

    /** 
     * Get customer list.
     * 
     * @param  string  $mode 
     * @param  string  $param 
     * @param  string  $relation  client|provider
     * @param  string  $orderBy 
     * @param  object  $pager 
     * @access public
     * @return array
     */
    public function getList($mode = 'all', $param = '', $relation = 'client', $orderBy = 'id_desc', $pager = null)
    {
        $customerIdList = $this->getCustomersSawByMe();
        if(empty($customerIdList)) return array();
        $this->session->set('customerIdList', $customerIdList);

        $this->app->loadClass('date', $static = true);
        $thisMonth = date::getThisMonth();
        $thisWeek  = date::getThisWeek();

        if($this->session->customerQuery == false) $this->session->set('customerQuery', ' 1 = 1');
        $customerQuery = $this->loadModel('search', 'sys')->replaceDynamic($this->session->customerQuery);

        if(strpos($orderBy, 'id') === false) $orderBy .= ', id_desc';

        return $this->dao->select('*')->from(TABLE_CUSTOMER)
            ->where('deleted')->eq(0)
            ->beginIF($relation == 'client')->andWhere('relation')->ne('provider')
            ->beginIF($relation == 'provider')->andWhere('relation')->ne('client')
            ->beginIF($mode == 'field')->andWhere('mode')->eq($param)->fi()
            ->beginIF($mode == 'past')->andWhere('nextDate')->lt(helper::today())->fi()
            ->beginIF($mode == 'today')->andWhere('nextDate')->eq(helper::today())->fi()
            ->beginIF($mode == 'tomorrow')->andWhere('nextDate')->eq(formattime(date::tomorrow(), DT_DATE1))->fi()
            ->beginIF($mode == 'thisweek')->andWhere('nextDate')->between($thisWeek['begin'], $thisWeek['end'])->fi()
            ->beginIF($mode == 'thismonth')->andWhere('nextDate')->between($thisMonth['begin'], $thisMonth['end'])->fi()
            ->beginIF($mode == 'public')->andWhere('public')->eq('1')->fi()
            ->beginIF($mode == 'assignedTo')->andWhere('assignedTo')->eq($this->app->user->account)->fi()
            ->beginIF($mode == 'query')->andWhere($param)->fi()
            ->beginIF($mode == 'bysearch')->andWhere($customerQuery)->fi()
            ->beginIF(strpos('all, bysearch, public, assignedTo, query', $mode) === false)->andWhere('nextDate')->ne('0000-00-00')->fi()
            ->andWhere('id')->in($customerIdList)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /** 
     * Get customer pairs.
     * 
     * @param  string  $relation
     * @param  bool    $emptyOption 
     * @access public
     * @return array
     */
    public function getPairs($relation = '', $emptyOption = true)
    {
        $customerIdList = $this->getCustomersSawByMe();

        if(empty($customerIdList))
        {
           $customers = array();
        }
        else
        {
            $customers = $this->dao->select('id, name')->from(TABLE_CUSTOMER)
                ->where('deleted')->eq(0)
                ->beginIF($relation == 'client')->andWhere('relation')->ne('provider')->fi()
                ->beginIF($relation == 'provider')->andWhere('relation')->ne('client')->fi()
                ->fetchPairs();
            foreach($customers as $id => $name)
            {
                if(!isset($customerIdList[$id])) unset($customers[$id]);
            }
            krsort($customers);
        }

        if($emptyOption) $customers = array('' => '') + $customers;
        return $customers;
    }

    /**
     * Create a customer.
     * 
     * @param  object    $customer 
     * @access public
     * @return int|bool
     */
    public function create($customer = null, $relation = 'client')
    {
        $now = helper::now();
        if(empty($customer))
        {
            $customer = fixer::input('post')
                ->setIF($this->post->name == '' and !$this->post->selectContact, 'name', $this->post->contact)
                ->add('relation', $relation)
                ->setIF($relation == 'provider', 'public', 1)
                ->add('createdBy', $this->app->user->account)
                ->add('assignedTo', $this->app->user->account)
                ->add('createdDate', $now)
                ->remove('address')
                ->get();

            /* check field before insert. */
            $this->dao->insert(TABLE_CUSTOMER)
                ->data($customer)
                ->check('contact', 'length', 30, 0);
        }

        $customer->name = trim($customer->name);
        if(!$this->post->continue)
        {
            $return = $this->checkUnique($customer);
            if($return['result'] == 'fail') return $return;
        }

        $this->loadModel('contact', 'crm');

        if(!empty($customer->contact))
        {
            $contact = new stdclass();
            $contact->realname    = $customer->contact;
            $contact->customer    = '';
            $contact->phone       = $customer->phone;
            $contact->email       = str_replace(array(' ', '，'), ',', $customer->email);
            $contact->qq          = $customer->qq;
            $contact->createdBy   = $this->app->user->account;
            $contact->createdDate = $now;

            $this->dao->insert(TABLE_CONTACT)->data($contact)
                ->autoCheck()
                ->checkIF($contact->phone, 'phone', 'length', 20, 7);

            if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

            if(!$this->post->continue)
            {
                $return = $this->contact->checkContact($contact);
                if($return['result'] == 'fail') return $return;
            }
        }

        if(!isset($customer->contact) or $this->post->selectContact) $this->config->customer->require->create = 'name';
        $this->dao->insert(TABLE_CUSTOMER)
            ->data($customer, $skip = 'uid,contact,email,qq,phone,continue,selectContact,contactID')
            ->autoCheck()
            ->batchCheck($this->config->customer->require->create, 'notempty')
            ->exec();

        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());
        $customerID = $this->dao->lastInsertID();
        $objectType = $relation == 'provider' ? 'provider' : 'customer';
        $this->loadModel('action', 'sys')->create($objectType, $customerID, 'Created');

        if(isset($contact))
        {
            $contact->customer = $customerID;
            $return = $this->contact->create($contact);
            if($return['result'] == 'fail') return $return;
        }

        if($this->post->selectContact)
        {
            $contactID = $this->post->contactID;
            $contact   = $this->contact->getByID($contactID);
            $contacts  = $this->contact->getPairs();

            $resume = new stdclass();
            $resume->customer = $customerID;
            $resume->contact  = $contactID;
            $resumeID = $this->loadModel('resume', 'crm')->create($contactID, $resume);

            if($resumeID)
            {
                $changes[] = array('field' => 'customer', 'old' => $contact->customer, 'new' => $customerID, 'diff' => '');
                $actionID  = $this->action->create('contact', $contactID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }
        }

        if(!empty($customerID) && $this->post->address['location'])
        {
            $address = new stdclass();
            $address->objectType = 'customer';
            $address->objectID   = $customerID;
            $address->title      = $this->post->address['title'];
            $address->area       = $this->post->address['area'];
            $address->location   = $this->post->address['location'];

            $this->dao->insert(TABLE_ADDRESS)->data($address)->autoCheck()->exec();
        }   

        $locate = $relation == 'provider' ? helper::createLink('provider', 'browse') : helper::createLink('customer', 'browse');
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate, 'customerID' => $customerID);
    }

    /**
     * Update a customer.
     * 
     * @param  int    $customerID 
     * @access public
     * @return array
     */
    public function update($customerID)
    {
        $oldCustomer = $this->getByID($customerID);
        $customer    = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->setIF(!$this->post->public and $oldCustomer->relation == 'client', 'public', 0)
            ->stripTags('desc', $this->config->allowedTags)
            ->get();

        $customer->name = trim($customer->name);
        /* Add http:// in head when that has not http:// or https://. */
        if(strpos($customer->site, '://') === false )  $customer->site  = 'http://' . $customer->site;
        if(strpos($customer->weibo, 'http://weibo.com/') === false ) $customer->weibo = 'http://weibo.com/' . $customer->weibo;
        if($customer->site == 'http://') $customer->site = '';
        if($customer->weibo == 'http://weibo.com/') $customer->weibo = '';

        $customer = $this->loadModel('file', 'sys')->processEditor($customer, $this->config->customer->editor->edit['id']);
        $this->dao->update(TABLE_CUSTOMER)
            ->data($customer, $skip = 'uid')
            ->autoCheck()
            ->batchCheck($this->config->customer->require->edit, 'notempty')
            ->where('id')->eq($customerID)
            ->exec();

        return commonModel::createChanges($oldCustomer, $customer);
    }

    /**
     * Update editeddate. 
     * 
     * @param  int    $customerID 
     * @access public
     * @return void
     */
    public function updateEditedDate($customerID)
    {
        $this->dao->update(TABLE_CUSTOMER)
            ->set('editedDate')->eq(helper::now())
            ->where('id')->eq($customerID)
            ->exec();
    }

    /**
     * Assign an customer to a member again.
     * 
     * @param  int    $customerID 
     * @access public
     * @return void
     */
    public function assign($customerID)
    {
        $customer = fixer::input('post')
            ->setDefault('assignedBy', $this->app->user->account)
            ->setDefault('assignedDate', helper::now())
            ->setIF(!$this->post->public, 'public', 0)
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->get();

        $this->dao->update(TABLE_CUSTOMER)
            ->data($customer, $skip = 'uid, comment')
            ->autoCheck()
            ->where('id')->eq($customerID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Link contact.
     * 
     * @param  int    $customerID 
     * @access public
     * @return bool
     */
    public function linkContact($customerID)
    {
        $this->loadModel('action', 'sys');
        $this->loadModel('contact', 'crm');
        if(!$this->post->selectContact)
        {
            $contact = fixer::input('post')
                ->add('customer', $customerID)
                ->add('createdBy', $this->app->user->account)
                ->add('createdDate', helper::now())
                ->remove('contact')
                ->get();

            return $this->contact->create($contact);
        }

        if($this->post->contact)
        {
            $contactID = $this->post->contact;
            $contact   = $this->contact->getByID($contactID);
            $contacts  = $this->contact->getPairs();

            if($contact->customer != $customerID or ($contact->left != '' and strtotime($contact->left) <= strtotime(helper::today())))
            {
                $resume = new stdclass();
                $resume->customer = $customerID;
                $resume->contact  = $contactID;
                $resumeID = $this->loadModel('resume', 'crm')->create($contactID, $resume);

                if($resumeID)
                {
                    $changes[] = array('field' => 'customer', 'old' => $contact->customer, 'new' => $customerID, 'diff' => '');
                    $actionID  = $this->action->create('contact', $contactID, 'Edited');
                    $this->action->logHistory($actionID, $changes);
                }

                $this->loadModel('action', 'sys')->create('customer', $customerID, 'linkContact', '', $this->post->newcontact ? $this->post->realname : $contacts[$this->post->contact]);

                return array('result' => 'success', 'message' => $this->lang->saveSuccess);
            }
        }

        return array('result' => 'fail', 'message' => dao::getError());
    }

    /**
     * Combine sizeList for customer.
     * 
     * @access public
     * @return array
     */
    public function combineSizeList()
    {
        $sizeList = array();
        foreach($this->lang->customer->sizeNameList as $key => $sizeName)
        {
            $sizeList[$key] = $sizeName . '(' . $this->lang->customer->sizeNoteList[$key] . ')';
            if(empty($sizeName)) $sizeList[$key] = '';
        }
        return $sizeList;
    }

    /**
     * Combine levelList for customer.
     * 
     * @access public
     * @return array
     */
    public function combineLevelList()
    {
        $levelList = array();
        foreach($this->lang->customer->levelNameList as $key => $levelName)
        {
            $levelList[$key] = $levelName . '(' . $this->lang->customer->levelNoteList[$key] . ')';
            if(empty($levelName)) $levelList[$key] = '';
        }
        return $levelList;
    }


    /**
     * Check customer is unique.
     * 
     * @param  object  $customer
     * @access public
     * @return array
     */
    public function checkUnique($customer)
    {
        if($customer->name) $data = $this->dao->select('*')->from(TABLE_CUSTOMER)->where('name')->eq($customer->name)->fetch();
        if(!empty($data))
        {
            $error = sprintf($this->lang->error->unique, $this->lang->customer->name, html::a(helper::createLink('customer', 'view', "customerID={$data->id}"), $data->name, "target='_blank'"));
            return array('result' => 'fail', 'error' => $error);
        }
    }

    /**
     * Move customer to public if customer longtime no edited.
     * 
     * @access public
     * @return void
     */
    public function moveCustomerPool()
    {
        $reserveDays = isset($this->config->customer->reserveDays) ? $this->config->customer->reserveDays : 0;
        if($reserveDays == 0) return true;
        
        $reserveTime = date(DT_DATETIME1, strtotime("-{$reserveDays} day"));
        $customers = $this->dao->select('id')->from(TABLE_CUSTOMER)
            ->where('editedDate')
            ->andWhere('editedDate')->lt($reserveTime)
            ->andWhere('status')->in('potential,intension,failed')
            ->andWhere('public')->eq('0')
            ->andWhere('deleted')->eq('0')
            ->fetchAll('id');
        if(empty($customers)) return true;

        $this->dao->update(TABLE_CUSTOMER)
            ->set('public')->eq('1')
            ->set('editedDate')->eq(helper::now())
            ->where('id')->in(array_keys($customers))
            ->exec();

        $changes[] = array('field' => 'public', 'old' => '0', 'new' => '1', 'diff' => '');
        foreach($customers as $key => $customer)
        {
            $actionID = $this->loadModel('action', 'sys')->create('customer', $key, 'Edited');
            $this->action->logHistory($actionID, $changes);
        }
    }

    /**
     * Merge two customers.
     * 
     * @param  int    $customerID 
     * @access public
     * @return void
     */
    public function merge($customerID)
    {
        $this->dao->update(TABLE_CONTRACT)->set('customer')->eq($this->post->customer)->where('customer')->eq($customerID)->exec();
        $this->dao->update(TABLE_ORDER)->set('customer')->eq($this->post->customer)->where('customer')->eq($customerID)->exec();
        $this->dao->update(TABLE_RESUME)->set('customer')->eq($this->post->customer)->where('customer')->eq($customerID)->exec();
        $this->dao->update(TABLE_ADDRESS)->set('objectID')->eq($this->post->customer)->where('objectType')->eq('customer')->andWhere('objectID')->eq($customerID)->exec();
        $this->dao->update(TABLE_ACTION)->set('customer')->eq($this->post->customer)->where('customer')->eq($customerID)->exec();
        $this->dao->update(TABLE_ACTION)->set('objectID')->eq($this->post->customer)->where('objectType')->eq('customer')->andWhere('objectID')->eq($customerID)->andWhere('action')->notin('created,edited')->exec();
        $this->dao->update(TABLE_TASK)->set('customer')->eq($this->post->customer)->where('customer')->eq($customerID)->exec();
        $this->dao->update(TABLE_TRADE)->set('trader')->eq($this->post->customer)->where('trader')->eq($customerID)->exec();

        $tripList = $this->loadModel('trip', 'oa')->getList();
        foreach($tripList as $trip)
        {
            $tripCustomers = explode(',', $trip->customers);
            $tripCustomers = array_flip($tripCustomers);
            if(!isset($tripCustomers[$customerID])) continue;

            unset($tripCustomers[$customerID]);
            $tripCustomers   = array_flip($tripCustomers);
            $tripCustomers[] = $this->post->customer;
            $tripCustomers   = array_unique($tripCustomers);
            $this->dao->update(TABLE_TRIP)->set('customers')->eq(implode(',', $tripCustomers))->where('id')->eq($trip->id)->exec();
        }

        if(!dao::isError())
        {
            $originCustomer = $this->getByID($customerID);
            $targetCustomer = $this->getByID($this->post->customer);

            $newCustomer = new stdclass();
            $newCustomer->type      = $targetCustomer->type ? $targetCustomer->type : $originCustomer->type;
            $newCustomer->size      = $targetCustomer->size ? $targetCustomer->size : $originCustomer->size;
            $newCustomer->industry  = $targetCustomer->industry ? $targetCustomer->industry : $originCustomer->industry;
            $newCustomer->area      = $targetCustomer->area ? $targetCustomer->area : $originCustomer->area;
            $newCustomer->intension = $targetCustomer->intension ? $targetCustomer->intension : $originCustomer->intension;
            $newCustomer->site      = $targetCustomer->site ? $targetCustomer->site : $originCustomer->site;
            $newCustomer->weibo     = $targetCustomer->weibo ? $targetCustomer->weibo : $originCustomer->weibo;
            $newCustomer->weixin    = $targetCustomer->weixin ? $targetCustomer->weixin : $originCustomer->weixin;
            $newCustomer->depositor = $targetCustomer->depositor ? $targetCustomer->depositor : $originCustomer->depositor;
            $newCustomer->desc      = $targetCustomer->desc ? $targetCustomer->desc : $originCustomer->desc;

            $levelList  = array_flip(array_keys($this->lang->customer->levelNameList));
            $statusList = array_flip(array_keys($this->lang->customer->statusList));

            $newCustomer->level  = zget($levelList, $targetCustomer->level) > zget($levelList, $originCustomer->level) ? $targetCustomer->level : $originCustomer->level;
            $newCustomer->status = zget($statusList, $targetCustomer->status) > zget($statusList, $originCustomer->status) ? $targetCustomer->status : $originCustomer->status;

            $this->dao->update(TABLE_CUSTOMER)->data($newCustomer)->where('id')->eq($this->post->customer)->exec();
            $this->dao->update(TABLE_CUSTOMER)->set('deleted')->eq(1)->where('id')->eq($customerID)->exec();
        }
        return !dao::isError();
    }
}
