<?php
/**
 * The view file of blog view method of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     blog
 * @version     $Id: view.html.php 4029 2016-08-26 06:50:41Z liugang $
 * @link        http://www.ranzhico.com
 */
?>
<?php include './header.html.php'; ?>
<?php $root = '<li>' . $this->lang->currentPos . $this->lang->colon .  html::a($this->inlink('index'), $lang->home) . '</li>'; ?>
<?php js::set('articleID', $article->id);?>
<?php js::set('categoryID', $category->id);?>
<div class='col-md-10'>
  <div class='article'>
    <header>
      <h1><?php echo $article->title;?></h1>
      <dl class='dl-inline'>
        <dd data-toggle='tooltip' data-placement='top' data-original-title='<?php printf($lang->article->lblAddedDate, $article->createdDate);?>'><i class="icon-time icon-large"></i> <?php echo $article->createdDate;?></dd>
        <dd data-toggle='tooltip' data-placement='top' data-original-title='<?php printf($lang->article->lblAuthor, $users[$article->author]);?>'><i class='icon-user icon-large'></i> <?php echo $users[$article->author]; ?></dd>
        <dd class='pull-right'>
          <span class='label label-warning' data-toggle='tooltip' data-placement='top' data-original-title='<?php printf($lang->article->lblViews, $article->views);?>'><i class='icon-eye-open'></i> <?php echo $article->views; ?></span>
        </dd>
      </dl>
      <?php if($article->summary):?>
      <section class='abstract'><strong><?php echo $lang->article->summary;?></strong><?php echo $lang->colon . $article->summary;?></section>
      <?php endif; ?>
    </header>
    <section class='article-content'>
      <?php echo $article->content;?>
    </section>
    <footer>
      <?php if($article->keywords):?>
      <p class='small'><strong class='text-muted'><?php echo $lang->article->keywords;?></strong><span class='article-keywords'><?php echo $lang->colon . $article->keywords;?></span></p>
      <?php endif; ?>
      <?php extract($prevAndNext);?>
      <ul class='pager pager-justify'>
        <?php if($prev): ?>
        <li class='previous'><?php echo html::a(inlink('view', "id=$prev->id"), '<i class="icon-arrow-left"></i> ' . $prev->title, 'id="pre"'); ?></li>
        <?php else: ?>
        <li class='preious disabled'><a href='###'><i class='icon-arrow-left'></i> <?php print($lang->article->none); ?></a></li>
        <?php endif; ?>
        <?php if($next):?>
        <li class='next'><?php echo html::a(inlink('view', "id=$next->id"), $next->title . ' <i class="icon-arrow-right"></i>', 'id="next"'); ?></li>
        <?php else:?>
        <li class='next disabled'><a href='###'> <?php print($lang->article->none); ?><i class='icon-arrow-right'></i></a></li>
        <?php endif; ?>
      </ul>
    </footer>
  </div>
  <div id='commentBox'><?php echo $this->fetch('message', 'comment', "objectType=blog&objectID={$article->id}");?></div>
  <?php echo html::a('', '', "name='comment'");?>
</div>
<?php include './footer.html.php';?>
