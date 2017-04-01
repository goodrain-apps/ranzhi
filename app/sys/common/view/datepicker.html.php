<?php
/**
 * The datepicker view of common module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     common 
 * @version     $Id: datepicker.html.php 4029 2016-08-26 06:50:41Z liugang $
 * @link        http://www.ranzhico.com
 */
if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
$clientLang = $this->app->getClientLang();
css::import($jsRoot . 'datetimepicker/css/min.css');
js::import($jsRoot  . 'datetimepicker/js/min.js'); 
?>
<style>
.only-pick-time table {width: 100%}
.only-pick-time table td {width: 150px}
.only-pick-time table > tfoot .today, .only-pick-time table > thead {display: none !important;}
</style>
<script>
/**
 * Format date to a string
 *
 * @param  string   format
 * @return string
 */
Date.prototype.format = function(format)
{
    var date =
    {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),
        "S+": this.getMilliseconds()
    };
    if (/(y+)/i.test(format))
    {
        format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
    }
    for (var k in date)
    {
        if (new RegExp("(" + k + ")").test(format))
        {
            format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? date[k] : ("00" + date[k]).substr(("" + date[k]).length));
        }
    }
    return format;
}

$(function()
{
    $.fn.fixedDate = function()
    {
        return $(this).each(function()
        {
            var $this = $(this);
            if($this.hasClass('date-picker-up') || (!$this.hasClass('date-picker-down') && $this.offset().top + 200 > $(document.body).height()))
            {
                $this.attr('data-picker-position', 'top-right');
            }

            if($this.val() != '' && !$this.hasClass('form-time'))
            {
                var date = new Date(Date.parse($this.val().replace(/-/g, '/')));
                if(!date.valueOf()) date = new Date();

                if($this.hasClass('form-datetime')) $this.val(date.format('yyyy-MM-dd hh:mm:ss'));
                else if($this.hasClass('form-month')) $this.val(date.format('yyyy-MM'));
                else $this.val(date.format('yyyy-MM-dd'));
            }
            return $this;
        });
    };
    var options = 
    {
        language: '<?php echo $clientLang; ?>',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        format: 'yyyy-mm-dd hh:ii'
    }
    window.datetimepickerDefaultOptions = options;

    $('.form-datetime').fixedDate().datetimepicker(options);
    $('.form-date').fixedDate().datetimepicker($.extend(options, {minView: 2, format: 'yyyy-mm-dd'}));
    $('.form-time').fixedDate().datetimepicker($.extend(options, {eleClass: 'only-pick-time', startView: 1, minView: 0, maxView: 1, format: 'hh:ii'}));
    $('.form-month').fixedDate().datetimepicker($.extend(options, {startView: 3, minView: 3, format: 'yyyy-mm'}));
});
</script>
