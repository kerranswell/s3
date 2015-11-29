<?php

$id = $_REQUEST['id'];
$item = $dsp->usersadmin_admin->blankItem();
if ($id > 0) {
    $item = $dsp->usersadmin->GetItem($id);
}
if ($item['role'] == USER_ROLE_SUPER && $dsp->authadmin->user['role'] != USER_ROLE_SUPER) exit;
$b = $dsp->_BuilderPatterns->create_block('usersadmin_edit', 'usersadmin_edit', 'center');
$params = $dsp->usersadmin_admin->getParams('edit');
$dsp->usersadmin_admin->makeRoles();

$b_item = $dsp->_Builder->addNode($dsp->_Builder->createNode('item', array()), $b);
foreach ($params as $f => $p)
{
    $v = isset($item[$f]) ? $item[$f] : '';
    if (isset($_POST['record'][$f])) $v = $_POST['record'][$f];

    $p['name'] = $f;
    if (!empty($dsp->usersadmin_admin->errors[$p['name']])) $p['error'] = $dsp->usersadmin_admin->errors[$p['name']];
    if (is_array($v))
    {
        $b_arr = $dsp->_Builder->addNode($dsp->_Builder->createNode('field', $p), $b_item);
        $dsp->_Builder->addArray($v, '', array(), $b_arr, false);
    } else {
        $dsp->_Builder->addNode($dsp->_Builder->createNode('field', $p, $v), $b_item);
    }
}



