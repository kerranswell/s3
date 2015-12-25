<?php

    if (!$dsp->authadmin->IsLogged() && $_POST['opcode'] != 'login'&& $_POST['opcode'] != 'restore') exit;

    if (!empty($_POST['opcode'])) {
//        print ADMIN_POST_DIR . $_POST['opcode'] . '.php';
        if (!empty($_POST['tablename']) && is_file(ADMIN_POST_DIR . $_POST['tablename'] . '_' . $_POST['opcode'] . '.php')) {
            require(ADMIN_POST_DIR . $_POST['tablename'] . '_' . $_POST['opcode'] . '.php');
        } else if (is_file(ADMIN_POST_DIR . $_POST['opcode'] . '.php')) {
            require(ADMIN_POST_DIR . $_POST['opcode'] . '.php');
        } else {
            
        } // if


        switch ($_POST['opcode'])
        {
            case 'content' : case 'block_picture' :
                $dsp->content->ajax();
                break;
        }

    } // if



    if (isset($_POST['no_redirect']) &&  $_POST['no_redirect'])
    {

    } else {
        Redirect($_SERVER['REQUEST_URI']);
    }
?>