<?php

class news extends record {

    private $structure = 0;
    private $per_page = 10;
    private $pid = 0;

    private function getStructure()
    {
        if (!$this->structure)
        {
            $sql = "select p.id, p.pid, p.title, p.translit, p.`date`, p.status, p.`url` from `".$this->__tablename__."` p where p.`status` = 1 order by p.`date` desc";
            $rows = $this->dsp->db->Select($sql);

            $this->structure = array();
            foreach ($rows as $row)
            {
                $this->preparePage($row);

                $this->structure['id'][$row['id']] = $row;
                $this->structure['pid'][$row['pid']][$row['id']] = &$this->structure['id'][$row['id']];
            }

        }

        return $this->structure;
    }


    public function preparePage(&$page)
    {
        $page['xml'] = $this->dsp->content->prepareXml($page['xml']);
    }

    public function show()
    {
        global $nodes, $page;

        $this->getParts();

        foreach ($this->parts as &$p)
        {
            if ($p['translit'] == $nodes[0])
            {
                $this->pid = $p['id'];
                $p['active'] = 1;
            }
        }
        if (!$this->pid) $this->page404();
        $this->addValueToXml(array('parts' => $this->parts));


        $sql = "select * from ".$this->__tablename__." n where n.`status` = 1 and n.`pid` = ? order by n.`date` desc limit ".($page-1)*$this->per_page.", ".$this->per_page;
        $rows = $this->dsp->db->Select($sql, $this->pid);
        $this->dsp->content->preparePages($rows, array(TH_BLOG_PICTURE));

        $this->addValueToXml(array('items' => $rows));

//        $this->getStructure();

/*        $url = implode("/",$nodes);
        if ($url == "") $url = "/";*/

//        $page_id = $this->getPageIdFromStructureByUrl($url);

        if (isset($nodes[1]) && $nodes[1] != '')
        {
            $this->page404();
        }

//        $page = $this->structure['id'][$page_id];
        $this->addValueToXml(array('items' => $this->structure['id']));

        $this->paginator();

        $template = 'news';

        $this->dsp->_Builder->Transform( $template . '.xsl');

    }

    public function getParts()
    {
        $sql = "select * from ".$this->__tablename__." where `pid` = 0 and status = 1";
        $rows = $this->dsp->db->Select($sql);
        foreach ($rows as $row)
            $this->parts[$row['id']] = $row;
    }

    public function paginator()
    {
        global $page;
        $sql = "select count(*) from ".$this->__tablename__." where status = 1 and pid = ".$this->pid;
        $total = $this->dsp->db->SelectValue($sql);

        $this->addValueToXml(array('paginator' => array('total' => $total)));
    }

    private function setActiveItems($id)
    {
        if (!isset($this->structure['id'][$id])) return;

        $pid = $this->structure['id'][$id]['pid'];
        $this->structure['id'][$id]['is_active'] = 1;
        if ($pid > 0) $this->setActiveItems($pid);
    }

    private function getPageIdFromStructureByUrl($url)
    {
        $this->getStructure();

        foreach ($this->structure['id'] as $id => $r)
        {
            if ($r['url'] == $url) return $id;
        }

        return 0;
    }

    public function page404()
    {
        echo 'Page not found.';
        exit;
    }
}

?>