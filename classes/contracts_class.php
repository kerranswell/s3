<?php

class contracts
{
    public $file = '93ftra67bnk.csv';
    public $contracts = array();

    public function loadFromFile()
    {
        $s = file_get_contents(ROOT_DIR.$this->file);
        $rows = explode("\n", $s);
        foreach ($rows as $row)
        {
            list($inn, $num) = explode(",", $row);
            $inn = trim($inn);
            $num = trim($num);
            $num = trim($num, "\r");
            $c = array();
            if ($inn != '') $c['inn'] = $inn;
            if ($num != '') $c['num'] = $num;
            if (count($c) > 0) $this->contracts[] = $c;
        }
    }

    public function exists($inn, $num)
    {
        $this->loadFromFile();
        foreach ($this->contracts as $c)
        {
            if ($c['inn'] == $inn || $c['num'] == $num) return true;
        }

        return false;
    }
}