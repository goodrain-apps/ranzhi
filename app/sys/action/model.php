<?php
/**
 * The model file of action module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     action
 * @version     $Id: model.php 5028 2013-07-06 02:59:41Z wyd621@gmail.com $
 * @link        http://www.ranzhico.com
 */
?>
<?php
class actionModel extends model
{
    const BE_UNDELETED  = 0;    // The deleted object has been undeleted.
    const CAN_UNDELETED = 1;    // The deleted object can be undeleted.
    const BE_HIDDEN     = 2;    // The deleted object has been hidded.

    /**
     * Create an action.
     * 
     * @param  string $objectType 
     * @param  int    $objectID 
     * @param  string $actionType
     * @param  string $comment 
     * @param  mix    $extra        the extra info of this action, like customer, contact, order etc.  according to different modules and actions, can set different extra.
     * @param  string $actor
     * @param  int    $customer 
     * @param  int    $contact 
     * @access public
     * @return int
     */
    public function create($objectType, $objectID, $actionType, $comment = '', $extra = '', $actor = '', $customer = 0, $contact = 0)
    {
        $action = new stdclass();

        $action->objectType = strtolower($objectType);
        $action->objectID   = $objectID;
        $action->customer   = $customer;
        $action->contact    = $contact;
        $action->actor      = $actor ? $actor : $this->app->user->account;
        $action->action     = strtolower($actionType);
        $action->date       = helper::now();
        $action->comment    = trim(strip_tags($comment, "<img>")) ? $comment : '';
        $action->extra      = $extra;
        $action->nextDate   = $this->post->nextDate;

        /* If objectType is customer or contact, save objectID as customer id or contact id. */
        if($objectType == 'customer' || $objectType == 'provider') $action->customer = $objectID;
        if($objectType == 'contact')  $action->contact  = $objectID;

        $this->dao->insert(TABLE_ACTION)
            ->data($action, $skip = 'nextDate,files,labels')
            ->batchCheckIF($actionType == 'record', $this->config->action->require->createRecord, 'notempty')
            ->checkIF($this->post->nextDate, 'nextDate', 'ge', helper::today())
            ->exec();

        return $this->dbh->lastInsertID();
    }

    /**
     * Save a record of an order.
     * 
     * @param  object    $order 
     * @access public
     * @return void
     */
    public function createRecord($objectType, $objectID, $customer, $contact)
    {
        $actionID = $this->create($objectType, $objectID, $action = 'record', $this->post->comment, $this->post->date, $actor = null, $customer, $contact);

        if(!$actionID) return false;
        $this->loadModel('file')->saveUpload('action', $actionID);
        return $this->syncContactInfo($objectType, $objectID, $customer, $contact);
    }
    
    /**
     * Sync contact info.
     * 
     * @param  int    $objectType 
     * @param  int    $objectID 
     * @param  int    $customer 
     * @param  int    $contact 
     * @access public
     * @return void
     */
    public function syncContactInfo($objectType, $objectID, $customer, $contact)
    {
        $contactInfo['contactedDate'] = $this->post->date;
        $contactInfo['contactedBy']   = $this->app->user->account;
        $contactInfo['editedDate']    = helper::now();

        $this->dao->update(TABLE_CUSTOMER)->data($contactInfo)->where('id')->eq($customer)->andWhere('contactedDate')->lt($this->post->date)->exec();
        $this->dao->update(TABLE_CONTACT)->data($contactInfo)->where('id')->eq($contact)->andWhere('contactedDate')->lt($this->post->date)->exec();

        if($objectType == 'order')    $this->dao->update(TABLE_ORDER)->data($contactInfo)->where('id')->eq($objectID)->andWhere('contactedDate')->lt($this->post->date)->exec();
        if($objectType == 'contract') $this->dao->update(TABLE_CONTRACT)->data($contactInfo)->where('id')->eq($objectID)->andWhere('contactedDate')->lt($this->post->date)->exec();

        $nextDate = $this->post->nextDate ? $this->post->nextDate : ''; 
        $this->dao->update(TABLE_CUSTOMER)->set('nextDate')->eq($nextDate)->where('id')->eq($customer)->exec();
        $this->dao->update(TABLE_CONTACT)->set('nextDate')->eq($nextDate)->where('id')->eq($contact)->exec();
        if($objectType == 'order') $this->dao->update(TABLE_ORDER)->set('nextDate')->eq($nextDate)->where('id')->eq($objectID)->exec();
        if($objectType == 'contract') $this->dao->update(TABLE_CONTRACT)->set('nextDate')->eq($nextDate)->where('id')->eq($objectID)->exec();

        return !dao::isError();
    }

    /**
     * Get actions of an object.
     * 
     * @param  string $objectType 
     * @param  int    $objectID 
     * @param  string $action 
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($objectType, $objectID, $action = '', $pager = null, $origin = '')
    {
        $actions = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('1 = 1')
            ->beginIF($objectType == 'customer' || $objectType == 'provider')->andWhere('customer')->eq($objectID)->fi()
            ->beginIF($objectType == 'contact')->andWhere('contact')->eq($objectID)->fi()
            ->beginIF($objectType != 'customer' and $objectType != 'provider' and $objectType != 'contact')
              ->andWhere('objectType')->eq($objectType)
              ->andWhere('objectID')->eq($objectID)
            ->fi()
            ->beginIF($action)->andWhere('action')->eq($action)->fi()
            ->orderBy('id_desc')
            ->page($pager)
            ->fetchAll('id');

        $histories = $this->getHistory(array_keys($actions));
        $contacts  = $this->loadModel('contact', 'crm')->getPairs(0, false, '');
        $this->loadModel('file');

        foreach($actions as $actionID => $action)
        {
            $action->history = isset($histories[$actionID]) ? $histories[$actionID] : array();
            $action->files = $this->loadModel('file')->getByObject('action', $actionID);
            if($action->action == 'record') $action->contact = isset($contacts[$action->contact]) ? $contacts[$action->contact] : '';
            $actions[$actionID] = $action;
        }

        return $actions;
    }

    /**
     * Get an action record.
     * 
     * @param  int    $actionID 
     * @access public
     * @return object
     */
    public function getById($actionID)
    {
        $action = $this->dao->findById((int)$actionID)->from(TABLE_ACTION)->fetch();
        if(!$action) return false;
        $action->files = $this->loadModel('file')->getByObject('action', $actionID);
        return $action;
    }

    /**
     * Get deleted objects.
     * 
     * @param  string    $type all|hidden 
     * @param  string    $orderBy 
     * @param  object    $pager 
     * @access public
     * @return array
     */
    public function getTrashes($type, $orderBy, $pager)
    {
        $extra = $type == 'hidden' ? self::BE_HIDDEN : self::CAN_UNDELETED;
        $trashes = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('action')->eq('deleted')
            ->andWhere('extra')->eq($extra)
            ->orderBy($orderBy)->page($pager)->fetchAll();
        if(!$trashes) return array();
        
        /* Group trashes by objectType, and get there name field. */
        foreach($trashes as $object) 
        {
            $object->objectType = str_replace('`', '', $object->objectType);
            $typeTrashes[$object->objectType][] = $object->objectID;
        }

        foreach($typeTrashes as $objectType => $objectIds)
        {
            $objectIds   = array_unique($objectIds);
            $table       = $this->config->objectTables[$objectType];
            $field       = $this->config->action->objectNameFields[$objectType];

            if(!$table) continue;
            $objectNames[$objectType] = $this->dao->select("id, $field AS name")->from($table)->where('id')->in($objectIds)->fetchPairs();

            /* Get titles if objectType is order. */
            if($objectType == 'order')
            {
                $this->app->loadLang('order', 'crm');
                $orders = $this->loadModel('order', 'crm')->getByIdList($objectIds);
                foreach($orders as $order) $objectNames['order'][$order->id] = $order->title;
            }
        }

        /* Add name field to the trashes. */
        foreach($trashes as $trash) $trash->objectName = isset($objectNames[$trash->objectType][$trash->objectID]) ? $objectNames[$trash->objectType][$trash->objectID] : $trash->objectID;
        return $trashes;
    }

    /**
     * Get histories of an action.
     * 
     * @param  int    $actionID 
     * @access public
     * @return array
     */
    public function getHistory($actionID)
    {
        return $this->dao->select()->from(TABLE_HISTORY)->where('action')->in($actionID)->orderBy('id')->fetchGroup('action');
    }

    /**
     * Log histories for an action.
     * 
     * @param  int    $actionID 
     * @param  array  $changes 
     * @access public
     * @return void
     */
    public function logHistory($actionID, $changes)
    {
        foreach($changes as $change) 
        {
            $change['action'] = $actionID;
            $this->dao->insert(TABLE_HISTORY)->data($change)->exec();
        }
    }

    /**
     * Print actions of an object.
     * 
     * @param  array    $action 
     * @access public
     * @return void
     */
    public function printAction($action)
    {
        $objectType = $action->objectType;
        $actionType = strtolower($action->action);

        /**
         * Set the desc string of this action.
         *
         * 1. If the module of this action has defined desc of this actionType, use it.
         * 2. If no defined in the module language, search the common action define.
         * 3. If not found in the lang->action->desc, use the $lang->action->desc->common or $lang->action->desc->extra as the default.
         */
        if(isset($this->lang->$objectType->action->$actionType))
        {
            $desc = $this->lang->$objectType->action->$actionType;
        }
        elseif(isset($this->lang->action->desc->$actionType))
        {
            $desc = $this->lang->action->desc->$actionType;
        }
        else
        {
            $desc = $action->extra ? $this->lang->action->desc->extra : $this->lang->action->desc->common;
        }

        if($this->app->getViewType() == 'mhtml') $action->date = date('m-d H:i', strtotime($action->date));

        /* Cycle actions, replace vars. */
        foreach($action as $key => $value)
        {
            if($key == 'history' or $key == 'files') continue;

            /* Desc can be an array or string. */
            if(is_array($desc))
            {
                if($key == 'extra') continue;
                $desc['main'] = str_replace('$' . $key, $value, $desc['main']);
            }
            else
            {
                $desc = str_replace('$' . $key, $value, $desc);
            }
        }

        /* If the desc is an array, process extra. Please bug/lang. */
        if(is_array($desc))
        {
            $extra = strtolower($action->extra);
            if(isset($desc['extra'][$extra])) 
            {
                echo str_replace('$extra', $desc['extra'][$extra], $desc['main']);
            }
            else
            {
                echo str_replace('$extra', $action->extra, $desc['main']);
            }
        }
        else
        {
            echo $desc;
        }
    }

    /**
     * Get actions as dynamic.
     * 
     * @param  string $account 
     * @param  string $period 
     * @param  string $orderBy 
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getDynamic($account = 'all', $period = 'all', $orderBy = 'date_desc', $pager = null)
    {
        if($this->session->myQuery == false) $this->session->set('myQuery', ' 1 = 1');
        $myQuery = $this->loadModel('search', 'sys')->replaceDynamic($this->session->myQuery);

        /* Computer the begin and end date of a period. */
        $beginAndEnd = $this->computeBeginAndEnd($period);
        extract($beginAndEnd);

        /* Get actions. */
        $actions = $this->dao->select('*')->from(TABLE_ACTION)
            ->where(1)
            ->andWhere('objectType')->notin('attend,refund,leave,overtime,trip,egress,action')
            ->beginIF($period != 'bysearch' && $period  != 'all')->andWhere('date')->gt($begin)->fi()
            ->beginIF($period != 'bysearch' && $period  != 'all')->andWhere('date')->lt($end)->fi()
            ->beginIF($period != 'bysearch' && $account != 'all')->andWhere('actor')->eq($account)->fi()
            ->beginIF($period == 'bysearch')->andWhere($myQuery)->fi()
            ->orderBy($orderBy)
            ->fetchAll();

        if(!$actions) return array();
        $actions = $this->transformActions($actions);

        $idList = array();
        foreach($actions as $key => $action) 
        {
            if($this->checkPriv($action)) $idList[] = $action->id;
        }
        /* Fix pager. */
        $actionIDList = $this->dao->select('id')->from(TABLE_ACTION)->where('id')->in($idList)->orderBy($orderBy)->page($pager)->fetchAll('id');
        foreach($actions as $key => $action)
        {
            if(!isset($actionIDList[$action->id])) unset($actions[$key]);
        }

        return $actions;
    }

    /**
     * Transform the actions for display.
     * 
     * @param  int    $actions 
     * @access public
     * @return void
     */
    public function transformActions($actions)
    {
        /* Group actions by objectType, and get there name field. */
        foreach($actions as $object) $objectTypes[$object->objectType][] = $object->objectID;
        foreach($objectTypes as $objectType => $objectIds)
        {
            if(!isset($this->config->objectTables[$objectType])) continue;    // If no defination for this type, omit it.

            $objectIds = array_unique($objectIds);
            $table     = $this->config->objectTables[$objectType];
            $field     = $this->config->action->objectNameFields[$objectType];
            if($table != '`oa_todo`' and $table != '`cash_trade`')
            {
                $objectNames[$objectType] = $this->dao->select("id, $field AS name")->from($table)->where('id')->in($objectIds)->fetchPairs();
                if($objectType == 'order') $objectNames[$objectType] = $this->dao->select('o.id, concat(c.name, o.createdDate) as name')
                    ->from(TABLE_ORDER)->alias('o')
                    ->leftJoin(TABLE_CUSTOMER)->alias('c')->on('o.customer=c.id')
                    ->where('o.id')->in($objectIds)
                    ->fetchPairs(); 
            }
            elseif($table == '`oa_todo`')
            {
                $todos = $this->dao->select("id, $field AS name, account, private, type, idvalue")->from($table)->where('id')->in($objectIds)->fetchAll('id');
                foreach($todos as $id => $todo)
                {
                    if($todo->type == 'task') $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_TASK)->fetch('name');
                    if($todo->type == 'customer') $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_CUSTOMER)->fetch('name'); 
                    if($todo->type == 'order') 
                    {
                        $order = $this->dao->select('c.name, o.createdDate')
                            ->from(TABLE_ORDER)->alias('o')
                            ->leftJoin(TABLE_CUSTOMER)->alias('c')->on('o.customer=c.id')
                            ->where('o.id')->eq($todo->idvalue)
                            ->fetch(); 
                        $todo->name = $order->name . '|' . date('Y-m-d', strtotime($order->createdDate));
                    }
                    if(isset($this->lang->action->objectTypes[$todo->type])) $todo->name = $this->lang->action->objectTypes[$todo->type] . ':' . $todo->name;

                    if($todo->private == 1 and $todo->account != $this->app->user->account) 
                    {
                       $objectNames[$objectType][$id] = $this->lang->todo->thisIsPrivate;
                    }
                    else
                    {
                       $objectNames[$objectType][$id] = $todo->name;
                    }
                }
            } 
            else
            {
                $this->app->loadLang('trade', 'cash');
                $trades = $this->dao->select("id, type, money, currency")->from($table)->where('id')->in($objectIds)->fetchAll('id');
                foreach($trades as $id => $trade)
                {
                    $objectNames[$objectType][$id] = $this->lang->trade->typeList[$trade->type] . $this->lang->currencySymbols[$trade->currency] . $trade->money;
                }
            }
        }
        $objectNames['user'][0] = 'guest';    // Add guest account.

        foreach($actions as $action)
        {
            /* Add name field to the actions. */
            $action->objectName = isset($objectNames[$action->objectType][$action->objectID]) ? $objectNames[$action->objectType][$action->objectID] : '';

            $actionType = strtolower($action->action);
            $objectType = strtolower($action->objectType);
            $action->date        = date(DT_MONTHTIME2, strtotime($action->date));
            $action->actionLabel = isset($this->lang->action->label->$actionType) ? $this->lang->action->label->$actionType : $action->action;
            $action->objectLabel = $objectType;
            if(isset($this->lang->action->label->$objectType))
            {
                $objectLabel = $this->lang->action->label->$objectType;
                if(!is_array($objectLabel)) $action->objectLabel = $objectLabel;
                if(is_array($objectLabel) and isset($objectLabel[$actionType])) $action->objectLabel = $objectLabel[$actionType];
            }

            /* app name. */
            $action->appName = '';
            if(isset($this->config->action->objectAppNames[$objectType])) $action->appName = $this->config->action->objectAppNames[$objectType];

            /* Other actions, create a link. */
            if(strpos($action->objectLabel, '|') !== false)
            {
                list($objectLabel, $moduleName, $methodName, $vars) = explode('|', $action->objectLabel);
                $vars = empty($vars) ? '' : sprintf($vars, $action->objectID);
                if(!empty($action->appName)) $moduleName = "{$action->appName}.{$moduleName}";
                $action->objectLink  = helper::createLink($moduleName, $methodName, $vars);
                $action->objectLabel = $objectLabel;
            }
            else
            {
                $action->objectLink = '';
            }
        }
        return $actions;
    }

    /**
     * Print changes of every action.
     * 
     * @param  string    $objectType 
     * @param  array     $histories 
     * @param  string    $action 
     * @access public
     * @return void
     */
    public function printChanges($objectType, $histories, $action)
    {
        if(empty($histories)) return;

        $maxLength            = 0;          // The max length of fields names.
        $historiesWithDiff    = array();    // To save histories without diff info.
        $historiesWithoutDiff = array();    // To save histories with diff info.

        /* Diff histories by hasing diff info or not. Thus we can to make sure the field with diff show at last. */
        foreach($histories as $history)
        {
            if($history->field == 'assignedTo')
            {
                $users = $this->loadModel('user')->getPairs();
                $history->old = $users[$history->old];
                $history->new = $users[$history->new];
            }

            $fieldName = $history->field;
            $history->fieldLabel = isset($this->lang->$objectType->$fieldName) ? $this->lang->$objectType->$fieldName : $fieldName;
            if(isset($this->config->action->actionModules[$action]))
            {
                $module = $this->config->action->actionModules[$action];
                $history->fieldLabel = isset($this->lang->$module->$fieldName) ? $this->lang->$module->$fieldName : $fieldName;
            }
            if($objectType == 'contact') $history->fieldLabel = isset($this->lang->contact->$fieldName) ? $this->lang->contact->$fieldName : (isset($this->lang->resume->$fieldName) ? $this->lang->resume->$fieldName : $fieldName);
            if(($length = strlen($history->fieldLabel)) > $maxLength) $maxLength = $length;
            $history->diff ? $historiesWithDiff[] = $history : $historiesWithoutDiff[] = $history;
        }
        $histories = array_merge($historiesWithoutDiff, $historiesWithDiff);

        foreach($histories as $history)
        {
            $history->fieldLabel = str_pad($history->fieldLabel, $maxLength, $this->lang->action->label->space);
            if($history->diff != '')
            {
                $history->diff      = str_replace(array('<ins>', '</ins>', '<del>', '</del>'), array('[ins]', '[/ins]', '[del]', '[/del]'), $history->diff);
                $history->diff      = ($history->field != 'subversion' and $history->field != 'git') ? htmlspecialchars($history->diff) : $history->diff;   // Keep the diff link.
                $history->diff      = str_replace(array('[ins]', '[/ins]', '[del]', '[/del]'), array('<ins>', '</ins>', '<del>', '</del>'), $history->diff);
                $history->diff      = nl2br($history->diff);
                $history->noTagDiff = preg_replace('/&lt;\/?([a-z][a-z0-9]*)[^\/]*\/?&gt;/Ui', '', $history->diff);
                printf($this->lang->action->desc->diff2, $history->fieldLabel, $history->noTagDiff, $history->diff);
            }
            else
            {
                printf($this->lang->action->desc->diff1, $history->fieldLabel, $history->old, $history->new);
            }
        }
    }

    /**
     * Undelete a record.
     * 
     * @param  int      $actionID 
     * @access public
     * @return void
     */
    public function undelete($actionID)
    {
        $action = $this->loadModel('action')->getById($actionID);
        if($action->action != 'deleted') return;

        /* Update deleted field in object table. */
        $table = $this->config->objectTables[$action->objectType];
        $this->dao->update($table)->set('deleted')->eq(0)->where('id')->eq($action->objectID)->exec();

        /* Update action record in action table. */
        $this->dao->update(TABLE_ACTION)->set('extra')->eq(ACTIONMODEL::BE_UNDELETED)->where('id')->eq($actionID)->exec();
        $this->action->create($action->objectType, $action->objectID, 'undeleted');
    }

    /**
     * Update an action.
     * 
     * @param  object    $action
     * @param  int       $actionID 
     * @access public
     * @return void
     */
    public function update($action, $actionID)
    {
        $this->dao->update(TABLE_ACTION)->data($action, $skip = 'referer')->autoCheck()->where('id')->eq($actionID)->exec();
        return !dao::isError();
    }

    /**
     * Update comment of a action.
     * 
     * @param  int    $actionID 
     * @access public
     * @return void
     */
    public function updateComment($actionID)
    {
        $this->dao->update(TABLE_ACTION)
            ->set('date')->eq(helper::now())
            ->set('comment')->eq($this->post->lastComment)
            ->where('id')->eq($actionID)
            ->exec();
    }

    /**
     * Hide an object. 
     * 
     * @param  int    $actionID 
     * @access public
     * @return void
     */
    public function hideOne($actionID)
    {
        $action = $this->getById($actionID);
        if($action->action != 'deleted') return;

        $this->dao->update(TABLE_ACTION)->set('extra')->eq(self::BE_HIDDEN)->where('id')->eq($actionID)->exec();
        $this->create($action->objectType, $action->objectID, 'hidden');
    }

    /**
     * Hide all deleted objects.
     * 
     * @access public
     * @return void
     */
    public function hideAll()
    {
        $this->dao->update(TABLE_ACTION)
            ->set('extra')->eq(self::BE_HIDDEN)
            ->where('action')->eq('deleted')
            ->andWhere('extra')->eq(self::CAN_UNDELETED)
            ->exec();
    }

    /**
     * update a action read status to read. 
     * 
     * @param  int    $actionID 
     * @param  string $type 
     * @access public
     * @return bool
     */
    public function read($actionID, $type = 'action')
    {
        /* Save read status to session if type isn't action. */
        if($type != 'action')
        {
            if(!isset($this->app->user->readNotices)) $this->app->user->readNotices = array();
            $this->app->user->readNotices[$actionID] = $actionID;
            return true;
        }

        /* Update action data. */
        $account = $this->app->user->account;
        $reader = $this->dao->select('reader')->from(TABLE_ACTION)->where('id')->eq($actionID)->fetch('reader');
        $readers = explode(',', trim($reader, ','));
        foreach($readers as $key => $value) if($value == $account or $value == '') unset($readers[$key]);

        $read = empty($readers) ? 1 : 0;
        $reader = empty($readers) ? '' : ',' . join(',', $readers) . ',';
        
        $this->dao->update(TABLE_ACTION)->set('read')->eq($read)->set('reader')->eq($reader)->where('id')->eq($actionID)->exec();
        return !dao::isError();
    }

    /**
     * Send notice to user. return failed user account.
     * 
     * @param  int    $actionID 
     * @param  string $reader 
     * @param  bool   $onlyNotice 
     * @access public
     * @return string
     */
    public function sendNotice($actionID, $reader, $onlyNotice = false)
    {
        $readers = is_array($reader) ? $reader : explode(',', trim($reader, ','));
        $failedReaders = array();

        foreach($readers as $key => $account) if($account == '' or $account == $this->app->user->account) unset($readers[$key]);
        foreach($readers as $key => $account) 
        {
            if(!$onlyNotice and !$this->loadModel('user')->isOnline($account))
            {
                unset($readers[$key]);
                $failedReaders[] = $account;
            }
        }

        if(!empty($readers)) 
        {
            $reader = ',' . join(',', $readers) . ',';
            $oldReader = $this->dao->select('reader')->from(TABLE_ACTION)->where('id')->eq($actionID)->fetch('reader');
            if(!empty($oldReader)) $reader .= $oldReader;
            $this->dao->update(TABLE_ACTION)->set('read')->eq(0)->set('reader')->eq($reader)->where('id')->eq($actionID)->exec();
        }

        return join(',', $failedReaders);
    }

    /**
     * Get unread notice for user.
     * 
     * @param  string $account 
     * @param  string $skipNotice 
     * @access public
     * @return array
     */
    public function getUnreadNotice($account = '', $skipNotice = '')
    {
        if($account == '') $account = $this->app->user->account;
        $users = $this->loadModel('user')->getPairs();

        $actions = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('`read`')->eq('0')
            ->andWhere('reader')->like("%,$account,%")
            ->beginIf($skipNotice != '')->andWhere('id')->notin($skipNotice)->fi()
            ->orderBy('id_desc')
            ->fetchAll('id');

        if(!empty($actions)) $actions = $this->transformActions($actions);

        /* Create action notices. */
        $notices = array();
        foreach($actions as $action)
        {
            $notice = new stdclass();
            $notice->id    = $action->id;
            $notice->title = sprintf($this->lang->action->noticeTitle, $action->objectLabel, $action->objectLink, $action->appName, $action->objectName);
            $notice->type  = 'success';
            $notice->read  = helper::createLink('action', 'read', "actionID={$notice->id}");

            /* process user and status. */
            if($action->objectType == 'leave')    $this->loadModel('leave', 'oa');
            if($action->objectType == 'overtime') $this->loadModel('overtime', 'oa');
            if($action->objectType == 'egress')   $this->loadModel('egress', 'oa');
            if($action->objectType == 'attend')   $this->loadModel('attend', 'oa');
            if($action->objectType == 'refund')   $this->loadModel('refund', 'oa');
            if(isset($users[$action->actor])) $action->actor = $users[$action->actor];
            if($action->action == 'assigned' and isset($users[$action->extra])) $action->extra = $users[$action->extra];
            if($action->action == 'reviewed' and isset($this->lang->{$action->objectType}->statusList[$action->extra])) $action->extra = $this->lang->{$action->objectType}->statusList[$action->extra];
            if($action->action == 'reviewed' and isset($this->lang->{$action->objectType}->reviewStatusList[$action->extra])) $action->extra = $this->lang->{$action->objectType}->reviewStatusList[$action->extra];

            /* Get contents. */
            ob_start();
            $this->printAction($action);
            $notice->content = ob_get_contents(); 
            ob_end_clean();

            $notices[$action->id] = $notice;
        }

        /* Create todo notices. */
        $interval = $this->config->pingInterval;
        $now      = helper::now();
        $link     = helper::createLink('sys.todo', 'calendar');
        $todos    = $this->loadModel('todo', 'sys')->getList('self', $account, date(DT_DATE1), 'undone');
        if($todos)
        {
            $begins[1]  = date('Hi', strtotime($now));
            $ends[1]    = date('Hi', strtotime("+$interval seconds $now"));
            $begins[10] = date('Hi', strtotime("+10 minute $now"));
            $ends[10]   = date('Hi', strtotime("+10 minute $interval seconds $now"));
            $begins[30] = date('Hi', strtotime("+30 minute $now"));
            $ends[30]   = date('Hi', strtotime("+30 minute $interval seconds $now"));
            foreach($todos as $todo)
            {
                if(empty($todo->begin)) continue;
                $time = str_replace(':', '', $todo->begin);

                $lastTime = 0;
                if((int)$time > (int)$begins[1]  and (int)$time <= (int)$ends[1])  $lastTime = 1;
                if((int)$time > (int)$begins[10] and (int)$time <= (int)$ends[10]) $lastTime = 10;
                if((int)$time > (int)$begins[30] and (int)$time <= (int)$ends[30]) $lastTime = 30;
                if($lastTime)
                {
                    $notice = new stdclass();
                    $notice->id      = 'todo' . $todo->id;
                    $notice->title   = sprintf($this->lang->action->noticeTitle, $this->lang->todo->common, $link, 'oa', "{$todo->begin} {$todo->name}");
                    $notice->content = ''; 
                    $notice->type    = 'success';
                    $notice->read    = '';

                    $notices[$notice->id] = $notice;
                }
            }
        }
        else
        {
            $this->app->loadModuleConfig('attend', 'oa');
            $signInLimit = date('Y-m-d ') . $this->config->attend->signInLimit;
            $begin = (int)date('Hi', strtotime("+30 minute $signInLimit"));
            $end   = (int)date('Hi', strtotime("+30 minute $interval seconds $signInLimit"));
            if((int)date('Hi') >= $begin and (int)date('Hi') <= $end)
            {
                $notice = new stdclass();
                $notice->id      = "emptyTodo";
                $notice->title   = sprintf($this->lang->action->noticeTitle, $this->lang->todo->common, $link, 'oa', "{$this->lang->todo->emptyTodo}");
                $notice->content = ''; 
                $notice->type    = 'success';
                $notice->read    = '';

                $notices[$notice->id] = $notice;
            }
        }

        /* Create past order notice. */
        $orders = $this->loadModel('order', 'crm')->getList('past');
        foreach($orders as $order)
        {
            /* Skip not assigned to me, read and showed notice. */
            if($order->assignedTo != $account) continue;
            if(isset($this->app->user->readNotices["order{$order->id}"])) continue;
            if(strpos(",$skipNotice,", ",order{$order->id},") !== false) continue;

            $link = helper::createLink('crm.order', 'view', "orderID=$order->id");
            $notice = new stdclass();
            $notice->id      = 'order' . $order->id;
            $notice->title   = sprintf($this->lang->action->noticeTitle, $this->lang->order->record . $this->lang->order->common, $link, 'crm', $order->title);
            $notice->content = ''; 
            $notice->type    = 'success';
            $notice->read    = helper::createLink('action', 'read', "actionID={$notice->id}&type=order");

            $notices[$notice->id] = $notice;
        }

        return $notices;
    }

    /**
     * Compute the begin date and end date of a period.
     * 
     * @param  string    $period   all|today|yesterday|twodaysago|latest2days|thisweek|lastweek|thismonth|lastmonth
     * @access public
     * @return array
     */
    public function computeBeginAndEnd($period)
    {
        $this->app->loadClass('date');

        $today      = date::today();
        $tomorrow   = date::tomorrow();
        $yesterday  = date::yesterday();
        $twoDaysAgo = date::twoDaysAgo();

        $period = strtolower($period);

        if($period == 'all')        return array('begin' => '1970-1-1',  'end' => '2109-1-1');
        if($period == 'today')      return array('begin' => $today,      'end' => $tomorrow);
        if($period == 'yesterday')  return array('begin' => $yesterday,  'end' => $today);
        if($period == 'twodaysago') return array('begin' => $twoDaysAgo, 'end' => $yesterday);
        if($period == 'latest3days')return array('begin' => $twoDaysAgo, 'end' => $tomorrow);

        /* If the period is by week, add the end time to the end date. */
        if($period == 'thisweek' or $period == 'lastweek')
        {
            $func = "get$period";
            extract(date::$func());
            return array('begin' => $begin, 'end' => $end . ' 23:59:59');
        }

        if($period == 'thismonth')  return date::getThisMonth();
        if($period == 'lastmonth')  return date::getLastMonth();
    }

    /**
     * Check privilege for action.
     * 
     * @param  object    $action 
     * @access public
     * @return bool
     */
    public function checkPriv($action)
    {
        $canView = true;

        if($action->customer)
        {
            static $customers = array();
            if(empty($customers)) $customers = $this->loadModel('customer')->getCustomersSawByMe();
            if(!in_array($action->customer, $customers)) $canView = false;
        }

        if($action->contact)
        {
            static $contacts = array();
            if(empty($contacts)) $contacts = $this->loadModel('contact', 'crm')->getContactsSawByMe();
            if(!in_array($action->contact, $contacts)) $canView = false;
        }

        if($action->objectType == 'order')
        {
            static $orders = array();
            if(empty($orders)) $orders = $this->loadModel('order', 'crm')->getOrdersSawByMe();
            if(!in_array($action->objectID, $orders)) $canView = false;
        }

        if($action->objectType == 'project' && !($this->loadModel('project', 'proj')->checkPriv($action->objectID))) $canView = false;

        if($action->objectType == 'task')
        {
            $task = $this->loadModel('task')->getByID($action->objectID);
            if(!($this->loadModel('task', 'sys')->checkPriv($task, 'view'))) $canView = false;
        }

        if($action->objectType == 'trade')
        {
            $trade = $this->loadModel('trade', 'cash')->getByID($action->objectID);
            $rights = $this->app->user->rights;
            if(empty($trade) or ($this->app->user->admin != 'super' and $trade->type == 'out' and (!isset($rights['tradebrowse']['out']) or !$this->loadModel('tree')->hasRight($trade->category)))) $canView = false;
        }

        if($action->objectType == 'todo')
        {
            $todo = $this->loadModel('todo')->getByID($action->objectID);
            if(empty($todo) or ($this->app->user->account != $todo->account && $this->app->user->account != $todo->assignedTo)) $canView = false;
        }

        if($action->objectType == 'doc')
        {
            $doc     = $this->loadModel('doc', 'doc')->getById($action->objectID);
            $canView = $this->doc->hasRight($doc);
        }

        if($action->objectType == 'doclib')
        {
            $lib     = $this->loadModel('doc', 'doc')->getLibById($action->objectID);
            $canView = $this->doc->hasRight($lib);
        }

        $objectType = $action->objectType;
        $actionType = $action->action;
        if(isset($this->lang->action->label->$objectType))
        {
            $objectLabel = $this->lang->action->label->$objectType;
            if(!is_array($objectLabel)) $action->objectLabel = $objectLabel;
            if(is_array($objectLabel) and isset($objectLabel[$actionType])) $action->objectLabel = $objectLabel[$actionType];

            if(strpos($action->objectLabel, '|') !== false)
            {
                list($objectLabel, $moduleName, $methodName, $vars) = explode('|', $action->objectLabel);
                $action->objectLabel = $objectLabel;
                if((!$this->loadModel('common')->isOpenMethod($moduleName, $methodName)) and (!commonModel::hasPriv($moduleName, $methodName))) $canView = false;
            }
        }

        if(!commonModel::hasAppPriv($action->appName)) $canView = false;

        return $canView;
    }
}
