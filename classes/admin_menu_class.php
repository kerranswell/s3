<?php

class admin_menu extends Record {

    public function getAdminMenu()
    {
        $menu = array();

        $menu[] = array('title' => 'Новости', 'op' => 'news', 'link' => '/admin/?op=news&pid=1');
        $menu[] = array('title' => 'Блог', 'op' => 'news', 'link' => '/admin/?op=news&pid=2');
        $menu[] = array('title' => 'Страницы', 'op' => 'pages', 'link' => '/admin/?op=pages&pid=0');
        $menu[] = array('title' => 'Клиенты', 'op' => 'lists', 'link' => '/admin/?op=lists&pid=5');
        $menu[] = array('title' => 'Компании', 'op' => 'lists', 'link' => '/admin/?op=lists&pid=6');

        return $menu;
    }

} // class admin_menu

?>