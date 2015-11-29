<?php

class news extends record {

    private $structure = 0;
    private $per_page = 10;
    private $pid = 0;

    protected function init()
    {
        $this->service_id = $this->dsp->services->services['name'][$this->__tablename__]['id'];
    }

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

        $tag = 0;
        if (isset($nodes[1]) && $nodes[1] == 'tag' && isset($nodes[2]) && is_numeric($nodes[2]))
        {
            $sql = "select n.* from ".$this->__tablename__." n
                    left join tags2items ti on (ti.item_id = n.id and ti.service_id = ?)
                where n.`status` = 1 and ti.tags_id = ? and n.`pid` = ? group by n.id  order by n.`date` desc limit ".($page-1)*$this->per_page.", ".$this->per_page;
                $rows = $this->dsp->db->Select($sql, $this->service_id, $nodes[2], $this->pid);
            $tag = $nodes[2];
            $this->dsp->common->addValueToXml(array('tag' => $tag));
        } else
        if (isset($nodes[1]) && $nodes[1] != '')
        {
            $this->page404();
        } else {
            $sql = "select n.* from ".$this->__tablename__." n
                where n.`status` = 1 and n.`pid` = ? order by n.`date` desc limit ".($page-1)*$this->per_page.", ".$this->per_page;
            $rows = $this->dsp->db->Select($sql, $this->pid);
        }

        $this->dsp->content->preparePages($rows, array(TH_BLOG_PICTURE));
        $n_ids = array();
        foreach ($rows as &$row)
        {
            $row['date'] = dateFormatted($row['date']);
            $n_ids[] = $row['id'];
        }

        if (count($n_ids) > 0)
        {
            $sql = "select t.title, t.id, ti.item_id from tags2items ti
                    left join tags t on (t.id = ti.tags_id)
                    where ti.item_id in (".implode(",", $n_ids).") and ti.service_id = ?
                    ";
            $tags = $this->dsp->db->Select($sql, $this->service_id);
            foreach ($rows as &$row)
            {
                if (!isset($row['tags'])) $row['tags'] = array();
                foreach ($tags as $t)
                {
                    if ($row['id'] == $t['item_id']) $row['tags'][] = $t;
                }
            }
        }

        $this->addValueToXml(array('items' => $rows));

        $this->dsp->common->addValueToXml(array('news_pid' => $this->pid));

        $this->paginator($tag);

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

    public function paginator($tag = 0)
    {
        global $page;
        if ($tag > 0)
        {
            $sql = "select count(*) from ".$this->__tablename__." n
                    left join tags2items ti on (ti.item_id = n.id and ti.service_id = ?)
                    where n.status = 1 and ti.tags_id = ? and n.pid = ?";
            $total = $this->dsp->db->SelectValue($sql, $this->service_id, $tag, $this->pid);
        } else {
            $sql = "select count(*) from ".$this->__tablename__." where status = 1 and pid = ".$this->pid;
            $total = $this->dsp->db->SelectValue($sql);
        }

        $pages = array();
        $total_pages = ceil($total / $this->per_page);
        for ($i=1; $i <= $total_pages; $i++)
        {
            $p = array();
            $p['num'] = $i;
            $p['active'] = ($i == $page) ? true : false;
            $pages[] = $p;
        }

        $this->addValueToXml(array('paginator' => array('total' => $total, 'page' => $page, 'pages' => $pages, 'total_pages' => $total_pages, 'pre_url' => '/'.$this->parts[$this->pid]['url'].'/'.($tag > 0 ? 'tag/'.$tag.'/' : ''))));
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