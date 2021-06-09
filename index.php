<?php
require_once 'classes/classes.php';


function get_records_sql($table,$condition,$print = 0)
{
    $db_host = "localhost";
    $db_user = "root"; // Логин БД
    $db_password = "root"; // Пароль БД
    $db_base = 'jc_database'; // Имя БД
    // Подключение к базе данных
    $mysqli = new mysqli($db_host, $db_user, $db_password, $db_base);
    $mysqli->set_charset("utf8");
    if ($condition!='') {
        $sql = "SELECT * FROM `$table` WHERE $condition";
        $result = $mysqli->query($sql);
        if (($print) == 1) {
            print_r($sql);
        }

    }
    else {
        $sql = "SELECT * FROM `$table`";
        $result = $mysqli->query($sql);
        if (($print) == 1) {
            print_r($sql);
        }
    }
    return $result;
}

function get_field_list($table_name) {
    $db_host = "localhost";
    $db_user = "root"; // Логин БД
    $db_password = "root"; // Пароль БД
    $db_base = 'jc_database'; // Имя БД
    // Подключение к базе данных
    $mysqli = new mysqli($db_host, $db_user, $db_password, $db_base);
    $mysqli->set_charset("utf8");
    $field_list = array();
    $sql = "SHOW COLUMNS FROM `$table_name`";
    $res_sql = $mysqli->query($sql);


    while ($res_sql1 = mysqli_fetch_assoc($res_sql)) {
        $field_list[] = $res_sql1['Field'];
    }

    return $field_list;
}


$records = get_records_sql('users','');
$field_list = get_field_list('users');
foreach ($records as $record) {
    for ($i = 0; $i < count($field_list); $i++) {
        $records1[] = $record[$field_list[$i]];
    }
    $records2[] = $records1;
    unset($records1);
}

$table = new html_table();
 $headers = ['Ид','Фамилия','Имя','Отчество','Статус','Пароль','Логин','Админ'];
 /*$content = [
    ['20','Ручка'],
    ['30','Карандаш'],
    ['10','Спички']
];*/
$table = $table->printTable($headers, $records2);
echo ($table);