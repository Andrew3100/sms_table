<?php
require_once 'libs/html_elements_lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';

$form = new html_form();


$form->openForm('action.php','GET');
$form->getFormByType('date',1,'Дата');
$form->closeForm('Отправить данные','danger');