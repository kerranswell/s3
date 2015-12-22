<?php

class usersadmin_admin extends record {


    public $__tablename__  = 'usersadmin';
    public $act = 'list';

    protected $table_structure = array(
        'id' => array('type' => 'int', 'params' => array(
            'edit' => array('showtype' => 'none'),
            'list' => array('showtype' => 'none'),
        ), 'title' => 'ID'),
        'login' => array('type' => 'int', 'params' => array(
            'edit' => array('showtype' => 'string'),
            'list' => array('showtype' => 'label'),
        ), 'title' => 'Login'),
        'pass' => array('type' => 'string', 'params' => array(
            'edit' => array('showtype' => 'password'),
            'list' => array('showtype' => 'none'),
        ), 'title' => 'Пароль'),
        'pass2' => array('type' => 'string', 'params' => array(
            'edit' => array('showtype' => 'password'),
            'list' => array('showtype' => 'none'),
        ), 'title' => 'Подтверждение'),
        'status' => array('type' => 'int', 'params' => array(
            'edit' => array('showtype' => 'checkbox'),
            'list' => array('showtype' => 'none'),
        ), 'title' => 'Статус'),
        'role' => array('type' => 'int', 'params' => array(
            'edit' => array('showtype' => 'select', 'xml_options' => '/root/usersadmin_admin_class/user_roles'),
            'list' => array('showtype' => 'label'),
        ), 'title' => 'Роль'),
    );

    protected function init()
    {
        $this->act = empty($_REQUEST['act']) ? 'list' : $_REQUEST['act'];
    }


    public function getPath()
    {
        $rows = array(array('id' => 0, 'pid' => 0, 'title' => 'Пользователи'));

        return $rows;
    }

    public function getList()
    {
        $wheres = array();
        switch ($this->dsp->authadmin->user['role'])
        {
            case USER_ROLE_SUPER :
                $wheres[] = "1";
                break;
            default: $wheres[] = "role != ".USER_ROLE_SUPER;
        }
        if (count($wheres) > 0) $wheres = " where (".implode(") and (", $wheres).")";
        $sql = "select `id`, `login`, `role` from `".$this->__tablename__."`
                ".$wheres;

        $rows = $this->dsp->db->Select($sql);
        foreach ($rows as &$row)
        {
            $row['role'] = $this->dsp->usersadmin->user_roles[$row['role']];
        }

        return $rows;
    }

    public function getParams($place)
    {
        $ar = array();
        foreach ($this->table_structure as $f => $d)
        {
            $ar[$f] = $d['params'][$place];
            $ar[$f]['title'] = $d['title'];
        }

        return $ar;
    }

    public function blankItem()
    {
        $ar = array();
        foreach ($this->table_structure as $f => $d)
        {
            switch ($d['type'])
            {
                case 'int' : $ar[$f] = 0; break;
                case 'date' : $ar[$f] = date('d.m.Y H:i'); break;
                default : $ar[$f] = '';
            }
        }

        return $ar;
    }

    public function makeRoles()
    {
        $rows = array();
        foreach ($this->dsp->usersadmin->user_roles as $id => $title)
        {
            if ($this->dsp->authadmin->user['role'] != USER_ROLE_SUPER && $id == USER_ROLE_SUPER) continue;
            $rows[] = array('value' => $id, 'title' => $title);
        }
        $this->dsp->_Builder->addArray(array('user_roles' => $rows), '', array(), $this->getBuilderBlock(), false);
    }

    public function updateItem()
    {
        $id = $_REQUEST['id'];
        if ($id > 0)
        {
            $item = $this->GetItem($id);
        }
        if ($item['role'] == USER_ROLE_SUPER && $this->dsp->authadmin->user['role'] != USER_ROLE_SUPER) return;
//echo '<pre>'; print_r($_POST); echo '</pre>'; exit;
        $save = $_POST['record'];
        $save['id'] = $id;

        $this->errors = $this->checkUpdate($save);
        unset($save['pass2']);

        if (count($this->errors) > 0)
        {
            return;
        }

        if (!empty($save['login'])) {
            $save['login'] = strtolower($save['login']);
        }

        if ($id > 0)
        {
            if ($save['pass'] == '') $save['pass'] = $item['pass'];
            else {
                $save['pass'] = sha1($save['pass'].$item['salt']);
            }

            $sql = "update `".$this->__tablename__."` set
                `login` = ?,
                `pass` = ?,
                `status` = ?
                where `id` = ?
            ".'';

            $this->dsp->db->Execute($sql, $save['login'], $save['pass'], !empty($save['status']) ? 1 : 0, $id);

            if ($this->dsp->authadmin->IsLogged() && $id == $this->dsp->authadmin->user['id']) {
                $this->dsp->authadmin->user = array_merge($this->dsp->authadmin->user, $save);
            }

            Redirect('/admin/?op=usersadmin&act=edit&id='.$id);
        } else {
            $save['salt'] = generatePass();
            $save['pass'] = sha1($save['pass'].$save['salt']);
            $sql = "insert into `usersadmin` (`id`, `login`, `pass`, `salt`, `status`, `role`) values (0, ?, ?, ?, ?, ?)".'';
            $this->dsp->db->Execute($sql, $save['login'], $save['pass'], $save['salt'], !empty($save['status']) ? 1 : 0, $save['role']);

            $new_id = $this->dsp->db->LastInsertId();

            $_SESSION['admin_message'] = 'Пользователь успешно создан';

            Redirect('/admin/?op=usersadmin&act=edit&id='.$new_id);
        }
    }

    protected function checkUpdate($item)
    {
        $errors = array();
        if (strlen(trim($item['login'])) < 4) $errors['login'] = 'Слишком короткий логин, должно быть не менее 4 символов';
        if (trim($item['login']) == '') $errors['login'] = 'Необходимо заполнить это поле';
        $sql = "select count(*) from `".$this->__tablename__."` where `login` = ? and `id` != ?".'';
        $v = $this->dsp->db->SelectValue($sql, $item['login'], $item['id']);
        if ($v > 0) $errors['login'] = 'Такой логин уже существует';

        if ($item['pass'] != '' && strlen($item['pass']) < 6) $errors['pass'] = 'Слишком короткий пароль, должно быть не менее 6 символов';

        if ($item['pass'] != $item['pass2'])
        {
            $errors['pass'] = 'Пароли не совпадают';
        }

        return $errors;
    }

}