<?php

class admin_menu extends Record {

    public function getAdminMenu()
    {
        $menu = array();

        $menu[] = array('title' => 'Новости', 'op' => 'news', 'link' => '/admin/?op=news&pid=1');
        $menu[] = array('title' => 'Блог', 'op' => 'news', 'link' => '/admin/?op=news&pid=2');
        $menu[] = array('title' => 'Страницы', 'op' => 'pages', 'link' => '/admin/?op=pages&pid=0');
        $menu[] = array('title' => 'Команда', 'op' => 'lists', 'link' => '/admin/?op=lists&pid=5');
        $menu[] = array('title' => 'Клиенты', 'op' => 'lists', 'link' => '/admin/?op=lists&pid=6');
        $menu[] = array('title' => 'Пользователи', 'op' => 'usersadmin', 'link' => '/admin/?op=usersadmin');
        $menu[] = array('title' => 'Документы', 'op' => '', 'link' => '/personal/', 'target' => '_blank');
        if ($this->dsp->authadmin->user['role'] == USER_ROLE_SUPER)
            $menu[] = array('title' => 'Обновить версию', 'op' => '', 'link' => '/admin/?op=version');
        $menu[] = array('title' => 'Выход', 'op' => '', 'link' => '/admin/logout');

        return $menu;
    }

} // class admin_menu

?>