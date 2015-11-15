<?php

class pages extends record {

    public $templates = array(
        1 => array('title' => 'Слайдер, светлый', 'value' => 1, 'params' => array('body_class' => 'light', 'xslt' => 'slider')),
        2 => array('title' => 'Слайдер, темный', 'value' => 2, 'params' => array('body_class' => 'dark', 'xslt' => 'slider')),
        3 => array('title' => 'Слайдер, карта', 'value' => 3, 'params' => array('body_class' => 'light', 'xslt' => 'slider')),
    );
    private $structure = 0;
    private $backs = array();

    public function getTemplates()
    {
        return $this->templates;
    }

    public function build()
    {
        $this->dsp->_Builder->addArray(array('templates' => $this->templates), '', array(), $this->getBuilderBlock(), false);
    }

    private function getStructure()
    {
        if (!$this->structure)
        {
            $sql = "select p.*, IF(p.bg_image_inherit > 0, p.bg_image_inherit, p.bg_image) as image_id from `pages` p where p.`status` = 1 order by p.pos asc";
            $rows = $this->dsp->db->Select($sql);

            $this->structure = array();
            foreach ($rows as $row)
            {
                $this->preparePage($row);

                $this->structure['id'][$row['id']] = $row;
                $this->structure['pid'][$row['pid']][$row['id']] = &$this->structure['id'][$row['id']];
            }

            $this->prepareBacks();
        }

        return $this->structure;
    }

    private function prepareBacks()
    {
        $ids = array();
        foreach ($this->backs as $image_id => &$back)
        {
            $ids[] = $image_id;
        }

        if (count($ids) > 0)
        {
            $sql = "select * from `images` where `idx` in (".implode(",", $ids).")";
            $rows = $this->dsp->db->Select($sql);

            foreach ($rows as $r)
            {
                $r['url'] = SITE.IMAGE_FOLDER.$this->dsp->i->getOriginalFromData($r);
                $this->backs[$r['idx']] = $r;
            }
        }
    }

    public function preparePage(&$page)
    {
        if ($page['image_id'] > 0 && !isset($this->backs[$page['image_id']]))
        {
            $this->backs[$page['image_id']] = array();
        }

        $page['body_class'] = $this->templates[$page['template']]['params']['body_class'];

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
        if (isset($this->structure['pid'][$page_id]) && count($this->structure['pid'][$page_id]) == 1)
        {
            $p = reset($this->structure['pid'][$page_id]);
            $nodes[] = $p['translit'];
            Redirect("/".implode("/", $nodes)."/");
        }
        $this->setActiveItems($page_id);
        $this->addValueToXml(array('pages' => $this->structure['id'], 'backs' => $this->backs));
/*        $pages_node = $this->dsp->_Builder->addNode($this->dsp->_Builder->createNode('pages', array()), $this->getBuilderBlock());
        foreach ($this->structure['id'] as $page)
        {
            $xml = $page['xml'];
            unset($page['xml']);
            $node = $this->dsp->_Builder->createNode('item', array('_key' => $page['id']));
            $this->dsp->_Builder->addArray($page, '', array(), $node, false);
            $this->dsp->_Builder->addNode($node, $pages_node);
        }*/

//        $this->addValueToXml(array('backs' => $this->backs));
/*
        $xml = '<rt>Test</rt>';
//        $x = $this->dsp->_Builder->createXMLNode($xml);

        $doc = new SimpleXMLElement($xml);
        $res = $doc->xpath("rt");
        */

//        $this->dsp->_Builder->addNode($x, $this->getBuilderBlock());
        $this->dsp->common->addValueToXml(array('item_id' => $page_id, 'body_class' => $this->templates[$page['template']]['params']['body_class']));

        $template = $this->templates[$page['template']]['params']['xslt'];

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