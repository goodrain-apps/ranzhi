<?php
/**
 * The index view file of index module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     index 
 * @version     $Id: index.html.php 4205 2016-10-24 08:19:13Z liugang $
 * @link        http://www.ranzhico.com
 */
include "../../common/view/header.lite.html.php";
js::import($jsRoot . 'jquery/ips.js');
$isSuperAdmin = $this->app->user->admin == 'super';
js::set('attend', commonModel::isAvailable('attend') ? 1 : 0);
?>
<!-- Desktop -->
<div id='desktop' class='fullscreen-mode' unselectable='on' style='-moz-user-select:none;-webkit-user-select:none;' onselectstart='return false;'>
  <div id='leftBar' class='dock-left'>
    <button id='start' class='dock-bottom' type='button' title="<?php echo $app->user->realname;?>">
      <div class='avatar avatar-md'><?php if(!empty($app->user->avatar)) echo html::image($app->user->avatar);?></div>
    </button>
    <ul id='startMenu' class='dropdown-menu fade'>
      <li class='with-avatar'><?php echo html::a($this->createLink('user', 'profile'), "<div class='avatar avatar-md'>" . (empty($app->user->avatar) ? '' : html::image($app->user->avatar)) . "</div><strong>{$app->user->realname}</strong>", "data-toggle='modal' data-id='profile'");?></li>
      <li class="divider"></li>
      <li class='dropdown-submenu'><?php include '../../common/view/selectlang.html.php';?></li>
      <li class='dropdown-submenu'><?php include '../../common/view/selecttheme.html.php';?></li>
      <li><a href='<?php echo $this->createLink('misc', 'about');?>' data-id='about' data-toggle='modal' data-width='500'><i class='icon icon-info-sign'></i> <?php echo $lang->index->about?></a></li>
      <li class="divider"></li>
      <?php if($isSuperAdmin):?>
      <li><?php echo html::a($this->createLink('entry', 'create'), "<i class='icon icon-plus'></i> {$lang->index->addEntry}", "data-id='superadmin' class='app-btn'"  )?></li>
      <?php endif;?>
      <li><a href='javascript:;' class='fullscreen-btn' data-id='allapps'><i class='icon icon-th-large'></i> <?php echo $lang->index->allEntries?></a></li>
      <li class="divider"></li>
      <li><?php echo html::a($this->createLink('user', 'logout'), "<i class='icon icon-signout'></i> {$lang->logout}")?></li>
    </ul>
    <div id='apps-menu'>
      <ul class='bar-menu'></ul>
      <button id='moreOptionBtn' data-toggle='tooltip' data-tip-class='s-menu-tooltip' data-placement='right' data-btn-type='menu' class='btn-more' data-original-title='...'><i class='icon icon-ellipsis-h'></i></button>
      <ul id='moreOptionMenu' class='bar-menu dropdown-menu fade'>
      </ul>
    </div>
  </div>
  <div id='bottomBar' class='dock-bottom'>
    <div id='taskbar'><ul class='bar-menu'></ul></div>
    <div id='bottomRightBar' class='dock-right'>
      <ul class='bar-menu'>
        <?php echo isset($signButtons) ? $signButtons : ''?>
        <li><button id='showDesk' type='button' class='fullscreen-btn icon-desktop' data-id='home' data-toggle='tooltip' title='<?php echo $lang->index->showDesk; ?>'></button></li>
      </ul>
      <div class='copyright'><?php printf($lang->poweredBy, $this->config->version, $this->config->version)?></div>
    </div>
  </div>
  <div id='home' class='fullscreen fullscreen-active'>
    <nav class='navbar navbar-main navbar-fixed-top' id='mainNavbar'>
      <div class='collapse navbar-collapse'>
        <ul class='nav navbar-nav'>
          <li><?php echo html::a($this->createLink('user', 'profile'), "<i class='icon-user'></i> " . $app->user->realname, "data-toggle='modal' data-id='profile'");?></li>
        </ul>
        <?php echo commonModel::createDashboardMenu();?>
        <ul class='nav navbar-nav navbar-right'>
          <li><a class='navbar-brand' href='<?php $this->createLink('index', 'index') ?>'><?php echo isset($this->config->company->name) ? $this->config->company->name : '' . $lang->ranzhi ?></a></li>
          <li><a href='javascript:;' class='refresh-all-panel'><i class='icon-repeat'></i></a></li>
          <li><a data-toggle='modal' href='<?php echo $this->createLink("block", "admin"); ?>' title='<?php echo $lang->index->addBlock; ?>'><i class='icon-plus'></i></a></li>
        </ul>
      </div>
    </nav>
    <div id='dashboardWrapper'>
      <div class='panels-container dashboard' id='dashboard' data-confirm-remove-block='<?php  echo $lang->block->confirmRemoveBlock;?>'>
        <div class='row'>
          <?php
          $index = 0;
          reset($blocks);
          ?>
          <?php foreach($blocks as $key => $block):?>
          <?php
          $index = $key;
          ?>
          <div class='col-xs-<?php echo $block->grid;?> pull-left'>
            <div class='panel <?php if(isset($block->params->color)) echo 'panel-' . $block->params->color;?>' id='block<?php echo $index?>' data-id='<?php echo $index?>' data-name='<?php echo $block->title?>' data-url='<?php echo $this->createLink('entry', 'printBlock', 'index=' . $index) ?>'>
              <div class='panel-heading'>
                <div class='panel-actions'>
                  <?php if(isset($block->moreLink) and isset($block->appid)) echo html::a($block->moreLink, $lang->more . "<i class='icon-double-angle-right'></i>", "class='more app-btn' data-id='{$block->appid}'");?>
                  <button class="btn btn-mini refresh-panel" type='button'><i class="icon-repeat"></i></button>
                  <div class='dropdown'>
                    <button role="button" class="btn btn-mini" data-toggle="dropdown" type='button'><span class="caret"></span></button>
                    <ul class="dropdown-menu pull-right" role="menu">
                      <li><a href="<?php echo $this->createLink("block", "admin", "index=$index"); ?>" data-toggle='modal' class='edit-block' data-title='<?php echo $block->title; ?>' data-icon='icon-pencil'><i class="icon-pencil"></i> <?php echo $lang->edit; ?></a></li>
                      <li><a href="javascript:;" class="remove-panel"><i class="icon-remove"></i> <?php echo $lang->delete; ?></a></li>
                      <?php if(!$block->source and $block->block == 'html'):?>
                        <li><a href="javascript:hiddenBlock(<?php echo $index;?>)" class="hidden-panel"><i class='icon-eye-close'></i> <?php echo $lang->index->hidden; ?></a></li>
                      <?php endif;?>
                    </ul>
                  </div>
                </div>
                <?php echo $block->title?>
              </div>
              <div class='panel-body no-padding'></div>
            </div>
          </div>
          <?php endforeach;?>
        </div>
      </div>
    </div>
  </div>
  <div id='allapps' class='fullscreen'>
    <header>
      <ul class='nav' id='appSearchNav'>
        <li><a href="javascript:;" class='app-search' data-key=''><i class='icon-th-large'></i> <span><?php echo $lang->index->allEntries?></span> &nbsp;<small class='muted entries-count'></small></a></li>
        <li><a href="javascript:;" class='app-search' data-key=':menu'><i class=''></i> <span><?php echo $lang->index->showOnLeft?></span> &nbsp;<small class='muted search-count'></small></a></li>
        <li><a href="javascript:;" class='app-search' data-key=':!menu'><i class=''></i> <span><?php echo $lang->index->notOnLeft?></span> &nbsp;<small class='muted search-count'></small></a></li>
      </ul>
      <div class='search-input'>
        <i class='icon-search icon'></i>
        <input id='search' type='text' class='form-control-pure form-control'>
        <button id='cancelSearch' class='btn btn-pure btn-mini' type='button'><i class='icon-remove'></i></button>
      </div>
      <div class='actions'>
        <?php if($isSuperAdmin):?>
        <?php echo html::a($this->createLink('entry', 'create'), "<i class='icon-plus'></i> {$lang->index->addEntry}", "data-id='superadmin' class='app-btn btn btn-pure'")?>
        <?php endif;?>
      </div>
    </header>
    <div class='all-apps-list' id='allAppsList'>
      <ul class='bar-menu'>
      </ul>
    </div>
  </div>
  <div id='deskContainer'></div>
  <div id='modalContainer'></div>
</div>
<div id='noticeBox'>
  <?php echo $notice;?>
</div>
<div id='categoryTpl' class='hide'>
  <ul id='categoryMenucategoryid' class='category categoryMenu dropdown-menu fade' data-id='categoryid'></ul>
</div>
<script>
<?php $dashboardMenu = (isset($dashboard) and isset($dashboard->visible) and $dashboard->visible == 0) ? 'list' : 'all';?>
var entries = [
{
    id        : 'dashboard',
    code      : 'dashboard',
    name      : '<?php echo $lang->index->dashboard;?>',
    abbr      : '<?php echo $lang->index->dashboardAbbr;?>',
    open      : 'iframe',
    desc      : '<?php echo $lang->index->dashboard?>',
    menu      : '<?php echo $dashboardMenu;?>',
    sys       : true,
    icon      : 'icon-home',
    url       : '<?php echo $this->createLink('todo', 'calendar')?>',
    order     : 0, 
},
{
    id        : 'allapps',
    code      : 'allapps',
    name      : '<?php echo $lang->index->allEntries?>',
    display   : 'fullscreen',
    desc      : '<?php echo $lang->index->allEntries?>',
    menu      : 'menu',
    icon      : 'icon-th-large',
    sys       : true,
    forceMenu : true,
    order     : 9999999
},
{
    id        : 'home',
    code      : 'home',
    name      : '<?php echo $title?>',
    display   : 'fullscreen',
    menu      : 'none',
    icon      : 'icon-desktop',
    sys       : true,
    forceMenu : true,
    order     : 9999998
}];

<?php if($isSuperAdmin || commonModel::hasAppPriv('superadmin')):?>
<?php $superadminMenu  = (isset($superadmin) and isset($superadmin->visible) and $superadmin->visible == 0) ? 'list' : 'all';?>

entries.push(
{
    id    : 'superadmin',
    code  : 'superadmin',
    name  : '<?php echo $lang->index->superAdmin;?>',
    open  : 'iframe',
    desc  : '<?php echo $lang->index->superAdmin?>',
    menu  : '<?php echo $superadminMenu;?>',
    sys   : true,
    icon  : 'icon-cog',
    url   : "<?php echo $this->createLink('admin')?>",
    order : 9999997
});
<?php endif;?>

var ipsLang = {};
<?php
foreach ($lang->index->ips as $key => $value)
{
    echo 'ipsLang["' . $key . '"] = "' . $value . '";';
}
?>

<?php echo $allEntries;?>
</script>
<?php include "../../common/view/footer.html.php"; ?>
