<?php

class messages extends record
{
    public function getNumber($id)
    {
        $sql = "select * from ".$this->__tablename__." where `id` = ?";
        $row = $this->dsp->db->SelectRow($sql, $id);

        $m = date("n", $row['date']);
        $y = date("Y", $row['date']);
        $bottom_limit = mktime(0, 0, 0, $m, 1, $y);

        if (!$row['id']) return 0;

        $sql = "select count(*) from ".$this->__tablename__."
                where `id` != ? and `date` < ? and `date` >= ?";

        $num = $this->dsp->db->SelectValue($sql, $id, $row['date'], $bottom_limit) + 1;
        $num = $num < 10 ? "0".$num : $num;

        return $num."-".date("dmy", $row['date']);
    }

}