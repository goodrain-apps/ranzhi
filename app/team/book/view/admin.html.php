<?php
/**
 * The admin browse view file of book module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai<daitingting@xirangit.com>
 * @package     book
 * @version     $Id: admin.html.php 3138 2015-11-09 07:32:18Z chujilu $
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.admin.html.php';?>
<?php 
$path = explode(',', $node->path);
js::set('path', json_encode($path));
?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><i class='icon-book'></i> <?php echo $book->title;?></strong>
    <div class='panel-actions'>
      <?php echo html::a($this->inlink('create'), '<i class="icon-plus"></i> ' . $lang->book->createBook, "class='btn btn-primary'");?>
    </div>
  </div>
  <div class='panel-body'><div class='books'><?php echo $catalog;?></div></div>
</div>
<?php include '../../common/view/footer.html.php';?>
