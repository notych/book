<?php
include "config.php";
include "libdb.php";
include "lib.php";

$dbclass = "Db" . DB_DRIVE;
if (count($_GET) == 0) {
    $name_object = "Tbooks";
    $name_method = "a_view";
} else {
    if (isset($_GET["table"])) {
        $name_object = "T" . $_GET["table"];
        if (isset($_GET["action"])) {
            $name_method = "a_" . $_GET["action"];
        } else {
            $name_method = "a_view";
        }
    }
}

$db = new $dbclass;
$obj = new $name_object($db);
$html = $obj->$name_method();
include("template.php")
?>
