<?php
//if (!session_id()) session_start();
//    $dsp->authadmin->Init();
//    $notify = '';
    $email = email_validate($_POST['email']);

    if ($email) {
        $user = $dsp->usersadmin->getUserByEmail($email);

        if (!$user) {
            $_SESSION['admin_message'] = 'Пользователя с таким E-mail не существует';
        } else {
            require LIB_DIR."class.phpmailer.php";

            $password = generatePass();
            $dsp->usersadmin->setNewPassword($password, $user['id']);

            $mail = new PHPMailer();
            $mail->CharSet = 'utf-8';
            $mail->setFrom('auto@citsb.ru', SITE_NAME);
            $mail->addAddress($email, $user['login']);
            $body = <<<EOF
Здравствуйте, {$user['login']}
Ваш новый пароль: {$password}
EOF;
            $mail->Subject = 'Восстановление пароля';
            $mail->Body = $body;
            $mail->send();
            Redirect('/admin/?restore=2');
        }

        if ( strpos( $_SERVER['REQUEST_URI'], 'e-notify=' ) !== FALSE )
            $_SERVER['REQUEST_URI'] = substr( $_SERVER['REQUEST_URI'], 0, strpos( $_SERVER['REQUEST_URI'], 'e-notify=' ) - 1 );

        if ( !empty( $notify ) )
        {
            $_SERVER['REQUEST_URI'] .= ( strpos( $_SERVER['REQUEST_URI'], '?' ) !== FALSE ? '&':'?' ).'e-notify=' . base64_encode($notify);
        }
    } else {
        $_SESSION['admin_message'] = 'Пользователя с таким E-mail не существует';
    }


?>