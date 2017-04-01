<?php
/**
 * The edit view file of article module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     article
 * @version     $Id: edit.html.php 4029 2016-08-26 06:50:41Z liugang $
 * @link        http://www.ranzhico.com
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('type',$type);?>
<?php include '../../../sys/common/view/kindeditor.html.php';?>
<?php include '../../../sys/common/view/chosen.html.php';?>
<?php js::set('private', $article->private);?>
<div class='panel'>
  <div class='panel-heading'><strong><i class='icon-edit'></i> <?php echo $type == 'blog' ? $lang->blog->edit : ($type == 'page' ? $lang->page->edit : $lang->article->edit);?></strong></div>
  <div class='panel-body'>
  <form method='post' id='ajaxForm'>
    <table class='table table-form'>
      <tr>
        <th style='width: 100px'><?php echo $lang->article->category;?></th>
        <td style='width: 40%'>
        <?php 
        echo html::select("categories[]", $categories, array_keys($article->categories), "multiple='multiple' class='form-control chosen'");
        ?>
        </td>
        <td>
          <?php if($type == 'blog'):?>
          <label class='checkbox'><input type='checkbox' name='private' id='private' value='1' /><?php echo $lang->article->private;?></label>
          <?php endif;?>
        </td>
      </tr>
      <?php if($type == 'blog'):?>
      <tr id='userTR'>
        <th><?php echo $lang->category->users;?></th>
        <td colspan='2'><?php echo html::select('users[]', $users, $article->users, "class='form-control chosen' multiple");?></td>
      </tr>
      <tr id='groupTR'>
        <th><?php echo $lang->article->groups;?></th>
        <td colspan='2'><?php echo html::checkbox('groups', $groups, $article->groups);?></td>
      </tr>
      <?php endif;?>
      <tr>
        <th><?php echo $lang->article->title;?></th>
        <td colspan='2'><?php echo html::input('title', $article->title, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->article->keywords;?></th>
        <td colspan='2'><?php echo html::input('keywords', $article->keywords, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->article->content;?></th>
        <td colspan='2'><?php echo html::textarea('content', htmlspecialchars($article->content), "rows='20' class='form-control'");?></td>
      </tr>
      <?php if($type != 'blog'):?>
      <tr>
        <th><?php echo $lang->article->status;?></th>
        <td><?php echo html::select('status', $lang->article->statusList, $article->status, "class='form-control chosen'");?></td>
      </tr>
      <?php endif;?>
      <tr>
        <th></th><td colspan='2'><?php echo html::submitButton();?></td>
      </tr>
    </table>
  </form>
  </div>
</div>

<?php include '../../../sys/common/view/treeview.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
