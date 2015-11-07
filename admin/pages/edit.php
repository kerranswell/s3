<?php

$id = $_REQUEST['id'];
$item = $dsp->pages_admin->blankItem();
if ($id > 0) $item = $dsp->pages->GetItem($id);
if (isset($item['xml']) && $item['xml'] != '') $item['xml'] = json_decode($item['xml'], true);
if ($item['bg_image'] > 0) $item['bg_image'] = $dsp->i->default_path.$dsp->i->resize($item['bg_image'], TH_BG_IMAGE_ADMIN);
$b = $dsp->_BuilderPatterns->create_block('pages_edit', 'pages_edit', 'center');
$params = $dsp->pages_admin->getParams('edit');

$dsp->content->makeBlock();

$b_item = $dsp->_Builder->addNode($dsp->_Builder->createNode('item', array()), $b);
foreach ($item as $f => $v)
{
    if (isset($_POST['record'][$f])) $v = $_POST['record'][$f];
    $p = isset($params[$f]) ? $params[$f] : array();
    $p['name'] = $f;
    if (!empty($dsp->pages_admin->errors[$p['name']])) $p['error'] = $dsp->pages_admin->errors[$p['name']];
    if (is_array($v))
    {
        $b_arr = $dsp->_Builder->addNode($dsp->_Builder->createNode('field', $p), $b_item);
        $dsp->_Builder->addArray($v, '', array(), $b_arr, false);
    } else {
        $dsp->_Builder->addNode($dsp->_Builder->createNode('field', $p, $v), $b_item);
    }
}



