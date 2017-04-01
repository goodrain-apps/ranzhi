<?php
if(!isset($config->lieu)) $config->lieu = new stdclass();
$config->lieu->require = new stdclass();
$config->lieu->require->create = 'start,begin,finish,end,overtime,hours';
$config->lieu->require->edit   = 'start,begin,finish,end,overtime,hours';
