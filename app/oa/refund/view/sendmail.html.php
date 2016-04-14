<?php
/**
 * The mail file of customer module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chu Jilu <chujilu@cnezsoft.com>
 * @package     customer
 * @version     $Id: sendmail.html.php 867 2010-06-17 09:32:58Z yuren_@126.com $
 * @link        http://www.ranzhico.com
 */
?>
<?php
$onlybody = isonlybody() ? true : false;
if($onlybody) $_GET['onlybody'] = 'no';
?>
<table width='98%' align='center'>
  <tr class='header'>
    <td>
      <?php echo "{$lang->refund->common}{$lang->refund->review}#{$refund->id}{$lang->colon}";?>
      <?php echo html::a(commonModel::getSysURL() . $this->createLink('oa.refund', 'view', "id=$refund->id"), $refund->name)?>
    </td>
  </tr>
  <tr>
    <td>
      <div class='content'>
        <table>
          <tr>
            <th><?php echo $lang->refund->date?></th>
            <td><?php echo $refund->date?></td>
            <th><?php echo $lang->refund->createdBy?></th>
            <td><?php echo zget($users, $refund->createdBy)?></td>
            <th><?php echo $lang->refund->related?></th>
            <td><?php foreach(explode(',', $refund->related) as $account) echo zget($users, $account) . ' '?></td>
          </tr>
          <tr>
            <th><?php echo $lang->refund->money?></th>
            <td><?php echo $refund->money?></td>
            <th><?php echo $lang->refund->status?></th>
            <td style='color: red'><?php echo zget($lang->refund->statusList, $refund->status)?></td>
            <th><?php echo $lang->refund->reason?></th>
            <td><?php echo $refund->reason?></td>
          </tr>
          <tr>
            <th><?php echo $lang->refund->firstReviewer?></th>
            <td><?php echo zget($users, $refund->firstReviewer)?></td>
            <th><?php echo $lang->refund->firstReviewDate?></th>
            <td><?php echo $refund->firstReviewDate?></td>
            <th><?php echo $lang->refund->category?></th>
            <td><?php echo zget($categories, $refund->category)?></td>
          </tr>
          <tr>
            <th><?php echo $lang->refund->secondReviewer?></th>
            <td><?php echo zget($users, $refund->secondReviewer)?></td>
            <th><?php echo $lang->refund->secondReviewDate?></th>
            <td colspan='3'><?php echo $refund->secondReviewDate?></td>
          </tr>
          <tr>
            <th><?php echo $lang->refund->refundBy?></th>
            <td><?php echo zget($users, $refund->refundBy)?></td>
            <th><?php echo $lang->refund->refundDate?></th>
            <td colspan='3'><?php echo $refund->refundDate?></td>
          </tr>
          <tr>
            <th><?php echo $lang->refund->desc?></th>
            <td colspan='5'><?php echo $refund->desc?></td>
          </tr>
        </table>
        <?php if(!empty($refund->detail)):?>
        <p><?php echo $lang->refund->detail?></p>
        <table>
          <tr>
            <th><?php echo $lang->refund->date?></th>
            <th><?php echo $lang->refund->category?></th>
            <th><?php echo $lang->refund->money?></th>
            <th><?php echo $lang->refund->status?></th>
            <th><?php echo $lang->refund->related?></th>
            <th><?php echo $lang->refund->desc?></th>
            <th><?php echo $lang->refund->reason?></th>
          </tr>
          <?php foreach($refund->detail as $detail):?>
          <tr>
            <td><?php echo $detail->date?></td>
            <td><?php echo zget($categories, $detail->category)?></td>
            <td><?php echo $detail->money?></td>
            <td><?php echo zget($lang->refund->statusList, $detail->status)?></td>
            <td><?php foreach(explode(',', $detail->related) as $account) echo zget($users, $account) . ' '?></td>
            <td><?php echo $detail->desc?></td>
            <td><?php echo $detail->reason?></td>
          </tr>
          <?php endforeach;?>
        </table>
        <?php endif;?>
      </div>
    </td>
  </tr>
  <tr>
    <td><?php include '../../../sys/common/view/mail.html.php';?></td>
  </tr>
</table>
<?php if($onlybody) $_GET['onlybody'] = 'yes';?>
