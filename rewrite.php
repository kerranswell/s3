<?php
ob_start();
//$_core_mode_ = 'wrapper';
require_once(dirname(__FILE__) . "/core/core.php");
//require_once(CLASS_DIR . "/record_admin_class.php");
//if ($_GET['sess']) {session_start(); print_r($_SESSION); exit;}
if ($_REQUEST['p_'] == '')
{
    $_REQUEST['p_'] = 'intro/';
    $_REQUEST['root'] = 1;
}

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


/* Init stuff */

$num = time();
if (file_exists(ROOT_DIR."admin/version.txt"))
{
    $f = file_get_contents(ROOT_DIR."admin/version.txt");

    if (preg_match("|([0-9]+)|", $f, $matches))
    {
        $num = (int)$matches[1];
    }
}

/*$pass_intro = $_COOKIE['pass_intro'] == 1 ? 1 : 0;

if (!empty($nodes[0]) && $nodes[0] == 'intro')
{
    setcookie(
        'pass_intro',
        1,
        null,	// + 30 days
        '/',
        HOST,
        false
    );
}*/

/*if (!session_id()) session_start();
print_r($_SESSION); exit;
if (!empty($_SESSION['pass_intro']) && $_SESSION['pass_intro'] == 1) $pass_intro = 1;
else if (!empty($nodes[0]) && $nodes[0] == 'intro')
{
    $pass_intro = 0;
    $_SESSION['pass_intro'] = 1;
}*/

$dsp->_Builder->addArray(array('timestamp' => time(), 'version' => $num, 'mobile' => isset($_REQUEST['mobile']) ? 1 : 0, 'pass_intro' => $pass_intro));

if (!empty($nodes[0]) && $nodes[0] == 'intro' && $pass_intro)
{
    Redirect("/about/mission/");
}

/* Init stuff */




$inodes = implode("/", $nodes);
$full_path = SITE."/".($inodes != '' ? implode("/", $nodes)."/" : "");
$dsp->_Builder->addArray(array('path' => $full_path), 'path_origin');

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

/*if ($dsp->auth->isLogged()) {
    $dsp->_Builder->addArray($dsp->auth->user, 'user');
} elseif (isset($_SESSION['notLocalUser'])) {
    $dsp->_Builder->addArray(array('not_local_user' => $_SESSION['notLocalUser']));  
}*/


if (!empty($nodes[0])) {
    if ($nodes[0] == 'news' || $nodes[0] == 'blog') {
        require(ROOT_DIR . "news.php");
    }
    else if (is_file(ROOT_DIR . $nodes[0] . "/rewrite.php")) {
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
