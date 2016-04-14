<?php
/**
 * The config file of entry module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entry 
 * @version     $Id: config.php 3205 2015-11-23 06:27:38Z daitingting $
 * @link        http://www.ranzhico.com
 */
$config->entry = new stdclass();
$config->entry->require = new stdclass();
$config->entry->require->create = 'name,code,open,key,login';
$config->entry->require->edit   = 'name,login';
