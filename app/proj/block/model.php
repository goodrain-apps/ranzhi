<?php
/**
 * The model file for block module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
if(strpos(realpath('./'), '/ext/') !== false) helper::import(realpath('../../../../sys/block/model.php'));
if(strpos(realpath('./'), '/ext/') === false) helper::import(realpath('../../sys/block/model.php'));
class projblockModel extends blockModel
{
    /**
     * Get block list.
     * 
     * @access public
     * @return string
     */
    public function getAvailableBlocks()
    {
        foreach($this->lang->block->availableBlocks as $key => $block)
        {
            if($key == 'attend') continue;
            $method = $key == 'project' ? 'index' : 'browse';
            if(!commonModel::hasPriv($key, $method)) unset($this->lang->block->availableBlocks->$key);
        }
        return json_encode($this->lang->block->availableBlocks);
    }

    /**
     * Get task params for created by me.
     * 
     * @access public
     * @return string
     */
    public function getMyCreatedTaskParams()
    {
        return $this->getTaskParams();
    }

    /**
     * Get task params for assigned to me.
     * 
     * @access public
     * @return string
     */
    public function getAssignedMeTaskParams()
    {
        return $this->getTaskParams();
    }

    /**
     * Get task params.
     * 
     * @access public
     * @return string
     */
    public function getTaskParams()
    {
        $this->app->loadLang('task', 'sys');

        $params = new stdclass();

        $params->type['name']    = $this->lang->block->type;
        $params->type['options'] = $this->lang->block->typeList->task;
        $params->type['control'] = 'select';

        $params->num['name']    = $this->lang->block->num;
        $params->num['default'] = 15; 
        $params->num['control'] = 'input';

        $params->orderBy['name']    = $this->lang->block->orderBy;
        $params->orderBy['default'] = 'id_desc';
        $params->orderBy['options'] = $this->lang->block->orderByList->task;
        $params->orderBy['control'] = 'select';

        $this->lang->task->statusList = array_merge(array('unfinished' => $this->lang->block->waitTask), $this->lang->task->statusList);

        $params->status['name']    = $this->lang->task->status;
        $params->status['options'] = $this->lang->task->statusList;
        $params->status['control'] = 'select';
        $params->status['attr']    = 'multiple';

        return json_encode($params);
    }

    /**
     * Get project params.
     * 
     * @access public
     * @return string
     */
    public function getProjectParams()
    {
        $params = new stdclass();

        $params->status['name']    = $this->lang->block->status;
        $params->status['options'] = $this->lang->block->statusList->project;
        $params->status['control'] = 'select';

        $params->num['name']    = $this->lang->block->num;
        $params->num['default'] = 15; 
        $params->num['control'] = 'input';

        $params->orderBy['name']    = $this->lang->block->orderBy;
        $params->orderBy['default'] = 'createdDate_desc';
        $params->orderBy['options'] = $this->lang->block->orderByList->project;
        $params->orderBy['control'] = 'select';

        return json_encode($params);
    }
}
