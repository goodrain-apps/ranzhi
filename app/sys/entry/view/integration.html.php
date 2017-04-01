<?php
/**
 * The integration view of entry module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     entry 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
include '../../common/view/header.html.php';
?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><i class='icon-link'></i> <?php echo $lang->entry->integration;?></strong>
  </div>
  <div class='panel-body'>
    <form method='post' class='form-inline' id='entryForm'>
      <table class='table table-form'>
        <tr>
          <th class='w-100px'><?php echo $lang->entry->integration;?></th>
          <td class='w-p50'><?php echo html::radio('integration', $lang->entry->integrationList, $entry->integration);?></td>
        </tr>
        <tbody class="integration <?php echo $entry->integration ? '' : 'hide';?>">
        <?php if(!$entry->zentao):?>
        <tr>
          <th><?php echo $lang->entry->logout;?></th>
          <td><?php echo html::input('logout', $entry->logout, "class='form-control' placeholder='{$lang->entry->note->logout}'");?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->entry->block;?></th>
          <td><?php echo html::input('block', $entry->block, "class='form-control' placeholder='{$lang->entry->note->api}'");?></td>
          <td></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->entry->key;?></th>
          <td><?php echo html::input('key', $entry->key, "class='form-control' readonly='readonly'");?></td>
          <td><?php echo html::a('javascript:void(0)', $lang->entry->createKey, 'onclick="createKey()"')?></td>
        </tr>
        <tr>
          <th><?php echo $lang->entry->ip;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('ip', $entry->ip, "class='form-control' title='{$lang->entry->note->ip}' placeholder='{$lang->entry->note->ip}'");?>
              <div class='input-group-addon'>
                <label class="checkbox"><input type="checkbox" id="allip" name="allip" value="1"> <?php echo $lang->entry->note->allip;?></label>
              </div>
            </div>
          </td>
          <td></td>
        </tr>
        </tbody>
        <tr><th></th><td><?php echo html::submitButton() . html::backButton();?></td><td></td></tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
