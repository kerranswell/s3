<?php

switch ($_REQUEST['act'])
{
    case 'getList' :

        $s = strtolower( $_REQUEST['s'] );
        $table = strtolower( $_REQUEST['table'] );

        $sql = 'SELECT `id`, `title` FROM `'.$table.'` t
            where lower(t.`title`) like "%'.mysql_real_escape_string( $s, $dsp->db->getDbh() ).'%"
            ORDER BY t.`title` LIMIT 0,10';

        $list = $dsp->db->Select( $sql );

        foreach( $list as $a ){
//            echo '<div class="table2items_item" data-title="'.htmlspecialchars($a['title']).'">'.$a['title'].'<input type="hidden" name="table2items['.$table.']" /><a href="#" class="table2items_del">[X]</a></div>';
            echo '<div class="item" data-id="'.$a['id'].'">'.htmlspecialchars($a['title']).'</div>';
        }

        break;
}


exit;

?>