<?php

require_once 'libs/html_elements_lib.php';
//require_once 'libs/lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';
global $DB;


echo '<h3 style="margin-top: 80px; text-align: center">Информационная база данных проекта по мониторингу деятельности по международному сотрудничеству</h3>';
$form = new html_form();
echo $form->openForm('auth.php','post');
echo '<div style="
            position: absolute; 
            left: 50%; top: 30%;
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            -o-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);>';
echo $form->getFormByType('text','login','Имя пользователя',600);
echo $form->getFormByType('password','pass','Пароль',600);
echo '<br>';
echo $form->closeForm('Войти','primary');
echo '<a href="mailto: funikov.1997@mail.ru?subject=Восстановление пароля в ЭМОУ" target="_top" style="margin-left: 25px;" class="btn btn-warning">Забыли пароль?</a>';
echo '</div>';

