<?php
/**
 * The control file of egress module of Ranzhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com> 
 * @package     egress
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
include '../trip/control.php';
class egress extends trip 
{
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('trip');
        $this->type = 'egress';
    }

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
     * personal's egress. 
     * 
     * @param  string $date 
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function personal($date = '', $orderBy = 'id_desc')
    {
        die($this->fetch('egress', 'browse', "mode=personal&date=$date&orderBy=$orderBy", 'oa'));
    }

    /**
     * Department's egress. 
     * 
     * @param  string $date 
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function department($date = '', $orderBy = 'id_desc')
    {
        die($this->fetch('egress', 'browse', "mode=department&date=$date&orderBy=$orderBy", 'oa'));
    }

    /**
     * Company's egress. 
     * 
     * @param  string $date 
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function company($date = '', $orderBy = 'id_desc')
    {
        die($this->fetch('egress', 'browse', "mode=company&date=$date&orderBy=$orderBy", 'oa'));
    }
}
