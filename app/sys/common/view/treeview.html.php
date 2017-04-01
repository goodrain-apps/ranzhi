<?php
/**
 * The treeview view of common module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     common 
 * @version     $Id: treeview.html.php 4029 2016-08-26 06:50:41Z liugang $
 * @link        http://www.ranzhico.com
 */
css::import($jsRoot . 'jquery/treeview/min.css');
js::import($jsRoot . 'jquery/treeview/min.js');
?>
<script language='javascript'>$(function()
{
    $('.tree').each(function()
    {
        var $this = $(this);
        $this.treeview($.extend({collapsed: false, unique: false}, $this.data()));
    });
})</script>
