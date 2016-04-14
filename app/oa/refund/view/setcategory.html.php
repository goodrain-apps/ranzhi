<?php
/**
 * The set category view file of refund module of Ranzhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     refund
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='with-side'>
  <div class='side'>
    <nav class='menu leftmenu affix'>
      <ul class='nav nav-primary'>
        <li><?php commonModel::printLink('refund', 'settings', '', "{$lang->refund->settings}");?></li>
        <li><?php commonModel::printLink('refund', 'setcategory', '', "{$lang->refund->setCategory}");?></li>
      </ul>
    </nav>
  </div>
  <div class='main'>
    <div class='panel'>
      <div class='panel-heading'><?php echo $lang->refund->setCategory;?></div>
      <div class='panel-body'>
        <form id='ajaxForm' class='form-inline' method='post'>
          <table class='table table-form table-condensed'>
            <tr>
              <td>
                <?php if(empty($expenseList))
                { 
                    echo $lang->refund->categoryTips;
                } 
                else
                {
                    echo html::checkbox('refundCategories', $expenseList, $refundCategories);
                }
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <?php if(!empty($expenseList))
                { 
                    echo html::submitButton();
                } 
                commonModel::printLink('cash.tree', 'browse', 'type=out', $lang->refund->setExpense, "class='btn btn-primary setExpense'");
                ?>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
