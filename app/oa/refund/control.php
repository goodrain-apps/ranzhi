<?php
/**
 * The control file of refund of Ranzhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     refund
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class refund extends control
{
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
     * create a refund.
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $refundID = $this->refund->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->loadModel('action')->create('refund', $refundID, 'Created', '');
            /* If the reviewer is as same as the login user, auto review. */
            $refund = $this->refund->getByID($refundID);
            if($refund->status == 'doing' || $refund->status == 'pass')
            {
                $this->loadModel('action')->create('refund', $refundID, 'reviewed');
            }
            $this->sendmail($refundID, $actionID);

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('personal')));
        }

        $this->view->currencyList = $this->loadModel('common', 'sys')->getCurrencyList();
        $this->view->currencySign = $this->loadModel('common', 'sys')->getCurrencySign();
        $this->view->categories   = $this->refund->getCategoryPairs();
        $this->view->users        = $this->loadModel('user')->getPairs('noclosed,nodeleted,noforbidden');
        $this->display();
    }

    /**
     * Edit a refund.
     * 
     * @param  int    $refundID 
     * @access public
     * @return void
     */
    public function edit($refundID)
    {
        $refund = $this->refund->getByID($refundID);
        $this->checkPriv($refund, 'edit');

        if($_POST)
        {
            $changes = $this->refund->update($refundID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $files = $this->loadModel('file')->saveUpload('refund', $refundID);

            if(!empty($changes) or $files)
            {
                $fileAction = '';
                if($files) $fileAction = $this->lang->addFiles . join(',', $files);
                $actionID = $this->loadModel('action')->create('refund', $refundID, 'Edited', $fileAction);
                if($changes) $this->action->logHistory($actionID, $changes);
            }

            /* If the reviewer is as same as the login user, auto review. */
            $refund = $this->refund->getByID($refundID);
            if($refund->status == 'doing' || $refund->status == 'pass')
            {
                $this->loadModel('action')->create('refund', $refundID, 'reviewed');
            }

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('view', "refundID=$refundID&mode=personal")));
        }

        $this->view->currencyList = $this->loadModel('common', 'sys')->getCurrencyList();
        $this->view->currencySign = $this->loadModel('common', 'sys')->getCurrencySign();
        $this->view->categories   = $this->refund->getCategoryPairs();
        $this->view->refund       = $refund;
        $this->view->users        = $this->loadModel('user')->getPairs('noclosed,nodeleted,noforbidden');
        $this->display();
    }

    /**
     * view personal refund.
     * 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function personal($date = '', $type = '', $orderBy = 'status, id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->browse('personal', $date, $type, $orderBy, $recTotal, $recPerPage, $pageID);
    }

    /**
     * view company refund.
     * 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function company($date = '', $type = '', $orderBy = 'status, id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->browse('company', $date, $type, $orderBy, $recTotal, $recPerPage, $pageID);
    }

    /**
     * view todo refund.
     * 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function todo($date = '', $type = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->browse('todo', $date, $type, $orderBy, $recTotal, $recPerPage, $pageID);
    }

    /**
     * browse refund.
     * 
     * @param  string $mode 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function browse($mode = 'personal', $date = '', $type = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $categories = $this->refund->getCategoryPairs();

        /* Build search form. */
        $this->loadModel('search', 'sys');
        $users = $this->loadModel('user')->getPairs('noclosed');
        $this->config->refund->search['actionURL'] = $this->createLink('refund', $mode, "date=&type=bysearch");
        $this->config->refund->search['params']['category']['values']       = array('' => '') + $categories;
        $this->config->refund->search['params']['createdBy']['values']      = $users;
        $this->config->refund->search['params']['firstReviewer']['values']  = $users;
        $this->config->refund->search['params']['secondReviewer']['values'] = $users;
        $this->config->refund->search['params']['refundBy']['values']       = $users;
        $this->search->setSearchParams($this->config->refund->search);

        if($date == '' or (strlen($date) != 6 and strlen($date) != 4)) $date = date("Y");
        $currentYear  = substr($date, 0, 4);
        $currentMonth = strlen($date) == 6 ? substr($date, 4, 2) : '';
        $currentDate  = $currentYear . '-' . $currentMonth;
        $monthList    = $this->refund->getAllMonth();
        $yearList     = array_reverse(array_keys($monthList));

        $deptList    = $this->loadModel('tree')->getPairs('', 'dept');
        $deptList[0] = '/';
        $users       = $this->loadModel('user')->getList();
        $userPairs   = array();
        $userDept    = array();
        foreach($users as $key => $user) 
        {
            $userPairs[$user->account] = $user->realname;
            $userDept[$user->account] = zget($deptList, $user->dept);
        }

        $refunds = array();
        if($mode == 'personal') $refunds = $this->refund->getList($mode, $type, $currentDate, '', '', $this->app->user->account, $orderBy, $pager);
        if($mode == 'company')  $refunds = $this->refund->getList($mode, $type, $currentDate, '', '', '', $orderBy, $pager);
        if($mode == 'todo' and empty($this->config->refund->refundBy)) $refunds = $this->refund->getList($mode, $type, $currentDate, '', 'pass', '', $orderBy, $pager);
        if($mode == 'todo' and !empty($this->config->refund->refundBy) and $this->config->refund->refundBy == $this->app->user->account) $refunds = $this->refund->getList($mode, $type, $currentDate, '', 'pass', '', $orderBy, $pager);

        /* Set return url. */
        $this->session->set('refundList', $this->app->getURI(true));

        $this->view->title        = $this->lang->refund->$mode;
        $this->view->refunds      = $refunds;
        $this->view->orderBy      = $orderBy;
        $this->view->mode         = $mode;
        $this->view->pager        = $pager;
        $this->view->categories   = $categories;
        $this->view->currencySign = $this->loadModel('common', 'sys')->getCurrencySign();
        $this->view->userPairs    = $userPairs;
        $this->view->userDept     = $userDept;
        $this->view->currentYear  = $currentYear;
        $this->view->currentMonth = $currentMonth;
        $this->view->monthList    = $monthList;
        $this->view->yearList     = $yearList;
        $this->view->date         = $date;
        $this->display('refund', 'browse');
    }
    
    /**
     * View a refund.
     * 
     * @param  int    $refundID 
     * @param  string $mode
     * @access public
     * @return void
     */
    public function view($refundID = 0, $mode = '')
    {
        $refund = $this->refund->getByID($refundID);

        $this->view->title        = $this->lang->refund->view;
        $this->view->refund       = $refund;
        $this->view->mode         = $mode;
        $this->view->users        = $this->loadModel('user')->getPairs();
        $this->view->currencySign = $this->loadModel('common', 'sys')->getCurrencySign();
        $this->view->categories   = $this->refund->getCategoryPairs();
        $this->view->preAndNext   = $this->loadModel('common', 'sys')->getPreAndNextObject('refund', $refundID);
        $this->display();
    }

    /**
     * Delete a refund.
     * 
     * @param  int    $refundID 
     * @access public
     * @return void
     */
    public function delete($refundID)
    {
        $refund = $this->refund->getByID($refundID);
        $this->checkPriv($refund, 'delete', 'json');

        $this->refund->delete($refundID);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success'));
    }

    /**
     * browse review list.
     * 
     * @access public
     * @return void
     */
    public function browseReview($date = '', $status = 'unreviewed', $type = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $categories = $this->refund->getCategoryPairs();

        /* Build search form. */
        $this->loadModel('search', 'sys');
        $users = $this->loadModel('user')->getPairs('noclosed');
        $this->config->refund->search['actionURL'] = $this->createLink('refund', 'browseReview', "date=&status=$status&type=bysearch");
        $this->config->refund->search['params']['category']['values']       = array('' => '') + $categories;
        $this->config->refund->search['params']['createdBy']['values']      = $users;
        $this->config->refund->search['params']['firstReviewer']['values']  = $users;
        $this->config->refund->search['params']['secondReviewer']['values'] = $users;
        $this->config->refund->search['params']['refundBy']['values']       = $users;
        $this->search->setSearchParams($this->config->refund->search);

        if($date == '' or (strlen($date) != 6 and strlen($date) != 4)) $date = date("Y");
        $currentYear  = substr($date, 0, 4);
        $currentMonth = strlen($date) == 6 ? substr($date, 4, 2) : '';
        $currentDate  = $currentYear . '-' . $currentMonth;
        $monthList    = $this->refund->getAllMonth();
        $yearList     = array_reverse(array_keys($monthList));

        $account  = $this->app->user->account;
        $refunds  = array();
        $newUsers = array();
        $users    = $this->loadModel('user')->getList();
        foreach($users as $key => $user) $newUsers[$user->account] = $user;

        /* Get dept info. */
        $allDeptList = $this->loadModel('tree')->getPairs('', 'dept');
        $allDeptList['0'] = '/';
        $managedDeptList = array();
        $tmpDept = $this->loadModel('tree')->getDeptManagedByMe($account);
        foreach($tmpDept as $d) $managedDeptList[$d->id] = $d->name;

        /* Get refund list for secondReviewer. */
        $secondRefunds = array();
        if(!empty($this->config->refund->secondReviewer) and $this->config->refund->secondReviewer == $account)
        {
            if($status == 'unreviewed') $secondRefunds = $this->refund->getList($mode = 'browseReview', $type, $currentDate, $deptIDList = '', 'doing', '', $orderBy, $pager);
            if($status == 'reviewed')   $secondRefunds = $this->refund->getList($mode = 'browseReview', $type, $currentDate, $deptIDList = '', 'pass,finish', '', $orderBy, $pager);
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

        if(!empty($deptList))
        {
            if($status == 'unreviewed') $firstRefunds = $this->refund->getList($mode = 'browseReview', $type, $currentDate, $deptIDList = array_keys($deptList), 'wait', '', $orderBy, $pager);
            if($status == 'reviewed')   $firstRefunds = $this->refund->getList($mode = 'browseReview', $type, $currentDate, $deptIDList = array_keys($deptList), 'pass,finish', '', $orderBy, $pager);
        }

        $refunds = array_merge($secondRefunds, $firstRefunds);

        $this->session->set('refundList', $this->app->getURI(true));

        $this->view->title        = $this->lang->refund->review;
        $this->view->users        = $newUsers;
        $this->view->refunds      = $refunds;
        $this->view->deptList     = $allDeptList;
        $this->view->categories   = $categories;
        $this->view->currencySign = $this->loadModel('common', 'sys')->getCurrencySign();
        $this->view->currentYear  = $currentYear;
        $this->view->currentMonth = $currentMonth;
        $this->view->monthList    = $monthList;
        $this->view->yearList     = $yearList;
        $this->view->status       = $status;
        $this->view->date         = $date;
        $this->view->orderBy      = $orderBy;
        $this->view->pager        = $pager;

        $this->display();
    }

    /**
     * Review refund.
     * 
     * @param  int     $refundID 
     * @param  string  $status 
     * @access public
     * @return void
     */
    public function review($refundID)
    {
        $refund = $this->refund->getByID($refundID);

        if($_POST)
        {
            $result = $this->refund->review($refundID);
            if(is_array($result)) $this->send($result);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            
            /* send email. */
            $actionID = $this->loadModel('action')->create('refund', $refundID, 'reviewed');

            $refund = $this->refund->getByID($refundID);
            if($refund->status == 'pass' && !empty($this->config->refund->secondReviewer) 
                && $this->config->refund->secondReviewer != $this->app->user->account && $this->config->refund->secondReviewer == $refund->createdBy)
            {
                $this->loadModel('action')->create('refund', $refundID, 'reviewed', '', '', $this->config->refund->secondReviewer);
            }
            $this->sendmail($refundID, $actionID);

            $isDetail = ($refund->parent != 0) ? true : false;
            $this->send(array('result' => 'success', 'isDetail' => $isDetail, 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $this->view->title      = $this->lang->refund->review;
        $this->view->refund     = $refund;
        $this->view->categories = $this->refund->getCategoryPairs();
        $this->view->currencySign = $this->loadModel('common', 'sys')->getCurrencySign();
        $this->display();
    }

    /**
     * Refund a reimbursement.
     * 
     * @param  int    $refundID 
     * @access public
     * @return void
     */
    public function reimburse($refundID)
    {
        $this->refund->reimburse($refundID);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            
        /* send email. */
        $actionID = $this->loadModel('action')->create('refund', $refundID, 'reimburse', '', '');
        $this->sendmail($refundID, $actionID);

        $this->send(array('result' => 'success', 'refundID' => $refundID));
    }

    /**
     * Create trade of refund.
     * 
     * @param  int    $refundID 
     * @access public
     * @return void
     */
    public function createTrade($refundID)
    {
        if(!commonModel::hasPriv('refund', 'reimburse')) $this->deny('refund', 'reimburse');

        $this->app->loadLang('trade', 'cash');

        if($_POST)
        {
            $this->refund->createTrade($refundID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $this->view->title         = $this->lang->refund->common;
        $this->view->refundID      = $refundID;
        $this->view->depositorList = $this->loadModel('depositor', 'cash')->getPairs();
        $this->view->orderList     = $this->loadModel('order', 'crm')->getPairs($customerID = 0);
        $this->view->contractList  = $this->loadModel('contract', 'crm')->getList($customerID = 0);
        $this->view->customerList  = $this->loadModel('customer')->getPairs('client');

        $this->display();
    }

    /**
     * Set reviewer for refund. 
     * 
     * @param  string $module
     * @access public
     * @return void
     */
    public function setReviewer($module = '')
    {
        $this->loadModel('user');
        if($_POST)
        {
            $settings = fixer::input('post')->get();

            if($settings->firstReviewer and $settings->secondReviewer and $settings->firstReviewer == $settings->secondReviewer) $this->send(array('result' => 'fail', 'message' => $this->lang->refund->uniqueReviewer));

            $this->loadModel('setting')->setItems('system.oa.refund', $settings);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        if($module)
        {
            $this->lang->menuGroups->refund = $module;
            $this->lang->refund->menu       = $this->lang->$module->menu;
        }

        $this->view->title           = $this->lang->refund->reviewer; 
        $this->view->firstReviewer   = !empty($this->config->refund->firstReviewer) ? $this->config->refund->firstReviewer : '';
        $this->view->secondReviewer  = !empty($this->config->refund->secondReviewer) ? $this->config->refund->secondReviewer : '';
        $this->view->firstReviewers  = array('' => $this->lang->dept->moderators) + $this->user->getPairs('noempty,nodeleted,noforbidden,noclosed');
        $this->view->secondReviewers = $this->user->getPairs('nodeleted,noclosed,noforbidden');
        $this->view->module          = $module;
        $this->display();
    }

    /**
     * Set category for refund.
     * 
     * @param  string $module
     * @access public
     * @return void
     */
    public function setCategory($module = '')
    {
        $expenseList   = $this->loadModel('tree')->getOptionMenu('out', 0, true);
        $expenseIdList =  array_keys($expenseList);

        if($_POST)
        {
            $this->refund->setCategory($expenseIdList);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
        }

        if($module)
        {
            $this->lang->menuGroups->refund = $module;
            $this->lang->refund->menu       = $this->lang->$module->menu;
        }

        $refundCategories = $this->dao->select('*')->from(TABLE_CATEGORY)->where('type')->eq('out')->andWhere('refund')->eq(1)->fetchAll('id');
        $refundCategories = array_keys($refundCategories);
        $refundCategories = implode($refundCategories, ',');

        $this->view->title            = $this->lang->refund->setCategory;
        $this->view->expenseList      = $expenseList;
        $this->view->refundCategories = $refundCategories;
        $this->view->module           = $module;
        $this->display();
    }

    /**
     * Set depositor for refund.
     * 
     * @param  string $module
     * @access public
     * @return void
     */
    public function setDepositor($module = '')
    {
        if($_POST)
        {
            $this->loadModel('setting')->setItem('system.oa.refund.depositor', $this->post->depositor);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
        }

        if($module)
        {
            $this->lang->menuGroups->refund = $module;
            $this->lang->refund->menu       = $this->lang->$module->menu;
        }

        $this->view->title         = $this->lang->refund->setDepositor;
        $this->view->depositorList = $this->loadModel('depositor', 'cash')->getPairs();
        $this->view->module        = $module;
        $this->display();
    }

    /**
     * Set refundBy for refund.
     * 
     * @param  string $module
     * @access public
     * @return void
     */
    public function setRefundBy($module = '')
    {
        if($_POST)
        {
            $this->loadModel('setting')->setItem('system.oa.refund.refundBy', $this->post->refundBy);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
        }

        if($module)
        {
            $this->lang->menuGroups->refund = $module;
            $this->lang->refund->menu       = $this->lang->$module->menu;
        }

        $this->view->title  = $this->lang->refund->refundBy;
        $this->view->users  = $this->loadModel('user')->getPairs('nodeleted,noclosed,noforbidden');
        $this->view->module = $module;
        $this->display();
    }

    /**
     * Send email.
     * 
     * @param  int    $refundID 
     * @param  int    $actionID 
     * @access public
     * @return void
     */
    public function sendmail($refundID, $actionID)
    {
        /* Reset $this->output. */
        $this->clear();

        /* Get action info. */
        $action          = $this->loadModel('action')->getById($actionID);
        $history         = $this->action->getHistory($actionID);
        $action->history = isset($history[$actionID]) ? $history[$actionID] : array();

        /* Set toList and ccList. */
        $refund = $this->refund->getById($refundID);
        $users  = $this->loadModel('user')->getPairs();

        if($action->action == 'reviewed')
        {
            if($refund->status == 'doing') $toList = $this->config->refund->secondReviewer;
            if($refund->status != 'doing')
            {
                $toList = $refund->createdBy;
                if(!empty($this->config->refund->refundBy)) $toList .= ',' . $this->config->refund->refundBy;
            }
            $subject = "{$this->lang->refund->common}{$this->lang->refund->review}#{$refund->id} " . zget($users, $refund->createdBy) . " - {$refund->name}";
        }

        if($action->action == 'reimburse')
        {
            $toList = $refund->createdBy;
            $subject = "{$this->lang->refund->reimburse}#{$refund->id} " . zget($users, $refund->createdBy) . " - {$refund->name}";
        }

        if($action->action == 'created' or $action->action == 'revoked' or $action->action == 'commited')
        {
            if(!empty($this->config->refund->firstReviewer))
            {
                $toList = $this->config->refund->firstReviewer; 
            }
            else
            {
               $dept = $this->loadModel('tree')->getByID($this->app->user->dept);
               $toList = isset($dept->moderators) ? trim($dept->moderators, ',') : '';
            }
            $subject = "{$this->lang->refund->create}#{$refund->id} " . zget($users, $refund->createdBy) . " - {$refund->name}";
        }

        /* send notice if user is online and return failed accounts. */
        $toList = $this->loadModel('action')->sendNotice($actionID, $toList);

        /* Create the email content. */
        $this->view->refund     = $refund;
        $this->view->action     = $action;
        $this->view->users      = $users;
        $this->view->categories = $this->refund->getCategoryPairs();

        $this->loadModel('mail');
        $mailContent = $this->parse($this->moduleName, 'sendmail');

        /* Send emails. */
        $this->loadModel('mail')->send($toList, $subject, $mailContent);
        if($this->mail->isError()) trigger_error(join("\n", $this->mail->getError()));
    }

    /**
     * Check refund privilege and locate personal if no privilege. 
     * 
     * @param  object $refund 
     * @param  string $action 
     * @param  string $errorType   html|json 
     * @access private
     * @return void
     */
    private function checkPriv($refund, $action, $errorType = '')
    {
        if($this->app->user->admin == 'super') return true;

        $pass = true;
        $action = strtolower($action);
        $account = $this->app->user->account;

        if(strpos(',edit,delete,', ",$action,") !== false)
        {
            if(($refund->status != 'wait' and $refund->status != 'draft') or $refund->createdBy != $account) $pass = false;
        }

        if(!$pass)
        {
            if($errorType == '') $errorType = empty($_POST) ? 'html' : 'json';
            if($errorType == 'json')
            {
                $this->app->loadLang('notice');
                $this->send(array('result' => 'fail', 'message' => $this->lang->notice->typeList['accessLimited']));
            }
            else
            {
                $locate     = helper::safe64Encode($this->server->http_referer);
                $noticeLink = helper::createLink('notice', 'index', "type=accessLimited&locate={$locate}");
                $this->locate($noticeLink);
            }
        }
        return $pass;
    }

    /**
     * Cancel a refund or commit a refund. 
     * 
     * @param  int    $refundID 
     * @access public
     * @return void
     */
    public function switchStatus($refundID)
    {
        $refund = $this->refund->getByID($refundID);
        if(!$refund) return false;

        if($refund->status == 'wait')
        {
            $this->dao->update(TABLE_REFUND)->set('status')->eq('draft')->where('id')->eq($refundID)->exec();
            $actionID = $this->loadModel('action')->create('refund', $refundID, 'revoked');
            $this->sendmail($refundID, $actionID);
        }
        if($refund->status == 'draft')
        {
            $this->dao->update(TABLE_REFUND)->set('status')->eq('wait')->where('id')->eq($refundID)->exec();
            $actionID = $this->loadModel('action')->create('refund', $refundID, 'commited');
            $this->sendmail($refundID, $actionID);
        }

        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
    }

    /**
     * get data to export.
     * 
     * @param  string $mode 
     * @param  string $orderBy 
     * @access public
     * @return void
     */
    public function export($mode = 'all', $orderBy = 'id_desc')
    { 
        if($_POST)
        {
            $categories   = $this->refund->getCategoryPairs();
            $currencySign = $this->loadModel('common', 'sys')->getCurrencySign();
            $deptList     = $this->loadModel('tree')->getPairs('', 'dept');
            $users        = $this->loadModel('user')->getList();
            $userPairs    = array();
            $userDepts    = array();
            foreach($users as $key => $user) 
            {
                $userPairs[$user->account] = $user->realname;
                $userDepts[$user->account] = zget($deptList, $user->dept, ' ');
            }

            /* Create field lists. */
            $fields = explode(',', $this->config->refund->list->exportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = isset($this->lang->refund->$fieldName) ? $this->lang->refund->$fieldName : $fieldName;
                unset($fields[$key]);
            }
            $fields['dept'] = $this->lang->user->dept;

            $refunds = array();
            if($mode == 'all')
            {
                $refundQueryCondition = $this->session->refundQueryCondition;
                if(strpos($refundQueryCondition, 'limit') !== false) $refundQueryCondition = substr($refundQueryCondition, 0, strpos($refundQueryCondition, 'limit'));
                $stmt = $this->dbh->query($refundQueryCondition);
                while($row = $stmt->fetch()) $refunds[$row->id] = $row;
            }
            if($mode == 'thisPage')
            {
                $stmt = $this->dbh->query($this->session->refundQueryCondition);
                while($row = $stmt->fetch()) $refunds[$row->id] = $row;
            }

            foreach($refunds as $refund)
            {
                $refund->createdBy   = zget($userPairs, $refund->createdBy);
                $refund->createdDate = substr($refund->createdDate, 0, 10);
                $refund->dept        = zget($userDepts, $refund->createdBy);
                $refund->category    = zget($categories, $refund->category);
                $refund->money       = zget($currencySign, $refund->currency) . $refund->money;
                $refund->status      = zget($this->lang->refund->statusList, $refund->status);

                $related = array();
                foreach(explode(',', $refund->related) as $account) 
                {
                    if(empty($account)) continue;
                    $related[] = zget($userPairs, $account);
                }

                $refund->related    = implode(',', $related);
                $refund->reviewer   = zget($userPairs, $refund->firstReviewer) . ' ' . zget($userPairs, $refund->secondReviewer);
                $refund->refundBy   = zget($userPairs, $refund->refundBy);
                $refund->refundDate = substr($refund->refundDate, 0, 10);
            }

            $this->post->set('fields', $fields);
            $this->post->set('rows', $refunds);
            $this->post->set('kind', 'refund');
            $this->fetch('file', 'export2CSV' , $_POST);
        }

        $this->display();
    }
}
