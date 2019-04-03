<?php
include($_SERVER["DOCUMENT_ROOT"] . "/config.php");
include($_SERVER["DOCUMENT_ROOT"] . "/libdb.php");
include($_SERVER["DOCUMENT_ROOT"] . "/libtable.php");

include "libadmin.php";
$dbclass ="Db".DB_DRIVE;
$db = new $dbclass;
if (count($_GET) == 0) {
    $name_object = "Teditbooks";
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
