<?php
/**
 * The view file of browse function of announce module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Xiying Guan <guanxiying@xirangit.com> 
 * @package     announce 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<?php js::set('mode', $mode)?>
<li id='bysearchTab'><?php echo html::a('#', "<i class='icon-search icon'></i>" . $lang->search->common)?></li>
<div id='menuActions'>
  <?php commonModel::printLink('announce', 'create', '', "<i class='icon-plus'></i> " . $lang->announce->create, "class='btn btn-primary'");?>
</div>
<div id='mainContent'>
  <div class='panel list list-condensed'>
    <section class='items items-hover'>
      <?php foreach($announces as $announce):?>
      <div class='item'>
        <div class='item-heading'>
          <div class='text-muted pull-right'>
            <span title="<?php echo zget($users, $announce->author);?>"><i class='icon-user'></i> <?php echo $users[$announce->author];?></span> &nbsp; 
            <span title="<?php echo $lang->announce->createdDate;?>"><i class='icon-time'></i> <?php echo substr($announce->createdDate, 0, 10);?></span>&nbsp; 
            <span data-toggle='tooltip' data-placement='top' data-original-title='<?php printf($lang->article->lblViews, $announce->views);?>'><i class='icon-eye-open icon-large'></i> <?php echo $announce->views;?></span>
          </div>
          <h4><span class='label label-primary'><?php echo $categories[$announce->category];?></span> <?php echo html::a(inlink('view', "announceID={$announce->id}"), $announce->title, "class='nounderline' data-toggle='modal'");?></h4>
        </div>
        <div class='item-content'>
          <div class='text'><?php echo $announce->content;?></div>
          <div class='pull-left'>
            <?php echo html::a(inlink('viewReaders', "announceID={$announce->id}"), sprintf($lang->article->lblReaders, count($announce->readers)), "class='nounderline' data-toggle='modal'");?>
          </div>
          <div class='text pull-right'>
            <?php echo html::a(inlink('view', "announceID={$announce->id}"), $lang->view, "data-toggle='modal'");?>
            <?php commonModel::printLink('announce', 'edit', "announceID={$announce->id}", $lang->edit);?>
            <?php commonModel::printLink('announce', 'delete', "announceID={$announce->id}", $lang->delete, "class='deleter'");?>
          </div>
        </div>
      </div>
      <?php endforeach;?>
    </section>
    <footer class='clearfix'><?php $pager->show('right', 'short');?></footer>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
