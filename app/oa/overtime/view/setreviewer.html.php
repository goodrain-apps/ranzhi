<?php
/**
 * The set reviewer view file of overtime module of RanZhi.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     overtime 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../../sys/common/view/chosen.html.php';?>
<div class='panel'>
  <div class='panel-heading'><strong><?php echo $lang->overtime->setReviewer;?></strong></div>
  <div class='panel-body'>
    <form id='ajaxForm' method='post'>
      <table class='table table-form table-condensed w-p40'>
      <tr>
        <th class='w-100px'><?php echo $lang->overtime->reviewedBy;?></th>
        <td><?php echo html::select('reviewedBy', array('' => $this->lang->dept->moderators) + $users, $reviewedBy, "class='form-control chosen'")?></td><td></td>
      </tr>
      <tr><th></th><td colspan='2'><?php echo html::submitButton();?></td></tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
