<?php
/**
 * The control file of leads module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     leads
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class leads extends control
{
    /**
     * Construct method.
     * 
     * @param  string $moduleName 
     * @param  string $methodName 
     * @param  string $appName 
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '', $appName = '')
    {
        parent::__construct($moduleName, $methodName, $appName);
        $this->loadModel('contact', 'crm');
    }

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
     * Browse contact.
     * 
     * @param string $orderBy     the order by
     * @param int    $recTotal 
     * @param int    $recPerPage 
     * @param int    $pageID 
     * @access public
     * @return void
     */
    public function browse($mode = 'all', $status = 'wait', $origin = '',  $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {   
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $contacts = $this->contact->getList($customer = '', $relation = 'client', $mode, $status, $origin, $orderBy, $pager);
        $this->session->set('leadsQueryCondition', $this->dao->get());
        $this->session->set('leadsList', $this->app->getURI(true));
        $this->app->user->canEditContactIdList = ',' . implode(',', array_keys($contacts)) . ',';

        /* Build search form. */
        $this->loadModel('search', 'sys');
        $this->config->leads->search['actionURL'] = $this->createLink('leads', 'browse', 'mode=bysearch');
        $this->search->setSearchParams($this->config->leads->search);

        $this->view->title    = $this->lang->contact->list;
        $this->view->mode     = $mode;
        $this->view->origin   = $origin;
        $this->view->status   = $status;
        $this->view->contacts = $contacts;
        $this->view->pager    = $pager;
        $this->view->orderBy  = $orderBy;
        $this->display();
    }   

    /**
     * Create a leads.
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $return = $this->contact->create($contact = null, $type = 'leads'); 
            $this->send($return);
        }

        $this->lang->menuGroups->contact = 'leads';
        unset($this->lang->contact->menu);

        $this->view->title = $this->lang->leads->create;
        $this->display();
    }

    /**
     * Edit a contact.
     * 
     * @param  int    $contactID 
     * @param  string $mode 
     * @param  string $status 
     * @param  bool   $comment
     * @access public
     * @return void
     */
    public function edit($contactID = 0, $mode = 'assignedTo', $status = 'wait', $comment = false)
    {
        $contact = $this->contact->getByID($contactID);

        if($_POST)
        {
            $changes = array();
            if($comment == false)
            {
                $changes = $this->contact->update($contactID);
                if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            if($this->post->comment != '' or !empty($changes))
            {
                $action   = $this->post->comment == '' ? 'Edited' : 'Commented';
                $actionID = $this->loadModel('action')->create('contact', $contactID, $action, $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->loadModel('customer')->updateEditedDate($this->post->customer);
            $return = $this->contact->updateAvatar($contactID);

            $message = $return['result'] ? $this->lang->saveSuccess : $return['message'];
            $locate  = helper::createLink('leads', 'view', "contactID=$contactID");
            if(strpos($this->server->http_referer, 'contact') === false and strpos($this->server->http_referer, 'leads') === false) $locate = 'reload';
            $this->send(array('result' => 'success', 'message' => $message, 'locate' => $locate));
        }

        $this->view->title      = $this->lang->contact->edit;
        $this->view->mode       = $mode;
        $this->view->status     = $status;
        $this->view->contact    = $contact;
        $this->view->modalWidth = 1000;

        $this->display();
    }

    /**
     * View contact. 
     * 
     * @param  int    $contactID 
     * @param  string $mode 
     * @param  string $status 
     * @access public
     * @return void
     */
    public function view($contactID = 0, $mode = 'assignedTo', $status = 'wait')
    {
        $actionList = $this->loadModel('action')->getList('contact', $contactID);
        $actionIDList = array_keys($actionList);
        $actionFiles = $this->loadModel('file')->getByObject('action', $actionIDList);
        $fileList = array();
        foreach($actionFiles as $files)
        {
            foreach($files as $file) $fileList[$file->id] = $file;
        }

        $this->view->title      = $this->lang->contact->view;
        $this->view->mode       = $mode;
        $this->view->status     = $status;
        $this->view->contact    = $this->contact->getByID($contactID, $status);
        $this->view->addresses  = $this->loadModel('address', 'crm')->getList('contact', $contactID);
        $this->view->preAndNext = $this->loadModel('common', 'sys')->getPreAndNextObject('contact', $contactID); 
        $this->view->fileList   = $fileList;

        $this->display();
    }

    /**
     * Delete a lead.
     *
     * @param  int    $contactID
     * @access public
     * @return void
     */
    public function delete($contactID)
    {
        $contact = $this->loadModel('contact', 'crm')->getByID($contactID);
        if($contact->status != 'ignore') $this->send(array('result' => 'fail'));

        $this->contact->delete(TABLE_CONTACT, $contactID);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'locate' => inlink('browse')));
    }

    /**
     * Apply leads.
     * 
     * @access public
     * @return void
     */
    public function apply()
    {
        $remain = isset($this->config->leads->apply->remain) ? $this->config->leads->apply->remain : 10;
        $limit  = isset($this->config->leads->apply->limit) ? $this->config->leads->apply->limit : 30;

        $contactCount = $this->dao->select('count(*) as count')->from(TABLE_CONTACT)->where('assignedTo')->eq($this->app->user->account)->andWhere('status')->eq('wait')->fetch('count');
        if($contactCount >= $remain) $this->send(array('result' => 'fail', 'message' => $this->lang->leads->tips->apply));

        $contacts = $this->dao->select('*')->from(TABLE_CONTACT)->where('status')->eq('wait')->andWhere('assignedTo')->eq('')->orderBy('id_desc')->limit($limit)->fetchAll();
        foreach($contacts as $contact)
        {
            $this->dao->update(TABLE_CONTACT)->set('assignedTo')->eq($this->app->user->account)->where('id')->eq($contact->id)->exec();
        }
        if(!dao::isError()) return $this->send(array('result' => 'success', 'locate' => inlink('browse', "mode=assignedTo")));
        $this->send(array('result' => 'fail', 'message' => dao::getError()));
    }

    /**
     * Assign a contact to a member.
     * 
     * @param  int    $contactID 
     * @param  string    $table 
     * @access public
     * @return void
     */
    public function assign($contactID, $table = null)
    {
        if($_POST) 
        {
            $this->contact->assign($contactID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($this->post->assignedTo) 
            {
                $actionID = $this->loadModel('action')->create('contact', $contactID, 'Assigned', $this->post->comment, $this->post->assignedTo);
                $this->sendmail($contactID, $actionID);
            }
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->server->http_referer));
        }

        $this->view->title     = $this->lang->contact->assign;
        $this->view->users     = $this->loadModel('user')->getPairs('nodeleted,noforbidden,noclosed');
        $this->view->contactID = $contactID;
        $this->display();
    }

    /**
     * Transform contact.
     * 
     * @param  int     $contactID 
     * @access public
     * @return void
     */
    public function transform($contactID)
    {
        if($_POST)
        {
            $result = $this->contact->transform($contactID);
            if(is_array($result)) $this->send($result);

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('leads', $contactID, 'transform', '', '', '', '', $contactID);
            $this->send(array('result' => 'success', 'message' => $this->lang->importSuccess, 'locate' => $this->server->http_referer));
        }

        $this->view->title     = $this->lang->confirm . $this->lang->contact->common;
        $this->view->contact   = $this->contact->getByID($contactID, 'wait');
        $this->view->customers = $this->loadModel('customer')->getPairs('client');
        $this->display();
    }

    /**
     * Ignore contact in leads.
     * 
     * @param  int    $contactID 
     * @access public
     * @return void
     */
    public function ignore($contactID)
    {
        if($_POST)
        {
            $this->contact->ignore($contactID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('leads', $contactID, 'ignored', $this->post->comment, '', '', '', $contactID);
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->server->http_referer));
        }

        $this->view->title     = $this->lang->ignore;
        $this->view->contactID = $contactID;
        $this->display();
    }

    /**
     * Setting apply rule.
     * 
     * @access public
     * @return void
     */
    public function setting() 
    {
        if($_POST)
        {
            $this->loadModel('setting');
            if($this->post->applyLimit)  $this->setting->setItems('system.crm.leads.apply', array('limit' => $this->post->applyLimit));
            if($this->post->applyRemain) $this->setting->setItems('system.crm.leads.apply', array('remain' => $this->post->applyRemain));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
        }

        $this->view->title       = $this->lang->leads->applyRule;
        $this->view->applyLimit  = isset($this->config->leads->apply->limit)  ? $this->config->leads->apply->limit : 10;
        $this->view->applyRemain = isset($this->config->leads->apply->remain) ? $this->config->leads->apply->remain : 30;
        $this->display();
    }

    /**
     * Send email.
     * 
     * @param  int    $contactID 
     * @param  int    $actionID 
     * @access public
     * @return void
     */
    public function sendmail($contactID, $actionID)
    {
        /* Reset $this->output. */
        $this->clear();

        /* Set toList and ccList. */
        $contact = $this->contact->getById($contactID);
        $users   = $this->loadModel('user')->getPairs();
        $toList  = $contact->assignedTo;

        /* send notice if user is online and return failed accounts. */
        $toList = $this->loadModel('action')->sendNotice($actionID, $toList);

        /* Get action info. */
        $action          = $this->loadModel('action')->getById($actionID);
        $history         = $this->action->getHistory($actionID);
        $action->history = isset($history[$actionID]) ? $history[$actionID] : array();

        /* Create the email content. */
        $this->view->contact = $contact;
        $this->view->action  = $action;
        $this->view->users   = $users;

        $mailContent = $this->parse($this->moduleName, 'sendmail');

        /* Send emails. */
        $this->loadModel('mail')->send($toList, 'LEADS#' . $contact->id . ' ' . $contact->realname, $mailContent);
        if($this->mail->isError()) trigger_error(join("\n", $this->mail->getError()));
    }
}
