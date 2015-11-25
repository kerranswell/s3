<?php

class news extends record {

    private $structure = 0;

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
        $page['xml'] = json_decode($page['xml'], true);
        foreach ($page['xml'] as &$b)
        {
            if (isset($b['cells']) && is_array($b['cells'])) {
                foreach ($b['cells'] as &$c) {
                    if (in_array($b['type'], array('quote')))
                    {
                        $c = strip_tags($c);
                        $c = str_replace("\n", '<br />', $c);
                    }
                    $c = $this->dsp->transforms->stripInvalidXml($c);
                    $this->dsp->transforms->replaceEntityBack( $c );
                    $this->dsp->transforms->replaceEntity2Simbols( $c );
                    $this->dsp->transforms->removeCKShit( $c );
                    $c = '<![CDATA['.$c.']]>';
                }
            }
        }

        if (!is_array($page['xml'])) $page['xml'] = array();
    }

    public function show()
    {
        global $nodes;
        $this->getStructure();

        $url = implode("/",$nodes);
        if ($url == "") $url = "/";

        $page_id = $this->getPageIdFromStructureByUrl($url);

        if (!$page_id)
        {
            $this->page404();
        }

        $page = $this->structure['id'][$page_id];
/*        if (isset($this->structure['pid'][$page_id]) && count($this->structure['pid'][$page_id]) == 1)
        {
            $p = reset($this->structure['pid'][$page_id]);
            $nodes[] = $p['translit'];
            Redirect("/".implode("/", $nodes)."/");
        }*/
        $this->setActiveItems($page_id);
        $this->addValueToXml(array('items' => $this->structure['id']));

        $template = 'blog';

        $this->dsp->_Builder->Transform( $template . '.xsl');

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