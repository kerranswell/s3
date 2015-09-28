<?php

class record  {

    public $dsp;
    public $__tablename__  = '';

    protected $table_structure = array();

    public function record()
    {
        global $dsp;

        if ($this->__tablename__ == '') $this->__tablename__ = strtolower(get_class($this));

        $this->dsp = $dsp;
    }

    public function GetAll() {
       $items = $this->dsp->db->Select("SELECT * FROM `".$this->__tablename__."`");

       return $items;
    } // GetAll()

    public function GetItem($key) {

        $item = $this->dsp->db->SelectRow("SELECT * FROM `".$this->__tablename__."` WHERE `id` = ?", $key);

        return $item;
    } // GetItem()

    protected function tableStructure() { }

}

?>