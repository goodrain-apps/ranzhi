<?php
$config->provider->require = new stdclass();
$config->provider->require->create = 'contact';
$config->provider->require->edit   = 'name';

$config->provider->editor = new stdclass();
$config->provider->editor->edit = array('id' => 'desc', 'tools' => 'simple');
