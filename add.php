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
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
require_once 'libs/lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';
global $DB;

$table = array_keys($_GET)[0];

$form  = new html_form();

$assoc = $DB->getRecordsByConditionFetchAssoc('bsu_form_data',"`get_name` = '$table'",'*');


echo $form->openForm("add_script.php?$table",'post');
echo '<div style="position: absolute; left: 50%; top: 50%;-webkit-transform: translate(-50%, -50%);-moz-transform: translate(-50%, -50%);-ms-transform: translate(-50%, -50%);-o-transform: translate(-50%, -50%);transform: translate(-50%, -50%);>';
$i = 0;

foreach ($assoc as $arr) {
    $fetch[] = $arr['type_name'];
    $fetch1[] = $arr['descriptor_n'];
    if ($fetch1[$i] == 'Страна' OR $fetch1[$i] == 'Страна прибытия') {
        echo '<div class="ui-widget">
                <label for="tags" class="form-label">Страна</label>
                <input id="tags" name="'.$i.'" class="form-control">
              </div>';
    }
    else {
        echo $form->getFormByType($fetch[$i],$i,$fetch1[$i],500);
    }
    $i++;
}
echo '<br>';
echo $form->closeForm('Добавить','success');
echo '</div>';
?>
</body>
</html>
