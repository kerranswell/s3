<?php

class pages_admin extends record {

    public $__tablename__  = 'pages';

    protected $table_structure = array(
        'id' => array('type' => 'int', 'params' => array(
            'edit' => array('showtype' => 'none'),
            'list' => array('showtype' => 'none'),
        ), 'title' => 'ID'),
        'pid' => array('type' => 'int', 'params' => array(
            'edit' => array('showtype' => 'none'),
            'list' => array('showtype' => 'none'),
        ), 'title' => 'PID'),
        'title' => array('type' => 'string', 'params' => array(
            'edit' => array('showtype' => 'string'),
            'list' => array('showtype' => 'label'),
        ), 'title' => 'Заголовок'),
        'translit' => array('type' => 'string', 'params' => array(
            'edit' => array('showtype' => 'string'),
            'list' => array('showtype' => 'none'),
        ), 'title' => 'Translit'),
        'text' => array('type' => 'text', 'params' => array(
            'edit' => array('showtype' => 'editor'),
            'list' => array('showtype' => 'none'),
        ), 'title' => 'Текст'),
        'pos' => array('type' => 'int', 'params' => array(
            'edit' => array('showtype' => 'none'),
            'list' => array('showtype' => 'none'),
        ), 'title' => 'Позиция'),
        'status' => array('type' => 'int', 'params' => array(
            'edit' => array('showtype' => 'checkbox'),
            'list' => array('showtype' => 'none'),
        ), 'title' => 'Статус'),
    );

    public function getList()
    {
        $pid = empty($_REQUEST['pid']) ? 0 : (int)$_REQUEST['pid'];

        $sql = "select `id`, `pid`, `title`, `pos`, `status` from `pages` p
                where p.`pid` = ? order by `pos` asc".'';

        $rows = $this->dsp->db->Select($sql, $pid);

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
                case 'int' : $ar[$f] = 0;
                default : $ar[$f] = '';
            }
        }

        return $ar;
    }

    public function updateItem()
    {
        $id = $_REQUEST['id'];
        $save = $_POST['record'];
        if (trim($save['translit']) == '') $save['translit'] = translit($save['title']);

        if ($id > 0)
        {
            $sql = "update `".$this->__tablename__."` set
                `title` = ?,
                `translit` = ?,
                `text` = ?,
                `status` = ?
                where `id` = ?
            ".'';
            $this->dsp->db->Execute($sql, $save['title'], $save['translit'], $save['text'], !empty($save['status']) ? 1 : 0, $id);
        } else {
            $sql = "insert into `pages` (`id`, `title`, `translit`, `text`) values (0, ?, ?)".'';
            $this->dsp->db->Execute($sql, $save['title'], $save['translit'], $save['text'], !empty($save['status']) ? 1 : 0);

            Redirect('/admin/?op=pages&act=edit&id='.$this->dsp->db->LastInsertId());
        }
    }

}

?>