<?php
//Библиотека
require_once 'libs/html_elements_lib.php';
//Библиотека
require_once 'libs/lib.php';
//Классы
require_once 'classes/classes.php';

global $DB;

$user = new user();
$user->setUserData();
//роли пользователя
$roles = $user->getRoleListByLogin($user->login);

if (count($roles)>1) {
    $role_str = implode($roles,',');
}
else {

    $role_str = $roles[0];
}

$blocks = $DB->getRecordsByConditionFetchAssoc('administration_table_link',"`role_id` in ($role_str)",'*');

$roles_all_list = $DB->getAllRoles();

foreach ($blocks as $block) {
    $get = $block["get"];
    $head = $block["header"];
    $link = "<a href='table_list.php?$get'>$head</a>";
    $blocked[] = $link;
}

$bootstrap = new Bootstrap();

echo $bootstrap->setListMenu(array_values(array_unique($blocked)));