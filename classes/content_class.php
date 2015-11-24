<?php

class content {

    public $blocks = array();

    public function content () {
        $this->block_types[] = array('name' => 'column1', 'type' => 'column', 'title' => '1 колонка', 'params' => array('count' => 1), 'modules' => array('pages', 'news'));
        $this->block_types[] = array('name' => 'column2', 'type' => 'column', 'title' => '2 колонки', 'params' => array('count' => 2), 'modules' => array('pages'));
        $this->block_types[] = array('name' => 'quote', 'type' => 'quote', 'title' => 'Цитата', 'params' => array('count' => 1), 'modules' => array('pages'));
        $this->block_types[] = array('name' => 'contacts', 'type' => 'contacts', 'title' => 'Контакты', 'params' => array('count' => 3), 'modules' => array('pages'));
        $this->block_types[] = array('name' => 'team', 'type' => 'abstract_block', 'title' => 'Команда', 'params' => array(), 'modules' => array('pages'));
        $this->block_types[] = array('name' => 'companies', 'type' => 'abstract_block', 'title' => 'Компании', 'params' => array(), 'modules' => array('pages'));
        $this->block_types[] = array('name' => 'picture', 'type' => 'picture', 'title' => 'Картинка', 'params' => array('count' => 1), 'modules' => array('news'));
    }

    public function makeBlock() {
        $dom_block = $this->dsp->_BuilderPatterns->create_block('content_blocks', 'content_blocks', '');
        $this->dsp->_Builder->addArray($this->block_types, '', array(), $dom_block, false);
    }

    public function ajax()
    {
        switch ($_REQUEST['act'])
        {
            case 'getBlockHtml' : $this->getBlockHtml(); break;
            case 'block_picture' : $this->uploadPicture(); break;
            case 'delete_image' : $this->deleteImage(); break;
        }
    }

    public function deleteImage()
    {
        $idx = (int)$_POST['id'];
        if ($idx > 0)
            $this->dsp->i->clearByIDX($idx);
    }

    public function makeXml(&$xml)
    {
        if (!is_array($xml)) return;
        foreach ($xml as &$item)
        {
            foreach ($this->block_types as $b)
            {
                if ($b['name'] == $item['name'])
                {
                    $item['type'] = $b['type'];
                    $item['title'] = $b['title'];
                    break;
                }
            }
        }
    }

    public function xml_beforeUpdate($xml, $xml_old, $service_id, $item_id)
    {
        $xml = json_decode($xml, ARRAY_A);
        $xml_old = json_decode($xml_old, ARRAY_A);

        $old_ids = array();
        foreach ($xml_old as $xi)
        {
            if ($xi['type'] == 'picture')
            {
                $id = reset($xi['cells']);
                if (is_numeric($id)) $old_ids[$id] = 1;
            }
        }

        foreach ($xml as &$xi)
        {
            if ($xi['type'] == 'picture')
            {
                $in = reset($xi['cells']);
                if (is_numeric($in))
                {
                    if (isset($old_ids[$in])) unset($old_ids[$in]);
                    continue;
                }

                if (isset($_FILES['block_picture']['tmp_name'][$in]))
                {
                    $f = $this->dsp->i->getFileFromArray($_FILES['block_picture'], $in);
                    list($idx,) = $this->dsp->i->putToPlace($f, $service_id, $item_id);
                    $xi['cells'][0] = $idx;
                }
            }
        }

        foreach ($old_ids as $id => $t)
        {
            $this->dsp->i->clearByIDX($id);
        }

        return json_encode($xml);
    }

    public function deleteAllImages($xml)
    {
        $xml = json_decode($xml, ARRAY_A);
        foreach ($xml as &$xi)
        {
            if ($xi['type'] == 'picture')
            {
                $in = reset($xi['cells']);
                if (is_numeric($in))
                {
                    $this->dsp->i->clearByIDX($in);
                }
            }
        }
    }

    public function xml_prepare($xml)
    {
        if (!is_array($xml))
        {
            $xml = json_decode($xml, ARRAY_A);
        }

        $idx = array();
        foreach ($xml as &$xi)
        {
            if ($xi['type'] == 'picture')
            {
                $id = reset($xi['cells']);
                if (is_numeric($id)) $idx[] = $id;
            }
        }

        if (count($idx) > 0)
        {
            $images = $this->dsp->i->getImagesArray($idx);
            $previews = $this->dsp->i->resizeFromArray($images, TH_BLOCK_PICTURE_PREVIEW);
        }

        foreach ($xml as &$xi)
        {
            if ($xi['type'] == 'picture')
            {
                $idx = reset($xi['cells']);
                if (isset($previews[$idx]))
                    $xi['cells'] = array('idx' => $idx, 'path' => $this->dsp->i->default_path.$previews[$idx]);
                else $xi['cells'] = array(0);
            }
        }

        return $xml;
    }

    private function getBlockHtml()
    {
        $block_name = $_REQUEST['block_name'];

        $this->makeBlock();

        $dom_block = $this->dsp->_BuilderPatterns->create_block('content_block', 'content_block', 'ajax');
        $this->dsp->_Builder->addArray(array('block_name' => $block_name), '', array(), $dom_block, false);


        $r = $this->dsp->_Builder->Transform('admin/ajax.xsl', true);
        $r = str_replace('<?xml version="1.0"?>'.PHP_EOL, '', $r);
        echo $r;
        exit;
    }

    private function uploadPicture()
    {
        $f = $_POST['block_picture'];
        $inf = getimagesize($f['tmp_name'], $ind);
        $size = filesize($f['tmp_name']);
        if (!$size) {
            return array('error' => 'Размер фото должен быть не более 5мб');
        }

        if ($size > 1024*1024*5) {
            return array('error' => 'Размер фото должен быть не более 5мб');
        }

        $p = $this->dsp->i->putToPlace($f);
        if (!$p) return array('error' => $this->dsp->session->GetParam('admin_error'));

        $res = array('url' => sprintf('%s/th.php?url=%s', SITE, $this->dsp->i->resize($p[0], TH_BLOCK_PICTURE_PREVIEW)), 'val' => $p[0]);
        if (is_array($res))
        {
            if (!empty($res['error']))
            {
                $result['error'] = $res['error'];
            } else {
                $result['picture_url'] = $res['url'];
                $result['val'] = $res['val'];
                $result['success'] = true;
            }
        }
        header('Content-Type: application/json');

        echo json_encode($result);
    }

}

?>