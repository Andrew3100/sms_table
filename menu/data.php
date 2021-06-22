<?php
require_once 'libs/html_elements_lib.php';
require_once 'libs/lib.php';
require_once 'classes/classes.php';

include 'db/db_config.php';
 var_dump($DB->getRecordsForTableInterfaceArray('bsu_form_data','','*'));
