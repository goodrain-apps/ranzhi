<?php
/**
 * The create view file of refund module of Ranzhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     refund
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../../sys/common/view/datepicker.html.php';?>
<?php include '../../../sys/common/view/chosen.html.php';?>
<?php js::set('noResultsMatch', $lang->noResultsMatch);?>
<form id='ajaxForm' method='post' action="<?php echo $this->createLink('oa.refund', 'create')?>">
  <div class='panel'>
    <div class='panel-heading'>
      <?php echo $lang->refund->create;?>
    </div>
    <div class='panel-body'>
      <table class='table table-form w-p70'>
        <tr>
          <th class='w-100px'><?php echo $lang->refund->name?></th>
          <td class='w-400px'><?php echo html::input('name', '', "class='form-control'")?></td>
          <td></td>
        </tr>
        <?php if($categories):?>
        <tr>
          <th><?php echo $lang->refund->category?></th>
          <td><?php echo html::select('category', $categories, '', "class='form-control chosen'")?></td>
          <td></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->refund->money?></th>
          <td>
            <div class='input-group'>
              <div class='input-group-btn w-90px'><?php echo html::select('currency', $currencyList, '', "class='form-control'")?></div>
              <?php echo html::input('money', '', "class='form-control'")?>
              <div class='input-group-btn'><?php echo html::a("javascript:void(0)", "<i class='icon-double-angle-down'></i> " . $lang->refund->detail, "class='btn detail'")?></div>
            </div>
          </td>
          <td></td>
        </tr>
        <tr id='refund-date'>
          <th><?php echo $lang->refund->date?></th>
          <td><?php echo html::input('date', '', "class='form-control form-date'")?></td>
          <td></td>
        </tr>
        <tr id='refund-related'>
          <th><?php echo $lang->refund->related?></th>
          <td><?php echo html::select('related[]', $users, '', "class='form-control chosen' multiple")?></td>
          <td></td>
        </tr>
        <tr id='refund-detail' class='hidden'>
          <th><?php echo $lang->refund->detail?></th>
          <td colspan='2' id='detailBox'>
            <table class='table table-detail'>
              <tr>
                <td class='w-100px'><?php echo html::input('dateList[1]', '', "class='form-control form-date' placeholder='{$lang->refund->date}'")?></td>
                <?php if($categories):?>
                <td class='w-160px'><?php echo html::select('categoryList[1]', $categories, '', "class='form-control chosen' placeholder='{$lang->refund->category}'")?></td>
                <?php endif;?>
                <td class='w-100px'><?php echo html::input('moneyList[1]', '', "class='form-control' placeholder='{$lang->refund->money}'")?></td>
                <td class='w-200px'><?php echo html::select('relatedList[1][]', $users, '', "class='form-control chosen' multiple data-placeholder='{$lang->refund->related}'")?></td>
                <td><?php echo html::textarea('descList[1]', '', "class='form-control' style='height:32px;' placeholder='{$lang->refund->desc}'")?></td>
                <td class='w-70px'><i class='btn btn-mini icon-plus'></i>&nbsp;&nbsp;<i class='btn btn-mini icon-remove'></i></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->refund->desc?></th>
          <td colspan='2'><?php echo html::textarea('desc', '', "class='form-control'")?></td>
        </tr>
        <?php if(commonModel::hasPriv('file', 'uplaod')):?>
        <tr>
          <th><?php echo $lang->refund->files;?></th>
          <td colspan='2'><?php echo $this->fetch('file', 'buildForm')?></td>
        </tr>
        <?php endif;?>
        <tr><th></th><td colspan='2'><?php echo html::submitButton() . '&nbsp;&nbsp;' . html::backButton();?></td></tr>
      </table>
    </div>
  </div>
</form>
<script type='text/template' id='detailTpl'>
<tr>
  <td class='w-100px'><?php echo html::input('dateList[key]', '', "class='form-control form-date' placeholder='{$lang->refund->date}'")?></td>
  <td class='w-160px'><?php echo html::select('categoryList[key]', $categories, '', "class='form-control chosen' placeholder='{$lang->refund->category}'")?></td>
  <td class='w-100px'><?php echo html::input('moneyList[key]', '', "class='form-control' placeholder='{$lang->refund->money}'")?></td>
  <td class='w-200px'><?php echo html::select('relatedList[key][]', $users, '', "class='form-control chosen' multiple data-placeholder='{$lang->refund->related}'")?></td>
  <td><?php echo html::textarea('descList[key]', '', "class='form-control' style='height:32px;' placeholder='{$lang->refund->desc}'")?></td>
  <td class='w-70px'><i class='btn btn-mini icon-plus'></i>&nbsp;&nbsp;<i class='btn btn-mini icon-remove'></i></td>
</tr>
</script>
<?php js::set('key', 2)?>
<?php include '../../common/view/footer.html.php';?>
