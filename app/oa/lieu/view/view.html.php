<?php
/**
 * The view view file of lieu module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     lieu
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../../sys/common/view/header.modal.html.php';?>
<div class='panel-body'>
  <table class='table table-borderless'>
    <tr>
      <th class='text-right w-100px'><?php echo $lang->lieu->status;?></th>
      <td class='lieu-<?php echo $lieu->status;?>'><?php echo zget($lang->lieu->statusList, $lieu->status);?></td>
    </tr>
    <tr>
      <th class='text-right'><?php echo $lang->lieu->begin;?></th>
      <td><?php echo $lieu->begin . ' ' . $lieu->start;?></td>
    </tr>
    <tr>
      <th class='text-right'><?php echo $lang->lieu->end;?></th>
      <td><?php echo $lieu->end . ' ' . $lieu->finish;?></td>
    </tr>
    <tr>
      <th class='text-right' rowspan='<?php echo count($lieu->overtimeList) + 1;?>'><?php echo $lang->lieu->overtime;?></th>
    </tr>
    <?php foreach($lieu->overtimeList as $overtime):?>
    <?php if(!$overtime) continue;?>
    <tr>
      <td><?php echo zget($overtimePairs, $overtime);?></td>
    </tr>
    <?php endforeach;?>
    </tr>
    <tr>
      <th class='text-right'><?php echo $lang->lieu->createdBy;?></th>
      <td><?php echo zget($users, $lieu->createdBy);?></td>
    </tr>
    <tr>
      <th class='text-right'><?php echo $lang->lieu->createdDate;?></th>
      <td><?php echo $lieu->createdDate;?></td>
    </tr>
    <tr>
      <th class='text-right'><?php echo $lang->lieu->reviewedBy;?></th>
      <td><?php echo zget($users, $lieu->reviewedBy);?></td>
    </tr>
    <tr>
      <th class='text-right'><?php echo $lang->lieu->reviewedDate;?></th>
      <td><?php echo $lieu->reviewedDate;?></td>
    </tr>
  </table>
</div>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
