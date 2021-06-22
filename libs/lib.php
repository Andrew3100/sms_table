<?php

function getContentByMenu() {
    if ($_GET['data']==1) {
        $content = 'menu/data.php';
    }
    else {
        if ($_GET['reports']==1) {
            $content = 'menu/reports.php';
        }
        else {
            if ($_GET['import']==1) {
                $content = 'menu/import.php';
            }
        }
    }
    return $content;
}