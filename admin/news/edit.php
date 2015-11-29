<?php

$id = $_REQUEST['id'];
$item = $dsp->news_admin->blankItem();
if ($id > 0) {
    $item = $dsp->news->GetItem($id);
    $sql = "select t.`id`, t.`title` from tags2items ti
            left join `tags` t on (t.id = ti.tags_id)
            where ti.item_id = ? and ti.service_id = ?";
    $item['tags'] = $dsp->db->Select($sql, $id, $dsp->news_admin->service_id);

    $item['date'] = date('d.m.Y H:i', $item['date']);
}

if (isset($item['xml']) && $item['xml'] != '') $item['xml'] = json_decode($item['xml'], true);
$item['xml'] = $dsp->content->xml_prepare($item['xml']);
$b = $dsp->_BuilderPatterns->create_block('news_edit', 'news_edit', 'center');
$params = $dsp->news_admin->getParams('edit');

$dsp->content->makeBlock();
$dsp->content->makeXml($item['xml']);

$b_item = $dsp->_Builder->addNode($dsp->_Builder->createNode('item', array()), $b);
$dsp->common->addValueToXml(array('service_id' => $dsp->news_admin->service_id));
foreach ($params as $f => $p)
//foreach ($item as $f => $v)
{
    $v = isset($item[$f]) ? $item[$f] : '';
    if (isset($_POST['record'][$f])) $v = $_POST['record'][$f];
//    $p = isset($params[$f]) ? $params[$f] : array();
    $p['name'] = $f;
    if (!empty($dsp->news_admin->errors[$p['name']])) $p['error'] = $dsp->news_admin->errors[$p['name']];
    if (is_array($v))
    {
        $b_arr = $dsp->_Builder->addNode($dsp->_Builder->createNode('field', $p), $b_item);
        $dsp->_Builder->addArray($v, '', array(), $b_arr, false);
    } else {
        $dsp->_Builder->addNode($dsp->_Builder->createNode('field', $p, $v), $b_item);
    }
}



