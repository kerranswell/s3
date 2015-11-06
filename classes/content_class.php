<?php

class content {

    public $blocks = array();

    public function content () {
        $this->block_types[] = array('name' => 'column1', 'type' => 'column', 'title' => '1 колонка', 'params' => array('count' => 1));
        $this->block_types[] = array('name' => 'column2', 'type' => 'column', 'title' => '2 колонки', 'params' => array('count' => 2));
        $this->block_types[] = array('name' => 'column2_1', 'type' => 'column', 'title' => '2 и 1 колонки', 'params' => array());
        $this->block_types[] = array('name' => 'column1_2', 'type' => 'column', 'title' => '1 и 2 колонки', 'params' => array());
        $this->block_types[] = array('name' => 'column4', 'type' => 'column', 'title' => '4 колонки', 'params' => array('count' => 4));
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
        }
    }

    private function getBlockHtml()
    {
        $block_name = $_REQUEST['block_name'];

        $this->makeBlock();

        $dom_block = $this->dsp->_BuilderPatterns->create_block('content_block', 'content_block', 'ajax');
        $this->dsp->_Builder->addArray(array('block_name' => $block_name), '', array(), $dom_block, false);


        $this->dsp->_Builder->Transform('admin/ajax.xsl');
        exit;
    }

}

?>