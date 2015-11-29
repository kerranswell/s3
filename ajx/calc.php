<?php

require_once(dirname(__FILE__) . "/../core/core.php");

$result = array();
$result['success'] = 0;
switch ($_REQUEST['act'])
{
    case 'checkContract' :
        $inn = trim($_REQUEST['inn']);
        $num = trim($_REQUEST['num']);
        if ($dsp->contracts->exists($inn, $num)) $result['success'] = 1;
    break;
}

echo json_encode($result);
exit;