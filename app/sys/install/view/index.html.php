<?php
/**
 * The html template file of index method of install module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     install 
 * @version     $Id: index.html.php 3138 2015-11-09 07:32:18Z chujilu $
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class="modal-header text-right"><div class='btn dropdown'><?php include '../../common/view/selectlang.html.php';?></div></div>
      <div class='modal-body'>
        <div class='row'>
          <div class='col-md-8'>
            <h3><?php echo $lang->install->welcome;?></h3>
            <div><?php printf(nl2br(trim($lang->install->desc)), $config->version);?></div>
          </div>
          <div class='col-md-4'><!--<div id='ranzhi'><?php echo html::image($themeRoot . '/default/images/ips/app-ranzhi.png');?></div>--></div>
        </div>
      </div>
      <div class='modal-footer'>
        <?php if(!isset($latestRelease)):?>
        <p class='text-center'><?php echo html::a($this->createLink('install', 'step0'), $lang->install->start, "class='btn btn-primary btn-install'");?></p>
        <?php else:?>
        <?php vprintf($lang->install->newReleased, $latestRelease);?>
        <p>
          <?php 
          echo $lang->install->choice;
          echo html::a($latestRelease->url, $lang->install->seeLatestRelease, "target='_blank'");
          echo html::a($this->createLink('install', 'step0'), $lang->install->keepInstalling, "class='btn btn-primary btn-install'");
          ?>
        </p>
        <?php endif;?>
      </div>
    </div>
  </div>
</div>
<?php include './footer.html.php';?>
