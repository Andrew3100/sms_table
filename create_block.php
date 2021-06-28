<?php
require_once 'libs/html_elements_lib.php';
require_once 'libs/lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';




global $DB;

$form = new html_form();
$form->openForm('create_block1.php?create_template','post');
echo $form->getFormByType('text','block_name','Наименование раздела',600);
echo '<br>';
echo $form->getFormByType('text','block_get_name','Имя GET параметра для раздела',600);
echo '<br>';
echo $form->getFormByType('text','block_table_qua','Количество таблиц в разделе',60);
echo '<br>';
$form->autocompleteTextArea('multi_autocomplete','Кому доступен раздел? Введите наименования организаций',600,60);
echo '<br>';
$form->closeForm('Далее','success');
