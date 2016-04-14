<?php
/**
 * The model file of todo module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
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
            ->stripTags('desc', $this->config->allowedTags->front)
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
                    $todo->date    = $this->post->date;
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
                if(!empty($todo->assignedTo)) $this->loadModel('action')->create('todo', $todoID, 'assigned', '', $todo->assignedTo);
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
        return true;
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
        if($oldTodo->type != 'custom' and $oldTodo->type != 'undone') $oldTodo->name = '';
        $todo = fixer::input('post')
            ->remove('uid')
            ->cleanInt('date, pri, begin, end, private')
            ->setIF($this->post->type  != 'custom' and $this->post->type != 'undone', 'name', '')
            ->setIF($this->post->date  == false, 'date', '0000-00-00')
            ->setIF($this->post->begin == false, 'begin', '2400')
            ->setIF($this->post->end   == false, 'end', '2400')
            ->setDefault('private', 0)
            ->stripTags($this->config->todo->editor->edit['id'], $this->config->allowedTags->front)
            ->get();
        $this->dao->update(TABLE_TODO)->data($todo, $skip = 'comment')
            ->autoCheck()
            ->checkIF($todo->type == 'custom' or $todo->type == 'undone', $this->config->todo->require->edit, 'notempty')->where('id')->eq($todoID)
            ->exec();

        $todo->date = str_replace('-', '', $todo->date);
        if(!dao::isError()) return commonModel::createChanges($oldTodo, $todo);
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
            $order = $this->dao->select('c.name, o.createdDate')->from(TABLE_ORDER)->alias('o')->leftJoin(TABLE_CUSTOMER)->alias('c')->on('o.customer=c.id')->where('o.id')->eq($todo->idvalue)->fetch(); 
            $todo->name = $order->name . '|' . date('Y-m-d', strtotime($order->createdDate));
        }
        $todo->date = str_replace('-', '', $todo->date);
        return $todo;
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
            ->beginIF($mode == 'self')->andWhere()->markLeft()->where('account')->eq($account)->orWhere('assignedTo')->eq($account)->orWhere('finishedBy')->eq($account)->markRight()->fi()
            ->beginIF($mode == 'assignedtoother')->andWhere('account')->eq($account)->andWhere('assignedTo')->ne($account)->andWhere('assignedTo')->ne('')->fi()
            ->beginIF($mode == 'assignedtome')->andWhere('account')->ne($account)->andWhere('assignedTo')->eq($account)->fi()
            ->andWhere("date >= '$begin'")
            ->andWhere("date <= '$end'")
            ->beginIF(strpos('all, undone, unclosed,custom', $status) === false)->andWhere('status')->in($status)->fi()
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
                $order = $this->dao->select('c.name, o.createdDate')->from(TABLE_ORDER)->alias('o')->leftJoin(TABLE_CUSTOMER)->alias('c')->on('o.customer=c.id')->where('o.id')->eq($todo->idvalue)->fetch(); 
                $todo->name = $order->name . '|' . date('Y-m-d', strtotime($order->createdDate));
            }

            $todo->begin = date::formatTime($todo->begin);
            $todo->end   = date::formatTime($todo->end);

            /* If is private, change the title to private. */
            if($todo->private and $this->app->user->account != $todo->account) $todo->name = $this->lang->todo->thisIsPrivate;
            $todos[] = $todo;
        }
        return $todos;
    }

    /**
     * getCalendarData 
     * 
     * @access public
     * @return object
     */
    public function getCalendarData($date)
    {
        $dateLimit = array();
        $dateLimit['begin'] = date('Y-m-01', strtotime($date));
        $dateLimit['end']   = date('Y-m-d', strtotime("{$dateLimit['begin']} +1 month"));

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
            $data->color = $this->config->todo->calendarColor[$key];
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
        if($action == 'activate') return $todo->status == 'closed';

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
    public function buildBoardList($items, $type)
    {
        $div = '';
        $index = 1;
        foreach($items as $id => $name)
        {
            $div .= "<div class='board-item text-nowrap text-ellipsis' title='$name' data-id='$id' data-type='$type' data-action='create' data-index='$index' data-toggle='droppable' data-target='.day'>\r\n";
            $div .= "$name\r\n";
            $div .= "</div>\r\n";
            $index += 1;
        }
        return $div;
    }
}
