<div style="position:fixed; top:0; right: 0; width: 400px; background: rgb(0,0,0,0); background: rgba(0,0,0,0.8); color: green; margin:0px; padding:5px; max-height: 90%; overflow-y:auto;">
<h2 style="margin:0px;color:white;">$ HEADERS:</h2>
<h3 style="margin:5px;color:white;">GET</h3>
<?php

//var_dump($_GET);
foreach($_GET as $name=>$value) {
       echo $name."  =>  ";
       echo $value."<br />";
}

?>
<h3 style="margin:5px;color:white;">POST</h3>
<?php

//var_dump($_POST);
foreach($_POST as $name=>$value) {
       echo $name."  =>  ";
       echo $value."<br />";
}?>
<h3 style="margin:5px;color:white;">SESSION</h3>
<?php
//var_dump($SESSION);
foreach($_SESSION as $name=>$value) {
       echo $name."  =>  ";
       echo $value."<br />";
}

?></div>