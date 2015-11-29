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
        $menu[] = array('title' => 'Пользователи', 'op' => 'usersadmin', 'link' => '/admin/?op=usersadmin');
        $menu[] = array('title' => 'Документы', 'op' => '', 'link' => '/personal/');
        $menu[] = array('title' => 'Выход', 'op' => '', 'link' => '/admin/logout');

        return $menu;
    }

} // class admin_menu

?>