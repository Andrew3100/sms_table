<?php

require_once 'libs/html_elements_lib.php';
require_once 'libs/lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';
global $DB;

$block_name = $_POST['nameblock_name'];
$table_q = $_POST['nameblock_table_qua'];
$block_get_name = $_POST['nameblock_get_name'];
$form = new html_form();
echo $form->openForm('create_final.php','post');
echo $form->hidden($table_q,'table_qua');
echo $form->hidden($block_name,'block_name');
echo $form->hidden($block_get_name,'block_get_name');

for ($i = 1; $i <= $table_q; $i++) {
    echo "<b><h2>Данные по таблице № $i</h2></b>";
    echo $form->getFormByType('text','table_name_interface'.$i,'Наименование таблицы для интерфейса');
    echo $form->getFormByType('text','table_name_db'.$i,'Наименование таблицы базы данных (латинские буквы)');
    echo $form->getFormByType('text','get_param_name'.$i,'Имя GET параметра для таблицы');
    echo $form->getFormByType('text','fields_for_db'.$i,'Имена полей таблицы для БД');
    echo $form->getFormByType('text','headers_interface'.$i,'Имена заголовков таблицы для вывода в интерфейс');
    echo $form->getFormByType('text','forms_data_types'.$i,'Типы данных форм ввода (через запятую с пробелом)');

}
echo '<br>';
echo $form->closeForm('Далее','success');





