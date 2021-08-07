<?php

require_once 'classes/classes.php';
$user = new user();
$user->setUserData();

//отладка
debug();
//авторизация
is_auth();

function is_auth() {
    if ($_COOKIE['user'] == '') {
        header('Location: auth_form.php');
    }
}

function getContentByMenu() {
    if ($_GET['data']==1) {
        $content = 'data.php';
    }
    else {
        if ($_GET['reports']==1) {
            $content = 'reports.php';
        }
        else {
            if ($_GET['import']==1) {
                $content = 'import.php';
            }
            if ($_GET['create']) {
                $content = 'create_block.php';
            }
            if ($_GET['create_template']) {
                $content = 'create_block1.php';
            }
            if ($_GET['roots']) {
                $content = 'roots.php';
            }
            if ($_GET['create_user']) {
                $content = 'create_user.php';
            }
            if ($_GET['main']) {
                $content = 'hi.php';
            }
            if ($_GET['logs']) {
                $content = 'logs.php';
            }

        }
    }
    return $content;
}
//отладка
function debug() {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL);
}
//вывод для отладки
function pre($object) {
    echo '<pre>';
    var_dump($object);
    echo '</pre>';
}


//функция преобразует текстовое представление даты (по стандартам Excel) в нормальное
function GetDateByText($text) {
    $stack1 = 86400 * $text;
    $stack2 = time();
     $razd = $stack1 - $stack2;
    //секунд с 1900 года
    $text1900 = 86400 * $text;
    //секунд в разнице эпох
    $hueta = 25569*86400;
    $timestamp = $text1900 - $hueta;
    $date = date('d-m-Y',$timestamp);
    return $date;
}

GetDateByText(44433);

function getUserInfoBadge($name) {
    echo '<div class="container-fluid">
        <div class="row">
            <div class="col">';
    echo "<div style='text-align: right'>Вы зашли под учётной записью <b>$name</b> (<a href='exit.php'>Выход</a>)</div>";
    echo '      </div>
        </div>
      </div>';
}