<?php

define('WDEBUG', true);
define('DS', DIRECTORY_SEPARATOR);

set_time_limit(0);
un_magic_quotes();
// JS
$bp = $dsp->_BuilderPatterns;
$root = $bp->root();
$dom_head = $bp->append_simple_node($root, 'head');
$dom_js = $bp->append_simple_node($dom_head, 'js');

// Append javascripts
//    $bp->append_simple_node($dom_js, 'item', 'admin/static/js/custom/list_common');

// Append notify
$notify = $dsp->authadmin->RetriveParam("message");
$notify = empty($notify) ? ( Param('e-notify') ? base64_decode(Param('e-notify')):'' ) : $notify;
if (!empty($notify)) {
    $param = array();
    if ( Param('e-notify') ) $param = array('mode' => 'error');
    $bp->append_simple_node($dom_head, 'notify', $notify, $param);
}

$dsp->authadmin->Init();

if (!$dsp->authadmin->IsLogged()) {
    $dsp->_Builder->addNode($dsp->_Builder->createNode('block', array('align' => 'center', 'id' => 'login', 'name' => 'login', 'act' => '/admin/')));
    $dsp->_Builder->Transform('admin' . DS . 'login.xsl');
    exit();
}

if ($dsp->authadmin->user['role'] == USER_ROLE_USER)
{
//        require ("users.php");
    switch ($nodes[0])
    {
        case 'logout' :
            require "logout.php";
            break;
    }

    $dsp->lists->build();
    $dsp->pages->show();
    exit;
}
