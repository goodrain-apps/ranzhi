<?php
/**
 * The control file of refund of Ranzhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
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
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('view', "refundID=$refundID")));
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
    public function personal($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->browse('personal', $orderBy, $recTotal, $recPerPage, $pageID);
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
    public function company($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->browse('company', $orderBy, $recTotal, $recPerPage, $pageID);
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
    public function todo($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->browse('todo', $orderBy, $recTotal, $recPerPage, $pageID);
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
    public function browse($mode = 'personal', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

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

        if($mode == 'personal') $refunds = $this->refund->getList($mode, '', '', $this->app->user->account, $orderBy, $pager);
        if($mode == 'company')  $refunds = $this->refund->getList($mode, '', '', '', $orderBy, $pager);
        if($mode == 'todo')     $refunds = $this->refund->getList($mode, '', 'pass', '', $orderBy, $pager);

        /* Set return url. */
        $this->session->set('refundList', $this->app->getURI(true));

        $this->view->title        = $this->lang->refund->$mode;
        $this->view->refunds      = $refunds;
        $this->view->orderBy      = $orderBy;
        $this->view->mode         = $mode;
        $this->view->pager        = $pager;
        $this->view->categories   = $this->refund->getCategoryPairs();
        $this->view->currencySign = $this->loadModel('common', 'sys')->getCurrencySign();
        $this->view->userPairs    = $userPairs;
        $this->view->userDept     = $userDept;
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
    public function browseReview()
    {
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
            $secondRefunds = $this->refund->getList($mode = 'browseReview', $deptIDList = '', 'doing');
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
        if(!empty($deptList)) $firstRefunds = $this->refund->getList($mode = 'browseReview', $deptIDList = array_keys($deptList), 'wait');
        $refunds = array_merge($secondRefunds, $firstRefunds);

        $this->view->title        = $this->lang->refund->review;
        $this->view->users        = $newUsers;
        $this->view->refunds      = $refunds;
        $this->view->deptList     = $allDeptList;
        $this->view->categories   = $this->refund->getCategoryPairs();
        $this->view->currencySign = $this->loadModel('common', 'sys')->getCurrencySign();

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

        $this->display();
    }

    /**
     * Set reviewer for refund. 
     * 
     * @access public
     * @return void
     */
    public function settings()
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

        $this->view->title           = $this->lang->refund->settings; 
        $this->view->firstReviewer   = !empty($this->config->refund->firstReviewer) ? $this->config->refund->firstReviewer : '';
        $this->view->secondReviewer  = !empty($this->config->refund->secondReviewer) ? $this->config->refund->secondReviewer : '';
        $this->view->firstReviewers  = array('' => $this->lang->dept->moderators) + $this->user->getPairs('noempty,nodeleted,noforbidden,noclosed');
        $this->view->secondReviewers = $this->user->getPairs('nodeleted,noclosed,noforbidden');
        $this->display();
    }

    /**
     * Set category for refund.
     * 
     * @access public
     * @return void
     */
    public function setCategory()
    {
        $expenseList   = $this->loadModel('tree')->getOptionMenu('out', 0, true);
        $expenseIdList =  array_keys($expenseList);

        $refundCategories = $this->dao->select('*')->from(TABLE_CATEGORY)->where('type')->eq('out')->andWhere('refund')->eq(1)->fetchAll('id');
        $refundCategories = array_keys($refundCategories);
        $refundCategories = implode($refundCategories, ',');

        if($_POST)
        {
            $this->refund->setCategory($expenseIdList);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
        }

        $this->view->expenseList      = $expenseList;
        $this->view->refundCategories = $refundCategories;
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
        $users  = $this->loadModel('user')->getPairs('noletter');

        if($action->action == 'reviewed')
        {
            if($refund->status == 'doing') $toList = $this->config->refund->secondReviewer;
            if($refund->status != 'doing') $toList = $refund->createdBy;
            $subject = "{$this->lang->refund->common}{$this->lang->refund->review}#{$refund->id}{$this->lang->colon}{$refund->name} " . zget($users, $refund->createdBy);
        }

        if($action->action == 'reimburse')
        {
            $toList = $refund->createdBy;
            $subject = "{$this->lang->refund->reimburse}#{$refund->id}{$this->lang->colon}{$refund->name} " . zget($users, $refund->createdBy);
        }

        if($action->action == 'created' or $action->action == 'revoked' or $action->action == 'commited')
        {
            if(!empty($this->config->refund->firstReviewedBy))
            {
                $toList = $this->config->refund->firstReviewedBy; 
            }
            else
            {
               $dept = $this->loadModel('tree')->getByID($this->app->user->dept);
               $toList = trim($dept->moderators, ',');
            }
            $subject = "{$this->lang->refund->create}#{$refund->id}{$this->lang->colon}{$refund->name} " . zget($users, $refund->createdBy);
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
            if($refund->status != 'wait' or $refund->createdBy != $account) $pass = false;
        }

        if(!$pass)
        {
            if($errorType == '') $errorType = empty($_POST) ? 'html' : 'json';
            if($errorType == 'json')
            {
                $this->app->loadLang('error');
                $this->send(array('result' => 'fail', 'message' => $this->lang->error->typeList['accessLimited']));
            }
            else
            {
                $locate = helper::safe64Encode($this->server->http_referer);
                $errorLink = helper::createLink('error', 'index', "type=accessLimited&locate={$locate}");
                $this->locate($errorLink);
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
}
