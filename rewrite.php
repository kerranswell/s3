<?php

//$_core_mode_ = 'wrapper';
require_once(dirname(__FILE__) . "/core/core.php");
//require_once(CLASS_DIR . "/record_admin_class.php");
$dsp->_Builder->addArray(array('timestamp' => time()));

if (empty($_REQUEST['p_']) && $_SERVER['QUERY_STRING'] == 'er=1')
{
    $req = $_SERVER['REQUEST_URI'];
    if (substr_count($req, '%0a%20')) Redirect('/', 301);
    $_REQUEST['p_'] = $_SERVER['REQUEST_URI'];
    $dsp->pages->page404noRedirect();
    exit;
}

// Режим технических работ
if (defined('MAINTENANCE') && MAINTENANCE) {
    if (!_isAjax()) {
        require_once (dirname(__FILE__) . "/maintenance.html");
    }
    exit();
}

Un_magic_quotes();

if (!empty($_POST)) {
    include_once('post_processor.php');
}

parse_str($_SERVER['QUERY_STRING'], $pices);
unset($pices['p_']);
$query_string = http_build_query($pices);

if (isset($pices['_blocks']) || isset($pices['_sblocks'])) {
    $dsp->_Builder->addNode($dsp->_Builder->createNode("debug"));
}

if (!empty($query_string)) 
    $query_string = '?' . $query_string;

$nodes = explode('/', trim($_REQUEST['p_'], '/'));
$full_path = SITE."/".implode("/", $nodes)."/";
$dsp->_Builder->addArray(array('path' => $full_path), 'path_origin');

// редирект в подиум
if (isset($_REQUEST['p_']) && trim($_REQUEST['p_'], '/') == 'moda/podium')
    Redirect('/podium/', 301);

if(!empty($_REQUEST['p_']) && mb_substr($_REQUEST['p_'], -1) != '/' && !preg_match( '/\/[a-zA-Z0-9-_]+\.[a-z]{2,5}$/', $_REQUEST['p_'] ) ) {
    Redirect(SITE . '/' . $_REQUEST['p_'] . '/' . $query_string, 301);
}

$page = 1;
if ( array_search('page', $nodes) ) {
    $key  = (int)array_search('page', $nodes);
    $page = $nodes[$key+1];
    /**
     * если page не число - 404 ошибка
     */
    if ( !is_numeric($page) ){
        $dsp->pages->page404();
    }
    $_REQUEST['p__'] = $_REQUEST['p_'];
    unset($nodes[$key]);
    unset($nodes[$key+1]);
    $_REQUEST['p_'] = implode('/', $nodes) . '/';
}

if ($dsp->auth->isLogged()) {
    $dsp->_Builder->addArray($dsp->auth->user, 'user');
} elseif (isset($_SESSION['notLocalUser'])) {
    $dsp->_Builder->addArray(array('not_local_user' => $_SESSION['notLocalUser']));  
}

$dsp->social_connect->addSocialSettings();

/* #15938 выпилить капчу */ 
//if ($dsp->login_attempts->maxAttemptsReached()) {
//    $dsp->_Builder->addArray(array('need_captcha' => 1));
//}

// токен для логирования ошибок js
if ( strpos( $_SERVER['REQUEST_URI'], '/autologin/' ) === false )
{
    $log_token = md5( session_id() . time() );
    $dsp->_Builder->addArray(array('log_error_token' => $log_token ));
    $_SESSION['log_error_token'] = $log_token;
}

// These URLs don't exist but shouldn't give us 404 error, show index page instead
$reservedUrls = array(
    'qsubscribe_success'    => '/',
    'qsubscribe_fail'       => '/',
);
if (isset($reservedUrls[trim($_REQUEST['p_'], '/')])) {
	$_REQUEST['p_'] = $reservedUrls[trim($_REQUEST['p_'], '/')];
    require(ROOT_DIR . 'pages.php');
    exit;
}

// Controllers for mobile version should lay in 'mobile' folder, it has its own rewrite.php
if (_isMobile() && !defined('MOBILE_404')) {
    require(ROOT_DIR . 'mobile/rewrite.php');
}
if (!_isMobile() && !empty($nodes[0]) && $nodes[0] == 'madv') {
    if (!empty($_REQUEST['redirect_url'])) Redirect($_REQUEST['redirect_url'], 301);
    else Redirect("/");
}
$dsp->blocks->setPlacementTable('placement');
/*$dsp->_Builder->addArray(array(
                            'timestamp'           => time(),
                        ));*/

if (!empty($nodes[0])) {
	if (in_array($nodes[0], array('author', 'brand', 'person')))
	{
		require(ROOT_DIR.'author/rewrite.php');
	} else
    if (is_file(ROOT_DIR . $nodes[0] . "/rewrite.php")) {
        $path = ROOT_DIR . $nodes[0] . "/rewrite.php";
        array_shift($nodes);
        require($path);
    } elseif (is_file(ROOT_DIR . "/" . $nodes[0] . ".php")) {
        $path = ROOT_DIR . "/" . $nodes[0] . ".php";
        array_shift($nodes);
        require($path);
    } elseif (isset($nodes[1]) && is_file(ROOT_DIR . "/" . $nodes[0] . "/" . $nodes[1] . ".php")) {
        $path = ROOT_DIR . "/" . $nodes[0] . "/" . $nodes[1] . ".php";
        array_shift($nodes);
        array_shift($nodes);
        require($path);
    } elseif (is_file(ROOT_DIR . $nodes[0] . "/index.php")) {
        $path = ROOT_DIR . $nodes[0] . "/index.php";
        array_shift($nodes);
        require($path);
    } else {
        require(ROOT_DIR . "pages.php");
        //Redirect(SITE);
    }

    exit();
}

require (ROOT_DIR . 'pages.php');
//Redirect('/'); 404
