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
class oablockModel extends blockModel
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
            $method = 'browse';
            if(!commonModel::hasPriv($key, $method)) unset($this->lang->block->availableBlocks->$key);
        }
        return json_encode($this->lang->block->availableBlocks);
    }

    /**
     * Get announce params.
     * 
     * @access public
     * @return string
     */
    public function getAnnounceParams()
    {
        $params = new stdclass();
        $params->num['name']        = $this->lang->block->num;
        $params->num['default']     = 15; 
        $params->num['control']     = 'input';

        return json_encode($params);
    }

    /**
     * Get attend params.
     * 
     * @access public
     * @return string
     */
    public function getAttendParams()
    {
        $params = new stdclass();
        return json_encode($params);
    }
}
