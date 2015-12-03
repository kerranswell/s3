<?php

class lists extends record {

    private $structure = 0;
    private $backs = array();

    public function getTemplates()
    {
        return $this->templates;
    }

    public function build()
    {
        $this->getStructure();
        $this->dsp->_Builder->addArray(array('lists' => $this->structure['id']), '', array(), $this->getBuilderBlock(), false);
    }

    private function getStructure()
    {
        if (!$this->structure)
        {
            $sql = "select p.* from `lists` p where p.`status` = 1 order by p.pos asc";
            $rows = $this->dsp->db->Select($sql);

            $this->structure = array();
            foreach ($rows as &$row)
            {
/*                switch ($row['pid'])
                {
                    case 6 : $row['image_url'] = $this->dsp->i->default_path.$this->dsp->i->getOriginalFromData(); break;
                    default : $row['image_url'] = $this->dsp->i->default_path.$this->dsp->i->resize($row['bg_image'], TH_TEAM_IMAGE); break;
                }
*/
                $this->structure['id'][$row['id']] = $row;
                $this->structure['pid'][$row['pid']][$row['id']] = &$this->structure['id'][$row['id']];
            }

//            $this->structure['id'] = array_merge($this->structure['id'], $this->structure['pid'][6]);

            $this->preparePics();
        }

        return $this->structure;
    }

    private function preparePics()
    {
        $ids = array();
        foreach ($this->structure['id'] as $p)
        {
            $ids[] = $p['bg_image'];
        }

        if (count($ids) > 0)
        {
            $sql = "select * from `images` where `idx` in (".implode(",", $ids).")";
            $rows = $this->dsp->db->Select($sql);

            $pics = array();
            foreach ($rows as $r)
            {
                $pics[$r['idx']][6] = SITE.IMAGE_FOLDER.$this->dsp->i->getOriginalFromData($r);
                $pics[$r['idx']][5] = $this->dsp->i->default_path.$this->dsp->i->resizeFromData($r, TH_TEAM_IMAGE);
            }

            foreach ($this->structure['id'] as &$p)
            {
                if (!isset($pics[$p['bg_image']])) continue;
                $p['image_url'] = $pics[$p['bg_image']][$p['pid']];
            }

        }
    }

}

?>