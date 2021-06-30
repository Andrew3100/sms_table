<?php
require_once 'libs/html_elements_lib.php';
require_once 'libs/lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';
global $DB;
//количество ролей всего.
// Так сделано на случай, если роли будут добавляться.
// А они будут добавляться, если создаётся новый раздел
$count = $_POST['count_checks'];

for ($i = 0; $i < ($count); $i++) {
    $k = ($_POST["name$i"]);
    if ($k != NULL) {
        $role_id_arr[] = $k;
    }
}

$login = array_keys($_GET)[0];

for ($i = 0; $i < count($role_id_arr); $i++) {
    $roles_id = $DB->getRecordsByConditionFetchAssoc('roles',"`name` = '{$role_id_arr[$i]}'");
    foreach ($roles_id as $value) {
        $ids[] = $value['id'];
    }
}
$ids = implode($ids,',');

//ищем ид пользователя, который надо обновить
$id = $DB->getRecordsByConditionFetchAssoc('users',"`login` = '$login'",'*','1');

foreach ($id as $value) {
    $obj = new stdClass();
    $obj->login = $login;
    $obj->password = $value['password'];
    $obj->fullname = $value['fullname'];
    $obj->role_list = $ids;
    $id_us = $value['id'];
}

$DB->update_recordById('users',$obj,$id_us,'sa');
echo "<script>window.location.replace('index.php?roots=1&action_role=1&$login')</script>";
