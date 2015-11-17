<?php

class lists_admin extends record {

    public $__tablename__  = 'lists';
    public $pid = 0;
    public $act = 'list';

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
            'list' => array('showtype' => 'link_children'),
        ), 'title' => 'Заголовок'),
        'description' => array('type' => 'string', 'params' => array(
            'edit' => array('showtype' => 'string'),
            'list' => array('showtype' => 'none'),
        ), 'title' => 'Краткое описание'),
        'url' => array('type' => 'string', 'params' => array(
            'edit' => array('showtype' => 'string'),
            'list' => array('showtype' => 'none'),
        ), 'title' => 'Ссылка'),
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
        'bg_image' => array('type' => 'int', 'params' => array(
            'edit' => array('showtype' => 'image'),
            'list' => array('showtype' => 'none'),
        ), 'title' => 'Фото'),
        'status' => array('type' => 'int', 'params' => array(
            'edit' => array('showtype' => 'checkbox'),
            'list' => array('showtype' => 'none'),
        ), 'title' => 'Статус'),
    );

    protected function init()
    {
        $this->pid = empty($_REQUEST['pid']) ? 0 : (int)$_REQUEST['pid'];
        if (empty($_SESSION[$this->__tablename__]['pid'])) $_SESSION[$this->__tablename__]['pid'] = $this->pid;

        if (isset($_REQUEST['pid'])) $_SESSION[$this->__tablename__]['pid'] = $this->pid;
        else $this->pid = $_SESSION[$this->__tablename__]['pid'];

        if ($this->pid > 0)
        {
            $this->table_structure['title']['params']['list']['showtype'] = 'label';
        }

        $this->act = empty($_REQUEST['act']) ? 'list' : $_REQUEST['act'];
    }

    public function getPath($pid = -1)
    {
        if ($pid < 0) $pid = $this->pid;

        $rows = array();

        if ($pid > 0)
        {
            $row = $this->GetItem($pid, '`id`, `pid`, `title`');
            $rows[] = $row;
            $rows = array_merge($this->getPath($row['pid']), $rows);
        } else {
            $rows = array_merge(array(array('id' => 0, 'pid' => -1, 'title' => 'Корень')), $rows);
        }

        return $rows;
    }

    public function getList()
    {
        $pid = $this->pid;

        $sql = "select `id`, `pid`, `title`, `pos`, `status` from `".$this->__tablename__."` p
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
        $item = array();
        if ($id > 0)
        {
            $item = $this->GetItem($id, 'bg_image');
        }

        $save = $_POST['record'];
        $save['id'] = $id;
        $pid = $this->pid;
        if (trim($save['translit']) == '') $save['translit'] = translit($save['title']);

        # delete background image
        if ((isset($_POST['bg_image_delete']) || !empty($_FILES['record']['tmp_name']['bg_image'])) && $item['bg_image'] > 0)
        {
            $this->dsp->i->clearByIDX($item['bg_image']);
            $save['bg_image'] = 0;
        }

        $this->errors = $this->checkUpdate($save);

        if (count($this->errors) > 0)
        {
            return;
        }

        if (!empty($_FILES['record']['tmp_name']['bg_image']))
        {
            $f = $this->dsp->i->getFileFromArray($_FILES['record'], 'bg_image');
            list($save['bg_image'],) = $this->dsp->i->putToPlace($f);
        }

        if ($id > 0)
        {
            if (!isset($save['bg_image'])) $save['bg_image'] = $item['bg_image'];
            $sql = "update `".$this->__tablename__."` set
                `title` = ?,
                `description` = ?,
                `translit` = ?,
                `url` = ?,
                `text` = ?,
                `status` = ?,
                `bg_image` = ?
                where `id` = ?
            ".'';
            $r = $this->dsp->db->Execute($sql, $save['title'], $save['description'], $save['translit'], $save['url'], $save['text'], !empty($save['status']) ? 1 : 0, $save['bg_image'], $id);
            Redirect('/admin/?op='.$this->__tablename__.'&act=edit&id='.$id);
        } else {
            $pos = $this->dsp->db->SelectValue("select `pos` from `".$this->__tablename__."` where `pid` = ? order by `pos` desc limit 1".'', $pid);
            if (!$pos) $pos = 0; else $pos++;
            $sql = "insert into `".$this->__tablename__."` (`id`, `pid`, `title`, `description`, `translit`, `url`, `text`, `status`, `pos`, `bg_image`) values (0, ?, ?, ?, ?, ?, ?, ?, ?, ?)".'';
            $this->dsp->db->Execute($sql, $pid, $save['title'], $save['description'], $save['translit'], $save['url'], $save['text'], !empty($save['status']) ? 1 : 0, $pos, $save['bg_image']);

            Redirect('/admin/?op='.$this->__tablename__.'&act=edit&id='.$this->dsp->db->LastInsertId());
        }
    }

    protected function checkUpdate($item)
    {
        $errors = array();
        if (trim($item['title']) == '') $errors['title'] = 'Необходимо заполнить это поле';
        $sql = "select count(*) from `".$this->__tablename__."` where `translit` = ? and `id` != ?".'';
        $v = $this->dsp->db->SelectValue($sql, $item['translit'], $item['id']);
        if ($v > 0) $errors['translit'] = 'Такой url уже существует';

        return $errors;
    }

}

?>