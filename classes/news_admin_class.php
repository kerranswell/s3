<?php

class news_admin extends record {

    public $__tablename__  = 'news';
    public $pid = 0;
    public $act = 'list';
    public $service_id = 0;

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
        'translit' => array('type' => 'string', 'params' => array(
            'edit' => array('showtype' => 'none'),
            'list' => array('showtype' => 'none'),
        ), 'title' => 'Translit'),
        'text' => array('type' => 'text', 'params' => array(
            'edit' => array('showtype' => 'none'),
            'list' => array('showtype' => 'none'),
        ), 'title' => 'Текст'),
        'status' => array('type' => 'int', 'params' => array(
            'edit' => array('showtype' => 'checkbox'),
            'list' => array('showtype' => 'none'),
        ), 'title' => 'Статус'),
        'date' => array('type' => 'date', 'params' => array(
            'edit' => array('showtype' => 'date'),
            'list' => array('showtype' => 'label'),
        ), 'title' => 'Дата'),
        'xml' => array('type' => 'text', 'params' => array(
            'edit' => array('showtype' => 'xml'),
            'list' => array('showtype' => 'none'),
        ), 'title' => 'Контент'),
        'url' => array('type' => 'text', 'params' => array(
            'edit' => array('showtype' => 'none'),
            'list' => array('showtype' => 'none'),
        ), 'title' => 'Url'),
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

        $this->service_id = $this->dsp->services->services['name'][$this->__tablename__]['id'];

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

    public function sortFields($item)
    {
        $new = array();
        foreach ($this->table_structure as $name => $t)
        {
//            if (isset($item[$name]))
        }

        return $item;
    }

    public function getList()
    {
        $pid = $this->pid;

        $sql = "select `id`, `pid`, `title`, `status`, `date` from `".$this->__tablename__."` p
                where p.`pid` = ? order by `date` desc".'';

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
                case 'int' : $ar[$f] = 0; break;
                case 'date' : $ar[$f] = date('d.m.Y H:i'); break;
                default : $ar[$f] = '';
            }
        }

        return $ar;
    }

    private function makeUrl($pid = 0, $translit = '')
    {
        $url = array();

        if ($pid > 0)
        {
            $sql = "select `pid`, `translit` from news where `id` = ?";
            $row = $this->dsp->db->SelectRow($sql, $pid);
            if ($row['translit'] != '') $url[] = $row['translit'];

            $url2 = $this->makeUrl($row['pid']);
            $url = array_merge($url2, $url);
        }

        if ($translit != '') $url[] = $translit;

        return $url;
    }

    public function updateItem()
    {
        $id = $_REQUEST['id'];
        if ($id > 0)
        {
            $item = $this->GetItem($id, 'xml');
        }

        $save = $_POST['record'];
        $save['id'] = $id;
        $save['date'] = strtotime($save['date']);
        $pid = $this->pid;

//        $url = $this->makeUrl($pid, $save['translit']);
//        $save['url'] = implode("/", $url);

        if ($id > 0) $save['xml'] = $this->dsp->content->xml_beforeUpdate($save['xml'], $item['xml'], $this->service_id, $id);

        $this->errors = $this->checkUpdate($save);

        if (count($this->errors) > 0)
        {
            return;
        }

        if ($id > 0)
        {
            $sql = "update `".$this->__tablename__."` set
                `title` = ?,
                `text` = ?,
                `status` = ?,
                `xml` = ?,
                `date` = ?
                where `id` = ?
            ".'';
            $this->dsp->db->Execute($sql, $save['title'], $save['text'], !empty($save['status']) ? 1 : 0, $save['xml'], $save['date'], $id);
            Redirect('/admin/?op=news&act=edit&id='.$id);
        } else {
            $sql = "insert into `".$this->__tablename__."` (`id`, `pid`, `title`, `text`, `status`, `xml`, `date`) values (0, ?, ?, ?, ?, ?, ?)".'';
            $this->dsp->db->Execute($sql, $pid, $save['title'], $save['text'], !empty($save['status']) ? 1 : 0, $save['xml'], $save['date']);

            Redirect('/admin/?op=news&act=edit&id='.$this->dsp->db->LastInsertId());
        }
    }

    protected function checkUpdate($item)
    {
        $errors = array();
        if (trim($item['title']) == '') $errors['title'] = 'Необходимо заполнить это поле';

        return $errors;
    }

    public function deleteItem($id)
    {
        $item = $this->GetItem($id, 'xml');
        $this->dsp->content->deleteAllImages($item['xml']);
        $this->dsp->db->Execute("delete from `".$this->__tablename__."` where `id` = ?", $id);
    }

}

?>