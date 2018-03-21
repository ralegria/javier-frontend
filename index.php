<?php
require_once 'fw/Core.php';

$core = new Core();
if(isset($_REQUEST['route'])){
    $core->init($_REQUEST);
}else{
    $_REQUEST['route'] = 'index';
    $core->init($_REQUEST);
}