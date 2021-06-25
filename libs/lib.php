<?php

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

function pre($object) {
    echo '<pre>';
    var_dump($object);
    echo '</pre>';
}
