<?php

require_once 'libs/html_elements_lib.php';
require_once 'libs/lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';

global $DB;
$countries = $DB->getCountryList();
$countries = array_filter($countries);
$f = new html_form();
for ($i = 0; $i < count($countries); $i++) {
echo $f->hidden($countries[$i],'countries');
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>jQuery UI Autocomplete - Multiple values</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <!--    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>-->
    <!--    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
    <script src="jquery/js/jquery-1.9.1.js"></script>
    <script src="jquery/js/jquery-ui-1.10.3.custom.js"></script>
    <!--    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
    <script>
        let arr = document.getElementsByName('countries');
        let arr2 = Array.prototype.slice.call(arr)
        let complete = [];
        for (let i = 0; i < arr2.length; i++) {
            complete.push(arr2[i].value);
        }
        console.log(complete)
        $( function() {
            var availableTags = complete;
            $( "#tags" ).autocomplete({
                source: availableTags
            });
        } );
    </script>
</head>
<body>

</body>
</html>


<?php



$table = array_values($_GET)[1];
$id    = array_values($_GET)[0] ;
$form  = new html_form();

$assoc = $DB->getRecordsByConditionFetchAssoc('bsu_form_data',"`get_name` = '$table'",'*');

$datas = $DB->getRecordsByConditionFetchAssoc($table,"`id` = '$id'",'*');


foreach ($assoc as $arr) {
    $fetch[] = $arr['type_name'];
    $fetch1[] = $arr['descriptor_n'];
}


echo $form->openForm("upd_script.php?$table&$id",'post');



$fields = $DB->getRecordsByConditionFetchAssoc('bsu_form_data',"`get_name` = '$table'",'*');

foreach ($fields as $field) {
    $headers_db[] = $field['fn'];
}



$values_str = implode(',',$headers_db);

$records = $DB->getRecordsByConditionFetchAssoc($table,"`id` = $id",$values_str);


foreach ($records as $record) {
    for ($i = 0; $i < count($headers_db); $i++) {
        if ($fetch1[$i] == 'Страна' OR $fetch1[$i] == 'Страна прибытия') {
            echo '<div class="ui-widget" style="width: 500px;">
                <label for="tags" class="form-label">Страна</label>
                <input id="tags" value="'.$record[$headers_db[$i]].'" name="name'.$i.'" class="form-control">
              </div>';
            // echo $form->getFormByType($fetch[$i],$i,$fetch1[$i],500);
        }
        else {
            echo $form->getFormByType($fetch[$i],$i,$fetch1[$i],500,($record[$headers_db[$i]]));
        }

    }
}

echo '<br>';
echo $form->closeForm('Обновить','success');

echo '</body>';



