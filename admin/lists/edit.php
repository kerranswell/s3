<?php

$id = $_REQUEST['id'];
$item = $dsp->lists_admin->blankItem();
if ($id > 0) $item = $dsp->lists->GetItem($id);

if ($item['bg_image'] > 0) $item['bg_image'] = $dsp->i->default_path.$dsp->i->resize($item['bg_image'], TH_LISTS_BG_IMAGE_ADMIN);
$b = $dsp->_BuilderPatterns->create_block('lists_edit', 'lists_edit', 'center');
$params = $dsp->lists_admin->getParams('edit');

$b_item = $dsp->_Builder->addNode($dsp->_Builder->createNode('item', array()), $b);

foreach ($params as $f => $p)
{
    $v = isset($item[$f]) ? $item[$f] : '';
    if (isset($_POST['record'][$f])) $v = $_POST['record'][$f];
    $p['name'] = $f;
    if (!empty($dsp->lists_admin->errors[$p['name']])) $p['error'] = $dsp->lists_admin->errors[$p['name']];
    if (is_array($v))
    {
        $b_arr = $dsp->_Builder->addNode($dsp->_Builder->createNode('field', $p), $b_item);
        $dsp->_Builder->addArray($v, '', array(), $b_arr, false);
    } else {
        $dsp->_Builder->addNode($dsp->_Builder->createNode('field', $p, $v), $b_item);
    }
}



