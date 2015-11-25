<?php

class services extends record {

    public $services = array();
    
    public function init() {
        $sql = "select * from `services`";
        $rows = $this->dsp->db->Select($sql);
        foreach ($rows as $row)
        {
            $this->services['id'][$row['id']] = $row;
            $this->services['name'][$row['name']] = &$this->services['id'][$row['id']];
        }
    }
    
} // class Services
