<?php

class admin_menu extends Record {

    public function getAdminMenu()
    {
        $menu = array();

        $menu[] = array('title' => 'Страницы', 'op' => 'pages');

        return $menu;
    }

} // class admin_menu

?>