<?php
/**
 * The create view of entry module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entry 
 * @version     $Id: create.html.php 3294 2015-12-02 01:19:46Z liugang $
 * @link        http://www.ranzhico.com
 */
include '../../common/view/header.html.php';
js::set('loginUrl', $lang->entry->login);
js::set('loginPlaceholder', $lang->entry->note->login);
js::set('chanzhiURL', $lang->entry->chanzhiURL);
js::set('chanzhiPlaceholder', $lang->entry->chanzhiPlaceholder);
js::set('zentaoURL', $lang->entry->zentaoURL);
js::set('zentaoPlaceholder', $lang->entry->zentaoPlaceholder);
js::set('zentaoName', $lang->entry->zentao);
?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><i class='icon-building'></i> <?php echo $lang->entry->create;?></strong>
  </div>
  <div class='panel-body'>
    <form method='post' class='form-inline' id='entryForm'>
      <table class='table table-form'>
        <tr>
          <th class='w-100px'><?php echo $lang->entry->name;?></th>
          <td class='w-p50'>
            <div class='input-group'>
              <?php echo html::input('name', '', "class='form-control' placeholder='{$lang->entry->note->name}'");?>
              <label class='input-group-addon fix-border'><?php echo $lang->entry->abbr;?></label>
              <?php echo html::input('abbr', '', "class='form-control' maxlength='2' placeholder='{$lang->entry->note->abbr}'");?>
              <div class='input-group-addon'>
                <label class="checkbox"><input type="checkbox" id="visible" name="visible" value="1" checked="checked"> <?php echo $lang->entry->note->visible;?></label>
              </div>
              <div class='input-group-addon'>
                <label class="checkbox"><input type="checkbox" id="zentao" name="zentao" value="1"> <?php echo $lang->entry->integrateZentao;?></label>
              </div>
            </div>
          </td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->entry->code;?></th>
          <td><?php echo html::input('code', '', "class='form-control' placeholder='{$lang->entry->note->code}'");?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->entry->login;?></th>
          <td><?php echo html::input('login', '', "class='form-control' placeholder='{$lang->entry->note->login}'");?></td>
          <td></td>
        </tr>
        <tr class='hide'>
          <th><?php echo $lang->entry->zentaoAdmin;?></th>
          <td>
            <div class='required required-wrapper'></div>
            <div class='input-group'>
              <label class='input-group-addon fix-border'><?php echo $lang->account;?></label>
            <?php echo html::input('adminAccount', '', "class='form-control' placeholder='{$lang->entry->adminAccount}'");?>
              <label class='input-group-addon fix-border'><?php echo $lang->password;?></label>
            <?php echo html::password('adminPassword', '', "class='form-control' placeholder='{$lang->entry->adminPassword}'");?>
            </div>
          </td>
          <td></td>
        </tr>
        <tr class='hide'>
          <th><?php echo $lang->entry->key;?></th>
          <td><?php echo html::input('key', $key, "class='form-control'");?></td>
          <td><span class="help-inline"><?php echo html::a('javascript:void(0)', $lang->entry->createKey, 'onclick="createKey()"')?></span></td>
        </tr>
        <tr>
          <th><?php echo $lang->entry->priv;?></th>
          <td>
            <div class='input-group'>
              <?php foreach($groups as $id => $name):?>
                <span class='checkbox-group'><?php echo html::checkbox('groups', array($id => $name), '');?></span>
              <?php endforeach?>
            </div>
          </td>
          <td></td>
        </tr>
        <tr>
          <th></th><td><?php echo html::submitButton() . html::backButton();?></td><td></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
