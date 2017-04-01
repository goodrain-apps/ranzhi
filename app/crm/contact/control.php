<?php
/**
 * The control file of contact module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     contact
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class contact extends control
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
     * Browse contact.
     * 
     * @param string $orderBy     the order by
     * @param int    $recTotal 
     * @param int    $recPerPage 
     * @param int    $pageID 
     * @access public
     * @return void
     */
    public function browse($mode = 'all', $status = 'normal', $origin = '',  $orderBy = 't1.id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {   
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $contacts = $this->contact->getList($customer = '', $relation = 'client', $mode, $status, $origin, $orderBy, $pager);
        $this->session->set('contactQueryCondition', $this->dao->get());
        $this->session->set('contactList', $this->app->getURI(true));
        $this->session->set('customerList', $this->app->getURI(true));
        $this->app->user->canEditContactIdList = ',' . implode(',', $this->contact->getContactsSawByMe('edit', array_keys($contacts))) . ',';

        $customers = $this->loadModel('customer')->getPairs();

        /* Build search form. */
        $this->loadModel('search', 'sys');
        $this->config->contact->search['actionURL'] = $this->createLink('contact', 'browse', 'mode=bysearch');
        $this->config->contact->search['params']['t2.customer']['values'] = $customers;
        $this->search->setSearchParams($this->config->contact->search);

        $this->app->loadLang('resume', 'crm');

        $this->view->title     = $this->lang->contact->list;
        $this->view->mode      = $mode;
        $this->view->status    = $status;
        $this->view->origin    = $origin;
        $this->view->contacts  = $contacts;
        $this->view->customers = $customers;
        $this->view->pager     = $pager;
        $this->view->orderBy   = $orderBy;
        $this->display();
    }   

    /**
     * Create a contact.
     * 
     * @param  int    $customer
     * @access public
     * @return void
     */
    public function create($customer = 0)
    {
        if($_POST)
        {
            $return = $this->contact->create($contact = null, $type = 'contact'); 
            $this->send($return);
        }

        $this->app->loadLang('resume', 'crm');
        unset($this->lang->contact->menu);
        $this->view->title     = $this->lang->contact->create;
        $this->view->customer  = $customer;
        $this->view->customers = $this->loadModel('customer')->getPairs('client');
        $this->view->sizeList  = $this->customer->combineSizeList();
        $this->view->levelList = $this->customer->combineLevelList();
        $this->display();
    }

    /**
     * Edit a contact.
     * 
     * @param  int    $contactID 
     * @param  bool   $comment
     * @access public
     * @return void
     */
    public function edit($contactID, $comment = false)
    {
        $contact = $this->contact->getByID($contactID);
        $this->loadModel('common', 'sys')->checkPrivByCustomer(empty($contact) ? 0 : $contact->customer, 'edit');

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
                $actionID = $this->loadModel('action', 'sys')->create('contact', $contactID, $action, $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->loadModel('customer')->updateEditedDate($this->post->customer);
            $return = $this->contact->updateAvatar($contactID);

            $message = $return['result'] ? $this->lang->saveSuccess : $return['message'];
            $locate  = helper::createLink('contact', 'view', "contactID=$contactID");
            if(strpos($this->server->http_referer, 'contact') === false and strpos($this->server->http_referer, 'leads') === false) $locate = 'reload';
            $this->send(array('result' => 'success', 'message' => $message, 'locate' => $locate));
        }

        $this->app->loadLang('resume', 'crm');

        $this->view->title      = $this->lang->contact->edit;
        $this->view->customers  = $this->loadModel('customer')->getPairs();
        $this->view->contact    = $contact;
        $this->view->modalWidth = 1000;

        $this->display();
    }

    /**
     * View contact. 
     * 
     * @param  int    $contactID 
     * @access public
     * @return void
     */
    public function view($contactID, $status = 'normal')
    {
        if($this->session->customerList == $this->session->contactList) $this->session->set('customerList', $this->app->getURI(true));
        $this->app->user->canEditContactIdList = ',' . implode(',', $this->contact->getContactsSawByMe('edit', (array)$contactID)) . ',';

        $actionList = $this->loadModel('action', 'sys')->getList('contact', $contactID);
        $actionIDList = array_keys($actionList);
        $actionFiles = $this->loadModel('file', 'sys')->getByObject('action', $actionIDList);
        $fileList = array();
        foreach($actionFiles as $files)
        {
            foreach($files as $file) $fileList[$file->id] = $file;
        }

        $this->view->title      = $this->lang->contact->view;
        $this->view->contact    = $this->contact->getByID($contactID, $status);
        $this->view->addresses  = $this->loadModel('address', 'crm')->getList('contact', $contactID);
        $this->view->resumes    = $this->loadModel('resume', 'crm')->getList($contactID);
        $this->view->customers  = $this->loadModel('customer')->getPairs('client');
        $this->view->preAndNext = $this->loadModel('common', 'sys')->getPreAndNextObject('contact', $contactID); 
        $this->view->fileList   = $fileList;

        $this->display();
    }

    /**
     * Contact history.
     * 
     * @param  int    $customer 
     * @access public
     * @return void
     */
    public function block($customer)
    {
        $this->view->contacts = $this->contact->getList($customer);
        $this->display();
    }

    /**
     * Delete a contact.
     *
     * @param  int    $contactID
     * @access public
     * @return void
     */
    public function delete($contactID)
    {
        $contact = $this->contact->getByID($contactID);
        $this->loadModel('common', 'sys')->checkPrivByCustomer(empty($contact) ? 0 : $contact->customer, 'edit');

        $this->contact->delete(TABLE_CONTACT, $contactID);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'locate' => inlink('browse')));
    }

    /**
     * Get option menu.
     * 
     * @param  int    $customer 
     * @param  int    $current 
     * @access public
     * @return void
     */
    public function getOptionMenu($customer, $current = 0)
    {
        $options = $this->contact->getPairs($customer);
        foreach($options as $value => $text)
        {
            $selected = $value == $current ? 'selected' : '';
            echo "<option value='{$value}' {$selected}>{$text}</option>";
        }
        exit;
    }

    /**
     * vcard of a contact.
     * 
     * @param  int    $contactID 
     * @access public
     * @return void
     */
    public function vcard($contactID)
    {
        $contact = $this->contact->getByID($contactID);
        $customer = $this->loadModel('customer')->getByID($contact->customer);
        $addresses = $this->loadModel('address', 'crm')->getList('contact', $contactID);

        $fullAddress = '';
        foreach($addresses as $address) $fullAddress .= $address->fullLocation . ';';

        $vcard = "BEGIN:VCARD 
N:{$contact->realname}
ORG:{$customer->name}
TITLE:{$contact->dept} {$contact->title}
TEL;TYPE=WORK:{$contact->phone}
TEL;TYPE=CELL:{$contact->mobile}
ADR;TYPE=HOME:{$fullAddress}
EMAIL;TYPE=PREF,INTERNET:{$contact->email}
END:VCARD";

        $this->app->loadClass('qrcode');
        QRcode::png($vcard, false, 4, 6); 
    }

    /**
     * get data to export.
     * 
     * @param  string $type         contact | leads
     * @param  string $mode 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function export($type = 'contact', $mode = 'all', $orderBy = 'id_desc')
    { 
        if($_POST)
        {
            $contactLang   = $this->lang->contact;
            $contactConfig = $this->config->contact;

            /* Create field lists. */
            $fields = explode(',', $contactConfig->list->exportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = isset($contactLang->$fieldName) ? $contactLang->$fieldName : $fieldName;
                unset($fields[$key]);
            }

            $contacts = array();
            $queryCondition = $this->session->{$type . 'QueryCondition'};
            if($mode == 'all')
            {
                if(strpos($queryCondition, 'limit') !== false) $queryCondition = substr($queryCondition, 0, strpos($queryCondition, 'limit'));
                $stmt = $this->dbh->query($queryCondition);
                while($row = $stmt->fetch()) $contacts[$row->id] = $row;
            }

            if($mode == 'thisPage')
            {
                $stmt = $this->dbh->query($queryCondition);
                while($row = $stmt->fetch()) $contacts[$row->id] = $row;
            }

            $users     = $this->loadModel('user', 'sys')->getPairs();
            $customers = $this->loadModel('customer')->getPairs();

            $resumes     = $this->dao->select('*')->FROM(TABLE_RESUME)->where('deleted')->eq(0)->fetchGroup('contact');
            $addressList = $this->dao->select('*')->FROM(TABLE_ADDRESS)->fetchGroup('objectID');
            $areaList    = $this->loadModel('tree', 'sys')->getOptionMenu('area');

            foreach($contacts as $id => $contact)
            {
                $contact->resume = array();
                if(!empty($resumes[$id]))
                {
                    foreach($resumes[$id] as $resume)
                    {
                        $contact->resume[] = $resume->join . $this->lang->minus . ($resume->left ? $resume->left : $this->lang->today . ' ') . $customers[$resume->customer] . $resume->dept . $resume->title;
                    }
                }
            }

            foreach($contacts as $contact)
            {
                $contact->address = array();
                if(!empty($addressList[$contact->id]))
                {
                    foreach($addressList[$contact->id] as $address)
                    {
                        $contact->address[] = ((isset($address->area)) ? str_replace('/', ' ', zget($areaList, $address->area)) : '') . $address->location;
                    }
                }
                elseif(!empty($addressList[$contact->customer]))
                {
                    foreach($addressList[$contact->customer] as $address)
                    {
                        $contact->address[] = ((isset($address->area)) ? str_replace('/', ' ', zget($areaList, $address->area)) : '') . $address->location;
                    }
                }
            }

            foreach($contacts as $contact)
            {
                if(isset($customers[$contact->customer]))              $contact->customer = $customers[$contact->customer];
                if(isset($this->lang->genderList->{$contact->gender})) $contact->gender   = $this->lang->genderList->{$contact->gender};

                if(isset($users[$contact->createdBy]))   $contact->createdBy   = $users[$contact->createdBy];
                if(isset($users[$contact->editedBy]))    $contact->editedBy    = $users[$contact->editedBy];
                if(isset($users[$contact->contactedBy])) $contact->contactedBy = $users[$contact->contactedBy];

                $contact->createdDate   = substr($contact->createdDate, 0, 10);
                $contact->editedDate    = substr($contact->editedDate, 0, 10);
                $contact->contactedDate = substr($contact->contactedDate, 0, 10);
                $contact->nextDate      = substr($contact->contactedDate, 0, 10);

                if(isset($contact->resume))  $contact->resume  = join("; \n", $contact->resume);
                if(isset($contact->address)) $contact->address = join("; \n", $contact->address);
            }

            $this->post->set('fields', $fields);
            $this->post->set('rows', $contacts);
            $this->post->set('kind', 'contact');
            $this->fetch('file', 'export2CSV' , $_POST);
        }

        $this->display();
    }

    /**
     * Export contact template. 
     * 
     * @access public
     * @return void
     */
    public function exportTemplate()
    {
        if($_POST)
        {
            $fields = array();
            $rows   = array();
            foreach($this->config->contact->templateFields as $key)
            {
                $fields[$key] = $this->lang->contact->$key;
                for($i = 0; $i < $this->post->num; $i++) $rows[$i][$key] = '';
            }

            $data = new stdclass();
            $data->fields      = $fields;
            $data->kind        = 'contact';
            $data->rows        = $rows;
            $data->title       = $this->lang->contact->template;
            $data->customWidth = $this->config->contact->excelCustomWidth;
            $data->genderList  = array_values($this->lang->contact->genderList);
            $data->sysDataList = $this->config->contact->listFields;
            $data->listStyle   = $this->config->contact->listFields;

            $excelData = new stdclass();
            $excelData->dataList[] = $data;
            $excelData->fileName   = $this->lang->contact->template;

            $this->app->loadClass('excel')->export($excelData, $this->post->fileType);
        }

        $this->display('file', 'exportTemplate');
    }

    /**
     * Import contacts from excel. 
     * 
     * @access public
     * @return void
     */
    public function import()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $file = $this->loadModel('file', 'sys')->getUpload('files');
            if(empty($file)) $this->send(array('result' => 'fail', 'message' => $this->lang->contact->noFile));
            $file = $file[0];
            move_uploaded_file($file['tmpname'], $this->file->savePath . $file['pathname']);

            $file = $this->file->savePath . $file['pathname'];
            $phpExcel  = $this->app->loadClass('phpexcel');
            $phpReader = new PHPExcel_Reader_Excel2007(); 
            if(!$phpReader->canRead($file))
            { 
                $phpReader = new PHPExcel_Reader_Excel5(); 
                if(!$phpReader->canRead($file)) 
                {   
                    unlink($file);
                    die(js::alert($this->lang->excel->canNotRead));
                }
            } 
            $this->session->set('importFile', $file);
            $this->send(array('result' => 'success', 'locate' => (inlink('showImport'))));
        }

        $this->view->title = $this->lang->import;
        $this->display('file', 'import');
    }

    /**
     * Show import result. 
     * 
     * @access public
     * @return void
     */
    public function showImport()
    {
        if(!$this->session->importFile) $this->locate(inlink('browse'));
        
        $successList = $this->contact->import();
        $errorList   = $this->session->errorList;

        unlink($this->session->importFile);
        unset($_SESSION['importFile']);
    
        if(empty($errorList)) die(js::alert($this->lang->saveSuccess) . js::locate($this->createLink('leads', 'browse', 'mode=assignedTo')));

        $this->app->loadLang('search');
        $this->view->title       = $this->lang->import . $this->lang->contact->common;
        $this->view->successList = $successList;
        $this->view->errorList   = $errorList;
        $this->view->mode        = 'all';
        $this->view->status      = 'wait';
        $this->display();
    }
}
