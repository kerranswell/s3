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

    public function add($data, $type = 'message')
    {
        $sql = "insert into `messages` (`id`, `name`, `company`, `email`, `phone`, `comments`, `date`, `type`) values (0,?,?,?,?,?,?,?)";
        $this->dsp->db->Execute($sql, $data['name'], $data['company'], $data['email'], $data['phone'], $data['comments'], time(), $type);
        $id = $this->dsp->db->LastInsertId();

        $message_number = $this->dsp->messages->getNumber($id);
        $this->dsp->db->Execute("update `messages` set `number` = ?, `contracts_id` = ? where `id` = ?", $message_number, $type == 'contract' && isset($data['contracts_id']) ? $data['contracts_id'] : 0, $id);

        return array('number' => $message_number, 'id' => $id);
    }

}