<?php

require_once(dirname(__FILE__) . "/../core/core.php");
require_once(CLASS_DIR . 'mailmessage.php');

$result = array();
$result['success'] = 0;

//$to_email = '';

switch ($_POST['act'])
{
    case 'feedback' :

        $to = 'contact@citsb.ru';
//        $to = $to_email;

        $mail = new MailMessage();
        $mail->setTo('', $to);
        $mail->setSubject('Письмо с сайта');
        //$mail->setTemplate($_SERVER["DOCUMENT_ROOT"] . '/templates/mail/remindpassword.xsl', array('pass' => $password, 'link' => $link));
        $host = HOST;

        $name = $_POST['name'];
        $company = $_POST['company'];
        $email = $_POST['email'];
        $phone = empty($_POST['phone']) ? '---' : $_POST['phone'];
        $comments = $_POST['comments'];

        $body = <<<EOF
С сайта {$host} отправлено письмо:

Имя: {$name}
Компания: {$company}
Email: {$email}
Телефон: {$phone}
Комментарий:
{$comments}

EOF;

        $mail->setBody($body);
        $r = $mail->send();

        $result['success'] = $r ? 1 : 0;

        break;

    case 'service-submit' :

        $to = 'net_lead@citsb.ru';
//        $to = $to_email;

        # данные

        $company = $_POST['company'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $comments = $_POST['comments'];
        $data = $_POST['calc'];

        $mail = new MailMessage();
        $mail->setTo('', $to);
        $mail->setSubject('Request from / '.$company.' / '.($data['count_servers'] + $data['count_computers']).' / '.$name.' / '.$phone);
        $host = HOST;

        $tariff = $data['it-director'] ? 'IT-директор' : 'Системный администратор';
        $inn = array();
        if ($data['inn'] != '') $inn[] = $data['inn'];
        if ($data['contract_number'] != '') $inn[] = $data['contract_number'];
        $inn_num = implode(" / ", $inn);
        if (trim($inn) == '') $inn = '---';

        $body = <<<EOF
IP адрес отправителя: {$_SERVER['REMOTE_ADDR']}
Тарифный план: {$tariff}
Количество серверов / количество раб. станций: {$data['count_servers']} / {$data['count_computers']}
Инн / номер договора рекомендателя: {$inn_num}
Название компании или ИНН компании: {$company}
Контактное лицо: {$name}
Телефон для связи: {$phone}
E-mail для связи: {$email}
Комментарий к заказу:
{$comments}

EOF;

        $mail->setBody($body);
        $r = $mail->send();

        $sql = "insert into `contracts` (`id`, `tariff`, `servers`, `computers`, `rec_inn`, `rec_num`, `company`, `name`, `phone`, `email`, `comments`, `business`, `date`, `request`, `total`)
                values (0, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?)";
        $dsp->db->Execute($sql, $data['it-director'] ? 'it' : 'sys', $data['count_servers'], $data['count_computers'], $data['inn'], $data['contract_number'], $company, $name, $phone, $email, $comments, $data['business-yes'] ? 1 : 0, time(), $data['total']);
        $id = $dsp->db->LastInsertId();
        $r = $r && $id > 0;

        $contract_number = $dsp->contracts->getContractNumber($id);
        $dsp->db->Execute("update `contracts` set `contract_number` = ? where `id` = ?", $contract_number, $id);

        $result['message'] = <<<EOF
Благодарим за оказанное нам доверие. Ваш запрос передан ответственному лицу. Ответственный: Горохов Виталий. Вы можете с ним связаться, позвонив по телефону<br />
        +7 (495) 123-45-67 доп. номер #107 в рабочее время с 10 до 18 по московскому времени. Предварительный номер вашего договора: {$contract_number}.<br/>
<br />
Пока мы обрабатываем Ваш запрос, Вы можете ознакомиться с шаблоном нашего договора, а также посмотреть наше коммерческое предложение.<br/><br />
<a href="/upload/Dogovor_Template_wForms.pdf" target="_blank">Договор на обслуживание информационной системы предприятия</a><br />
<a href="/upload/Commercial-Prop-IT-Dir.pdf" target="_blank">Доп. соглашение на услугу IT директор</a><br />
<a href="/upload/Commercial-Prop.pdf" target="_blank">Шаблон коммерческого предложения</a><br />
EOF;


        $result['success'] = $r ? 1 : 0;

        break;

    case 'service-refuse' :
        $to = 'net_AngryLead@citsb.ru';
//        $to = $to_email;

        # данные

        $company = $_POST['company'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $comments = $_POST['comments'];
        $data = $_POST['calc'];

        $mail = new MailMessage();
        $mail->setTo('', $to);

        # subject
        $subj = array();
        $subj[] = "Angry from";
        if ($company != '') $subj[] = $company;
        $subj[] = $data['count_servers'] + $data['count_computers'];
        if ($name != '') $subj[] = $name;
        if ($phone != '') $subj[] = $phone;
        $subj = implode(" / ", $subj);

        $mail->setSubject($subj);
        $host = HOST;

        $tariff = $data['it-director'] ? 'IT-директор' : 'Системный администратор';
        $inn = array();
        if ($data['inn'] != '') $inn[] = $data['inn'];
        if ($data['contract_number'] != '') $inn[] = $data['contract_number'];
        $inn_num = implode(" / ", $inn);
        if (trim($inn) == '') $inn = '---';
        if (empty($company)) $company = '---';
        if (empty($name)) $name = '---';
        if (empty($phone)) $phone = '---';
        if (empty($email)) $email = '---';

        $body = <<<EOF
IP адрес отправителя: {$_SERVER['REMOTE_ADDR']}
Тарифный план: {$tariff}
Количество серверов / количество раб. станций: {$data['count_servers']} / {$data['count_computers']}
Инн / номер договора рекомендателя: {$inn_num}
Название компании или ИНН компании: {$company}
Контактное лицо: {$name}
Телефон для связи: {$phone}
E-mail для связи: {$email}
Комментарий к заказу:
{$comments}

EOF;

        $mail->setBody($body);
        $r = $mail->send();

        $result['success'] = $r ? 1 : 0;

        break;
}


echo json_encode($result);
exit;