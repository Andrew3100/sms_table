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
