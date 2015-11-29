<?php
    $dsp->authadmin->Init();
    $role = -1;
    if (isset($dsp->authadmin->user['role']))
        $role = $dsp->authadmin->user['role'];

    $dsp->authadmin->Logout();
    Redirect($role == USER_ROLE_SUPER || $role == USER_ROLE_EDITOR ? '/admin/' : '/');
?>