<?php
/**
 * The kindeditor view of common module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     common 
 * @version     $Id: kindeditor.html.php 3407 2015-12-22 02:41:47Z liugang $
 * @link        http://www.ranzhico.com
 */
if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
/* Get current module and method. */
$module = $this->moduleName;
$method = $this->methodName;

if(!isset($config->$module->editor->$method)) return;

/* Export $jsRoot var. */
js::set('jsRoot', $jsRoot);
js::set('themeRoot', $themeRoot);

/* Get editor settings for current page. */
$editors = $config->$module->editor->$method;

$editors['id'] = explode(',', $editors['id']);
js::set('editors', $editors);

$this->app->loadLang('file');
js::set('errorUnwritable', $lang->file->errorUnwritable);

/* Get current lang. */
$editorLangs = array('en' => 'en', 'zh-cn' => 'zh_CN', 'zh-tw' => 'zh_TW');
$editorLang  = isset($editorLangs[$app->getClientLang()]) ? $editorLangs[$app->getClientLang()] : 'en';
js::set('editorLang', $editorLang);

/* Import css and js for kindeditor. */
css::import($jsRoot . 'kindeditor/themes/default/default.css');
js::import($jsRoot . 'kindeditor/kindeditor-min.js');
js::import($jsRoot  . 'kindeditor/lang/' . $editorLang . '.js');

/* set uid for upload. */
$uid = uniqid('');
js::set('uid', $uid);
?>

<script>
var simple = 
[ 'formatblock', 'fontsize', '|', 'bold', 'italic','underline', '|', 
'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|',
'emoticons', 'image', 'link', '|', 'removeformat','undo', 'redo' ];

var full = 
[ 'formatblock', 'fontsize', 'lineheight', '|', 'forecolor', 'hilitecolor', '|', 'bold', 'italic','underline', 'strikethrough', '|',
'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', '|',
'insertorderedlist', 'insertunorderedlist', '|',
'emoticons', 'image', 'insertfile', 'hr', '|', 'link', 'unlink', '/',
'undo', 'redo', '|', 'cut', 'copy', 'paste', '|', 'plainpaste', 'wordpaste', '|', 'removeformat', 'clearhtml','quickformat', '|',
'indent', 'outdent', 'subscript', 'superscript', '|',
'table', 'code', '|', 'pagebreak', 'anchor', '|', 
'fullscreen', 'source', 'about'];

$(document).ready(initKindeditor);
function initKindeditor(afterInit)
{
    $(':input[type=submit]').after("<input type='hidden' id='uid' name='uid' value=" + v.uid + ">");

    var nextFormControl = 'input:not([type="hidden"]), textarea:not(.ke-edit-textarea), button[type="submit"], select';
    $.each(v.editors.id, function(key, editorID)
    {
        if(typeof(v.editors.filterMode) == 'undefined') v.editors.filterMode = true;
        editorTool = eval(v.editors.tools);
        var K = KindEditor, $editor = $('#' + editorID);
        var options = 
        {
            cssPath:[v.themeRoot + 'zui/css/min.css'],
            width:'100%',
            items:editorTool,
            filterMode:true, 
            bodyClass:'article-content',
            urlType:'absolute', 
            uploadJson: createLink('file', 'ajaxUpload', 'uid=' + v.uid),
            fileManagerJson : createLink('file', 'fileManager'),
            imageTabIndex:1,
            filterMode:v.editors.filterMode,
            allowFileManager:true,
            langType:v.editorLang,
            afterBlur: function(){this.sync();$editor.prev('.ke-container').removeClass('focus');},
            afterFocus: function(){$editor.prev('.ke-container').addClass('focus');},
            afterChange: function(){$editor.change().hide();},
            afterCreate: function()
            {
                var doc = this.edit.doc; 
                var cmd = this.edit.cmd; 
                if(!K.WEBKIT && !K.GECKO)
                {
                    var pasted = false;
                    $(doc.body).bind('paste', function(ev)
                    {
                        pasted = true;
                        return true;
                    });
                    setTimeout(function()
                    {
                        $(doc.body).bind('keyup', function(ev)
                        {
                            if(pasted)
                            {
                                pasted = false;
                                return true;
                            }
                            if(ev.keyCode == 86 && ev.ctrlKey) alert('<?php echo $this->lang->error->pasteImg;?>');
                        })
                    }, 10);
                }
                /* Paste in chrome.*/
                /* Code reference from http://www.foliotek.com/devblog/copy-images-from-clipboard-in-javascript/. */
                if(K.WEBKIT)
                {
                    $(doc.body).bind('paste', function(ev)
                    {
                        var $this    = $(this);
                        var original = ev.originalEvent;
                        var file     = original.clipboardData.items[0].getAsFile();
                        if(file)
                        {
                            var reader = new FileReader();
                            reader.onload = function (evt) 
                            {
                                var result = evt.target.result; 
                                var result = evt.target.result;
                                var arr    = result.split(",");
                                var data   = arr[1]; // raw base64
                                var contentType = arr[0].split(";")[0].split(":")[1];

                                html = '<img src="' + result + '" alt="" />';
                                $.post(createLink('file', 'ajaxPasteImage', 'uid=' + v.uid), {editor: html}, function(data)
                                {
                                    if(data) return cmd.inserthtml(data);

                                    alert(v.errorUnwritable);
                                    return cmd.inserthtml(html);
                                });
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                }
                /* Paste in firfox and other firfox.*/
                else
                {
                    $(doc.body).bind('paste', function(ev)
                    {
                        setTimeout(function()
                        {
                            var html = K(doc.body).html();
                            if(html.search(/<img src="data:.+;base64,/) > -1)
                            {
                                K(doc.body).html(html.replace(/<img src="data:.+;base64,.*".*\/>/, ''));
                                $.post(createLink('file', 'ajaxPasteImage', "uid=" + v.uid), {editor: html}, function(data)
                                {
                                    if(data) return K(doc.body).html(data);

                                    alert(v.errorUnwritable);
                                    return K(doc.body).html(html);
                                });
                            }
                        }, 80);
                    });
                }
                /* End */
            },
            afterTab: function(id)
            {
                var $next = $editor.next(nextFormControl);
                if(!$next.length) $next = $editor.parent().next().find(nextFormControl);
                if(!$next.length) $next = $editor.parent().parent().next().find(nextFormControl);
                $next = $next.first().focus();
                var keditor = $next.data('keditor');
                if(keditor) keditor.focus();
                else if($next.hasClass('chosen')) $next.trigger('chosen:activate');
            }
        };
        try
        {
            if(!window.editor) window.editor = {};
            var keditor = K.create('#' + editorID, options);
            window.editor['#'] = window.editor[editorID] = keditor;
            $editor.data('keditor', keditor);
        }
        catch(e){}
    });

    if($.isFunction(afterInit)) afterInit();
}
</script>
