<?php

class contracts extends record
{
    public $file = '93ftra67bnk.csv';
    public $contracts = array();
    public $documents = array();

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

    public function showDocs()
    {
        $this->loadDocs();

        $this->addValueToXml(array('items' => $this->documents));

        $this->dsp->_Builder->Transform( 'docs.xsl');
    }

    private function loadDocs()
    {
        $dir = ROOT_DIR."docs";

        $docs = array();
        $files = scandir($dir);
        foreach ($files as $file)
        {
            if ($file == '..' || $file == '.') continue;

            if (preg_match("|^(.+)\.diz$|i", $file, $matches))
            {
                $docs[$matches[1]] = array('diz' => $this->prepareCDATA(file_get_contents($dir."/".$file)));
            }
        }

        foreach ($files as $file)
        {
            if ($file == '..' || $file == '.') continue;
            if (preg_match("|^(.+)(\.[a-z0-9]+)$|i", $file, $matches))
            {
                if ($matches[2] == '.diz') continue;

                if (isset($docs[$matches[1]]))
                {
                    $docs[$matches[1]]['file'] = $file;
                }
            }
        }

        $this->documents = array();
        foreach ($docs as $name => $doc)
        {
            if (isset($doc['file']) && file_exists($dir."/".$doc['file']))
                $this->documents[] = $doc;
        }

    }

    public function prepareCDATA($c)
    {
        $c = $this->dsp->transforms->stripInvalidXml($c);
        $this->dsp->transforms->replaceEntityBack( $c );
        $this->dsp->transforms->replaceEntity2Simbols( $c );
        $c = '<![CDATA['.$c.']]>';
        return $c;
    }

    public function getContractNumber($id)
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

        return "T".$num."-".date("m", $row['date'])."/".$y;
    }

}