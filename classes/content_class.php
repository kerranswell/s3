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
        $this->block_types[] = array('name' => 'fullscreen_text', 'type' => 'column', 'title' => 'Описание в полном экране', 'params' => array('count' => 2), 'modules' => array('pages'));
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
        $xml = json_decode($xml, true);
        $xml_old = json_decode($xml_old, true);

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

/*            if ($xi['type'] == 'column')
            {
                if (is_array($xi['cells']))
                foreach ($xi['cells'] as &$c)
                {
                    $c = str_replace("<div>
	 </div>", "", $c);
                }
            }*/
        }

        foreach ($old_ids as $id => $t)
        {
            $this->dsp->i->clearByIDX($id);
        }

        return json_encode($xml);
    }

    public function deleteAllImages($xml)
    {
        $xml = json_decode($xml, true);
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
            $xml = json_decode($xml, true);
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

    public function getPictureIdsFromArray($rows)
    {
        $idx = array();
        foreach ($rows as $row)
        {
            $xml = $row['xml'];
            if (!is_array($xml)) $xml = json_decode($xml, true);
            foreach ($xml as &$xi)
            {
                if ($xi['type'] == 'picture')
                {
                    $id = reset($xi['cells']);
                    if (is_numeric($id)) $idx[] = $id;
                }
            }
        }

        return $idx;
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

    public function preparePages(&$rows, $codes = array(0))
    {
        if (!in_array(0, $codes)) $codes[] = 0;
        $idxs = $this->getPictureIdsFromArray($rows);
        $images = $this->dsp->i->getImagesArray($idxs);

        $resized = array();
        foreach ($codes as $c)
        {
            $resized[$c] = $this->dsp->i->resizeFromArray($images, $c);
        }

        foreach ($rows as &$row)
        {
            $row['xml'] = $this->prepareXml($row['xml']);
            foreach ($row['xml'] as &$xi)
            {
                if ($xi['type'] == 'picture')
                {
                    foreach ($codes as $c)
                    {
                        $idx = reset($xi['cells']);
                        if (isset($resized[$c][$idx]))
                        {
                            $path = $resized[$c][$idx];
                            $data = $this->dsp->i->imageValidatePath($path);
                            if (!isset($GLOBALS['isSizes'][$c]) || $data[4] <= $GLOBALS['isSizes'][$c][0]) $path = $resized[0][$idx];
                            $xi['cells'][$c] = array('idx' => $idx, 'path' => $this->dsp->i->default_path.$path);
                        }
                        else $xi['cells'][$c] = 0;
                    }
                }
            }
        }
    }

    public function prepareXml($xml)
    {
        if (!is_array($xml)) $xml = json_decode($xml, true);
        foreach ($xml as &$b)
        {
            if ($b['type'] == 'picture') continue;
            if (isset($b['cells']) && is_array($b['cells'])) {
                foreach ($b['cells'] as &$c) {
                    if (in_array($b['type'], array('quote')))
                    {
                        $c = strip_tags($c);
                        $c = str_replace("\n", '<br />', $c);
                    }

                    $c = $this->prepareCDATA($c);
                }
            }
        }

        if (!is_array($xml)) $xml = array();

        return $xml;
    }

    public function prepareCDATA($c)
    {
        $c = $this->dsp->transforms->stripInvalidXml($c);
        $this->dsp->transforms->replaceEntityBack( $c );
        $this->dsp->transforms->replaceEntity2Simbols( $c );
        $this->dsp->transforms->removeCKShit( $c );
        $c = '<![CDATA['.$c.']]>';
        return $c;
    }

}

?>