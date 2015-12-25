<?php

$dsp->authadmin->Init();
$role = -1;
if (isset($dsp->authadmin->user['role']))
    $role = $dsp->authadmin->user['role'];

$dsp->authadmin->Logout();
Redirect('/about/mission/');
