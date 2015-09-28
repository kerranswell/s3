<?php

$id = $_REQUEST['id'];
$item = $dsp->pages_admin->blankItem();
if ($id > 0) $item = $dsp->pages->GetItem($id);


$b = $dsp->_BuilderPatterns->create_block('pages_edit', 'pages_edit', 'center');
$params = $dsp->pages_admin->getParams('edit');
$b_item = $dsp->_Builder->addNode($dsp->_Builder->createNode('item', array()), $b);
foreach ($item as $f => $v)
{
    $p = isset($params[$f]) ? $params[$f] : array();
    $p['name'] = $f;
    $dsp->_Builder->addNode($dsp->_Builder->createNode('field', $p, $v), $b_item);
}



