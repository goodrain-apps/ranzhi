<?php
/**
 * The model file of todo module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: model.php 5035 2013-07-06 05:21:58Z wyd621@gmail.com $
 * @link        http://www.ranzhico.com
 */
?>
<?php
class todoModel extends model
{
    /**
     * Create a todo.
     * 
     * @param  date   $date 
     * @param  string $account 
     * @access public
     * @return void
     */
    public function create($date, $account)
    {
        $todo = fixer::input('post')
            ->add('account', $account)
            ->add('idvalue', 0)
            ->cleanInt('date, pri, begin, end, private')
            ->setIF($this->post->type != 'custom' and $this->post->idvalue, 'idvalue', $this->post->idvalue)
            ->setIF($this->post->date == false,  'date', '0000-00-00')
            ->setIF($this->post->begin == false, 'begin', '2400')
            ->setIF($this->post->end   == false, 'end',   '2400')
            ->setDefault('pri', '3')
            ->setDefault('status', 'wait')
            ->stripTags('desc', $this->config->allowedTags)
            ->remove('uid')
            ->get();
        $this->dao->insert(TABLE_TODO)->data($todo)
            ->autoCheck()
            ->checkIF($todo->type == 'custom', $this->config->todo->require->create, 'notempty')
            ->checkIF($todo->type != 'custom' and $todo->idvalue == 0, 'idvalue', 'notempty')
            ->exec();
        return $this->dao->lastInsertID();
    }

    /**
     * Create batch todo
     * 
     * @access public
     * @return void
     */
    public function batchCreate()
    {
        $actionList = array();
        $todos = fixer::input('post')->cleanInt('date')->get();
        for($i = 0; $i < $this->config->todo->batchCreate; $i++)
        {
            if($todos->names[$i] != '' || isset($todos->idvalues[$i + 1]))
            {
                $todo          = new stdclass();
                $todo->account = $this->app->user->account;
                if($this->post->date == false)
                {
                    $todo->date = '0000-00-00';
                }
                else
                {
                    $todo->date = $this->post->date;
                }
                $todo->type       = $todos->types[$i];
                $todo->pri        = $todos->pris[$i];
                $todo->assignedTo = $todos->assignedTo[$i];
                $todo->name       = isset($todos->names[$i]) ? $todos->names[$i] : '';
                $todo->desc       = $todos->descs[$i];
                $todo->begin      = isset($todos->begins[$i]) ? $todos->begins[$i] : 2400;
                $todo->end        = isset($todos->ends[$i]) ? $todos->ends[$i] : 2400;
                $todo->status     = "wait";
                $todo->private    = 0;
                $todo->idvalue    = isset($todos->idvalues[$i + 1]) ? $todos->idvalues[$i + 1] : '0';
                if(!empty($todo->assignedTo))
                {
                    $todo->assignedBy   = $todo->account;
                    $todo->assignedDate = helper::now();
                }

                $this->dao->insert(TABLE_TODO)->data($todo)->autoCheck()->exec();
                if(dao::isError()) return false;

                $todoID = $this->dao->lastInsertID();
                $this->loadModel('action')->create('todo', $todoID, 'created');
                if(!empty($todo->assignedTo)) 
                {
                    $actionID = $this->loadModel('action')->create('todo', $todoID, 'assigned', '', $todo->assignedTo);
                    $actionList[$todoID] = $actionID;
                }
            }
            else
            {
                unset($todos->types[$i]);
                unset($todos->pris[$i]);
                unset($todos->names[$i]);
                unset($todos->descs[$i]);
                unset($todos->begins[$i]);
                unset($todos->ends[$i]);
            }
        }
        return $actionList;
    }

    /**
     * update a todo.
     * 
     * @param  int    $todoID 
     * @access public
     * @return void
     */
    public function update($todoID)
    {
        $oldTodo = $this->getById($todoID);
        $todo = fixer::input('post')
            ->remove('uid')
            ->cleanInt('date, pri, begin, end, private')
            ->setIF($this->post->type  != 'custom', 'name', '')
            ->setIF($this->post->date  == false, 'date', '0000-00-00')
            ->setIF($this->post->begin == false, 'begin', '2400')
            ->setIF($this->post->end   == false, 'end', '2400')
            ->setDefault('private', 0)
            ->stripTags($this->config->todo->editor->edit['id'], $this->config->allowedTags)
            ->get();
        if($oldTodo->type != 'custom') $todo->name = $oldTodo->name;
        $this->dao->update(TABLE_TODO)->data($todo, $skip = 'comment')
            ->autoCheck()
            ->checkIF($todo->type == 'custom', $this->config->todo->require->edit, 'notempty')->where('id')->eq($todoID)
            ->exec();

        $todo->date = str_replace('-', '', $todo->date);
        if(!dao::isError()) return commonModel::createChanges($oldTodo, $todo);
    }

    /**
     * Batch update todos. 
     * 
     * @access public
     * @return bool
     */
    public function batchUpdate()
    {
        $this->loadModel('action');
        foreach($this->post->names as $id => $name)
        {
            if(empty($name) && empty($this->post->idvalues[$id])) continue;

            $oldTodo = $this->getById($id);

            $todo = new stdclass();
            $todo->name       = $name;
            $todo->type       = $this->post->types[$id];
            $todo->pri        = $this->post->pris[$id];
            $todo->assignedTo = $this->post->assignedTo[$id];
            $todo->desc       = $this->post->descs[$id];
            $todo->date       = isset($this->post->dates[$id])    ? $this->post->dates[$id]    : '0000-00-00';
            $todo->begin      = isset($this->post->begins[$id])   ? $this->post->begins[$id]   : 2400;
            $todo->end        = isset($this->post->ends[$id])     ? $this->post->ends[$id]     : 2400;
            $todo->idvalue    = isset($this->post->idvalues[$id]) ? $this->post->idvalues[$id] : $oldTodo->idvalue;

            $this->dao->update(TABLE_TODO)->data($todo)
                ->autoCheck()
                ->checkIF($todo->type == 'custom', $this->config->todo->require->edit, 'notempty')
                ->where('id')->eq($id)
                ->exec();

            if(dao::isError()) return false;

            $todo->date = str_replace('-', '', $todo->date);

            $changes = commonModel::createChanges($oldTodo, $todo);

            if($changes)
            { 
                $actionID = $this->action->create('todo', $id, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
        }

        return !dao::isError();
    }

    /**
     * Change the status of a todo.
     * 
     * @param  string $todoID 
     * @access public
     * @return bool
     */
    public function close($todoID)
    {
        $this->dao->update(TABLE_TODO)
            ->set('status')->eq('closed')
            ->set('closedBy')->eq($this->app->user->account)
            ->set('closedDate')->eq(helper::now())
            ->where('id')->eq((int)$todoID)
            ->exec();
        $this->loadModel('action')->create('todo', $todoID, 'closed', '', 'closed');
        return !dao::isError();
    }

    /**
     * Change the status of a todo.
     * 
     * @param  string $todoID 
     * @access public
     * @return bool
     */
    public function activate($todoID)
    {
        $this->dao->update(TABLE_TODO)
            ->set('status')->eq('wait')
            ->where('id')->eq((int)$todoID)
            ->exec();
        $this->loadModel('action')->create('todo', $todoID, 'activated', '', 'closed');
        return true;
    }

    /**
     * Change the status of a todo.
     * 
     * @param  string $todoID 
     * @param  string $status 
     * @access public
     * @return bool
     */
    public function finish($todoID)
    {
        $todo = $this->getById($todoID);

        $data = new stdclass();
        $data->status       = 'done';
        $data->finishedBy   = $this->app->user->account;
        $data->finishedDate = helper::now();
        if($todo->date == '00000000') $data->date = helper::today();

        $this->dao->update(TABLE_TODO)->data($data)->where('id')->eq((int)$todoID)->exec();

        if(!dao::isError()) $this->loadModel('action')->create('todo', $todoID, 'finished', '', 'done');

        return dao::isError();
    }

    /**
     * Assign to. 
     * 
     * @param  int    $todoID 
     * @access public
     * @return array|bool
     */
    public function assignTo($todoID)
    {
        $oldTodo = $this->getById($todoID);
        $todo = fixer::input('post')
            ->remove('account')
            ->add('assignedBy', $this->app->user->account)
            ->add('assignedDate', helper::now())
            ->get();

        $this->dao->update(TABLE_TODO)->data($todo)
            ->autoCheck()
            ->where('id')->eq($todoID)
            ->exec();

        if(!dao::isError()) return commonModel::createChanges($oldTodo, $todo);
        return false;
    }

    /**
     * Get info of a todo.
     * 
     * @param  int    $todoID 
     * @access public
     * @return object|bool
     */
    public function getById($todoID)
    {
        $todo = $this->dao->findById((int)$todoID)->from(TABLE_TODO)->fetch();
        if(!$todo) return false;
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

        $zentaoEntryList = $this->dao->select('*')->from(TABLE_ENTRY)->where('zentao')->eq(1)->fetchAll();
        foreach($zentaoEntryList as $zentaoEntry)
        {
            if(strpos($todo->type, $zentaoEntry->code . '_') !== false)
            {
                if(empty($zentaoTodoList)) $zentaoTodoList = $this->loadModel('sso')->getZentaoTodoList($zentaoEntry->code, $this->app->user->account);
                $type = substr($todo->type, strpos($todo->type, '_') + 1);
                $todo->name = isset($zentaoTodoList[$type][$todo->idvalue]) ? $zentaoTodoList[$type][$todo->idvalue] : '';
            }
        }
        $todo->date = str_replace('-', '', $todo->date);
        return $todo;
    }

    /**
     * Get todos by id list. 
     * 
     * @param  int    $todoIDList 
     * @access public
     * @return array 
     */
    public function getByIdList($todoIDList)
    {
        $todos     = $this->dao->select('*')->from(TABLE_TODO)->where('id')->in($todoIDList)->fetchAll('id');
        $tasks     = $this->dao->select('id, name')->from(TABLE_TASK)->fetchPairs();
        $customers = $this->loadModel('customer')->getPairs();
        $orders    = $this->dao->select('o.id, o.createdDate, c.name')
            ->from(TABLE_ORDER)->alias('o')
            ->leftJoin(TABLE_CUSTOMER)->alias('c')->on('o.customer=c.id')
            ->fetchAll('id');
        $zentaoEntryList = $this->dao->select('*')->from(TABLE_ENTRY)->where('zentao')->eq(1)->fetchAll();

        $this->loadModel('sso');
        foreach($todos as $todo)
        {
            if($todo->type == 'task') $todo->name = zget($tasks, $todo->idvalue); 
            if($todo->type == 'customer') $todo->name = zget($customers, $todo->idvalue); 
            if($todo->type == 'order') 
            {
                $order = zget($orders, $todo->idvalue, ''); 
                if($order) $todo->name = $order->name . '|' . date('Y-m-d', strtotime($order->createdDate));
            }

            foreach($zentaoEntryList as $zentaoEntry)
            {
                if(strpos($todo->type, $zentaoEntry->code . '_') !== false)
                {
                    if(empty($zentaoTodoList)) $zentaoTodoList = $this->sso->getZentaoTodoList($zentaoEntry->code, $this->app->user->account);
                    $type = substr($todo->type, strpos($todo->type, '_') + 1);
                    $todo->name = $zentaoTodoList[$type][$todo->idvalue];
                }
            }
        }

        return $todos;
    }

    /**
     * Get todo list of a user.
     * 
     * @param  string $mode     all|self|assignedToOther|assignedToMe
     * @param  string $account 
     * @param  date   $date     all|today|thisweek|lastweek|before, or a date. 
     * @param  string $status   
     * @param  string $orderBy   
     * @param  object $pager   
     * @access public
     * @return void
     */
    public function getList($mode = 'self', $account = '', $date = 'all',  $status = 'all', $orderBy="date, status, begin", $pager = null)
    {
        if($account == '') $account = $this->app->user->account;
        $mode = strtolower($mode);
        $this->app->loadClass('date');
        $todos = array();
        if(!is_array($date)) $date = strtolower($date);

        if(is_array($date))
        {
            $begin = strtolower($date['begin']);
            $end   = strtolower($date['end']);
        }
        elseif($date == 'today') 
        {
            $begin = date::today();
            $end   = $begin;
        }
        elseif($date == 'yesterday') 
        {
            $begin = date::yesterday();
            $end   = $begin;
        }
        elseif($date == 'thisweek')
        {
            extract(date::getThisWeek());
        }
        elseif($date == 'lastweek')
        {
            extract(date::getLastWeek());
        }
        elseif($date == 'thismonth')
        {
            extract(date::getThisMonth());
        }
        elseif($date == 'lastmonth')
        {
            extract(date::getLastMonth());
        }
        elseif($date == 'thisseason')
        {
            extract(date::getThisSeason());
        }
        elseif($date == 'thisyear')
        {
            extract(date::getThisYear());
        }
        elseif($date == 'future')
        {
            $begin = '0000-00-00';
            $end   = '1970-01-01';
        }
        elseif($date == 'all')
        {
            $begin = '1970-01-01';
            $end   = '2109-01-01';
        }
        elseif($date == 'before')
        {
            $begin = '1970-01-01';
            $end   = date::yesterday();
        }
        else
        {
            $begin = $end = $date;
        }

        $stmt = $this->dao->select('*')->from(TABLE_TODO)
            ->where('1=1')
            ->beginIF($mode == 'self')->andWhere('account', true)->eq($account)->orWhere('assignedTo')->eq($account)->orWhere('finishedBy')->eq($account)->markRight(1)->fi()
            ->beginIF($mode == 'assignedtoother')->andWhere('account')->eq($account)->andWhere('assignedTo')->ne($account)->andWhere('assignedTo')->ne('')->fi()
            ->beginIF($mode == 'assignedtome')->andWhere('assignedTo')->eq($account)->fi()
            ->andWhere("date >= '$begin'")
            ->andWhere("date <= '$end'")
            ->beginIF(strpos('all,undone,unclosed,custom', $status) === false)->andWhere('status')->in($status)->fi()
            ->beginIF($status == 'undone')->andWhere('status')->notin('done,closed')->fi()
            ->beginIF($status == 'custom')->andWhere('status')->notin('done,closed')->fi()
            ->beginIF($status == 'unclosed')->andWhere('status')->ne('closed')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->query();
        
        /* Set session. */
        $sql = explode('WHERE', $this->dao->get());
        $sql = explode('ORDER', $sql[1]);
        $this->session->set('todoReportCondition', $sql[0]);

        while($todo = $stmt->fetch())
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

            $todo->begin = date::formatTime($todo->begin);
            $todo->end   = date::formatTime($todo->end);

            /* If is private, change the title to private. */
            if($todo->private and $this->app->user->account != $todo->account) $todo->name = $this->lang->todo->thisIsPrivate;
            $todos[] = $todo;
        }

        $zentaoEntryList = $this->dao->select('*')->from(TABLE_ENTRY)->where('zentao')->eq(1)->fetchAll();
        foreach($zentaoEntryList as $zentaoEntry)
        {
            $this->lang->todo->typeList["{$zentaoEntry->code}_task"] = $zentaoEntry->name . $this->lang->todo->task;
            $this->lang->todo->typeList["{$zentaoEntry->code}_bug"]  = $zentaoEntry->name . $this->lang->todo->bug;

            static $zentaoTodoList = array();
            $this->loadModel('sso');
            foreach($todos as $todo)
            {
                if(strpos($todo->type, $zentaoEntry->code . '_') !== false)
                {
                    if(empty($zentaoTodoList)) $zentaoTodoList = $this->sso->getZentaoTodoList($zentaoEntry->code, $this->app->user->account);
                    $type = substr($todo->type, strpos($todo->type, '_') + 1);
                    $todo->name = !empty($todo->name) ? $todo->name : (isset($zentaoTodoList[$type][$todo->idvalue]) ? $zentaoTodoList[$type][$todo->idvalue] : $this->lang->todo->typeList["{$todo->type}"] . $todo->idvalue);
                }
            }
        }

        return $todos;
    }

    /**
     * getCalendarData 
     * 
     * @param  string $date
     * @access public
     * @return object
     */
    public function getCalendarData($date)
    {
        $begin     = date('Y-m-01', strtotime($date));
        $weekday   = date('w', strtotime($begin));
        $beginDays = $weekday == 0 ? 6 : $weekday - 1;
        $begin     = date('Y-m-d', strtotime("$begin -$beginDays days"));
        /* Make sure the calendar will display 6 rows.*/
        $end       = date('Y-m-d', strtotime("$begin +42 days"));

        $dateLimit = array();
        $dateLimit['begin'] = $begin;
        $dateLimit['end']   = $end;

        $calendars    = $this->lang->todo->typeList;
        $todos        = $this->getList('self', $this->app->user->account, $dateLimit);
        $calendarList = array();
        $todoList     = array();

        foreach($calendars as $key => $calendar)
        {
            $data = new stdclass();
            $data->name  = $key;
            $data->title = $calendar;
            $data->desc  = $calendar;
            $data->color = zget($this->config->todo->calendarColor, $key, $this->config->todo->calendarColor['custom']);
            $calendarList[] = $data;
        }

        foreach($todos as $todo)
        {
            $data = new stdclass();
            $data->id       = $todo->id;
            $data->title    = $todo->name;
            $data->desc     = $todo->name;
            $data->allDay   = ($todo->begin == '' and $todo->end == '');
            $data->start    = strtotime($todo->date . ' ' . $todo->begin) * 1000;
            $data->end      = strtotime($todo->date . ' ' . $todo->end) * 1000;
            $data->calendar = $todo->type;

            $data->data = new stdclass();
            $data->data->account    = $todo->account;
            $data->data->status     = $todo->status;
            $data->data->assignedTo = $todo->assignedTo;
            $data->data->assignedBy = $todo->assignedBy;

            $data->click = new stdclass();
            $data->click->title  = $this->lang->todo->view;
            $data->click->width  = '70%';
            $data->click->remote = helper::createLink('todo', 'view', "id=$todo->id", 'html');
            $todoList[] = $data;
        }


        $data = new stdclass();
        $data->calendars = $calendarList;
        $data->events    = $todoList;
        return $data;
    }

    /**
     * Judge an action is clickable or not.
     * 
     * @param  object    $todo 
     * @param  string    $action 
     * @access public
     * @return bool
     */
    public static function isClickable($todo, $action)
    {
        $action = strtolower($action);

        if($action == 'finish') return strpos('done,closed', $todo->status) === false;
        if($action == 'close') return $todo->status == 'done';
        if($action == 'activate') return strpos('done,closed', $todo->status) !== false;

        return true;
    }

    /**
     * Check privilage of todo. 
     * 
     * @param  object $task 
     * @param  string $action 
     * @access public
     * @return bool
     */
    public function checkPriv($todo, $action)
    {
        $account = $this->app->user->account;
        $action  = strtolower($action);
        if(strpos('view,edit,assignto,close,activate,finish,delete', $action) !== false)
        {
            if($todo->private == 0 or $todo->account == $account or $todo->assignedTo == $account) return true;
        }
        return false;
    }

    /**
     * build board list. 
     * 
     * @param  array  $items 
     * @access public
     * @return string
     */
    public function buildBoardList($items, $type, $index = 0)
    {
        $div = '';
        $index = $index ? $index + 1 : 1;
        foreach($items as $id => $name)
        {
            $div .= "<div class='board-item text-nowrap text-ellipsis' title='$name' data-name='$name' data-id='$id' data-type='$type' data-action='create' data-index='$index' data-toggle='droppable' data-target='.day'>\r\n";
            $div .= "$name\r\n";
            $div .= "</div>\r\n";
            $index += 1;
        }
        return $div;
    }
}
