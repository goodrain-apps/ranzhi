<?php
/**
 * The index view of admin module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin 
 * @version     $Id: index.html.php 3138 2015-11-09 07:32:18Z chujilu $
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='container' id='shortcutBox'>
  <div class='row'>
    <div class='col-md-4 col-sm-6'>
      <div class="shortcut user">
        <?php echo html::a($this->createLink('user', 'create'), '<h3>' . $lang->admin->shortcuts->createUser . '</h3>')?>  
      </div>
    </div>
    <div class='col-md-4 col-sm-6'> 
      <div class="shortcut company">
        <?php echo html::a($this->createLink('company', 'setbasic'), '<h3>' . $lang->admin->shortcuts->company . '</h3>')?>
      </div>
    </div>
    <div class='col-md-4 col-sm-6'>
      <div class="shortcut entry">
        <?php echo html::a($this->createLink('entry', 'create'), '<h3>' . $lang->admin->shortcuts->createEntry . '</h3>')?>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
