<?php
/**
 * The config items for rights.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     config
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
/* Init the rights. */
$config->rights = new stdclass();

$config->rights->guest = array();

$config->rights->member['index']['index']           = 'index';
$config->rights->member['admin']['index']           = 'index';
$config->rights->member['dashboard']['index']       = 'index';
$config->rights->member['dashboard']['todo']        = 'todo';
$config->rights->member['dashboard']['task']        = 'task';
$config->rights->member['entry']['visit']           = 'visit';
$config->rights->member['entry']['blocks']          = 'blocks';
$config->rights->member['entry']['setblock']        = 'setblock';
$config->rights->member['entry']['printblock']      = 'printblock';
$config->rights->member['entry']['customsort']      = 'customsort';
$config->rights->member['entry']['updateentrymenu'] = 'updateentrymenu';

$config->rights->member['user']['profile']                = 'profile';
$config->rights->member['user']['thread']                 = 'thread';
$config->rights->member['user']['reply']                  = 'reply';
$config->rights->member['user']['message']                = 'message';
$config->rights->member['user']['setreferer']             = 'setreferer';
$config->rights->member['user']['changepassword']         = 'changepassword';
$config->rights->member['user']['vcard']                  = 'vcard';
$config->rights->member['user']['uploadavatar']           = 'uploadavatar';
$config->rights->member['user']['cropavatar']             = 'cropavatar';
$config->rights->member['user']['edit']                   = 'edit';

$config->rights->member['search']['buildform']            = 'buildform';
$config->rights->member['search']['buildquery']           = 'buildquery';
$config->rights->member['search']['savequery']            = 'savequery';
$config->rights->member['search']['deletequery']          = 'deletequery';

$config->rights->member['misc']['qrcode']            = 'qrcode';
$config->rights->member['misc']['about']             = 'about';
$config->rights->member['contract']['getorder']      = 'getorder';
$config->rights->member['contract']['getoptionmenu'] = 'getoptionmenu';
$config->rights->member['customer']['index']         = 'index';
$config->rights->member['customer']['getoptionmenu'] = 'getoptionmenu';
$config->rights->member['provider']['index']         = 'index';
$config->rights->member['product']['index']          = 'index';
$config->rights->member['contact']['getoptionmenu']  = 'getoptionmenu';
$config->rights->member['contact']['block']          = 'block';
$config->rights->member['order']['sendmail']         = 'sendmail';
$config->rights->member['thread']['locate']          = 'locate';
$config->rights->member['tree']['redirect']          = 'redirect';

$config->rights->member['project']['index']      = 'index';
$config->rights->member['project']['create']     = 'create';
$config->rights->member['project']['edit']       = 'edit';
$config->rights->member['project']['view']       = 'view';
$config->rights->member['project']['member']     = 'member';
$config->rights->member['project']['finish']     = 'finish';
$config->rights->member['project']['activate']   = 'activate';
$config->rights->member['project']['suspend']    = 'suspend';
$config->rights->member['project']['importtask'] = 'importtask';
$config->rights->member['project']['delete']     = 'delete';

$config->rights->member['task']['sendmail']       = 'sendmail';
$config->rights->member['task']['browse']         = 'browse';
$config->rights->member['task']['kanban']         = 'kanban';
$config->rights->member['task']['outline']        = 'outline';
$config->rights->member['task']['create']         = 'create';
$config->rights->member['task']['batchcreate']    = 'batchcreate';
$config->rights->member['task']['edit']           = 'edit';
$config->rights->member['task']['view']           = 'view';
$config->rights->member['task']['finish']         = 'finish';
$config->rights->member['task']['start']          = 'start';
$config->rights->member['task']['assignto']       = 'assignto';
$config->rights->member['task']['activate']       = 'activate';
$config->rights->member['task']['cancel']         = 'cancel';
$config->rights->member['task']['close']          = 'close';
$config->rights->member['task']['batchclose']     = 'batchclose';
$config->rights->member['task']['export']         = 'export';
$config->rights->member['task']['delete']         = 'delete';
$config->rights->member['task']['recordestimate'] = 'recordestimate';

$config->rights->member['todo']['calendar']     = 'calendar';
$config->rights->member['todo']['create']       = 'create';
$config->rights->member['todo']['batchcreate']  = 'batchcreate';
$config->rights->member['todo']['edit']         = 'edit';
$config->rights->member['todo']['batchedit']    = 'batchedit';
$config->rights->member['todo']['browse']       = 'browse';
$config->rights->member['todo']['view']         = 'view';
$config->rights->member['todo']['delete']       = 'delete';
$config->rights->member['todo']['finish']       = 'finish';
$config->rights->member['todo']['batchfinish']  = 'batchfinish';
$config->rights->member['todo']['assignto']     = 'assignto';
$config->rights->member['todo']['close']        = 'close';
$config->rights->member['todo']['batchclose']   = 'batchclose';
$config->rights->member['todo']['activate']     = 'activate';
$config->rights->member['todo']['import2today'] = 'import2today';

$config->rights->member['reply']['post']          = 'post';
$config->rights->member['reply']['edit']          = 'edit';
$config->rights->member['reply']['delete']        = 'delete';
$config->rights->member['reply']['deletefile']    = 'deletefile';

$config->rights->member['message']['comment'] = 'comment';
$config->rights->member['message']['post']    = 'post';

$config->rights->member['action']['createrecord'] = 'createrecord';
$config->rights->member['action']['editrecord']   = 'editrecord';
$config->rights->member['action']['history']      = 'history';
$config->rights->member['action']['editcomment']  = 'editcomment';
$config->rights->member['action']['read']         = 'read';

$config->rights->member['file']['buildform']      = 'buildform';
$config->rights->member['file']['buildlist']      = 'buildlist';
$config->rights->member['file']['printfiles']     = 'printfiles';
$config->rights->member['file']['ajaxupload']     = 'ajaxupload';
$config->rights->member['file']['browse']         = 'browse';
$config->rights->member['file']['senddownheader'] = 'senddownheader';
$config->rights->member['file']['ajaxpasteimage'] = 'ajaxpasteimage';
$config->rights->member['file']['filemanager']    = 'filemanager';
$config->rights->member['file']['sort']           = 'sort';

$config->rights->member['announce']['viewreaders'] = 'viewreaders';

$config->rights->member['attend']['personal']         = 'personal';
$config->rights->member['attend']['edit']             = 'edit';
$config->rights->member['attend']['ajaxgetdeptusers'] = 'ajaxgetdeptusers';
$config->rights->member['attend']['read']             = 'read';

$config->rights->member['holiday']['browse']  = 'browse';

$config->rights->member['leave']['personal']     = 'personal';
$config->rights->member['leave']['create']       = 'create';
$config->rights->member['leave']['edit']         = 'edit';
$config->rights->member['leave']['delete']       = 'delete';
$config->rights->member['leave']['view']         = 'view';
$config->rights->member['leave']['switchstatus'] = 'switchstatus';
$config->rights->member['leave']['back']         = 'back';

$config->rights->member['makeup']['personal']     = 'personal';
$config->rights->member['makeup']['create']       = 'create';
$config->rights->member['makeup']['edit']         = 'edit';
$config->rights->member['makeup']['view']         = 'view';
$config->rights->member['makeup']['delete']       = 'delete';
$config->rights->member['makeup']['switchstatus'] = 'switchstatus';

$config->rights->member['overtime']['personal']     = 'personal';
$config->rights->member['overtime']['create']       = 'create';
$config->rights->member['overtime']['edit']         = 'edit';
$config->rights->member['overtime']['view']         = 'view';
$config->rights->member['overtime']['delete']       = 'delete';
$config->rights->member['overtime']['switchstatus'] = 'switchstatus';

$config->rights->member['lieu']['personal']     = 'personal';
$config->rights->member['lieu']['create']       = 'create';
$config->rights->member['lieu']['edit']         = 'edit';
$config->rights->member['lieu']['delete']       = 'delete';
$config->rights->member['lieu']['view']         = 'view';
$config->rights->member['lieu']['switchstatus'] = 'switchstatus';

$config->rights->member['trip']['personal'] = 'personal';
$config->rights->member['trip']['view']     = 'view';
$config->rights->member['trip']['create']   = 'create';
$config->rights->member['trip']['edit']     = 'edit';
$config->rights->member['trip']['delete']   = 'delete';

$config->rights->member['egress']['personal'] = 'personal';
$config->rights->member['egress']['create']   = 'create';
$config->rights->member['egress']['edit']     = 'edit';
$config->rights->member['egress']['delete']   = 'delete';
$config->rights->member['egress']['view']     = 'view';

$config->rights->member['out']['personal'] = 'personal';
$config->rights->member['out']['create']   = 'create';
$config->rights->member['out']['edit']     = 'edit';
$config->rights->member['out']['delete']   = 'delete';

$config->rights->member['refund']['personal']     = 'personal';
$config->rights->member['refund']['create']       = 'create';
$config->rights->member['refund']['edit']         = 'edit';
$config->rights->member['refund']['delete']       = 'delete';
$config->rights->member['refund']['view']         = 'view';
$config->rights->member['refund']['switchstatus'] = 'switchstatus';

$config->rights->member['my']['todo']     = 'todo';
$config->rights->member['my']['task']     = 'task';
$config->rights->member['my']['project']  = 'project';
$config->rights->member['my']['dynamic']  = 'dynamic';
$config->rights->member['my']['order']    = 'order';
$config->rights->member['my']['contract'] = 'contract';
$config->rights->member['my']['review']   = 'review';
