<?php
/**
 * The control file for block module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
class block extends control
{
    /**
     * Block Index Page.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $lang = $this->get->lang;
        $this->app->setClientLang($lang);
        $this->app->loadLang('block');

        $mode = strtolower($this->get->mode);
        if($mode == 'getblocklist')
        {   
            echo $this->block->getAvailableBlocks();
        }   
        elseif($mode == 'getblockform')
        {   
            $code = strtolower($this->get->blockid);
            $func = 'get' . ucfirst($code) . 'Params';
            echo $this->block->$func();
        }   
        elseif($mode == 'getblockdata')
        {   
            $code = strtolower($this->get->blockid);
            $func = 'print' . ucfirst($code) . 'Block';
            $this->$func();
        }
    }

    /**
     * Block Admin Page.
     * 
     * @param  int    $index 
     * @param  string $blockID 
     * @access public
     * @return void
     */
    public function admin($index = 0, $blockID = '')
    {
        $this->app->loadLang('block', 'sys');
        $title = $index == 0 ? $this->lang->block->createBlock : $this->lang->block->editBlock;

        if(!$index) $index = $this->block->getLastKey('oa') + 1;

        if($_POST)
        {
            $this->block->save($index, 'system', 'oa');
            if(dao::isError())  $this->send(array('result' => 'fail', 'message' => dao::geterror())); 
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->server->http_referer));
        }

        $block   = $this->block->getBlock($index, 'oa');
        $blockID = $blockID ? $blockID : ($block ? $block->block : '');

        $blocks = json_decode($this->block->getAvailableBlocks(), true);
        $this->view->blocks  = array_merge(array(''), $blocks);

        $this->view->title   = $title;
        $this->view->params  = $blockID ? json_decode($this->block->{'get' . ucfirst($blockID) . 'Params'}(), true) : array();;
        $this->view->blockID = $blockID;
        $this->view->block   = $block;
        $this->view->index   = $index;
        $this->display();
    }

    /**
     * Sort block. 
     * 
     * @param  string    $oldOrder 
     * @param  string    $newOrder 
     * @access public
     * @return void
     */
    public function sort($oldOrder, $newOrder)
    {
        $this->locate($this->createLink('sys.block', 'sort', "oldOrder=$oldOrder&newOrder=$newOrder&app=oa"));
    }

    /**
     * Delete block. 
     * 
     * @param  int    $index 
     * @access public
     * @return void
     */
    public function delete($index)
    {
        $this->locate($this->createLink('sys.block', 'delete', "index=$index&app=oa"));
    }

    /**
     * Print announce block.
     * 
     * @access public
     * @return void
     */
    public function printAnnounceBlock()
    {
        $this->lang->announce = new stdclass();
        $this->app->loadLang('announce', 'oa');
        $this->app->loadLang('article', 'sys');

        $this->processParams();

        $this->view->announces = $this->dao->select('*')->from(TABLE_ARTICLE)
            ->where('type')->eq('announce')
            ->orderBy('createdDate desc')
            ->limit($this->params->num)
            ->fetchAll('id');

        $this->view->users = $this->loadModel('user')->getPairs();
        $this->display();
    }

    /**
     * Print attend block. 
     * 
     * @access public
     * @return void
     */
    public function printAttendBlock()
    {
        $this->loadModel('attend', 'oa');
        $date     = date('Y-m-d');
        $dateTime = strtotime($date);
        if($this->config->attend->workingDays > 7)
        {
            $startDate = date('w', $dateTime) == 0 ? date('Y-m-d', $dateTime) : date('Y-m-d', strtotime("last Sunday $date"));
            $endDate   = date('Y-m-d', strtotime("next Saturday $startDate"));
        }
        else
        {
            $startDate = date('w', $dateTime) == 1 ? date('Y-m-d', $dateTime) : date('Y-m-d', strtotime("last Monday $date"));
            $endDate   = date('Y-m-d', strtotime("next Sunday $startDate"));
        }
        $attends = $this->attend->getByAccount($this->app->user->account, $startDate, $endDate);

        $dateLimit = array();
        $dateLimit['begin'] = $startDate;
        $dateLimit['end']   = $endDate;
        $todos = $this->loadModel('todo')->getList('self', $this->app->user->account, $dateLimit);

        /* Process todos. */
        $newTodos = array();
        foreach($todos as $todo)
        {
            if(!isset($newTodos[$todo->date])) $newTodos[$todo->date] = array();
            $time = date('H', strtotime("{$todo->date} {$todo->begin}")) > 12 ? 'PM' : 'AM';
            if(!isset($newTodos[$todo->date][$time])) $newTodos[$todo->date][$time] = array();
            $newTodos[$todo->date][$time][] = $todo;
        }

        $this->processParams();
        $this->view->attends   = $attends;
        $this->view->todos     = $newTodos;
        $this->view->date      = $date;
        $this->view->startDate = $startDate;
        $this->view->endDate   = $endDate;
        $this->display();
    }

    /**
     * Process params.
     * 
     * @access public
     * @return void
     */
    public function processParams()
    {
        $params = $this->get->param;
        $this->params = json_decode(base64_decode($params));

        $this->view->sso  = base64_decode($this->get->sso);
        $this->view->code = $this->get->blockid;
    }
}
