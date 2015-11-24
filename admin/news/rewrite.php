<?php

$path = $dsp->news_admin->getPath();

$block = $dsp->_BuilderPatterns->create_block('path', 'path', 'center');
$dsp->_Builder->addArray($path, '', array(), $block, false);

$mod_params = array('act' => $dsp->news_admin->act);
$b_common = $dsp->_Builder->addNode($dsp->_Builder->createNode('mod_params', array()));
$dsp->_Builder->addArray($mod_params, '', array(), $b_common, false);

$act = empty($_REQUEST['act']) ? 'list' : $_REQUEST['act'];

$f = ADMIN_DIR . "/" . $op . "/".$act.".php";
if (is_file($f)) require ($f);

