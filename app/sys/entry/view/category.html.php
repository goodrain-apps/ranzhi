<?php
/**
 * The category view file of entry module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     entry 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo $lang->entry->category;?></strong>
  </div>
  <div class='panel-body'>
    <form id='ajaxForm' method='post'>
      <div class='col-xs-6 col-md-4 col-md-offset-1' id='categoryList'>
      <?php
        $maxOrder = 0;
        foreach($children as $child)
        {
            if($child->order > $maxOrder) $maxOrder = $child->order;
            echo "<div class='form-group'><div class='input-group'>";
            echo html::input("children[$child->id]", $child->name, "class='form-control'");
            echo "<span class='input-group-addon'>" . html::a(helper::createLink('tree', 'delete', "id={$child->id}"), "<i class='icon-remove'></i>", "class='deleter'") . "</span>";
            echo html::hidden("mode[$child->id]", 'update');
            echo "</div></div>";
        }

        for($i = 0; $i < 5 ; $i ++)
        {
            echo "<div class='form-group'><div class='input-group'>";
            echo html::input("children[]", '', "class='form-control' placeholder='{$lang->entry->category}'");
            echo html::hidden('mode[]', 'new');
            echo "</div></div>";
        }

        echo "<div class='form-group'>" . html::submitButton() . "</div>";
        echo html::hidden('parent',   0);
        echo html::hidden('maxOrder', $maxOrder);
      ?>
      </div>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
