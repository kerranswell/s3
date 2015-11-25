<?php

$news = $dsp->news_admin->getList();

$b = $dsp->_BuilderPatterns->create_block('news_list', 'news_list', 'center');
$params = $dsp->news_admin->getParams('list');

$b_list = $dsp->_Builder->addNode($dsp->_Builder->createNode('list', array()), $b);
foreach ($news as &$page)
{
    $b_item = $dsp->_Builder->addNode($dsp->_Builder->createNode('item', array()), $b_list);
    $page['date'] = date("d.m.Y H:i", $page['date']);

    foreach ($page as $f => $v)
    {
        $p = isset($params[$f]) ? $params[$f] : array();
        $p['name'] = $f;
        $dsp->_Builder->addNode($dsp->_Builder->createNode('field', $p, $v), $b_item);
    }
}

$b_fields = $dsp->_Builder->addNode($dsp->_Builder->createNode('fields', array()), $b);
foreach ($params as $name => $p)
{
    $dsp->_Builder->addNode($dsp->_Builder->createNode('field', $p, $name), $b_fields);
}




