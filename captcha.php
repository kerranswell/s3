<?php

include(LIB_DIR.'kcaptcha/kcaptcha.php');

if (!session_id()) session_start();

$captcha = new KCAPTCHA();

$_SESSION['captcha_keystring'] = $captcha->getKeyString();
