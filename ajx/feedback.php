<?php

require_once(dirname(__FILE__) . "/../core/core.php");
require_once(CLASS_DIR . 'mailmessage.php');

$result = array();
$result['success'] = 0;

//$to = 'contact@citsb.ru';
$to = 'kdestroy@gmail.com';

$mail = new MailMessage();
$mail->setTo('', $to);
$mail->setSubject('Письмо с сайта');
//$mail->setTemplate($_SERVER["DOCUMENT_ROOT"] . '/templates/mail/remindpassword.xsl', array('pass' => $password, 'link' => $link));
$host = HOST;

$name = $_POST['name'];
$company = $_POST['company'];
$email = $_POST['email'];
$phone = empty($_POST['phone']) ? '---' : $_POST['phone'];

$body = <<<EOF
С сайта {$host} отправлено письмо:

Имя: {$name}
Компания: {$company}
Email: {$email}
Телефон: {$phone}

EOF;

$mail->setBody($body);
$r = $mail->send();

$result['success'] = $r ? 1 : 0;

echo json_encode($result);
exit;