<?php

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
    $dsp->_Builder->Transform('admin/login.xsl');
    exit();
}

switch ($nodes[0])
{
    case 'logout' :
        require "logout.php";
        break;
}

$dsp->common->addValueToXml(array('user' => $dsp->authadmin->user['login']));
$dsp->contracts->showDocs();
exit;
